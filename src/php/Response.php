<?php

class Response
{
  /* FIREGREEN ** param doit être passé en paramètre de generateIndex
     appelé le sendHTML
     tips : plutôt que de faire une concaténation de string, passez à echo
     votre suite de string pour qu'il les affiche un par un sans concaténation (+optimisé)
     exemple : echo '<script type=\'text/javascript\'>', params, '</script>'
     tips 2 : les quotes '' effectuent moins de traitement sur les chaines que ""
     qui effectuent des évaluation pour traiter des valeurs styles {$value} **
  */
  public static function generateIndex(){
    global $param;
    /*
      FIREGREEN ** src="'assets/js/main.min.js'", ça c'est pour importer le main.js il est déja
      dans le body.html
      ne mettez pas une balise script en dehors d'une balise header ou body.
      normalement vous devriez pouvoir inclure des fichier php, et normalement si vous faites
      appel à un fichier php ici, ce fichier php devrez pouvoir utiliser les paramètres visible
      dans cette fonction
      exemple : (<?= $val ?> affiche une simple variable)
      test.php
      <body>
        J'aime les fruits au <?= $param.perceval ?>
      </body>
      Response.php
      function test($param){
        require 'test.php'
      }
      **
    */
    $addScript = "<script type="'text/javascript'" src="'assets/js/main.min.js'">".$param."</script>";
    require"../header.html";
    echo $addScript;
    /* FIREGREEN ** inutile de mettre <!DOCTYPE html> dans le body ** */
    require"../body.html";
  }
}


 ?>
