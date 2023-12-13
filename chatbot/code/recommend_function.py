import os
os.chdir(os.path.dirname(os.path.realpath(__name__)))
import random
import torch
from sentence_transformers import SentenceTransformer
from travel_plan import TravelPlanner
import osmnx as ox
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
        parser.add_argument('--similarity_intention_cls_dir', default='./data/similarity_intention_cls.json', type=str, help='similarity cls for intention')
        parser.add_argument('--restaurant_database_dir', default='./data/restaurant_database.json', type=str, help='your restaurant data dictionary path')
        parser.add_argument('--viewpoint_database_dir', default='./data/viewpoint_database.json', type=str, help='your viewpoint data dictionary path')
        parser.add_argument('--member_database_dir', default='./data/member_database.json', type=str, help='your member data dictionary path')
        parser.add_argument('--restaurant_database_embeddings_dir', default='./data/restaurant_database_embedding.npy', type=str, help='your binary restaurant data path')
        parser.add_argument('--viewpoint_database_embeddings_dir', default='./data/viewpoint_database_embedding.npy', type=str, help='your binary viewpoint data path')
        parser.add_argument('--member_database_embeddings_dir', default='./data/member_database_embedding.npy', type=str, help='your binary member data path')
        parser.add_argument('--intention_cls_dir', default='./data/intention_cls.json', type=str, help='intention class path')
        parser.add_argument('--question_cls_dir', default='./data/question_cls.json', type=str, help='Different intention, Different question class')
        parser.add_argument('--edit_question_order_dir', default='./data/edit_question_order.json', type=str, help='for edit question, we need to know which is buttons')
        parser.add_argument('--gmap_dir', default='./data/map/tw/', type=str, help='where you store your map data')
        parser.add_argument('--intention_detect', default=False, type=bool, help='Turn on when no button list in chatbot')
        args = parser.parse_args(args=[])

        self.device = torch.device(0) if torch.cuda.is_available() else torch.device('cpu')
        self.intention_detect = args.intention_detect
        self.s_model = SentenceTransformer(args.similarity_model).eval()
        #self.ideachat = Ideachat()

        self.database_embeddings = {'restaurant':np.load(args.restaurant_database_embeddings_dir),
                                    'viewpoint':np.load(args.viewpoint_database_embeddings_dir),
                                    'member':np.load(args.member_database_embeddings_dir)}
        self.database_dict = {'restaurant':json.load(open(args.restaurant_database_dir)),
                              'viewpoint':json.load(open(args.viewpoint_database_dir)),
                              'member':json.load(open(args.member_database_dir))}

        self.intention = json.load(open(args.intention_cls_dir))
        self.question_cls = json.load(open(args.question_cls_dir))
        self.question_order = json.load(open(args.edit_question_order_dir))
        self.similarity_intention = json.load(open(args.similarity_intention_cls_dir))
        self.similarity_intention_cls = list(self.similarity_intention.keys())
        self.embeddings_c = self.s_model.encode(self.similarity_intention_cls, normalize_embeddings=True)

        self.QS = {}
        self.QN = {}

        self.recomm_aricle_caches = {}
        self.search_keys = {}
        self.COUNT_CACHE = 0
        self.counts = {}
        self.member_counts = {}
        self.HISTORY_CACHE = []
        self.member_id = None
        self.histories = {}
        self.edit_orders = {}
        self.purposes = {}

        #for travel plan
        self.travel_QAs = {}
        self.travel_points = {}
        self.travel_QN = {}
        self.travel_counts = {}
        self.planner = None
        self.travel_plans = {}
        self.travel_area = {}
        self.countries = [city.split('.')[0] for city in os.listdir(args.gmap_dir) if 'graphml' in city]
        self.G = {'台灣':{city.split('.')[0]:ox.io.load_graphml(filepath=args.gmap_dir+city) for city in os.listdir(args.gmap_dir) if 'graphml' in city},
                  '香港':{},
                  '日本':{}}

        dim, measure = self.database_embeddings['restaurant'].shape[1], faiss.METRIC_L2
        self.restaurant_index = faiss.index_factory(dim, args.similarity_model_method, measure)
        self.viewpoint_index = faiss.index_factory(dim, args.similarity_model_method, measure)
        self.member_index = faiss.index_factory(dim, args.similarity_model_method, measure)

        for k,i in zip(['restaurant', 'viewpoint', 'member'],[self.restaurant_index, self.viewpoint_index, self.member_index]):
          if not i.is_trained:
            i.train(self.database_embeddings[k])
          i.add(self.database_embeddings[k])


    def travel_plan_cycles(self):
        if self.travel_counts[self.member_id] == self.travel_QN[self.member_id]:
            self.travel_QAs[self.member_id][self.question_cls[self.HISTORY_CACHE[0][0]][self.travel_counts[self.member_id]-1]] = self.HISTORY_CACHE[-1][0].strip()
            self.get_points_from_database()
            self.planner = TravelPlanner(self.G[self.travel_area[self.member_id][0]][self.travel_area[self.member_id][1]], self.travel_points[self.member_id])
            travel_distance, best_path = self.planner.plan_travel()

            # This need to be removed, just for Test
            self.travel_plans[self.member_id] = {i:{'poi':best_path[i],'to_next_distance':travel_distance[i]} for i in range(len(travel_distance))}
            self.travel_plans[self.member_id].update({len(best_path)-1:{'poi':best_path[-1],'to_next_distance':'last'}})

            self.HISTORY_CACHE[-1][1] = self.travel_plans[self.member_id]
            self.travel_counts[self.member_id] += 1

            return self.HISTORY_CACHE[-1][1]

        elif self.HISTORY_CACHE[-1][0] == 'End_this_turn':
            self.HISTORY_CACHE = []
            del self.travel_QAs[self.member_id]
            del self.travel_QN[self.member_id]
            del self.travel_counts[self.member_id]
            del self.travel_points[self.member_id]
            del self.travel_plans[self.member_id]
            del self.travel_area[self.member_id]

            return ['', ['Thanks_word']]

        else:
            if self.travel_counts[self.member_id] == 0:
              self.HISTORY_CACHE[-1][1] = self.travel_QAs[self.member_id][self.question_cls[self.HISTORY_CACHE[0][0]][self.travel_counts[self.member_id]]]
              self.travel_counts[self.member_id] += 1

              return self.HISTORY_CACHE[-1][1]

            elif self.HISTORY_CACHE[-2][1][1][0] == 'Travel_question_word_3':
              if self.HISTORY_CACHE[-1][0][:2] in ['0' + str(t) if t < 10 else str(t) for t in range(0, 25)]:
                self.travel_QAs[self.member_id][self.question_cls[self.HISTORY_CACHE[0][0]][self.travel_counts[self.member_id]-1]] = self.HISTORY_CACHE[-1][0].strip()
                self.HISTORY_CACHE[-1][1] = self.travel_QAs[self.member_id][self.question_cls[self.HISTORY_CACHE[0][0]][self.travel_counts[self.member_id]]]
                self.travel_counts[self.member_id] += 1

                return self.HISTORY_CACHE[-1][1]
              else:

                return ['', ['Error_time_format_word']]

            elif self.HISTORY_CACHE[-2][1][1][0] == 'Travel_question_word_2':
              if self.HISTORY_CACHE[-1][0] in self.countries:
                self.travel_QAs[self.member_id][self.question_cls[self.HISTORY_CACHE[0][0]][self.travel_counts[self.member_id]-1]] = self.HISTORY_CACHE[-1][0].strip()
                self.HISTORY_CACHE[-1][1] = self.travel_QAs[self.member_id][self.question_cls[self.HISTORY_CACHE[0][0]][self.travel_counts[self.member_id]]]
                self.travel_counts[self.member_id] += 1

                return self.HISTORY_CACHE[-1][1]
              else:

                return ['', ['Error_country_format_word']]

            elif self.travel_counts[self.member_id] < self.travel_QN[self.member_id]:
              self.travel_QAs[self.member_id][self.question_cls[self.HISTORY_CACHE[0][0]][self.travel_counts[self.member_id]-1]] = self.HISTORY_CACHE[-1][0].strip()
              self.HISTORY_CACHE[-1][1] = self.travel_QAs[self.member_id][self.question_cls[self.HISTORY_CACHE[0][0]][self.travel_counts[self.member_id]]]
              self.travel_counts[self.member_id] += 1

              return self.HISTORY_CACHE[-1][1]
            else:

              return ['', ['Error_remind_word']]


    def get_points_from_database(self):
        topk = 16
        food_question = self.question_cls['Foodie_']
        poi_question = self.question_cls['Viewpoint_']
        food_mapping = {'家族出遊':'適合團體聚會', '情侶約會':'適合團體聚會', '朋友旅行':'適合團體聚會', '獨自旅行':'適合獨自用餐'}

        bf_search_key = ''
        lunch_search_key = ''
        dinner_search_key = ''
        for q in food_question:
          if q in self.travel_QAs[self.member_id].keys():
            bf_search_key += q+':'+self.travel_QAs[self.member_id][q]+' '
            lunch_search_key += q+':'+self.travel_QAs[self.member_id][q]+' '
            dinner_search_key += q+':'+self.travel_QAs[self.member_id][q]+' '
          elif q == '預算':
            bf_search_key += q+':'+' '
            lunch_search_key += q+':'+self.travel_QAs[self.member_id]['午餐價位']+' '
            dinner_search_key += q+':'+self.travel_QAs[self.member_id]['晚餐價位']+' '
          elif q == '時段':
            bf_search_key += q+':'+'早餐 '
            lunch_search_key += q+':'+'午餐 '
            dinner_search_key += q+':'+'晚餐 '
          elif q == '目的':
            bf_search_key += q+':'+food_mapping[self.travel_QAs[self.member_id]['目的']]+' '
            lunch_search_key += q+':'+food_mapping[self.travel_QAs[self.member_id]['目的']]+' '
            dinner_search_key += q+':'+food_mapping[self.travel_QAs[self.member_id]['目的']]+' '


        mp_search_key = ''
        np_search_key = ''
        dp_search_key = ''
        for q in poi_question:
          if q in self.travel_QAs[self.member_id].keys():
            mp_search_key += q+':'+self.travel_QAs[self.member_id][q]+' '
            np_search_key += q+':'+self.travel_QAs[self.member_id][q]+' '
            dp_search_key += q+':'+self.travel_QAs[self.member_id][q]+' '
          else:
            mp_search_key += q+':'+'早 '
            np_search_key += q+':'+'午 '
            dp_search_key += q+':'+'晚 '

        intervals = [9, 11, 13, 18, 20]
        arrival_time = int(self.travel_QAs[self.member_id]['抵達時間'][:2])
        time_zone = 0
        for i in range(len(intervals) - 1):
          if intervals[i] <= arrival_time < intervals[i + 1]:
              time_zone =  i + 1

        search_key_list = [bf_search_key, mp_search_key, lunch_search_key, np_search_key, dinner_search_key, dp_search_key][time_zone:]
        data_col = ['breakfast', 'morning_point', 'lunch','noon_point', 'dinner', 'dinner_point'][time_zone:] #travel_geo_data_col
        data = {}
        restaurant_data = list(self.database_dict['restaurant'].values())
        viewpoint_data = list(self.database_dict['viewpoint'].values())

        restaurant_exist = []
        viewpoint_exist = []
        for col, sk in zip(data_col, search_key_list):
          search_key_embedd = self.s_model.encode(sk, normalize_embeddings=True).reshape(1, -1)
          if col in ['breakfast', 'lunch', 'dinner']:
            _, I = self.restaurant_index.search(search_key_embedd, topk)
            if col == data_col[0]:
              data[col] = {tuple(restaurant_data[i][-1]):restaurant_data[i][0] for i in I[0][:4]}
              restaurant_exist = restaurant_exist + list(I[0][:4])
            else:
              pickup_index = []
              for ind in I[0]:
                if ind not in restaurant_exist:
                  if len(pickup_index)<4:
                    pickup_index.append(ind)
                  else:
                    break
              restaurant_exist += pickup_index
              data[col] = {tuple(restaurant_data[i][-1]):restaurant_data[i][0] for i in pickup_index}
          else:
            _, I = self.viewpoint_index.search(search_key_embedd, topk)
            pickup_index = []
            for ind in I[0]:
              if ind not in viewpoint_exist:
                if len(pickup_index)<4:
                  pickup_index.append(ind)
                else:
                  break
            viewpoint_exist += pickup_index
            data[col] = {tuple(viewpoint_data[i][-1]):viewpoint_data[i][0] for i in pickup_index}

        self.travel_area[self.member_id] = [self.travel_QAs[self.member_id]['國家'],self.travel_QAs[self.member_id]['地區']]
        self.travel_points[self.member_id] = data

    def get_recomm_from_database(self, add_condition=''):
        topk = 10
        search_key_words = ''

        if self.search_keys[self.member_id] is None:
            search_key_dict = {}
            for cls, sents in zip(self.QS[self.member_id], self.HISTORY_CACHE[1:]):
                search_key_dict[cls] = sents[0].strip()
            self.search_keys[self.member_id] = copy.copy(search_key_dict)
        else:
            if add_condition != '':
                self.search_keys[self.member_id][list(self.search_keys[self.member_id].keys())[-1]] = list(self.search_keys[self.member_id].values())[-1] + ',' + add_condition.strip()

        search_key_words = ','.join([i + ':' + j for i, j in zip(self.search_keys[self.member_id].keys(), self.search_keys[self.member_id].values())])
        search_key_embedd = self.s_model.encode(search_key_words, normalize_embeddings=True).reshape(1, -1)
        if self.purposes[self.member_id] == 'Foodie_':
            _, I = self.restaurant_index.search(search_key_embedd, topk)
        elif self.purposes[self.member_id] == 'Viewpoint_':
            _, I = self.viewpoint_index.search(search_key_embedd, topk)

        return I[0]

    def member_faq_search(self):
        topk = 3
        embeddings_k = self.s_model.encode(self.HISTORY_CACHE[-1][0], normalize_embeddings=True).reshape(1, -1)
        distance, I = self.member_index.search(embeddings_k, topk)

        return I[0], distance[0]

    def member_cycles(self):
        if len(self.HISTORY_CACHE) == 1:
            self.HISTORY_CACHE[-1][1] = self.intention[self.HISTORY_CACHE[0][0]]
            self.member_counts[self.member_id] = 0

            return self.HISTORY_CACHE[-1][1]

        elif self.HISTORY_CACHE[-1][0] == 'End_this_turn':
            self.HISTORY_CACHE = []

            return ['', ['Thanks_word']]

        elif self.HISTORY_CACHE[-1][0] == 'Contact_':
            self.HISTORY_CACHE = []

            return ['', ['Contact_info_word']]

        else:
            answer_index, threshold = self.member_faq_search()
            if threshold[0] > 0.7:
              if self.member_counts[self.member_id] < 2:
                  self.HISTORY_CACHE[-1][1] = ['', ['Rewrite_word']]
                  self.member_counts[self.member_id] += 1
              else:
                  self.HISTORY_CACHE[-1][1] = ['Member_button', ['Contact_word']]
            else:
                answers = list(self.database_dict['member'].values())[answer_index[0]]
                self.HISTORY_CACHE[-1][1] = ['Member_button', [answers]]

            return self.HISTORY_CACHE[-1][1]

    def other_cycles(self):
        if self.counts[self.member_id] == self.QN[self.member_id] or self.HISTORY_CACHE[-1][0] == 'Recommend_':
            recomm_index = self.get_recomm_from_database()
            if self.purposes[self.member_id] == 'Foodie_':
              recomm_data = list(self.database_dict['restaurant'].values())
            elif self.purposes[self.member_id] == 'Viewpoint_':
              recomm_data = list(self.database_dict['viewpoint'].values())

            self.recomm_aricle_caches[self.member_id] = [recomm_data[i][0] for i in recomm_index]
            recomm_article = self.recomm_aricle_caches[self.member_id][:5]
            self.HISTORY_CACHE[-1][1] = ['Recommend_button_3', recomm_article]
            
            if self.counts[self.member_id] == self.QN[self.member_id]:
              self.counts[self.member_id] += 1

            return self.HISTORY_CACHE[-1][1]

        elif self.HISTORY_CACHE[-1][0] == 'More_':
            recomm_article = self.recomm_aricle_caches[self.member_id][5:]
            self.HISTORY_CACHE[-1][1] = ['Recommend_button_1', recomm_article]

            return self.HISTORY_CACHE[-1][1]

        elif self.HISTORY_CACHE[-1][0] == 'Fix_':
            if self.purposes[self.member_id] == 'Foodie_':
              self.HISTORY_CACHE[-1][1] = ['Foodie_fix_button', ['Fix_word_1']]
            elif self.purposes[self.member_id] == 'Viewpoint_':
              self.HISTORY_CACHE[-1][1] = ['Viewpoint_fix_button', ['Fix_word_1']]

            return self.HISTORY_CACHE[-1][1]

        elif self.HISTORY_CACHE[-1][0] in [i for i in self.QS[self.member_id]]:
            order = list(self.edit_orders[self.member_id].keys())
            check_order = str(self.QS[self.member_id].index(self.HISTORY_CACHE[-1][0]))
            if check_order in order:
              self.HISTORY_CACHE[-1][1] = [self.edit_orders[self.member_id][check_order], ['Fix_word_2']]
            else:
              self.HISTORY_CACHE[-1][1] = ['', ['Fix_word_3']]

            return self.HISTORY_CACHE[-1][1]

        elif len(self.HISTORY_CACHE) != 1 and self.HISTORY_CACHE[-2][0] in [i for i in self.QS[self.member_id]]:
            self.search_keys[self.member_id][self.HISTORY_CACHE[-2][0]] = self.HISTORY_CACHE[-1][0]
            self.HISTORY_CACHE[-1][1] = ['Fixed_recommend_button', ['Fixed_recommend_word']]

            return self.HISTORY_CACHE[-1][1]

        elif self.HISTORY_CACHE[-1][0] == 'Add_more':
            self.HISTORY_CACHE[-1][1] = ['', ['Add_more_word']]

            return self.HISTORY_CACHE[-1][1]

        elif len(self.HISTORY_CACHE) != 1 and self.HISTORY_CACHE[-2][0] == 'Add_more':
            recomm_index = self.get_recomm_from_database(self.HISTORY_CACHE[-1][0])
            if self.purposes[self.member_id] == 'Foodie_':
              recomm_data = list(self.database_dict['restaurant'].values())
            elif self.purposes[self.member_id] == 'Viewpoint_':
              recomm_data = list(self.database_dict['viewpoint'].values())
            self.recomm_aricle_caches[self.member_id] = [recomm_data[i][0] for i in recomm_index]
            recomm_article = self.recomm_aricle_caches[self.member_id][:5]
            self.HISTORY_CACHE[-1][1] = ['Recommend_button_2', recomm_article]

            return self.HISTORY_CACHE[-1][1]

        elif self.HISTORY_CACHE[-1][0] == 'End_this_turn':
            self.HISTORY_CACHE = []
            del self.recomm_aricle_caches[self.member_id]
            del self.search_keys[self.member_id]
            del self.counts[self.member_id]
            del self.edit_orders[self.member_id]
            del self.QS[self.member_id]
            del self.QN[self.member_id]
            del self.purposes[self.member_id]

            return ['', ['Thanks_word']]

        else:
            if self.counts[self.member_id] == 0:
              self.HISTORY_CACHE[-1][1] = self.HISTORY_CACHE[0][0][self.counts[self.member_id]]
              self.counts[self.member_id] += 1

              return self.HISTORY_CACHE[-1][1]

            elif self.counts[self.member_id] < self.QN[self.member_id]:
              self.HISTORY_CACHE[-1][1] = self.HISTORY_CACHE[0][0][self.counts[self.member_id]]
              self.counts[self.member_id] += 1

              return self.HISTORY_CACHE[-1][1]
            
            else:

              return ['', ['Error_remind_word']]

    def intention_detect(self, msg):
        embeddings_msg = self.s_model.encode(msg, normalize_embeddings=True)
        similarity = self.embeddings_c @ embeddings_msg.T
        s_score = similarity[np.argmax(similarity)]
        msg = self.similarity_intention[self.similarity_intention_cls[np.argmax(similarity)]]

    def history_save(self, msg):
        self.HISTORY_CACHE.append([msg.strip(),''])

    def predict(self, msg, member_id):
        self.member_id = member_id
        if self.member_id not in self.histories.keys():
            if self.intention_detect:
                msg, similarity_score = self.intention_detect(msg)
                if similarity_score < 0.8:                 
                    return ['', ['Remind_word_1']]
            self.histories[self.member_id] = []
            self.HISTORY_CACHE = []
        else:
            self.HISTORY_CACHE = self.histories[self.member_id]

        self.history_save(msg)

        if self.HISTORY_CACHE[0][0] == 'Member_':
            response = self.member_cycles()
            if self.HISTORY_CACHE != []:
              self.histories[self.member_id] = self.HISTORY_CACHE
            else:
              del self.histories[self.member_id]
              del self.member_counts[self.member_id]

            return response

        elif self.HISTORY_CACHE[0][0] == 'Planner_':
            if self.member_id not in self.travel_QAs.keys():
                self.travel_QAs[self.member_id] = {i:j for i,j in zip(self.question_cls[self.HISTORY_CACHE[0][0]],self.intention[self.HISTORY_CACHE[0][0]])}
                self.travel_QN[self.member_id] = len(self.travel_QAs[self.member_id])
                self.travel_counts[self.member_id] = 0

            response = self.travel_plan_cycles()
            if self.HISTORY_CACHE != [] and self.HISTORY_CACHE[-1][1] != '':
                self.histories[self.member_id] = self.HISTORY_CACHE
            elif self.HISTORY_CACHE != [] and self.HISTORY_CACHE[-1][1] == '':
                self.HISTORY_CACHE = self.HISTORY_CACHE[:-1]
                self.histories[self.member_id] = self.HISTORY_CACHE
            else:
                del self.histories[self.member_id]
            
            return response

        else:
            if type(self.HISTORY_CACHE[0][0]) != list and self.HISTORY_CACHE[0][0] in self.intention.keys():
                self.purposes[self.member_id] = self.HISTORY_CACHE[0][0]
                self.edit_orders[self.member_id] = self.question_order[self.HISTORY_CACHE[0][0]]
                self.QS[self.member_id] = self.question_cls[self.HISTORY_CACHE[0][0]]
                self.QN[self.member_id] = len(self.QS[self.member_id])
                self.HISTORY_CACHE[0][0] = self.intention[self.HISTORY_CACHE[0][0]]
                self.search_keys[self.member_id] = None
                self.counts[self.member_id] = 0

            response = self.other_cycles()
            if self.HISTORY_CACHE != [] and self.HISTORY_CACHE[-1][1] != '':
                self.histories[self.member_id] = self.HISTORY_CACHE
            elif self.HISTORY_CACHE != [] and self.HISTORY_CACHE[-1][1] == '':
                self.HISTORY_CACHE = self.HISTORY_CACHE[:-1]
                self.histories[self.member_id] = self.HISTORY_CACHE
            else:
                del self.histories[self.member_id]

            return response
