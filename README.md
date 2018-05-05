# doge-much-buddies
# Requirements
- XAMPP with PHP >= 7.1
# Start XAMPP Apache and MySQL server
# Set up database
- Create MySQL database name dapp, username is root and no password
php artisan vendor:publish --tag=passport-migrations
php artisan migrate
php artisan passport:install

// Run
php artisan serve
- Create an user account

// Seed
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=DogSeeder
php artisan db:seed --class=PostSeeder
php artisan db:seed --class=DogImageSeed
php artisan db:seed --class=PostReactSeeder
php artisan db:seed --class=PostCommentSeeder
php artisan db:seed --class=PostTagSeeder

// Link Storage
php artisan storage:link