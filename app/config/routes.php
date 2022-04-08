<?php

/**
 * ? Allowed origins: *
 * ? Allowed methods: GET, POST, PUT, DELETE
 * ? Allowed headers: clientRef, Content-Type, Authorization, X-Requested-With
 * ? Allowed credentials: true
 * ? Max age: 86400
 * -----------------------------------------------------------------------------
 * ? Availlable Middlewares:
 * ? - Auth: check if user is logged in with "JWT" or "API KEY in Header" and has the right permissions to access the route
 * ? - Validation: Sanitize input data and check if it is valid (e.g. email, password, etc.)
 * -----------------------------------------------------------------------------
 * ? - Auth MIDDLEWARE PARAMETERS:
 * ? - - guest: check if user is not logged in
 * ? - - user: check if user is logged in
 * ? - - admin: check if user is logged in and has the right permissions to access the route
 * ? - Validation MIDDLEWARE PARAMETERS:
 * ? - - field1|field2|field3: List of fields to check if they are valid (e.g. email, password, etc.)
 * -----------------------------------------------------------------------------
 * ? Example:
 * ? - GET ROUTE:
 * * $router->get('/', 'HomeController@index');
 * ? - POST ROUTE:
 * * $router->post('/', 'HomeController@index');
 * ? - PUT ROUTE:
 * * $router->put('/', 'HomeController@index');
 * ? - DELETE ROUTE:
 * * $router->delete('/', 'HomeController@index');
 * ? - ROUTE WITH MIDDLEWARE:
 * * $router->get('/', 'HomeController@index', ['Auth@role', Validation@field1|field2|field3...]);
 */
