## Bookbank Online Portal

* Clone/Fork this repo
* Install Composer "curl -sS https://getcomposer.org/installer | php"(Keep it global)
* Navigate to project directory(bookbank) and run "composer install"
* Create a database called "bookbank" and pipe mysql dump file to that database
* Edit "app/config/database.php" and add your mysql credentials in line 59,60
* Execute `php artisan migrate`

To start the development server, cd into the repo and run `php artisan serve`

* Work in progress *