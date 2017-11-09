<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 09/11/2017
 * Time: 14:17
 */

namespace App\Controllers;

use App\Models\Item;
use App\Models\Lists;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class GuestsListController
{

    private $view;
    private $logger;
    private $router;

    public function __construct($c)
    {
        $this->view = $c->get('view');
        $this->logger = $c->get('logger');
        $this->model = $c->get('App\Repositories\UserRepository');
        $this->router = $c->get('router');

    }

    public function displayListGuest(Request $request, Response $response, $args){
        $url_form = $this->router->pathFor('viewGuest',["token"=>$args['token']]);
        $listItemGuestsId = Lists::select('id')->where('token','=',$args['token'])->first();
        $listItem = Item::where('idList','=',$listItemGuestsId->id)->get();
        return $this->view->render($response, 'listGuest.twig', ["url_form"=>$url_form,'erreurs'=>$erreurs=[],$args,"item"=>$listItem]);
    }
}