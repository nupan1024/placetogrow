# Instalación del proyecto 

Place to grow

Check out the [Project definition](https://david-valbuena.notion.site/Reto-af876a2875a3408c8095ab62408030fa).

## Tecnologías requeridas:
- Laravel 11
- MySql
- Vue 3.4
- PHP 8.2

## Setup proyecto
    $ git clone https://github.com/nupan1024/placetogrow.git
    $ cd placetogrow

## Installation

- composer install
- npm install
- cp .env.example .env
- update the .env file with the DB_ variables
- php artisan key:generate
- php artisan migrate --seed
- php artisan storage:link
- npm run build

## Development

- php artisan serve or create a vhost in apache or nginx
- npm run dev

## Configuration
- Set in .env values required for Place to pay
- If you run the seeders, you can sign in 
with email: admin@placetogrow.com and password: 12345678
- In case of error at the moment data load tables. 
Please, add `SANCTUM_STATEFUL_DOMAINS` in .env and set your local domain

## Command to create a user admin
    $ php artisan app:create-user
