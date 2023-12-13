import torch
import argparse
from transformers import AutoModelForCausalLM, AutoTokenizer
from transformers.generation import GenerationConfig
from zhconv import convert

class Ideachat:
    def __init__(self):
        parser = argparse.ArgumentParser()
        parser.add_argument('--chat_model', default='Qwen/Qwen-14B-Chat-Int4', type=str, help='chat model and tokenizer path')
        args = parser.parse_args(args=[])

        self.device = torch.device(0) if torch.cuda.is_available() else torch.device('cpu')
        self.tokenizer = AutoTokenizer.from_pretrained(args.chat_model, trust_remote_code=True)
        self.model = AutoModelForCausalLM.from_pretrained(args.chat_model, device_map="auto", trust_remote_code=True).eval()
        self.model.generation_config = GenerationConfig.from_pretrained(args.chat_model, trust_remote_code=True)
        self.max_memory = 4096
        self.histories = {}

    def run(self, user_input, member_id):          
        if user_input == '對話終止':
          self.history_cache = None
          del self.histories[member_id]
          torch.cuda.empty_cache()
          return '終止++'
        else:
          if member_id not in self.histories.keys():
            self.histories[member_id] = None

          response, self.histories[member_id] = self.model.chat(self.tokenizer, user_input, history=self.histories[member_id])

          texts = ' '.join([' '.join(i) for i in self.histories[member_id]])
          input_length = len(self.tokenizer.encode(texts, add_special_tokens=True))

          if input_length > self.max_memory:
            self.histories[member_id] = [self.histories[member_id][0]] + self.histories[member_id][2:]
            print('Over max memory, discard some sentences.')

          torch.cuda.empty_cache()
          return convert(response, 'zh-tw')