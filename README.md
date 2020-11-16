# The comuni Project

## Requirements

- [Laravel 6.x](https://laravel.com/docs/6.0#server-requirements)
- [Docker >= 18.06.1-ce](https://docs.docker.com/install)
- [Docker-compose >= 1.22.0](https://docs.docker.com/compose/install)
- [PHP >= 7.2.18](https://www.php.net/downloads.php)
- [Mysql >= 5.7](https://dev.mysql.com/downloads/installer/)
- [Nginx > = nginx/1.15.7](https://www.nginx.com/resources/wiki/start/topic/tutorials/install/)
- [Node >= v8.11.3](https://nodejs.org/en/download/)
- [Yarn >= 1.7.0](https://yarnpkg.com/en/docs/install#debian-stable)
- [FFMPEG 4.x](https://linuxize.com/post/how-to-install-ffmpeg-on-ubuntu-18-04/#installing-ffmpeg-4x-on-ubuntu)

## Setup

- Copy file `.env.example` to `.env`,
- Modify `.env` config file (optional). If you modify the `mysql` configurations in `.env` file, remember to modify the configurations in `docker-compose.yml` file too.

- Install or run Docker for the Development Environment

```BASH
docker-compose -f docker-compose.yml -f docker-compose.override.yml up -d

# Stop
docker-compose stop
```

- Install and run Docker for the Production Environment

```BASH
docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d
```

- `chmod` cache folders
```BASH
chmod -R 777 storage
chmod -R 777 bootstrap/cache
```
- Get src ffmpeg (find the path of the ffmpeg directory in the system to pass into SRC_FFMPEG in the env file)

```BASH
whereis ffmpeg
```

Set these environment variables (see .env file).

```BASH
DB_CONNECTION=mysql
DB_HOST=comuni_mysql
DB_PORT=3306
DB_DATABASE=comuni
DB_USERNAME=root
DB_PASSWORD=root

QUEUE_DRIVER=redis
REDIS_HOST=redis

MAIL_DRIVER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=example@gmail.com
MAIL_PASSWORD=*****
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=example@gmail.com
MAIL_FROM_NAME=comuni

SRC_FFMPEG="/snap/bin/ffmpeg"
```

### Installation

- Go into the `workspace` container

```BASH
docker exec -it comuni_workspace bash
```
- Install package PHP

```BASH
composer install
```
- Generate a new Application Key

```
php artisan key:generate
```

- Install node modules
```BASH
npm install
#or
yarn install
```

- Build
```BASH
npm run dev
#or
yarn run dev
```

- Run migration

```BASH

# Run migration
php artisan migrate --seed

# Or running outside the docker container
docker exec -it comuni_workspace php artisan migrate --seed
```

- Generate new Internal Password Grant OAuth2 clients.

```bash
php artisan passport:install
```


- Site will publish on 127.0.0.1:{`ports`} (`ports` config in docker-compose.yml > services > nginx > ports). Add domain to host file so we can access site by domain:{`ports`} (edit host in file ./ect/hosts)

```
127.0.0.1 comuni.local
```
- Asset project with domain

```
comuni.local:2025 or localhost:2025 or 127.0.0.1:2025
```

- If you want run project on your local instead of Docker, just skip all step about docker and create virtual host. And modify `.env` config of `DB_HOST`, `DB_HOST_TEST` to `127.0.0.1`

Run the development PHP Built-in server.

> After running docker, Application run in port 80. Visit http://localhost;

## Queue
Run queue comman.

```
php artisan queue:listen
```

## Contributing

To create a pull request, you need to run the following commands to make sure that your code is valid.

Run to check Javascript coding conventions (defined in .eslintrc.json file).

```bash
yarn run eslint
```

fix  javarscript convention

```
yarn run eslint:fix
```
or

```
yarn run watch:eslint
```

Run check phpcs coding venventions with rule PSR2

```bash
docker exec -it comuni_workspace phpcs --standard=PSR2 app routes config
```
or exec to container `comuni_workspace` and run code

```bash
npm run psr2
```
## Run Schedule

+ Config crontab publishing the draft posts:
```
crontab -e
``` 

```
* * * * * cd /path-to-project && php artisan public:post >> /dev/null 2>&1
```
