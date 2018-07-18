# Symfony 4 Docker
## Minimal Runtime Environment [![Build Status](https://travis-ci.org/tulik/symfony-4-docker-runtime-env.svg?branch=master)](https://travis-ci.org/tulik/symfony-4-docker-runtime-env)  [![symfony 4 docker](https://img.shields.io/badge/dev-symfony%204-F7CA18.svg?style=flat)](https://github.com/tulik/symfony-4-docker-runtime-env)



<p align="center">
  <img src="https://raw.githubusercontent.com/tulik/tulik.github.io/source/img/2018-02-10/sf4_docker_minimal_runtime_env.png">
</p>


### Minimal Symfony 4 Runtime Environment created with Docker.
Deployed with Travis and Kubernetes running on: [https://symfony-4-docker-runtime-env.tulik.info/](https://symfony-4-docker-runtime-env.tulik.info/)

# Enviroment details
* [PHP-FPM](https://php-fpm.org/)
* [Postgres](https://www.postgresql.org/)
* [Nginx](https://nginx.org/en/)
* [Adminer](https://www.adminer.org/)

# Run in 5 min - see video

<p align="center">
	<a href="http://www.youtube.com/watch?feature=player_embedded&v=vu7ygQ0b8NI
	" target="_blank"><img src="http://img.youtube.com/vi/vu7ygQ0b8NI/0.jpg" 
	alt="Setup Symfony 4 with Docker in 5 minutes" width="480" height="320" border="0" /></a>
</p>

## How to start

```
$ git clone https://github.com/tulik/symfony-4-docker-runtime-env.git
$ cd symfony-4-docker-runtime-env
$ docker-compose up -d
```

## Executing command inside of Docker container

```
$ docker-compose exec php bin/console doctrine:schema:update --force
```

or

```
$ docker-compose exec php composer req profiler

```

## &nbsp;

**Copyright Note:** Substantial portions of the solution was introduced by [Kévin Dunglas](https://github.com/dunglas).
