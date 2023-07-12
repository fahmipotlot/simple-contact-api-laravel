## Laravel Simple Contact API

Laravel Simple Contact API
- Register 
- Login
- CRUD Contact
- Testing
- Code coverage testing

## Requirements
1. PHP v8.2
2. PHP Composer installed

## Installation 

1. Clone repo `git clone git@github.com:fahmipotlot/simple-contact-api-laravel.git`
2. Copy .env file `cp .env.example .env`
3. Run `composer install`
4. Generate Key `php artisan key:generate`
5. Setup your local database credential to `.env`
6. Run `php artisan migrate`
7. Run `php artisan serve`
8. Run `composer test` for testing
9. Run `composer test-report` for testing and generate code coverage for run this step you must configure xdebug to your local environment, just follow this instructions https://xdebug.org/wizard

## Front End

https://github.com/fahmipotlot/simple-contact-app-vuejs