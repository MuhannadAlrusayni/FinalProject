Group B FinalProject

Installation
```sh
sudo apt update
sudo apt install composer  
composer install

npm install && npm run dev

cp .env.example .env

php artisan key:generate
```
Create DB For this project.

Add your DB name and your mysql user & password to .env file.

Change username function in ./vendor/laravel/ui/auth-backend/AuthenticatesUsers.php

from 
```php
 public function username()
    {
        return 'email';
    }

```
to

```php
public function username()
    {
        return 'national_id';
    }

```
To reset database run
```sh
php artisan migrate:fresh --seed
```

Do the following to use Sqlite

in terminal run:
```sh
touch ./database/database.sqlite
```

edit your ".env" file (DB variables)
```
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite
```
