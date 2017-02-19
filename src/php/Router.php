<?php

// FIREGREEN ** Faites une classe plus static, il n'y a qu'un router dans l'application **
class Router {

    private $url; // Contiendra l'URL sur laquelle on souhaite se rendre
    // FIREGREEN ** Faites une liste simple sans vous soucier des REQUEST_METHOD **
    private $routes = []; // Contiendra la liste des routes

    // le constructeur doit charger les routes, soit en avec un fichier json soit en dur
    public function __construct($url){
        $this->url = $url;
    }

    // FIREGREEN ** appelez la fonction 'insert' ou 'add', get c'est pas super pertinent comme nom
    // ou alors addGET mais laissez tomber les REQUEST_METHOD, inutile de classer
    // les requetes **
    public function get($path, $callable){
        $route = new Route($path, $callable);
        $this->routes["GET"][] = $route;
        return $route; // On retourne la route pour "enchainer" les méthodes
    }

    // FIREGREEN ** on va ignorer les REQUEST_METHOD **
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
    require "../index.html";

    //sendHTML

  }

  public static function connexion(){



  }
}




?>
