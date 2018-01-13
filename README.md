# Symfony 4 minimal Runtime Enviroment
[![Build Status](https://travis-ci.org/tulik/symfony-4-runtime-env.svg?branch=master)](https://travis-ci.org/tulik/symfony-4-runtime-env)

Minimal Symfony 4 Runtime Environment created with Docker.

### Enviroment details
* PHP-FPM
* Postgres
* Nginx

## Usage

```
$ git clone https://github.com/tulik/symfony-4-runtime-env.git
$ cd symfony-4-runtime-env
$ docker-compose up -d
```

## Executing command inside of Docker container

```
$ docker-compose exec php bin/console doctrine:schema:update --force
```

or

```
$ docker-compose req profiler
```

**That's all!**
