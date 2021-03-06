<?php

namespace App\Controllers;

use App\Models\Lists;
use App\Models\Creator;
use App\Models\Comment;
use App\Controllers\Tools\Tools;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Illuminate\Database\Eloquent\Model;

final class ListeController
{
    private $tools;
    private $user;
    private $listsArray;

    public function __construct($c)
    {
        $this->user;
        $this->listsArray = null;
        $this->tools= new Tools();
        $this->view = $c->get('view');
        $this->logger = $c->get('logger');
        $this->model = $c->get('App\Repositories\UserRepository');
        $this->router = $c->get('router');
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
            if(!$liste->isValidate){
                if($liste->token!=null){
                    return $response->withRedirect($this->router->pathFor('itemview',["id"=>$args['id'],"token"=>$liste->token]));
                }
                else{
                    $token=bin2hex(random_bytes(16));
                    $liste->token=$token;
                    if($liste->save()){
                        return $response->withRedirect($this->router->pathFor('itemview',["id"=>$args['id'],"token"=>$token]));
                    }
                    else{
                        echo "Problème d'insertion dans la base de donnée";
                    }
                }
            }
            else{
                echo "Le créateur de la liste l'a déjà validée ou la date limite est passée";    
            }
        }
        else{
            echo "Liste inéxistante dans la base de donnée";
        }
        return $response->withRedirect($this->router->pathFor('itemview',["id"=>$args['id']]));
    }
    public function creatList(Request $request, Response $response, $args){
        $creator = Creator::find($_SESSION['creatorCo']);
        $url_form = $this->router->pathFor('creatList');
        return $this->view->render($response, 'CreatList.twig', $this->tools->AddVarToRender(["url_form"=>$url_form, "creator"=>$creator]));
    }

    public function validation_creatList(Request $request, Response $response, $args){
        $creator = Creator::find($_SESSION['creatorCo']);

        $parsedBody = $request;
        $erreurArray=array();

        if(isset($parsedBody) && $parsedBody->getParsedBodyParam('envoi') === "Envoyer" ){
            $title=$parsedBody->getParsedBodyParam('title');
            $description=$parsedBody->getParsedBodyParam('description');
            $validityDate=$parsedBody->getParsedBodyParam('validityDate');
            $checkbox=$parsedBody->getParsedBodyParam('checkbox');

            /**gerer erreurs*/
            if(empty($title)){
                $erreurTitre="Merci d'entrer un titre";
                array_push($erreurArray,$erreurTitre);
            }else{
                if($title != filter_var($title, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)){
                    $erreurTitreFiltre ="Merci d'entrer un titre valide";
                    array_push($erreurArray,$erreurTitreFiltre);
                }
            }
            if(empty($description)){
                $erreurDesc="Merci d'entrer une description";
                array_push($erreurArray,$erreurDesc);
            }else{
                if($description != filter_var($description, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)){
                    $erreurDescFiltre ="Merci d'entrer un titre valide";
                    array_push($erreurArray,$erreurDescFiltre);
                }
            }
            if(empty($validityDate)){
                $erreurDate='Veuillez entrez une date';
                array_push($erreurArray, $erreurDate);
            }else{
                if(strtotime(date("d-m-Y"))>=strtotime($validityDate)){
                    $erreurDateFiltre="Veuillez entrez une date qui n'est pas egale ou enterieure à celle d'aujourd'hui";
                    array_push($erreurArray, $erreurDateFiltre);
                }
            }


            if($checkbox === NULL){
                $checkbox=0;

            }else{
                $checkbox=1;
            }
            if (sizeof($erreurArray)===0) {
                $list = new Lists();
                $list->id = uniqid();
                $list->title = $title;
                $list->description = $description;
                $list->validityDate = $validityDate;
                $list->token = null;
                $list->isRecipient = $checkbox;
                $list->idCreator = $_SESSION['creatorCo'];
                $list->save();
                return $response->withRedirect($this->router->pathFor('homeCo'));
            }
            else{
                $url_form = $this->router->pathFor('creatList');

                return $this->view->render($response, 'CreatList.twig', ["url_form"=>$url_form,'erreurs'=>$erreurArray, "creator"=>$creator]);
            }
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

            //si date actuelle est >= a date de l'event
            //alors genere le tokenfinal a la place du premier token effacé au préablable
            if(strtotime(date("d-m-Y"))>=strtotime($liste->validityDate)){
                if($liste->token==null&&$liste->isValidate){
                    $token=bin2hex(random_bytes(16));
                    $liste->token=$token;
                    $liste->save();
                    return $response->withRedirect($this->router->pathFor('itemview',["id"=>$args['id'],"token"=>$token]));
                }
                else{
                    if(!$liste->isValidate)
                        return "Liste non validée";
                    if($liste->token!=null)
                        return $response->withRedirect($this->router->pathFor('itemview',["id"=>$args['id'],"token"=>$liste->token]));
                }
            }
            else{
                return "Liste non validée";
            }
        }
        return $response->withRedirect($this->router->pathFor('itemview',["id"=>$args['id']]));
     
    }

    /*
    * Permet d'ajouter des commentaire globaux a une liste
    */
    public function addCommentList(Request $request, Response $response, $args){
        $comment= new Comment();
        $comment->id=uniqid();
        $comment->senderName=$request->getParsedBodyParam('senderName');
        $comment->content=$request->getParsedBodyParam('content');
        $liste=Lists::select('id')->where('token','=',$args["token"])->first();
        $comment->idlist=$liste->id;
        $comment->save();
        return $response->withRedirect($this->router->pathFor('viewGuest',["token"=>$args['token']]));
    }

    /* fontion qui permet d'effectuer une vérification sur la date de validite d'une liste
    * si la date est passee, efface le token correspondant a la liste
    * renvoi true si la liste est validée
    * renvoi false si la liste n'est pas encore validée
     *
     * fonction destiné à etre lancé par le serveur tout les jours /!\
    */
    public function checkAndUpValidityDate(Request $request, Response $response, $args){
        $liste=Lists::where("id","=",$args['id'])->first();
        if(isset($liste)){
            if(strtotime(date("d-m-Y"))>=strtotime($liste->validityDate)){
                if(!$liste->isValidate){
                    $liste->isValidate=true;
                    $liste->token=null;
                    $liste->save();
                }
                return true;
            }else{
                return false;
            }
        } else
            return false;
    }

    /*
    * fonction qui permet au createur de la liste de valider une de ses listes,
    * meme si la date de fin n'est pas arrivé
    */
    public function ValidateList(Request $request, Response $response, $args){
        $liste=Lists::where("id","=",$args['id'])->first();
        if(isset($liste)){
            $liste->isValidate=true;
            $liste->validityDate=date('Y-m-d H:i:s');
            $liste->token=null;
            if($liste->save())
                return $response->withRedirect($this->router->pathFor('itemview',["id"=>$args['id']]));
            else
                return false;
        }
        else
            return false;
    }


      
}


