<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () use ($router) {
    $books = \App\Models\Book::all();
    return response($books);
});
