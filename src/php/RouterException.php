<?php
/*
  FIREGREEN **
  Pour utiliser cette exeption faites un try catch dans l'index.php et
  faites un echo du message quand on demande une page et un JSON avec un champs status = 'error'
  cf. création dynamique d'un json, dans index.php
  **
*/
class error {
  public function __construct($message,$code = 0) {
      $this->message = $message;
      $this->code = $code;
      $this->status = "error";
  }

}
class RouterException extends Exception {
  public function __construct($message, $code = 0){
    parent::__construct($message, $code);
  }

  public function __toString(){
    return $this->message;
  }

  public function send() {
    $error = new error($this->message, $this->code);
    $result = stripos($_SERVER['HTTP_ACCEPT'], 'application/json');
    if($result != FALSE) {
      Response::jsonResponse($error);
    }
    else {
      echo ("ERREUR ".$this->code." !");
      var_dump($error);
      }
  }



}

 ?>
