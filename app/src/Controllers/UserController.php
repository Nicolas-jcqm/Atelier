<?php

namespace App\Controllers;

use App\Models\Creator;
use App\Models\Lists;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Controllers\Tools\Tools;

final class UserController
{
    private $view;
    private $logger;
    private $router;
    private $tools;
    
    public function __construct($c)
    {
        $this->view = $c->get('view');
        $this->logger = $c->get('logger');
        $this->model = $c->get('App\Repositories\UserRepository');
        $this->router = $c->get('router');
        $this->tools = new Tools();
    }

    public function signup(Request $request, Response $response, $args)
    {
        // Pour le debeug $this->logger->info("Affichage signup");
        $url_form = $this->router->pathFor('signup');
		return $this->view->render($response, 'signup.twig', ["url_form"=>$url_form,'erreurs'=>$erreurs=[]]);
    }
    public function signin(Request $request, Response $response, $args)
    {
        $url_form = $this->router->pathFor('signin');
        return $this->view->render($response, 'signin.twig', ["url_form"=>$url_form,'erreurs'=>$erreur=[]]);
    }

    public function validation_signup(Request $request, Response $response, $args){
        //Les données de mon body soit le $_POST ici
        $parsedBody = $request;
        $erreurArray=array();
        if(isset($parsedBody) && $parsedBody->getParsedBodyParam('envoi') === "S'incrire" ){
            $name=$parsedBody->getParsedBodyParam('name');
            $firstName=$parsedBody->getParsedBodyParam('firstName');
            $email=$parsedBody->getParsedBodyParam('mail');

            if(empty($firstName)){
                $erreurFisrt="Merci d'entrer un nom";
               array_push($erreurArray,$erreurFisrt);
            }else{
                if($firstName != filter_var($firstName, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)){
                    $erreurFirstNameFiltre ="Merci d'entrer un prénom valide";
                    array_push($erreurArray,$erreurFirstNameFiltre);
                }
            }
            if(empty($name)){
                $erreurName ="Merci d'entrer un prénom";
                array_push($erreurArray,$erreurName);
            }else{
                if(!filter_var($name, FILTER_SANITIZE_STRING,FILTER_FLAG_STRIP_HIGH)){
                    $erreurNameFiltre ="Merci d'entrer un nom valide";
                    array_push($erreurArray,$erreurNameFiltre);                }
            }
            if(empty($email)){
                $erreurEmail = "Veuillez entrer un email";
                array_push($erreurArray,$erreurEmail);
            }else {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $erreurMailFiltre = "Merci d'entrer un email valide";
                    array_push($erreurArray,$erreurMailFiltre);
                }
                if(Creator::where('mail', '=', $email)->exists()){
                    $erreurEmailExist = "L'email existe déja";
                    array_push($erreurArray,$erreurEmailExist);
                }
            }
            if (sizeof($erreurArray)===0){
                $pass = password_hash($_POST['password'], PASSWORD_DEFAULT, array(
                    'cost' => 12,
                ));

                $creator = new Creator();
                $creator->id = uniqid();
                $creator->name =$name;
                $creator->firstName = $firstName;
                $creator->mail = $email;
                $creator->password = $pass;
                $creator->save();
                //faire page validation avec juste un message pour dire qun est inscri avec le bouton pour aller se co
                return $response->withRedirect($this->router->pathFor('signin'));
            }

            else{
                $url_form = $this->router->pathFor('signup');
                return $this->view->render($response, 'signup.twig', ["url_form"=>$url_form,'erreurs'=>$erreurArray]);
            }


        }else{
            $erreurSurvenue="Désolé, une erreur est survenue";
            array_push($erreurArray,$erreurSurvenue);
            $url_form = $this->router->pathFor('signup');
            return $this->view->render($response, 'signup.twig', ["url_form"=>$url_form,'erreurs'=>$erreurArray]);
        }
    }

    public function validation_signin(Request $request, Response $response, $args){

        //Les données de mon body soit le $_POST ici
        $url_form=$this->router->pathFor('signin');
        $parsedBody = $request;
        $errorArray=array();
        if(isset($_POST) && $_POST['envoi'] === "Connexion"){
            if (Creator::where('mail', '=', $_POST['mail'])->exists()){
                $creator = Creator::where('mail', '=', $_POST['mail'])->first();
                $password= $creator->password;
                if (password_verify($_POST['password'],$password)) {
                    $_SESSION['creatorCo']=$creator->id;

                    return $response->withRedirect($this->router->pathFor('homeCo'));

                    //page twig avec la home connexion
                }
                else{
                    $erreurMpdExistPas="Votre mot de passe est incorrect, veuillez essayer à nouveau  ";
                    array_push($errorArray,$erreurMpdExistPas);
                    return $this->view->render($response, 'signin.twig', ["url_form"=>$url_form,'erreurs'=>$errorArray]);

                }

            }else{
                $erreurMailExistPas="L'email n'existe pas";
                array_push($errorArray,$erreurMailExistPas);
                return $this->view->render($response, 'signin.twig', ["url_form"=>$url_form,'erreurs'=>$errorArray]);

            }
        }else{
            $erreurMailExistPas="Une erreur est survenue ;) ";
            array_push($errorArray,$erreurMailExistPas);
            return $this->view->render($response, 'signin.twig', ["url_form"=>$url_form,'erreurs'=>$errorArray]);

        }
    }

    public function homeCo(Request $request, Response $response, $args){
        $creator = Creator::find($_SESSION['creatorCo']);
        $listsArray = Lists::where('idCreator','=',$creator->id)->get();

        return $this->view->render($response, 'homeCo.twig', $this->tools->AddVarToRender(["creator"=>$creator, $_SESSION['createrCo'],  "listsArray"=>$listsArray] ));
    }

    public function disconnect(Request $request, Response $response, $args){
        unset($_SESSION['creatorCo']);
        return $response->withRedirect($this->router->pathFor('homepage'));
    }
    /*
    public signUpCreator(equest $request, Response $response, $args){
        return $this->view->render()
    }
    */
}