FROM python:3.10.13-slim

ENV TZ=Asia/Hong_Kong
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get -y update && apt-get install -y curl unzip

RUN mkdir -p /aws
WORKDIR /aws
RUN curl "https://awscli.amazonaws.com/awscli-exe-linux-x86_64.zip" -o "awscliv2.zip"
RUN unzip awscliv2.zip
RUN ./aws/install
RUN rm awscliv2.zip

RUN mkdir -p /scripts
WORKDIR /code

RUN python -m pip install --upgrade pip
RUN python -m pip install --no-cache-dir --upgrade uvicorn fastapi 

COPY ./chatbot/code_small_w_fastapi/requirements.txt /scripts/requirements.txt
RUN python -m pip install --no-cache-dir --upgrade -r /scripts/requirements.txt

COPY ./startup.sh /scripts/startup.sh
COPY ./chatbot/code_small_w_fastapi /code

CMD ["sh", "/scripts/startup.sh"]

