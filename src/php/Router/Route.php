<?php
namespace Router;
class Route {

    private $path;
    private $callable;
    private $matches = [];
    private $params = [];

/**
* Permet de définir une route comme étant un chemin (path) avec sa méthode correspondante (callable)
*/
    public function __construct($path, $callable){
        $this->path = trim($path, '/');  // On retire les / inutiles
        $this->callable = $callable;
    }

  /**
  * Permet de "nettoyer" l'url récupérée en paramètre pour pouvoir comparer la partie utile au path a tester
  * retourne vrai si correspondant, faux sinon
  */
    public function match($url){
        $url = trim($url, '/');
        $path = preg_replace('#:([\w]+)#', '([^/]+)', $this->path);// a changer
        $regex = "#^$path$#i";
        if(!preg_match($regex, $url, $matches)){
            return false;
        }
        array_shift($matches);
        $this->matches = $matches;  // On sauvegarde les paramètre dans l'instance pour plus tard
        return true;
    }

/**
* Permet de lancer la méthode stockée dans callable en lui passant en argument le tableau de matches
*/
    public function call(){
      return call_user_func_array($this->callable, $this->matches);
    }
}

?>
