<?php

// Pages


// Home page
$router->get('public', 'Pages@index');
// About page
$router->get('public/about', 'Pages@about');

// Posts

/**
 * Get Routes
 */
// Go to Posts index page
$router->get('public/posts', 'Posts@index');
// Show Post Details
$router->get('public/post', 'Posts@show');
// Create new Post page
$router->get('public/post/create', 'Posts@create');
// Edit Post page
$router->get('public/post/edit', 'Posts@edit');
// Delete Post page
$router->get('public/post/delete', 'Posts@delete');

/**
 * Post Routes
 */
// Store new post
$router->post('public/post/store', 'Posts@store');
// Update post by ID
$router->post('public/post/update', 'Posts@update');
// Delete post by ID
$router->post('public/post/destroy', 'Posts@destroy');

/**
 * Experimental Routes
 */
// Update post by ID
// $router->put('public/post', 'Posts@update');
// Delete post by ID
// $router->delete('public/post', 'Posts@destroy');
