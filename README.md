# Symfony 4 Docker
## Minimal Runtime Environment [![Build Status](https://travis-ci.org/tulik/symfony-4-runtime-env.svg?branch=master)](https://travis-ci.org/tulik/symfony-4-runtime-env)

![alt](https://raw.githubusercontent.com/tulik/tulik.github.io/source/img/2018-02-10/sf4_docker_minimal_runtime_env.png)</center>

### Minimal Symfony 4 Runtime Environment created with Docker.

# Enviroment details
---
* [PHP-FPM](https://php-fpm.org/)
* [Postgres](https://www.postgresql.org/)
* [Nginx](https://nginx.org/en/)
* [Adminer](https://www.adminer.org/)

# Run in 5 min - see video

[![Setup Symfony 4 with Docker in 5 minutes](http://img.youtube.com/vi/vu7ygQ0b8NI/0.jpg)](http://www.youtube.com/watch?v=vu7ygQ0b8NI)</center>

## How to start

```bash
$ git clone https://github.com/tulik/symfony-4-runtime-env.git
$ cd symfony-4-runtime-env
$ docker-compose up
```

## Executing command inside of Docker container

```bash
$ docker-compose exec php bin/console doctrine:schema:update --force
```

or

```bash
$ docker-compose exec php composer req profiler
```

