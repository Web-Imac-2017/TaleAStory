<?php
namespace Server;

class error {

/**
* Permet de définir une erreur comme étant un message, un code et un status initialisé a "error"
*/
  public function __construct($message,$code = 0) {
      $this->message = $message;
      $this->code = $code;
      $this->status = "error";
  }

}
class RouterException extends \Exception {
  public function __construct($message, $code = 0){
    parent::__construct($message, $code);
  }

  public function __toString(){
    return $this->message;
  }

/**
* Permet d'envoyer l'erreur en fonction de sa provenance (json, html)
* Renvoie un objet si erreur json, echo si problème html
*/
  public function send() {
    $error = new error($this->message, $this->code);
    $result = stripos($_SERVER['HTTP_ACCEPT'], 'application/json');
    if($result != FALSE) {
      \Response::jsonResponse($error);
    }
    else {
      echo ("ERREUR ".$this->code." !");
      }
  }



}

 ?>
