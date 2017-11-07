<?php

namespace App\Controllers;

use App\Models\Creator;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class UserController
{
    private $view;
    private $logger;
    private $router;
    public function __construct($c)
    {
        $this->view = $c->get('view');
        $this->logger = $c->get('logger');
        $this->model = $c->get('App\Repositories\UserRepository');
        $this->router = $c->get('router');
    }

    public function signup(Request $request, Response $response, $args)
    {
        // Pour le debeug $this->logger->info("Affichage signup");
        $url_form = $this->router->pathFor('signup');
		return $this->view->render($response, 'signup.twig', ["url_form"=>$url_form]);
    }
    public function signin(Request $request, Response $response, $args)
    {
        $url_form = $this->router->pathFor('signin');
        return $this->view->render($response, 'signin.twig', ["url_form"=>$url_form]);
    }

    public function validation_signup(Request $request, Response $response, $args){
        var_dump($_POST);
        //Les données de mon body soit le $_POST ici
        $parsedBody = $request;
        if(isset($parsedBody) && $parsedBody->getParsedBodyParam('envoi') === "Envoyer" ){
            $name=$parsedBody->getParsedBodyParam('name');
            $firstName=$parsedBody->getParsedBodyParam('firstName');
            $email=$parsedBody->getParsedBodyParam('mail');

            if(empty($firstName)){
                $ereurFisrt="Merci d'entrer un nom";
                echo $ereurFisrt;
            }else{
                if(!filter_var($parsedBody->getParsedBodyParam('firstName'), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)){
                    $ereur ="Merci d'entrer un prénom valide";
                    echo $ereur;
                }
            }
            if(empty($name)){
                $ereurName ="Merci d'entrer un prénom";
                echo $ereurName;
            }else{
                if(!filter_var($parsedBody->getParsedBodyParam('name'), FILTER_SANITIZE_STRING)){
                $ereur ="Merci d'entrer un nom valide";
                echo $ereur;
                }
            }
            if(empty($email)){
                $ereurEmail = "Veuillez entrer un email";
                echo $ereurEmail;
            }else{
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    $ereur ="Merci d'entrer un email valide";
                    echo $ereur;
                }
            }


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


        }
    }

    public function validation_signin(Request $request, Response $response, $args){
        var_dump($_POST);
        //Les données de mon body soit le $_POST ici
        $parsedBody = $request;
        if(isset($parsedBody) && $parsedBody->getParsedBodyParam('envoi') === "Connexion"){
            if (Creator::where('mail', '=', $_POST['email'])->exists()){
                $emailCreator = Creator::where('mail', '=', $parsedBody->getParsedBodyParam('email'))->first();
                $password= $emailCreator->password;
                if (password_verify($_POST['password'],$password)) {
                    $_SESSION['creatorCo']=$emailCreator->id;
                    echo 'bien connecté';
                    //page twig avec la home connexion
                }
                else{

                }

            }
        }
    }

    /*
    public signUpCreator(equest $request, Response $response, $args){
        return $this->view->render()
    }
    */
}