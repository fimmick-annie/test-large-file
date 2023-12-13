
from fastapi import FastAPI
# import json
# import datetime
from recommand_function import Interactive

app = FastAPI()

# Interactive()
recomm_chat = Interactive()

@app.post("/chatting")
async def communicate(id: int, text :str):
    reply, id = recomm_chat.predict(text, id)
    return reply


@app.post("/clean")
async def clear_history(id: int):
    clear = recomm_chat.clear(id)
    return clear

# @app.post("/all-clean")
# async def clear_all():
#     clear = recomm_chat.allclear()
#     return clear