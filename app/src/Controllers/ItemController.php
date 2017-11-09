<?php

namespace App\Controllers;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Models\Item;
use App\Models\Creator;
use App\Models\Comment;
use App\Models\Lists;

final class ItemController
{
    private $view;
    private $logger;
	private $user;

     public function __construct($c)
    {
        $this->view = $c->get('view');
        $this->logger = $c->get('logger');
        $this->router = $c->get('router');
    }

   public function addItem(Request $request, Response $response, $args){
        
        $Item = new Item();
        $Item->id = uniqid();
        $Item->title = $request->getParsedBodyParam("title");
        $Item->description = $request->getParsedBodyParam("desc");
        $Item->price = $request->getParsedBodyParam("price");
        $Item->url = $request->getParsedBodyParam("url");
        $Item->idList = $request->getParsedBodyParam("idform");
        $Item->idGroup = 0;
        $Item->picture = 'default.jpg';
        
        if (isset($_FILES['FTU']) AND $_FILES['FTU'] ['error'] == 0){
            $nomdate = date('o').'-'.date("m").'-'.date('d').'-'.date('H').'-'.date('i').'-'.date('s');
            $ext = pathinfo($_FILES["FTU"]["name"], PATHINFO_EXTENSION);
            move_uploaded_file($_FILES['FTU'] ['tmp_name'], 'img/' .$nomdate.'.'.$ext);
            $Item->picture = $nomdate.'.'.$ext;
            
        }else{
            
        }
        
        $Item->save();
        
        $url = $this->router->pathFor('itemview', ["id" => $request->getParsedBodyParam("idform")]);
        
        $creator = Creator::find($_SESSION['creatorCo']);
        $this->view->render($response, 'ItemConfirmer.twig', ["creator" =>$creator, "url" =>$url]);
        
        
    }
    
    public function viewItem(Request $request, Response $response, $args){
        
        $creator = Creator::find($_SESSION['creatorCo']);
        $item = Item::where("idList","=",$args['id'])->get();
        $url = $this->router->pathFor('itemadd');
        $comment= Comment::where('idlist','=',$args['id'])->latest()->get();

        $creator = Creator::find($_SESSION['creatorCo']);
        $this->view->render($response, 'item.twig', ["creator" =>$creator, "item" =>$item, "listcom"=>$comment, "url" =>$url, "idlist" =>$args['id']]);
        
        $formcrea = "aucun";
        
        /*
        if(isset($_SESSION['creatorCo'])){
            $CreaRequest = Lists::where("id", "=", $args['id'])->get(['idCreator']);
            if ($CreaRequest == $_SESSION['creatorCo']) {
                $formcrea = "ok";
            }
        }
        var_dump($_SESSION['creatorCo']);
        var_dump($CreaRequest);
        var_dump($formcrea);
        */
        
        $this->view->render($response, 'item.twig', ["creator" =>$creator, "item" =>$item, "url" =>$url, "idlist" =>$args['id'], "formcrea" => $formcrea]);
        
    }

    /* Reservation d'un item*/
    public function bookItem(Request $request, Response $response, $args){

    }
    
 }
