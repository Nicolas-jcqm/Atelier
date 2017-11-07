<?php
/*
	Routes
	controller needs to be registered in dependency.php
*/

$app->get('/', 'App\Controllers\HomeController:dispatch')->setName('homepage');

$app->get('/users', 'App\Controllers\UserController:dispatch')->setName('userpage');

$app->get('/lists', 'App\Controllers\ListeController:displayLists')->setName('lists');