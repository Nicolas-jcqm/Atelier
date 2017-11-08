<?php
/*
	Routes
	controller needs to be registered in dependency.php
*/

$app->get('/', 'App\Controllers\HomeController:dispatch')->setName('homepage')->add($middleware_need_no_co);

$app->get('/signup', 'App\Controllers\UserController:signup')->setName('signup')->add($middleware_need_no_co);

$app->post('/signup', 'App\Controllers\UserController:validation_signup');

$app->get('/signin', 'App\Controllers\UserController:signin')->setName('signin')->add($middleware_need_no_co);

$app->post('/signin', 'App\Controllers\UserController:validation_signin');

//ajouter middleware pour verifier si l'utilisateur qui clique est le createur de la liste//soit rajouter la verification dans la function
$app->get('/liste/{id}/generateSharingToken','App\Controllers\ListeController:generateSharingToken')->setName('token');

//ajouter middleware pour verifier si l'utilisateur qui clique est le createur de la liste//soit rajouter la verification dans la function
$app->get('/liste/{id}/generateSharingFinalToken','App\Controllers\ListeController:generateSharingFinalToken')->setName('tokenfinal');

$app->get('/homeCo','App\Controllers\UserController:homeCo')->setName('homeCo')->add($middleware_need_co);
$app->get('/disconnect','App\Controllers\UserController:disconnect')->setName('disconnect')->add($middleware_need_co);
