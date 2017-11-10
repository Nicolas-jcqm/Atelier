<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 10/11/2017
 * Time: 08:09
 */

namespace App\Controllers;

use App\Models\Booking;
use App\Models\Item;
use App\Models\Lists;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class RecipientController
{

    private $user;
    private $listsArray;

    public function __construct($c)
    {
        $this->user;
        $this->listsArray = null;

        $this->view = $c->get('view');
        $this->logger = $c->get('logger');
        $this->model = $c->get('App\Repositories\UserRepository');
        $this->router = $c->get('router');
    }

    public function returnItemBooked($token){
        // Renvoi l'id List associé au Token
        $idList = Lists::select('id','isValidate')->where('token','=',$token)->first();
        if($idList->isValidate === 1) {
            $resIdList = $idList;
            // Renvoi la liste des item dans la liste
            $ListItem = Item::where('idList', '=', $resIdList->id)->get();
            // renvoi des réservations
            $booking = Booking::select('idItem', 'message', 'reserverName')->get();
            // Renvoi des Items de la liste, qui sont réservés
            $idItemBoo = array();
            foreach ($ListItem as $k => $v) {
                $isBooked = false;
                foreach ($booking as $k2 => $v2) {
                    $array = array();
                    if ($v->id === $v2->idItem) {
                        $array = array("title" => $v->title, "description" => $v->description, "picture" => $v->picture, "reserver" => $v2->reserverName, "message" => $v2->message);
                        array_push($idItemBoo, $array);
                    }
                }
            }
            /**
             * foreach ($idItemBoo as $k=>$v){
             * foreach ($v as $k2=>$v2){
             * echo $k2."      ".$v2."      <br>";
             * }
             * echo "<br>";
             * } */
        }else{
            $idItemBoo = null;
        }
        return $idItemBoo;
    }

    public function displayRecipient(Request $request, Response $response, $args){
        $url_form = $this->router->pathFor('viewRecipient',["token"=>$args['token']]);
        $itemsBooked = $this->returnItemBooked($args['token']);
        return $this->view->render($response, 'recipient.twig', ["url_form"=>$url_form,'erreurs'=>$erreurs=[],$args,'itemsBooked' => $itemsBooked]);
    }

}