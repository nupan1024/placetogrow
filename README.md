# Instalación del proyecto 

Place to grow

Check out the [Project definition](https://david-valbuena.notion.site/Reto-af876a2875a3408c8095ab62408030fa).

## Tecnologías requeridas:
- Laravel
- MySql
- Vue

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
