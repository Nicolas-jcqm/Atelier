<?php
/*
	Routes
	controller needs to be registered in dependency.php
*/

$app->get('/', 'App\Controllers\HomeController:dispatch')->setName('homepage');

$app->get('/signup', 'App\Controllers\UserController:signup')->setName('signup');

$app->post('/signup', 'App\Controllers\UserController:validation_signup');

$app->get('/signin', 'App\Controllers\UserController:signin')->setName('signin');

$app->post('/signin', 'App\Controllers\UserController:validation_signin');

$app->get('/lists', 'App\Controllers\ListeController:displayLists')->setName('lists');

$app->get('/homeCo','App\Controllers\UserController:homeCo')->setName('homeCo');
