<?php

namespace App\Controllers;

use App\Models\Lists;
use App\Models\Creator;
use App\Models\Comment;

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
            if(!$liste->isValidate){
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
                        return "Problème d'insertion dans la base de donnée";
                    }
                }
            }
            else{
                return "Le créateur de la liste l'a déjà validée ou la date limite est passée";    
            }
        }
        else{
            return "Liste inéxistante dans la base de donnée";
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
            $list->token=null;
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

            //si date actuelle est >= a date de l'event
            //alors genere le tokenfinal a la place du premier token effacé au préablable
            if(strtotime(date("d-m-Y"))>=strtotime($liste->validityDate)){
                if($liste->token==null&&$liste->isValidate){
                    $token=bin2hex(random_bytes(16));
                    $liste->token=$token;
                    $liste->save();
                    return $token;
                }
                else{
                    if(!$liste->isValidate)
                        return "Liste non validée";
                    if($liste->token!=null)
                        return $liste->token;
                }
            }
            else{
                return "Liste non validée";
            }
        }
        return false;
     
    }

    /*
	   * permet de renvoyer la liste des commentaires d'une liste de cadeau
     */
    public function commentList(Request $request, Response $response, $args){
        $comment= Comment::where('idlist','=',$args['id'])->latest()->get();
        foreach ($comment as $key => $value) {
            echo 'Nom: '.$value->senderName.'<br>Message; '.$value->content.'<br> Posté le '.$value->created_at->format('d/m/Y').' à '.$value->created_at->modify('+1 hour')->format('H:i:s').' (UTC +1)<br><br>';
        }
        $url_form = $this->router->pathFor('comment',["id"=>$args['id']]);
        return $this->view->render($response, 'comment.twig', ["url_form"=>$url_form,'erreurs'=>$erreur=[]]);
    }

    /*
    * Permet d'ajouter des commentaire globaux a une liste
    */
    public function addCommentList(Request $request, Response $response, $args){
        $comment= new Comment();
        $comment->id=uniqid();
        $comment->senderName=$request->getParsedBodyParam('senderName');
        $comment->content=$request->getParsedBodyParam('content');
        $comment->idlist=$args['id'];
        $comment->save();
        return $response->withRedirect($this->router->pathFor('comment',["id"=>$args['id']]));
    }

    /* fontion qui permet d'effectuer une vérification sur la date de validite d'une liste
    * si la date est passee, efface le token correspondant a la liste
    * renvoi true si la liste est validée
    * renvoi false si la liste n'est pas encore validée
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
                return true;
            else
                return false;
        }
        else
            return false;
    }
      
}


