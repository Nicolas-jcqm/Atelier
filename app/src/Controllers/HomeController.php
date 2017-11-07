<?php

namespace App\Controllers;

use App\Models\Creator;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class HomeController
{
    private $view;
    private $logger;
	private $user;
    private $router;

    public function __construct($c)
    {
        $this->view = $c->get('view');
        $this->logger = $c->get('logger');
        $this->model = $c->get('App\Repositories\UserRepository');
        $this->router = $c->get('router');
    }

    public function dispatch(Request $request, Response $response, $args)
    {
        $this->logger->info("Home page action dispatched");
		echo $_SESSION['creatorCo'];
        $name = Creator::find($_SESSION['creatorCo'])->name;
        $this->view->render($response, 'hello.twig',["name"=>$name]);
		
        return $response;
    }
}