#Laravel 5.7 & JWT & CRUD for Library Management System 

#HOW TO INSTALL
###System require PHP > 7.1

### Step 1
copy .env.example to .env

Change mysql config to your env

``
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=homestead
  DB_USERNAME=homestead
  DB_PASSWORD=secret
``

run some php command from the root of project

``composer install``

``php artisan key:generate``

``php artisan migrate``

``php artisan migrate --seed``


And you ready to go

