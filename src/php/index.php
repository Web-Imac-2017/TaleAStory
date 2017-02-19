<?php

  require 'Autoloader.php';
  Autoloader::register();

  $router = new Router($_GET['url']);

  /*
<?php
Le callable dans notre cas est un string qui cible une certaine fonction d'une certaine classe
Genre pour tester "Router::index", pour le second paramètre c'est bien matches

namespace Foobar;

class Foo {
    static public function test($name) {
        print "Bonjour {$name}!\n";
    }
}

// Depuis PHP 5.3.0
call_user_func_array(__NAMESPACE__ .'\Foo::test', array('Hannes'));

// Depuis PHP 5.3.0
call_user_func_array(array(__NAMESPACE__ .'\Foo', 'test'), array('Philip'));

?>
*/

  $router->get('/:id', "Router::index");
  $router->get('/', "Router::index");
  $router->run();


?>
