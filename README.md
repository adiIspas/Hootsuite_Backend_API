# Hootsuite_Backend_API
API for adding and analysing transactions creating in PHP with [Symfony](http://symfony.com/). For storage transactions I used [MongoDB](https://www.mongodb.com/).

## Running

You can run the Docker environment using [docker-compose] (https://docs.docker.com/compose/) in `code` directory:

    $ docker-compose up

Or start from `run.sh` file.

## Describe Application

This application implements a simple REST API for adding and analysing transactions.

The app offer 3 methods for that.

### Adding transaction (POST Method)

JSON payload of the form: {“sender”: sender_id(integer), “receiver”: receiver_id(integer), “timestamp”: ts(integer), “sum”: x(integer)}

Call from `http://localhost:8000/transactions/`

### Getting transactions (GET Method)

Call from `http://localhost:8000/transactions/?user=user_param&day=day_param&threshold=threshold_param`

Where:
1. `user_param` are integer user id;
2. `day_param` are integer timestamp;
3. `threshold` are integer thresold for value of transactions.

### Getting balance (GET Method)

Call from `http://localhost:8000/transactions/?user=user_param&since=since_param&until=until_param`

Where:
1. `user_param` are integer user id;
2. `since_param` are integer timestamp for start day;
3. `until_param` are integer timestamp for end day.



