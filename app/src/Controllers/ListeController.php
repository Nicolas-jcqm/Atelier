<?php

namespace App\Controllers;

use App\Models\Lists;
use App\Models\Creator;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Illuminate\Database\Eloquent\Model;

final class ListeController
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

    public function displayLists(){
        $this->user = 1;
        // $this->>user = $_SESSION[''];
        $this->listsArray = Lists::where('idCreator','=',$this->user)->get();
        foreach ($this->listsArray as $l){
            echo $l->title . '    ' . $l->description . '    '.'<br>';
        }
    }

    /*
     * Function qui genere un token, l'ajoute a la base et le renvoi
     * destiné au créateur, qui pourra le partager
     * idliste l'id de la liste actuelle
     * retourne le token en cas de reussite ou false en cas d'echec
     */
    public function generateSharingToken(Request $request, Response $response, $args){
        $liste=Lists::where("id","=",$args['id'])->first();
        if(isset($liste)){
            if($liste->token!=null){
                return $liste->token;
            }
            else{
                $token=bin2hex(random_bytes(16));
                $liste->token=$token;
                if($liste->save()){
                    return $token;
                }
                else{
                    return false;
                }
            }
        }
        else{
            return false;
        }
    }
    public function creatList(Request $request, Response $response, $args){
        $creator = Creator::find($_SESSION['creatorCo']);
        $url_form = $this->router->pathFor('creatList');
        return $this->view->render($response, 'CreatList.twig', ["url_form"=>$url_form, "creator"=>$creator]);
    }

    public function validation_creatList(Request $request, Response $response, $args){
        $parsedBody = $request;
        var_dump('ok',$parsedBody->getParsedBodyParam('checkbox'));

        if(isset($parsedBody) && $parsedBody->getParsedBodyParam('envoi') === "Envoyer" ){
            $title=$parsedBody->getParsedBodyParam('title');
            $description=$parsedBody->getParsedBodyParam('description');
            $validityDate=$parsedBody->getParsedBodyParam('validityDate');
            $checkbox=$parsedBody->getParsedBodyParam('checkbox');



            if($checkbox === NULL){

                $checkbox=4;

            }else{
                $checkbox=1;
            }

            $list = new Lists();
            $list->id = uniqid();
            $list->title=$title;
            $list->description=$description;
            $list->validityDate=$validityDate;
            $list->token='hhhh';
            $list->isRecipient=$checkbox;
            $list->idCreator=$_SESSION['creatorCo'];
            $list->save();
            return $response->withRedirect($this->router->pathFor('homeCo'));

        }

    }
    /*
    * fonction qui genere le token de la liste validée,
    * destiné au bénéficiaire de la liste.
    *
    * l'on considère que le token est supprimer de la base lorsque l'achat est validé
    */
    public function generateSharingFinalToken(Request $request, Response $response, $args){
        $liste=Lists::where("id","=",$args['id'])->first();
        if(isset($liste)){

            //si date actuelle est > a date de l'event 
            //alors remplace le token de partage et genere le tokenfinal
            if(strtotime(date("d-m-Y"))>strtotime($liste->validityDate)){
                if($liste->token==null){
                    $token=bin2hex(random_bytes(16));
                    $liste->token=$token;
                    $liste->save();
                    return $token;
                }
            }
        }
        return false;
     
    }


}


