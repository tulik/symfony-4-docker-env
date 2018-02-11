# Symfony 4 Docker
## Minimal Runtime Environment [![Build Status](https://travis-ci.org/tulik/symfony-4-docker-runtime-env.svg?branch=master)](https://travis-ci.org/tulik/symfony-4-runtime-env)  [![symfony 4 docker](https://img.shields.io/badge/dev-symfony%204-F7CA18.svg?style=flat)](https://github.com/tulik/symfony-4-docker-runtime-env)



<p align="center">
  <img src="https://raw.githubusercontent.com/tulik/tulik.github.io/source/img/2018-02-10/sf4_docker_minimal_runtime_env.png">
</p>


### Minimal Symfony 4 Runtime Environment created with Docker.

# Enviroment details
* [PHP-FPM](https://php-fpm.org/)
* [Postgres](https://www.postgresql.org/)
* [Nginx](https://nginx.org/en/)
* [Adminer](https://www.adminer.org/)

# Run in 5 min - see video

<p align="center">
<iframe width="560" height="315" src="https://www.youtube.com/embed/vu7ygQ0b8NI" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
</p>

## How to start

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

