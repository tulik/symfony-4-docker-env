# Mute all `make` specific output. Comment this out to get some debug information.
.SILENT:

.PHONY:  box-build-php box-build-web box-build-app box-build

# If the command does not exist in this makefile
.DEFAULT default:
	if [ "$@" != "" ]; then echo "Command '$@' not found."; fi; \
	$(MAKE) help; \
	if [ "$@" != "" ]; then exit 2; fi; \

help:
	@echo "Usage:"
	@echo "     make [command]"
	@echo
	@echo "Available commands:"
	@echo ""
	@grep '^[^#[:space:]].*:' Makefile | grep -v '^default' | grep -v '^\.' | grep -v '=' | grep -v '^_' | sed 's/:\([^#]*\)//' | column -c 2 -s '#' -t


# Generic php image used as a base by all the workers/fpm servers
PHP_IMAGE="symfony.azurecr.io/php"

# Generic nginx image used as by php-fpm
NGINX_IMAGE="symfony.azurecr.io/nginx"
# HTTP2 proxy - should not be used in production
HTTP2_PROXY="symfony.azurecr.io/h2-proxy"

build-dev: # Build develepment images
	docker build --no-cache -t ${PHP_IMAGE}:dev -f symfony/docker/Dockerfile --target symfony_dev .
	docker build --no-cache -t ${NGINX_IMAGE}:dev -f symfony/docker/Dockerfile --target nginx_dev .
	docker build --no-cache -t ${HTTP2_PROXY}:dev -f symfony/docker/dev/h2-proxy/Dockerfile --target h2_proxy_dev .

build-ci: # Build continues integration images
	docker build --no-cache -t ${PHP_IMAGE}:ci -f symfony/docker/Dockerfile --target symfony_ci .
	docker build --no-cache -t ${NGINX_IMAGE}:ci -f symfony/docker/Dockerfile --target nginx_ci .
	docker build --no-cache -t ${HTTP2_PROXY}:ci -f symfony/docker/ci/h2-proxy/Dockerfile --target h2_proxy_ci .

az-login: # Login to azure portal
	az login
az-cr-login: az-login # Login to the container registry (necessary to push images)
	az acr login --name symfony

az-push-ci: az-cr-login # Push the API application image to the CR
	docker push ${PHP_IMAGE}:ci
	docker push ${NGINX_IMAGE}:ci
	docker push ${HTTP2_PROXY}:ci

up: # Start the development environment
	docker-compose -f symfony/docker/dev/docker-compose.yaml up

down: # Stop the development environment
	docker-compose -f symfony/docker/dev/docker-compose.yaml down

shell: # Start a shell in the dev.api container
	docker-compose -f symfony/docker/dev/docker-compose.yaml exec php sh
