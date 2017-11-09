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
echo ' liste item'.$listItem;
        echo '<br>';

        foreach ($idBook as $key=>$val){
            echo 'val'.$val;
            echo '<br>';

            foreach ($listItem as $key=>$val2){
                echo 'vale2'.$val2->id;
                echo '<br>';
                echo 'new';
                echo '<br>';

                if($val2->id != $val->idItem ){

                    array_push($listItemNoBook,$val2);
                }
            }

        }
        return $this->view->render($response, 'listGuest.twig', ["url_form"=>$url_form,'erreurs'=>$erreurs=[],$args,"item"=>$listItem]);

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
                var_dump( 'item', $idItem);
                $book->idItem=1;
                $book->save();

                $this->displayListGuest($request,  $response, $args);


            }

        }
    }
}