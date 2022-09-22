## Project Fey

Project regarding automatic hygiene checking (CBP price control) to minimize wrong inputted price in marketplace.  

Instalation:
============================================================

    git clone https://github.com/icube-mage/delivery-project-fey.git  

    cd into project directory  

    composer install  

    php artisan migrate:refresh --seed

    npm install

    npm run build

Configuration:
============================================================
* rename *.env.example* file to *.env*
* setup database connection on DB_*
* change LOG_CHANNEL to daily
* for production change 
`APP_DEBUG to false` and `APP_ENV to production`
* run command `php artisan key:generate`


How To Cleanup History:
============================================================
* create crontab to run 
```
php artisan schedule:run
```
* or use manual command 
```
php artisan catalogprice:clean
```
