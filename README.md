# Symfony 4 Docker
## Minimal Runtime Environment [![Build Status](https://travis-ci.org/tulik/symfony-4-docker-runtime-env.svg?branch=master)](https://travis-ci.org/tulik/symfony-4-docker-runtime-env)  [![symfony 4 docker](https://img.shields.io/badge/dev-symfony%204-F7CA18.svg?style=flat)](https://github.com/tulik/symfony-4-docker-runtime-env) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tulik/symfony-4-docker-runtime-env/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tulik/symfony-4-docker-runtime-env/?branch=master) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/5335051b-cb89-44b0-9a2e-d455f623ab33/mini.png)](https://insight.sensiolabs.com/projects/5335051b-cb89-44b0-9a2e-d455f623ab33)



<p align="center">
  <img src="https://raw.githubusercontent.com/tulik/symfony-4-docker-runtime-env/master/documentation/images/logo.png">
</p>

# Table of content
- [See it working! Deployed with Travis and Kubernetes](#see-it-working)
- [Environment architecture](#environment-architecture)
- [Quick start](#quick-start)
- [Video tuturial](#video-tuturial)
- [Directory structure](#directory-structure)

## See it working! 
**[Deployed with Travis and Kubernetes](https://symfony-4-docker-runtime-env.tulik.info/)**
 - Further documentation about deployment will be provied.
 
# Environment architecture

<p align="center">
  <img src="https://raw.githubusercontent.com/tulik/symfony-4-docker-runtime-env/master/documentation/images/schema.png">
</p>

Now it has mocked support for MySQL databases.

# Quick start

```
$ git clone https://github.com/tulik/symfony-4-docker-runtime-env.git
$ cd symfony-4-docker-runtime-env
$ docker-compose up
```
Wait for containers to start, then to [http://localhost](http://localhost)

## Video tuturial
<p align="center">
	<a href="http://www.youtube.com/watch?feature=player_embedded&v=NIEKB5iRcOs
	" target="_blank"><img src="http://img.youtube.com/vi/NIEKB5iRcOs/0.jpg" 
	alt="Setup Symfony 4 with Docker in 5 minutes" width="480" height="320" border="0" /></a>
</p>

## Blackfire profiler
**[Blackfire.io](https://blackfire.io)** is continuous PHP Performance Testing. 

Register your trial, be able to profile both in development and with a paid subscription in production too!
Don't forget to get your **[Blackfire Companion](https://blackfire.io/docs/integrations/chrome)**.

[See call graph](https://blackfire.io/profiles/54e07b00-ead8-4d3b-a471-9334b3d28354/graph)

# Directory structure
```
symfony-4-docker-runtime-env
├── documentation
│   └── images
├── h2-proxy
│   └── conf.d
├── helm
│   └── symfony
│       ├── charts
│       └── templates
└── symfony
    ├── bin
    ├── config
    │   ├── packages
    │   │   ├── dev
    │   │   ├── prod
    │   │   └── test
    │   └── routes
    ├── docker
    │   ├── nginx
    │   │   └── conf.d
    │   ├── php
    │   └── varnish
    │       └── conf
    ├── public
    └── src
        ├── Controller
        ├── Entity
        ├── Migrations
        └── Repository
```

<sub><sub>
<hr noshade color="#FFFFFF" width="100%" size="1" style="padding:0; margin:8px 0 8px 0; border:none; width:100%; height: 1px; color:#FFFFFF; background-color: #FFFFFF" />

**Copyright Note:** Substantial portions of the solution was introduced by Kévin Dunglas.
</sub></sub>
