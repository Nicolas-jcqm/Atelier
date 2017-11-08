<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Models\Lists;
use Illuminate\Database\Eloquent\Model;

final class ListeController
{


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


