<?php
/*
  FIREGREEN **
  Pour utiliser cette exeption faites un try catch dans l'index.php et
  faites un echo du message quand on demande une page et un JSON avec un champs status = 'error'
  **
*/
class RouterException extends Exception {

  public function __construct($message, $code = 0){
    parent::__construct($message, $code);
  }

  public function __toString(){
    return $this->message;
  }
}

 ?>
