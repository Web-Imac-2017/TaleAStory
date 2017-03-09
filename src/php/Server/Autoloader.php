<?php
namespace Server;
class Autoloader{
    static function register(){
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

/**
* Permet de retrouver le fichier correspondant a la classe appelée en paramètre entrant au sein de l'arborescence
*
*/
    static function autoload($class){
     $parts = preg_split('#\\\#', $class);
     $className = array_pop($parts);
     $path = implode(DS, $parts);
     $file = $className.'.php';
     $filepath = ROOT.strtolower($path).DS.$file;
     require_once ($filepath);
    }
}

?>
