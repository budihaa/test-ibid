<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->group(['middleware' => 'auth'], function () use ($router) {
        // Soal 1
        $router->get('/books', 'BookController@index');
        $router->get('/books/{id}', 'BookController@show');
        $router->post('/books/', 'BookController@store');
        $router->put('/books/{id}', 'BookController@update');
        $router->delete('/books/{id}', 'BookController@delete');
    });

    // Soal 2
    $router->post('/login', 'AuthController@login');
    $router->post('/register', 'AuthController@register');
    $router->post('/logout', 'AuthController@logout');

    // Soal 3
    $router->get('/products', 'ProductController@index');
});

// Soal 7
$router->get('/filter', 'ArrayController@index');

// Soal 9
$router->get('/debug-sentry', function () {
    throw new Exception('My second Sentry error!');
});

// Soal 10
$router->get('/send-email', 'EmailController@index');
