
## Project name: Bouquet

## Description

This is a backend part of project written on PHP using Laravel framework, PostgreSQL database. 

## Deployment

- requirements: **Apache 2.4.58**, **PHP-8.3**, **MariaDB-10.6.16**
- settings: 
  - copy `.env.example` to `.env`;
  - edit `DB_` section in it
  - execute from the project root:
    - `composer install`
    - `php artisan migrate`
    - `php artisan key:generate`
    - `php artisan jwt:secret`
    - `php artisan storage:link`

- to launching the application, execute from the project root: `php artisan serve`
- run queue: `php artisan queue:work --daemon`.

## Development

- coding standards: `PSR-12`;
- run tests: `./vendor/bin/phpunit`;
- run PHP Code Sniffer: `./vendor/bin/phpcs`
- run Code Fixer: `./vendor/bin/phpcbf`
