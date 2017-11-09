<?php
/*
	Routes
	controller needs to be registered in dependency.php
*/

$app->get('/', 'App\Controllers\HomeController:dispatch')->setName('homepage')->add($middleware_need_no_co);

//authentification

$app->get('/signup', 'App\Controllers\UserController:signup')->setName('signup')->add($middleware_need_no_co);

$app->post('/signup', 'App\Controllers\UserController:validation_signup');

$app->get('/signin', 'App\Controllers\UserController:signin')->setName('signin')->add($middleware_need_no_co);

$app->post('/signin', 'App\Controllers\UserController:validation_signin');

$app->get('/homeCo','App\Controllers\UserController:homeCo')->setName('homeCo')->add($middleware_need_co);

$app->get('/disconnect','App\Controllers\UserController:disconnect')->setName('disconnect')->add($middleware_need_co);


$app->get('/lists', 'App\Controllers\ListeController:displayLists')->setName('lists');


$app->get('/homeCo/{id}/item', 'App\Controllers\ItemController:viewItem')->setName('itemview');


$app->post('/homeCo/itemadd', 'App\Controllers\ItemController:addItem')->setName('itemadd')->add($middleware_need_co);

$app->get('/viewGuest/{token}', 'App\Controllers\GuestsListController:displayListGuest')->setName('viewGuest');



//ajouter middleware pour verifier si l'utilisateur qui clique est le createur de la liste//soit rajouter la verification dans la function
$app->get('/liste/{id}/generateSharingToken','App\Controllers\ListeController:generateSharingToken')->setName('token');

//ajouter middleware pour verifier si l'utilisateur qui clique est le createur de la liste//soit rajouter la verification dans la function
$app->get('/liste/{id}/generateSharingFinalToken','App\Controllers\ListeController:generateSharingFinalToken')->setName('tokenfinal');

$app->get('/liste/{id}/commentList','App\Controllers\ListeController:commentList')->setName('comment');

$app->post('/homeCo/{id}/addComment','App\Controllers\ListeController:addCommentList');

$app->get('/liste/{id}/checkAndUpValidityDate','App\Controllers\ListeController:checkAndUpValidityDate')->setName('checklistdate');

$app->get('/liste/{id}/ValidateList','App\Controllers\ListeController:ValidateList')->setName('ValidateList');

//Reservation
$app->post('liste/{id}/book', 'App\Controllers\ItemController:bookItem');

//creation d'une liste
$app->get('/creatList', 'App\Controllers\ListeController:creatList')->setName('creatList')->add($middleware_need_co);
$app->post('/creatList', 'App\Controllers\ListeController:validation_creatList');
