<?php

/**
 * Routes Definition
 * 
 * ? Headers:
 * ? - Allowed origins: $_ENV['CLIENT_ADDRESS']
 * ? - Allowed methods: GET, POST, PUT, DELETE, OPTIONS
 * ? - Allowed headers: Content-Type, Authorization, X-Requested-With
 * ? - Allowed credentials: true
 * ? - Max age: 86400
 * -----------------------------------------------------------------------------
 * ? Availlable Middlewares:
 * ? - Auth: check if user is logged in with "JWT" or "API KEY in Header" and has the right permissions to access the route
 * ? - Validation: Sanitize input data and check if it is valid (e.g. email, password, etc.)
 * -----------------------------------------------------------------------------
 * ? Auth MIDDLEWARE FLAGS:
 * ? - - guest: check if user is not logged in
 * ? - - user: check if user is logged in
 * ? - - admin: check if user is logged in and has the right permissions to access the route
 * 
 * ? Validation MIDDLEWARE PARAMETERS:
 * ? - - field1|field2|field3: List of fields to check if they are valid (e.g. email, password, etc.)
 * ? - - scope: Scope of the validation rules (e.g. all, Example1, Example2, etc.)
 * -----------------------------------------------------------------------------
 * ? Example:
 * 
 * ? - GET ROUTE:
 * * $router->get('/', 'HomeController@index');
 * 
 * ? - POST ROUTE:
 * * $router->post('/', 'HomeController@index');
 * 
 * ? - PUT ROUTE:
 * * $router->put('/', 'HomeController@index');
 * 
 * ? - DELETE ROUTE:
 * * $router->delete('/', 'HomeController@index');
 * 
 * ? - ROUTE WITH MIDDLEWARES:
 * * $router->get('/', 'HomeController@index', ['Auth@guest', Validation@email|password|etc.@Example1']);
 * 
 * ? - ROUTE WITH CUSTOM FUNCTION:
 * * $router->get('/', fn($data) => {
 * *     // Do something
 * * });
 * 
 * ? - ROUTE WITH CUSTOM FUNCTION AND MIDDLEWARES:
 * * $router->get('/', fn($data) => {
 * *     // Do something
 * * }, ['Auth@guest', Validation@email|password|etc.@Example1']);
 * 
 * @package App\Config
 * @author Mohammed-Aymen Benadra
 */
