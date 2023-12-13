import os
os.chdir('./') #change to your root
import random
import torch
from sentence_transformers import SentenceTransformer
#from travel_idea import Ideachat
import faiss
import numpy as np
import argparse
import os
import json
import copy

class Interactive:
    def __init__(self):
        # Parse command-line arguments
        parser = argparse.ArgumentParser()
        parser.add_argument('--similarity_model', default='BAAI/bge-large-zh-v1.5', type=str, help='Similarity model path')
        parser.add_argument('--similarity_model_method', default='Flat', type=str, help='Similarity model method for similarity score')
        parser.add_argument('--database_dir', default='./data/database.json', type=str, help='your data dictionary path')
        parser.add_argument('--database_embeddings_dir', default='./data/database_embedding.npy', type=str, help='your binary data path')
        parser.add_argument('--intention_cls_dir', default='./data/intention_cls.json', type=str, help='intention class path')
        parser.add_argument('--question_cls_dir', default='./data/question_cls.json', type=str, help='Different intention, Different question class')
        parser.add_argument('--similarity_intention_cls_dir', default='./data/similarity_intention_cls.json', type=str, help='similarity cls for intention')
        parser.add_argument('--edit_question_order_dir', default='./data/edit_question_order.json', type=str, help='for edit question, we need to know which is buttons')
        args = parser.parse_args(args=[])

        self.device = torch.device(0) if torch.cuda.is_available() else torch.device('cpu')
        self.s_model = SentenceTransformer(args.similarity_model).eval()
        #self.ideachat = Ideachat()

        self.database_embeddings = np.load(args.database_embeddings_dir)
        self.database_dict = json.load(open(args.database_dir))
        self.intention = json.load(open(args.intention_cls_dir))
        self.question_cls = json.load(open(args.question_cls_dir))
        self.question_order = json.load(open(args.edit_question_order_dir))
        self.similarity_intention = json.load(open(args.similarity_intention_cls_dir))

        self.QS = {}
        self.QN = {}

        self.recomm_aricle_caches = {}
        self.search_keys = {}
        self.COUNT_CACHE = 0
        self.counts = {}
        self.HISTORY_CACHE = []
        self.member_id = None
        self.histories = {}
        self.edit_orders = {}

        self.cls = list(self.similarity_intention.keys())
        self.embeddings_c = self.s_model.encode(self.cls, normalize_embeddings=True)


        dim, measure = self.database_embeddings.shape[1], faiss.METRIC_L2
        self.index = faiss.index_factory(dim, args.similarity_model_method, measure)
        if not self.index.is_trained:
            self.index.train(self.database_embeddings)
        self.index.add(self.database_embeddings)
        print('目前資料庫中有' + str(self.index.ntotal) + '筆資料')

    def get_recomm_from_database(self, add_condition=''):
        topk = 10
        search_key_words = ''

        if self.search_keys[self.member_id] is None:
            search_key_dict = {}
            for cls, sents in zip(self.QS[self.member_id], self.HISTORY_CACHE[1:]):
                search_key_dict[cls + ''] = sents[0].strip().replace('++','')
            self.search_keys[self.member_id] = copy.copy(search_key_dict)
        else:
            if add_condition != '':
                self.search_keys[self.member_id][list(self.search_keys[self.member_id].keys())[-1]] = list(self.search_keys[self.member_id].values())[-1] + ',' + add_condition.strip()

        search_key_words = ','.join([i[:-2] + ':' + j for i, j in zip(self.search_keys[self.member_id].keys(), self.search_keys[self.member_id].values())])
        search_key_embedd = self.s_model.encode(search_key_words, normalize_embeddings=True).reshape(1, -1)

        _, I = self.index.search(search_key_embedd, topk)

        return I[0]

    def member_faq_search(self):
        topk = 3
        embeddings_k = self.s_model.encode(self.HISTORY_CACHE[-1][0], normalize_embeddings=True).reshape(1, -1)
        distance, I = self.index.search(embeddings_k, topk)

        return I[0], distance[0]

    def member_cycles(self):
        if len(self.HISTORY_CACHE) == 1:
            self.HISTORY_CACHE[-1][1] = self.intention[self.HISTORY_CACHE[0][0]]
            return self.HISTORY_CACHE[-1][1]

        elif self.HISTORY_CACHE[-1][0] == '重問':
            self.HISTORY_CACHE[-1][1] = '請您再一次描述問題'
            return self.HISTORY_CACHE[-1][1]

        elif self.HISTORY_CACHE[-2][0] == '重問':
            answer_index, threshold = self.member_faq_search()
            if threshold[0] > 1:
                self.HISTORY_CACHE[-1][1] = '您的問題可能需要與客服聯繫'
            else:
                answers = [str(i + 1) + '.' + list(self.database_dict.values())[ind] for i, ind in enumerate(answer_index)]
                self.HISTORY_CACHE[-1][1] = '\r\n'.join(['最符合的三個答案:'] + answers + [
                    '沒解答到，與客服聯繫(聯繫)/其他疑問(再問)/有幫助到，結束:D(解答)(這應該是個button)'])
            return self.HISTORY_CACHE[-1][1]

        elif self.HISTORY_CACHE[-1][0] == '再問':
            self.HISTORY_CACHE[-1][1] = '還有什麼想要詢問的呢？請直接跟我說'
            return self.HISTORY_CACHE[-1][1]

        elif self.HISTORY_CACHE[-2][0] == '再問':
            answer_index, threshold = self.member_faq_search()
            if threshold[0] > 1:
                self.HISTORY_CACHE[-1][1] = '沒有能找到較符合的回答，可能需要您重新描述問題'
            else:
                answers = [str(i + 1) + '.' + list(self.database_dict.values())[ind] for i, ind in enumerate(answer_index)]
                self.HISTORY_CACHE[-1][1] = '\r\n'.join(['最符合的三個答案:'] + answers + [
                    '沒解答到，重新描述問題(重問)/其他疑問(再問)/有幫助到，結束:D(解答)(這應該是個button)'])
            return self.HISTORY_CACHE[-1][1]

        elif self.HISTORY_CACHE[-1][0] == '解答':
            self.HISTORY_CACHE = []
            return '感謝您使用！'

        elif self.HISTORY_CACHE[-1][0] == '聯繫':
            self.HISTORY_CACHE = []
            return '客服聯繫方式：xxxxxxxx, 感謝您使用！'

        else:
            answer_index, threshold = self.member_faq_search()
            if threshold[0] > 1:
                self.HISTORY_CACHE[-1][1] = '沒有能找到較符合的回答，可能需要您重新描述問題'
            else:
                answers = [str(i + 1) + '.' + list(self.database_dict.values())[ind] for i, ind in enumerate(answer_index)]
                self.HISTORY_CACHE[-1][1] = '\r\n'.join(['最符合的三個答案:'] + answers + [
                    '沒解答到，重新描述問題(重問)/其他疑問(再問)/有幫助到，結束:D(解答)(這應該是個button)'])
            return self.HISTORY_CACHE[-1][1]

    def other_cycles(self):
        if len(self.HISTORY_CACHE) == self.QN[self.member_id] or self.HISTORY_CACHE[-1][0] == '推薦':
            recomm_index = self.get_recomm_from_database()
            recomm_data = list(self.database_dict.values())
            self.recomm_aricle_caches[self.member_id] = [recomm_data[i] for i in recomm_index]
            recomm_article = '\r\n'.join(self.recomm_aricle_caches[self.member_id][:5])
            self.HISTORY_CACHE[-1][1] = recomm_article + '\r\n需要更多推薦(更多)/修改資訊(修改)/增加資訊(增加)/滿意結束:D(滿意)(這應該是個button)'  # 應該是個button
            return self.HISTORY_CACHE[-1][1]

        elif self.HISTORY_CACHE[-1][0] == '更多':
            recomm_article = '\r\n'.join(self.recomm_aricle_caches[self.member_id][5:])
            self.HISTORY_CACHE[-1][1] = recomm_article + '\r\n需要修改資訊(修改)/增加資訊(增加)/滿意結束:D(滿意)(這應該是個button)'  # 應該是個button
            return self.HISTORY_CACHE[-1][1]

        elif self.HISTORY_CACHE[-1][0] == '修改':
            self.HISTORY_CACHE[-1][1] = '您想修改哪一個資訊:' + '/'.join(self.QS[self.member_id]) + '(這應該是個button)'
            return self.HISTORY_CACHE[-1][1]

        elif self.HISTORY_CACHE[-1][0] in [i for i in self.QS[self.member_id]]:
            order = list(self.edit_orders[self.member_id].keys())
            check_order = str(self.QS[self.member_id].index(self.HISTORY_CACHE[-1][0][:-2]))
            if check_order in order:
              self.HISTORY_CACHE[-1][1] = '您想修改' + self.HISTORY_CACHE[-1][0][:-2] + '為..?\n'+self.edit_orders[self.member_id][check_order]
            else:
              self.HISTORY_CACHE[-1][1] = '您想修改' + self.HISTORY_CACHE[-1][0][:-2] + '為..? 請直接輸入:D'
            return self.HISTORY_CACHE[-1][1]

        elif len(self.HISTORY_CACHE) != 1 and self.HISTORY_CACHE[-2][0] in [i for i in self.QS[self.member_id]]:
            self.search_keys[self.member_id][self.HISTORY_CACHE[-2][0]] = self.HISTORY_CACHE[-1][0].replace('++','')
            self.HISTORY_CACHE[-1][1] = '已為您修改完成，要繼續修改還是重新推薦?修改/推薦(這應該是個button)'
            return self.HISTORY_CACHE[-1][1]

        elif self.HISTORY_CACHE[-1][0] == '增加':
            self.HISTORY_CACHE[-1][1] = '請直接告訴我您想增加的想法！'
            return self.HISTORY_CACHE[-1][1]

        elif len(self.HISTORY_CACHE) != 1 and self.HISTORY_CACHE[-2][0] == '增加':
            recomm_index = self.get_recomm_from_database(self.HISTORY_CACHE[-1][0])
            recomm_data = list(self.database_dict.values())
            self.recomm_aricle_caches[self.member_id] = [recomm_data[i] for i in recomm_index]
            recomm_article = '\r\n'.join(self.recomm_aricle_caches[self.member_id][:5])
            self.HISTORY_CACHE[-1][1] = recomm_article + '\r\n需要重新推薦(更多)/修改資訊(修改)/增加資訊(增加)/滿意結束:D(滿意)(這應該是個button)'
            return self.HISTORY_CACHE[-1][1]

        elif self.HISTORY_CACHE[-1][0] == '滿意':
            self.HISTORY_CACHE = []
            del self.recomm_aricle_caches[self.member_id]
            del self.search_keys[self.member_id]
            del self.counts[self.member_id]
            del self.edit_orders[self.member_id]
            del self.QS[self.member_id]
            del self.QN[self.member_id]
            return '感謝您使用！'

        else:
            if self.counts[self.member_id] == 0:
              self.HISTORY_CACHE[-1][1] = '接下來需要您回答一些問題～！' + self.HISTORY_CACHE[0][0][self.counts[self.member_id]]
              self.counts[self.member_id] += 1
              return self.HISTORY_CACHE[-1][1]

            else:
              self.HISTORY_CACHE[-1][1] = self.HISTORY_CACHE[0][0][self.counts[self.member_id]]
              self.counts[self.member_id] += 1
              return self.HISTORY_CACHE[-1][1]

    def history_save(self, msg):
        self.HISTORY_CACHE.append([msg.strip(),''])

    def intention_detect(self, msg):
        embeddings_msg = self.s_model.encode(msg, normalize_embeddings=True)
        similarity = self.embeddings_c @ embeddings_msg.T
        s_score = similarity[np.argmax(similarity)]
        msg = self.similarity_intention[self.cls[np.argmax(similarity)]]

        return msg, s_score

    #Return must be "reponse, member id"
    def predict(self, msg, member_id):
        self.member_id = member_id
        if self.member_id not in self.histories.keys():
            msg, similarity_score = self.intention_detect(msg)
            if similarity_score < 0.55:
              return '我沒有明白您的問題，請您重新描述問題，您的問題可以像這樣，例如：\n1.我想找餐廳\n2.我想找景點\n3.我有會員問題\n4.給我一些旅行的靈感', self.member_id
            self.histories[self.member_id] = []
            self.HISTORY_CACHE = []
        else:
            self.HISTORY_CACHE = self.histories[self.member_id]

        self.history_save(msg)

        if self.HISTORY_CACHE[0][0] == '會員':
            response = self.member_cycles()
            # if history cache is [] mean task done, need to reset.
            if self.HISTORY_CACHE != []:
              self.histories[self.member_id] = self.HISTORY_CACHE
            else:
              del self.histories[self.member_id]

            return response, self.member_id

        #elif self.HISTORY_CACHE[0][0] == '給我一些靈感':
        #    if len(self.HISTORY_CACHE) == 1:
        #      response = self.ideachat.run(self.intention[self.HISTORY_CACHE[0][0]], self.member_id)
        #      self.HISTORY_CACHE[-1][1] = '你好，需要什麼樣的協助？'
        #      self.histories[self.member_id] = self.HISTORY_CACHE
        #      return self.HISTORY_CACHE[-1][1], self.member_id
        #    else:
        #      response = self.ideachat.run(self.HISTORY_CACHE[-1][0], self.member_id)
        #      if response == '終止':
        #        self.HISTORY_CACHE = []
        #        del self.histories[self.member_id]
        #        return '感謝您使用！', self.member_id
        #      else:
        #        self.HISTORY_CACHE[-1][1] = '。\n'.join(response.split('。'))
        #        self.histories[self.member_id] = self.HISTORY_CACHE
        #        return self.HISTORY_CACHE[-1][1], self.member_id

        else:
            if type(self.HISTORY_CACHE[0][0]) != list and self.HISTORY_CACHE[0][0] in self.intention.keys():
                self.edit_orders[self.member_id] = self.question_order[self.HISTORY_CACHE[0][0]]
                self.QS[self.member_id] = self.question_cls[self.HISTORY_CACHE[0][0]]
                self.QN[self.member_id] = len(self.QS[self.member_id]) + 1
                self.HISTORY_CACHE[0][0] = self.intention[self.HISTORY_CACHE[0][0]]
                self.search_keys[self.member_id] = None
                self.counts[self.member_id] = 0

            response = self.other_cycles()
            if self.HISTORY_CACHE != []:
                self.histories[self.member_id] = self.HISTORY_CACHE
            else:
                del self.histories[self.member_id]

            return response, self.member_id
            
# if __name__ == "__main__":
#     m_ids = ['LINE_001', 'LINE_002']
#     recomm_chat = Interactive()
#     count = 0
#     keep_going = True

#     while keep_going:
#       m_id = m_ids[count%2]
#       print('Current ID:', m_id)
#       print('*'*20)

#       user = input()
#       print('\n\n')
#       reply, m_id = recomm_chat.predict(user, m_id)

#       print('Chatbot:' + reply + '\n\n')
#       count+=1