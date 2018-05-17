Build the PHP and Nginx Docker images:
```
docker build -t gcr.io/personal-dev-environment/php -t gcr.io/personal-dev-environment/php:latest symfony
docker build -t gcr.io/personal-dev-environment/nginx -t gcr.io/personal-dev-environment/nginx:latest -f symfony/Dockerfile.nginx symfony
docker build -t gcr.io/personal-dev-environment/varnish -t gcr.io/personal-dev-environment/varnish:latest -f symfony/Dockerfile.varnish symfony
```
Push your images to your Docker registry, example with Google Container Registry:
```
gcloud docker -- push gcr.io/personal-dev-environment/php
gcloud docker -- push gcr.io/personal-dev-environment/nginx
gcloud docker -- push gcr.io/personal-dev-environment/varnish
```

Deploy your API to the container:

```
helm install ./symfony/helm/symfony --namespace=symfony --name sf \
    --set php.repository=gcr.io/personal-dev-environment/php \
    --set nginx.repository=gcr.io/personal-dev-environment/nginx \
    --set secret=MyAppSecretKey \
    --set postgresql.postgresPassword=MyPgPassword \
    --set postgresql.persistence.enabled=true \
    --set corsAllowUrl='^https?://[a-z\]*\.mywebsite.com$'
```
