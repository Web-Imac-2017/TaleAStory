<?php

class Router {

    static $routes = [];

    public static function insert($path, $callable){

        $path = trim($path, '/');
        self::$routes["GET"][] = new Route($path, $callable);
        return self::$routes["GET"];
    }

    public static function run(){
      $url = $_GET['url'];
      foreach(self::$routes["GET"] as $route){
          if($route->match($url)){
              return $route->call();
          }
      }
      throw new RouterException('No matching routes'); //faire la 404
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
