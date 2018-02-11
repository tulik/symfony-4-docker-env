# Symfony 4 Docker - Minimal Runtime Environment
[![Build Status](https://travis-ci.org/tulik/symfony-4-runtime-env.svg?branch=master)](https://travis-ci.org/tulik/symfony-4-runtime-env)

Minimal Symfony 4 Runtime Environment created with Docker.

### Enviroment details
* [PHP-FPM](https://php-fpm.org/)
* [Postgres](https://www.postgresql.org/)
* [Nginx](https://nginx.org/en/)
* [Adminer](https://www.adminer.org/)

## Usage

```
$ git clone https://github.com/tulik/symfony-4-runtime-env.git
$ cd symfony-4-runtime-env
$ docker-compose up
```

## Executing command inside of Docker container

```
$ docker-compose exec php bin/console doctrine:schema:update --force
```

or

```
$ docker-compose exec php composer req profiler
```

**That's all!**
