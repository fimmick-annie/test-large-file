aws --profile default configure set aws_access_key_id "AKIARYSWGJQ257FMKGZ5"
aws --profile default configure set aws_secret_access_key "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"
aws --profile default configure set region "ap-southeast-1"

aws s3 sync s3://20230915-laravel-testenv ./data/map/

#uvicorn app.mainai:app --host 0.0.0.0 --port 80 --reload-dir /code/app --reload
uvicorn mainai:app --host 0.0.0.0 --port 80 --reload-dir /code --reload
