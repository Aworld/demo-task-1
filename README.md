# User statistical data about posts

Api provides statistical data about random users and their posts in last 6 months:

  - Average character length of a post / month 
  - Longest post by character length / month 
  - Total posts split by week 
  - Average number of posts per user / month

## Installation

Api requires [Docker](https://www.docker.com/) to run.

Get your environment for the first time:

```sh
$ cd demo-task-1
$ docker-compose up -d --build
```

Create .env file for your environment

```sh
$ cp .env.sample .env
```
Under `.env` add required credentials (client_id, email, name). Open your browser and type `http://localhost:8888` and you are good to go!


## Troubleshooting
Sometimes bad things happen to good people, so if after `docker-compose` command you cannot see any output in browser, here are few hints what could be checked:

  - Check if your docker container port is not conflicting. If it does, you can amend port number under `docker-compose.yaml`
  - You might be missing vendor folder in the root of this project. Please, run `composer install` from the terminal. In case you do not have composer installed, not a problem - type `docker exec -it task-api bash` and once you get inside container, run `composer install` there. 
