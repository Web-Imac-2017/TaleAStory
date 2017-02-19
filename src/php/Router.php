<?php

class Router {

    private $url = $_GET['url'];
    // FIREGREEN ** Faites une liste simple sans vous soucier des REQUEST_METHOD **
    private $routes = []; // chargera le JSON d'Estelle

    public static function insert($path, $callable){
        $path = trim($path, '/');
        $route = new Route($path, $callable);
        $this->routes["GET"][] = $route;
        return $route;
    }

    public static function run(){
      foreach($this->routes as $route){
          if($route->match($url)){
              return $route->call();
          }
      }
      throw new RouterException('No matching routes');//faire la 404
  }

  public static function index(){
    require "../index.html";
    //sendHTML

  }

  public static function connexion(){
    require"../connexion.html";
  }
}
?>
