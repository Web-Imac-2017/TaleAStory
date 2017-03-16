<?php
namespace Server;
use \Controller\CurrentUserController;
use \Server\Session;
use \Model\Player;
use \View\Success;
class Router {

    static $routes = [];
    static $webRoot = "";
    const DEFAULT_ROUTE = "__default__";
    static $defaultRoute = null;

/**
* Permet de récupérer le webroot actuel
*/
    public static function init() {
        $script_path = $_SERVER['SCRIPT_NAME'];
        self::$webRoot = str_replace('php/index.php', '', $script_path);
    }

/**
* Attend en paramètre la route et la méthode correspondante
* Permet d'insérer chaque routes et la méthode lui correspondant dans le tableau $routes
* Retourne le tableau statique de routes
*/
    public static function insert($path, $callable){
        $path = trim($path, '/');
        if($path == self::DEFAULT_ROUTE){
          self::$defaultRoute = new Route($path, $callable);
          return self::$defaultRoute;
        }
        self::$routes["GET"][] = new Route($path, $callable);
        return self::$routes["GET"];
    }

/**
* Permet de lancer la méthode correspondant à la route actuelle
* Renvoie la la route et méthode correpondante ou sortie en erreur (pas censé arriver car il y a un path default dans le json)
*/
    public static function run(){
      $url = $_GET['url'];
      foreach(self::$routes["GET"] as $route){
          if($route->match($url)){
              return $route->call();
          }
      }
      if(self::$defaultRoute != null)
        return self::$defaultRoute->call();
      throw new RouterException('No matching routes',404);
  }

/**
* Permet d'envoyer les informations de l'utilisateur courant a la classe response
*
*/
  public static function index(){
    $player = Player::connectSession();
    $token = Form::generateToken();
    if ($player == NULL){
      $e = (object)array();
    }
    else{
      $e = $player;
    }
    $e->token = $token;
    Response::generateIndex($e);
  }

  /**
  * Permet de rediriger sur la page de connexion si celle-ci a été appelée
  *
  */
  public static function connexion(){
    Response::jsonResponse((object)array( 'post_converted' => json_decode(file_get_contents('php://input'), true),
                                          'post' => $_POST,
                                          'userPseudo' => 'Marcel Patulacci',
                                          'userImgPath' => 'patulacci_tiny.jpg',
                                          'time' => '16h45'));
  }

/**
* Le paramètre d'entrée contient le json Routes.Json
* Permet de remplir le tableau de routes par le biais du json
* Ne retourne rien, il met a jour un tableau statique
*/
  public static function setRoutes($json_path){
    $json = file_get_contents($json_path);
    if($json == FALSE) {
      throw new RouterException('Json file not found', 404);
    }
    $obj_json= json_decode($json, true);
    foreach($obj_json as $key => $value){
      self::insert($key, $value);
    }
  }
}
?>
