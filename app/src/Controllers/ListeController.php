<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Models\Lists;

final class ListeController
{

    private $user;
    private $listsArray;

    public function __construct()
    {
        $this->user;
        $this->listsArray = null;
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
     */
    public function generateToken($idliste){
        $token=bin2hex(random_bytes(16));
        $liste=Lists::where("id","=",$idliste)->first();
        $liste->save();
        $liste->token=$token;
        return $token;
    }

    public function __get($attName) {
        if(property_exists($this, $attName))
            return $this->$attName;
        else throw new \Exception("Erreur : attribut ".$attName." inexistant.", 1);
    }

    public function __set($attName, $value) {
        if(property_exists($this, $attName))
            $this->$attName = $value;
        else throw new \Exception("Erreur : attribut ".$attName." inexistant.", 1);
    }
}


