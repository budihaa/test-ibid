<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', 'BookController@index');
$router->get('/{id}', 'BookController@show');
$router->post('/', 'BookController@store');
$router->put('/{id}', 'BookController@update');
$router->delete('/{id}', 'BookController@delete');
