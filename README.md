# Installation & Configuration

### Composer Install

**MacOS**
```shell
docker run --rm --interactive --tty --volume $PWD:/app composer install
```

**Windows (PowerShell)**
```shell
docker run --rm --interactive --tty --volume ${PWD}:/app composer install
```

**Windows (Cmd)**
```shell
docker run --rm --interactive --tty --volume %cd%:/app composer install
```

### Run
```shell
docker compose up
```

## Migration

```shell
docker-compose exec laravel php artisan migrate   
```
