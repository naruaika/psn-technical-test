# Technical Test - Backed Engineer

A simple customer RESTFul API service.

## Requirements

- GNU/Linux: [Docker Compose](https://docs.docker.com/compose/install/)
- macOS: [Docker Desktop](https://www.docker.com/products/docker-desktop/)
- Windows: [WSL2](https://docs.docker.com/desktop/windows/wsl/) and [Docker Desktop](https://www.docker.com/products/docker-desktop/)

## Setup

To run the application, execute below commands in terminal:

```sh
# Clone this repository
git clone git@github.com:naruaika/psn-technical-test.git

cd psn-technical-test

# Setup the project
cp .env.example .env
docker run --rm --interactive --tty --volume $PWD:/app --user $(id -u):$(id -g) composer install

# Run the project
vendor/bin/sail up
```

Open the terminal of the Laravel application container and execute above commands to setup the application:

```sh
php artisan key:generate
php artisan migrate
```

In case you want to seed the database with dummy data, you can execute below command in terminal:

```sh
php artisan migrate:fresh --seed
```

To run the application tests, you can execute below command in terminal:

```sh
php artisan test
```

## Design Desicions

### Database Schemas

<!-- UUID -->

<!-- Customer is not user -->

<!-- Reusable phone number -->

<!-- On update/delete cascade -->

...

### API Routes

<!-- Versioning -->

...

### Logging

<!-- System Security vs User Privacy -->

...
