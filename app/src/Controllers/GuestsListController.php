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
use App\Models\Booking;
use App\Models\Comment;
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
        $listItemNoBook=array();
        $url_form = $this->router->pathFor('viewGuest',["token"=>$args['token']]);

        $listItemGuestsId = Lists::select('id')->where('token','=',$args['token'])->first();
        $listItem = Item::where('idList','=',$listItemGuestsId->id)->get();


        $idBook= Booking::select('idItem')->get();
        $exist=true;
        foreach ($listItem as $key=>$val2){
            foreach ($idBook as $key=>$val){


                if( $val->idItem === $val2->id ){
                   $exist=false;
                    break;
                }
            }
            if($exist){
                array_push($listItemNoBook,$val2);
            }
            $exist=true;
        }
        
        $comment= Comment::where('idlist','=',$listItemGuestsId->id)->latest()->get();
        return $this->view->render($response, 'listGuest.twig', ["url_form"=>$url_form,'erreurs'=>$erreurs=[],"args"=>$args['token'], "comment"=>$comment,"item"=>$listItemNoBook]);

    }
    public function bookItem(Request $request, Response $response, $args){
        $parsedBody = $request;
        $erreurArray=array();

        if(isset($parsedBody) && $parsedBody->getParsedBodyParam('envoi') === "Reserver cette item" ) {
            $reserverName = $parsedBody->getParsedBodyParam('reserverName');
            $message = $parsedBody->getParsedBodyParam('message');
            $idItem= $parsedBody->getParsedBodyParam('iditem');

            if (empty($reserverName)) {
                $erreurReserverName = "Merci d'entrer un nom";
                array_push($erreurArray, $erreurReserverName);
            } else {
                if ($reserverName != filter_var($reserverName, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)) {
                    $erreurReserverNameFiltre = "Merci d'entrer un prénom valide";
                    array_push($erreurArray, $erreurReserverNameFiltre);
                }
            }
            if(empty($message)){
                $erreurMessage="Merci d'entrer un nom";
                array_push($erreurArray,$erreurMessage);
            }else{
                if($message != filter_var($message, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)){
                    $erreurMessageFiltre ="Merci d'entrer un prénom valide";
                    array_push($erreurArray,$erreurMessageFiltre);
                }
            }

            if (sizeof($erreurArray)===0){
                $book = new Booking();
                $book->id = uniqid();
                $book->reserverName =$reserverName;
                $book->message=$message;
$book->idItem=$parsedBody->getParsedBodyParam('idItem');
                var_dump( 'item', $idItem);
                $book->save();

                $this->displayListGuest($request,  $response, $args);


            }

        }
    }
}