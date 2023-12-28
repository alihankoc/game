# Game App For Kroppa Setup Instructions

This repository contains a Dockerized setup for a Laravel 10 application with PHP 8.2 and MySQL 8.0.

## Prerequisites

Make sure you have the following installed:

- Docker
- Docker Compose

## Setup Instructions

### 1. Clone the Repository

```bash
git clone https://github.com/alihankoc/game.git

cd game

cp .env.example .env #change credentials for your own setup

docker-compose up -d --build

docker-compose exec app php artisan key:generate

docker-compose exec app php artisan migrate
```

## Accessing the App

Once the containers are up and running, access your Laravel app at:

http://localhost

#### Please make sure that you have correctly provide the database information in environment file.

- The app service runs the Laravel application on port 80.
- The db service runs MySQL on port 3306.
- Make sure these ports are available on your local machine.
