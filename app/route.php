<?php
/*
	Routes
	controller needs to be registered in dependency.php
*/
	use App\Controllers;

$app->get('/', 'App\Controllers\HomeController:dispatch')->setName('homepage');

$app->get('/signup', 'App\Controllers\UserController:signup')->setName('signup');

$app->post('/signup', 'App\Controllers\UserController:validation_signup');

$app->get('/signin', 'App\Controllers\UserController:signin')->setName('signin');

$app->post('/signin', 'App\Controllers\UserController:validation_signin');

$app->get('/lists', 'App\Controllers\ListeController:displayLists')->setName('lists');

//ajouter middleware pour verifier si l'utilisateur qui clique est le createur de la liste//soit rajouter la verification dans la function
$app->get('/liste/{id}/generateSharingToken','App\Controllers\ListeController:generateSharingToken')->setName('token');

//ajouter middleware pour verifier si l'utilisateur qui clique est le createur de la liste//soit rajouter la verification dans la function
$app->get('/liste/{id}/generateSharingFinalToken','App\Controllers\ListeController:generateSharingFinalToken')->setName('tokenfinal');