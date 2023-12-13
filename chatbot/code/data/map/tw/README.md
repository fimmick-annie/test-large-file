1.Install the requirements first.

2.Directly use python to run recommand_function.py,(just for test)

3.Edit the "if __name__ == "__main__":" before connect to LINE or WhatsApp.

4.The similarity model will automatically download.

5.ideachat need T4 or V100 gpu to run it, it need 15GB GPU memory. The model would download automatically.

6. There are a lot of places need to be modified into button list.

If you don't have GPU to run it, please edit the faiss-gpu to faiss-cpu in requirements.txt

Key list for using: [美食++, 會員++, 給我一些靈感++]
