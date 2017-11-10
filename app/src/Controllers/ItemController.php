<?php

namespace App\Controllers;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Models\Item;
use App\Models\Booking;
use App\Models\Lists;
use App\Models\Creator;
use App\Models\Comment;
use App\Controllers\Tools\Tools;

final class ItemController
{
    private $view;
    private $logger;
	private $user;
    private $tools;

     public function __construct($c)
    {
        $this->view = $c->get('view');
        $this->logger = $c->get('logger');
        $this->router = $c->get('router');
        $this->tools = new Tools();
    }

   public function addItem(Request $request, Response $response, $args){

       $erreurArray=array();
       $parsedBody = $request;

       if(isset($parsedBody) && $parsedBody->getParsedBodyParam('valid') === "valider" ){

           $title=$request->getParsedBodyParam("title");
           $desc=$request->getParsedBodyParam("desc");
           $price= $request->getParsedBodyParam("price");
           $url=$request->getParsedBodyParam("url");
           $idList= $request->getParsedBodyParam("idform");

           if(empty($title)){
               $erreurTitle="Merci d'entrer un titre";
               array_push($erreurArray,$erreurTitle);
           }else{
               if($title != filter_var($title, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)){
                   $erreurTitleFiltre ="Merci d'entrer un titre valide";
                   array_push($erreurArray,$erreurTitleFiltre);
               }
           }
           if(empty($desc)) {
               $erreurDesc = "Merci d'entrer une description";
               array_push($erreurArray, $erreurDesc);
           }
               else{
                   if ($desc != filter_var($desc, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)) {
                       $erreurDescFiltre = "Merci d'entrer une description valide";
                       array_push($erreurArray, $erreurDescFiltre);
                   }
               }

           if(empty($price)){
               $erreurPrice="Merci d'entrer un prix";
               array_push($erreurArray,$erreurPrice);
           }else{
               if($price != filter_var($price,  FILTER_VALIDATE_INT)){
                   $erreurPriceFiltre ="Merci d'entrer un prix valide";
                   array_push($erreurArray,$erreurPriceFiltre);
               }
           }


               if($url != filter_var($url,   FILTER_VALIDATE_URL)){
                   $erreurUrlFiltre ="Merci d'entrer une url valide";
                   array_push($erreurArray,$erreurUrlFiltre);

           }
           if (sizeof($erreurArray)===0) {

               $Item = new Item();
               $Item->id = uniqid();
               $Item->title = $title;
               $Item->description = $desc;
               $Item->price = $price;
               $Item->url = $url;
               $Item->idList =$idList;
               $Item->idGroup = 0;
               $Item->picture = 'default.jpg';

               if (isset($_FILES['FTU']) AND $_FILES['FTU'] ['error'] == 0) {
                   $nomdate = date('o') . '-' . date("m") . '-' . date('d') . '-' . date('H') . '-' . date('i') . '-' . date('s');
                   $ext = pathinfo($_FILES["FTU"]["name"], PATHINFO_EXTENSION);
                   move_uploaded_file($_FILES['FTU'] ['tmp_name'], 'img/' . $nomdate . '.' . $ext);
                   $Item->picture = $nomdate . '.' . $ext;
               }
               $Item->save();
                // $this->view->render($response, 'ItemConfirmer.twig', $this->tools->AddVarToRender(["creator" =>$creator, "url" =>$url]));
                return $response->withRedirect($this->router->pathFor('itemview', ["id" => $request->getParsedBodyParam("idform")]));
           }
           else{
               return $response->withRedirect($this->router->pathFor('itemview', ['erreurs'=>$erreurArray,"id" => $request->getParsedBodyParam("idform")]));
           }
       }
    }
    
    public function viewItem(Request $request, Response $response, $args){
        $erreurArray=array();

        $creator = Creator::find($_SESSION['creatorCo']);
        $item = Item::where("idList","=",$args['id'])->get();
        $url = $this->router->pathFor('itemadd');
        $liste = Lists::where("id","=", $args["id"])->first();
        $creator = Creator::find($_SESSION['creatorCo']);
        
        $formcrea = "aucun";
        
        
        if(isset($_SESSION['creatorCo'])){
            $CreaRequest = Lists::where("id", "=", $args['id'])->first(['idCreator']);
            if ($CreaRequest->idCreator == $_SESSION['creatorCo']) {
                $formcrea = "ok";
            }
        }

        // $this->view->render($response, 'item.twig', $this->tools->AddVarToRender(["creator" =>$creator, "item" =>$item, "liste"=>$liste,"url" =>$url, "idlist" =>$args['id'], "formcrea" => $formcrea]));
        $this->view->render($response, 'item.twig', ["creator" =>$creator, "item" =>$item,"liste"=>$liste,"url" =>$url, "idlist" =>$args['id'], "formcrea" => $formcrea,'erreurs'=>$erreurArray]);
    }
    
    public function groupItem(Request $request, Response $response, $args){
       
        var_dump($request->getParsedBodyParam("check"));
        $nameGroup = $request->getParsedBodyParam("nameGroup");
        $check = $request->getParsedBodyParam("check");
        $idgroupGenerate = uniqid();
        $size = count($check);
        

        for($i=0; $i < $size ; $i++){
            Item::where('id', '=', $check[$i])->update(['idGroup' => $idgroupGenerate]);
            Item::where('id', '=', $check[$i])->update(['nameGroup' => $nameGroup]);
        }
        
        return $response->withRedirect($this->router->pathFor('itemview', ["id" => $request->getParsedBodyParam("idlist")]));
    }
}
