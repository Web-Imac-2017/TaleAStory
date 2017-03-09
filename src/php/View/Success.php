<?php
namespace View;
class Success {

/**
* Permet de définir une erreur comme étant un message, un code et un status initialisé a "error"
*/
  public static $status = "ok";

  public function __construct($result,$code = 0) {
      $this->message = $message;
      $this->code = $code;
      $this->status = self:$status;
  }

}
?>
