<?php

namespace App\Controllers;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Models\Item;
use App\Models\Creator;

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
        
        $Item->title = $request->getParsedBodyParam("title");
        $Item->description = $request->getParsedBodyParam("desc");
        $Item->price = $request->getParsedBodyParam("price");
        $Item->url = $request->getParsedBodyParam("url");
        $Item->idList = $request->getParsedBodyParam("idform");
        $Item->idGroup = null;
        
        if (isset($_FILES['FTU']) AND $_FILES['FTU'] ['error'] == 0){
            $nomdate = date('o').'-'.date("m").'-'.date('d').'-'.date('H').'-'.date('i').'-'.date('s');
            $ext = pathinfo($_FILES["FTU"]["name"], PATHINFO_EXTENSION);
            move_uploaded_file($_FILES['FTU'] ['tmp_name'], 'img/' .$nomdate.'.'.$ext);
            $Item->picture = $nomdate.'.'.$ext;
            
        }else{
            echo "Sorry, there was an error uploading your file.";
        }
        
        $Item->save();
        
        $this->viewItemNoRoute($request->getParsedBodyParam("idlist"));
        
        
    }
    
    public function viewItem(Request $request, Response $response, $args){
        
        $item = Item::where("idList","=",$args['id']);
        
        var_dump($item);
        
        $creator = Creator::find($_SESSION['creatorCo']);
        $this->view->render($response, 'item.twig', ["creator" =>$creator, "item" =>$item]);
        
    }
    
    
    public function viewItemNoRoute($list){
        
        $item = Item::where("idList","=",$list);
        
        var_dump($item);
        
        $creator = Creator::find($_SESSION['creatorCo']);
        $this->view->render($response, 'item.twig', ["creator" =>$creator, "item" =>$item]);
        
    }
 }
