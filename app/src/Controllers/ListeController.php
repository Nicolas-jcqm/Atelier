<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Models\Lists;

final class ListeController
{

    /*
     * Function qui genere un token, l'ajoute a la base et le renvoi
     * destiné au créateur, qui pourra le partager
     * idliste l'id de la liste actuelle
     */
    public function generateToken(idliste){
        $token==bin2hex(random_bytes(16));
        $liste=Lists::where("id","=",$idliste)->first();
        $liste->save();
        $liste->token=$token;
        return $token;
    }
}
