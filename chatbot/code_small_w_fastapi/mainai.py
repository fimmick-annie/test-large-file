from fastapi import FastAPI
from recommend_function import Interactive

app = FastAPI()

# Interactive()
recomm_chat = Interactive()

@app.post("/chatting")
async def communicate(id: int, text :str):
    reply = recomm_chat.predict(text, id)
    print(f"incoming msg: {text} \t| reply: {reply}")
    return reply

# @app.post("/clean")
# async def clear_history(id: int):
#     clear = recomm_chat.clear(id)
#     return clear

@app.get("/health-check")
async def checking():
    return "200"
