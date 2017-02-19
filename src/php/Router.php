<?php

class Router {

    private $url; // Contiendra l'URL sur laquelle on souhaite se rendre
    private $routes = []; // Contiendra la liste des routes

    public function __construct($url){
        $this->url = $url;
    }

    public function get($path, $callable){
        $route = new Route($path, $callable);
        $this->routes["GET"][] = $route;
        return $route; // On retourne la route pour "enchainer" les méthodes
    }

    public function run(){
      if(!isset($this->routes[$_SERVER['REQUEST_METHOD']])){
          throw new RouterException('REQUEST_METHOD does not exist');
      }
      foreach($this->routes[$_SERVER['REQUEST_METHOD']] as $route){
          if($route->match($this->url)){
              return $route->call();
          }
      }
      throw new RouterException('No matching routes');
  }

  public static function index(){
    require "index.html";

    //sendHTML

  }

  public static function connexion(){



  }
}




?>
