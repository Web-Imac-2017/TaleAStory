<?php

class Router {
    static $routes = [];
    static $webRoot = "";
    const DEFAULT_ROUTE = "__default__";
    static $defaultRoute = null;

    public static function init() {
        $script_path = $_SERVER['SCRIPT_NAME'];
        self::$webRoot = str_replace('php/index.php', '', $script_path);
    }


    public static function insert($path, $callable){
        $path = trim($path, '/');
        if($path == self::DEFAULT_ROUTE){
          self::$defaultRoute = new Route($path, $callable);
          return self::$defaultRoute;
        }
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
      if(self::$defaultRoute != null)
        return self::$defaultRoute->call();
      throw new RouterException('No matching routes');
  }

  public static function index(){
    Response::generateIndex((object)array(/*'userID' => '1',*/
                                          'userName' => 'Marcel',
                                          'userSurname'=> 'Patulacci',
                                          'userImgPath' => 'patulacci_tiny.jpg',
                                          'time' => '16h45'));
  }

  public static function connexion(){
    require"../connexion.html";
  }

  public static function setJson($json_path){
    $json = file_get_contents($json_path);
    $obj_json= json_decode($json, true);
    foreach($obj_json as $key => $value){
      self::insert($key, $value);
    }
  }
}
?>
