<?php
namespace App\Controllers\Tools;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class Tools
{
    //Ajout de variable dans render pour qu'elles soit disponibles dans tout les twigs
    public function AddVarToRender($tab){
        $tab['session']=$_SESSION['creatorCo'];
        return $tab;
    }
}
