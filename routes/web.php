<?php

/** @var \Laravel\Lumen\Routing\Router $router */

// Soal 1
$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('/books', 'BookController@index');
    $router->get('/books/{id}', 'BookController@show');
    $router->post('/books/', 'BookController@store');
    $router->put('/books/{id}', 'BookController@update');
    $router->delete('/books/{id}', 'BookController@delete');
});

// Soal 3
$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('/firebase', 'ProductController@index');
});

// Soal 7
$router->get('/filter', 'ArrayController@index');
