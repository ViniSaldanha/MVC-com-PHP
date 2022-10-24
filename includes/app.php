<?php


require __DIR__.'/../vendor/autoload.php';


use \App\Utils\View;
use \WilliamCosta\DotEnv\Environment;
use \WilliamCosta\DatabaseManager\Database;
use \App\Http\Middleware\Queue as MiddlewareQueue;


Environment::load(__DIR__.'/../');

Database::config(
    getEnv('DB_HOST'),
    getEnv('DB_NAME'),
    getEnv('DB_USER'),
    getEnv('DB_PASS'),
    getEnv('DB_PORT')
);


define('URL',getenv('URL'));

View::init([
    'URL' => URL
]);

MiddlewareQueue::setMap([
    'maintenance' => \App\Http\Middleware\Maintenance::class,
    'required-admin-logout' => \App\Http\Middleware\RequireAdminLogout::class,
    'required-admin-login' => \App\Http\Middleware\RequireAdminLogin::class
]);


MiddlewareQueue::setDefault([
    'maintenance'
]);

