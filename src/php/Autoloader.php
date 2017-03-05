<?php

class Autoloader{
    static function register(){
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

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
