This project is based on https://github.com/ymcui/Chinese-LLaMA-Alpaca-2.  
Training for lora need based model, based model weight could get from this link: https://drive.google.com/drive/folders/1YNa5qJ0x59OEOI7tNODxea-1YvMPoH05?usp=share_link ,and put it in models folder.  
Training lora model need to use A100 GPU on Colab, it use 33+ gb gpu memory.  
After training if you want to test the demo tool(Gradio), you have to combine the based model and lora model as one model then put it in combine_models folder.  
For using Gradio, it need to use V100 on colab, 15.6 gb of gpu memory is needed.

Using gradio_demo.py in scripts/inference/ 
