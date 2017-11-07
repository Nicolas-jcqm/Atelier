<?php

namespace App\Controllers;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class ItemController
{
    private $view;
    private $logger;
	private $user;

    public function __construct($view, LoggerInterface $logger, $user)
    {
        $this->view = $view;
        $this->logger = $logger;
        $this->model = $user;
    }

    public function addItem($title, $desc, $price, $url , $picture){
        $Item = new \app\Models\Item();
        
        $Item->title = $title;
        $Item->description = $desc;
        $Item->price = $price;
        $Item->url = $url;
        $Item->idGroup = null;
        
        if (isset($_FILES['fileToUpload']) AND $_FILES['fileToUpload'] ['error'] == 0){
            $nomdate = date('o').'-'.date("m").'-'.date('d').'-'.date('H').'-'.date('i').'-'.date('s');
            $ext = pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION);
            move_uploaded_file($_FILES['fileToUpload'] ['tmp_name'], 'img/' .$nomdate.'.'.$ext);
            $Item->picture; = $nomdate.'.'.$ext;
            
        }else{
            echo "Sorry, there was an error uploading your file.";
        }
        
        $Item->save();
        
    }
}
