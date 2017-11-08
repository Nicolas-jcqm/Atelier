<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

$app->add(function ($request, $response, $next) {
    $this->view->offsetSet('flash', $this->flash);
    return $next($request, $response);
});

$middleware_need_co = function ($request, $response, $next) {
    if(!isset($_SESSION['creatorCo'])){
        return $response->withRedirect($this->router->pathFor('signin'));
    }else {
        return $next($request, $response);
    }

};
$middleware_need_no_co=function ($request, $response, $next) {
    if(isset($_SESSION['creatorCo'])){
        return $response->withRedirect($this->router->pathFor('homeCo'));
    }else {
        return $next($request, $response);

    }
};