# Installation & Configuration

## Run

```shell
docker run --rm --interactive --tty --volume $PWD:/app composer install
docker compose up
```

## Migration

```shell
docker-compose exec laravel php artisan migrate   
```
