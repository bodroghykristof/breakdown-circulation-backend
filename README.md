# Getting Started with Cocktail Land app with Laravel

<p align="center">
<a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="200"></a>
</p>


With this app you can search for cocktail recipes and see them on pictures. We used the Cocktail DB API. After registration and login, you can access more features like savibg your favorite cocktails and even making your own.

This app is the backend part of the Cocktail Land project.

Technologies used in project:

 - Redis
 - Maria DB

You can find the backend code for this app here.

## To run the application

In the project directory, you should run:

### composer install
### php artisan migrate
### php artisan update:cache
You need to install redis to make it work
### php artisan serve

Use http://localhost:8000 for API calls.
