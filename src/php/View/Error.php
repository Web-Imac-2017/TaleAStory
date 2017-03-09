<?php
namespace View;
class Error {

/**
* Permet de définir une erreur comme étant un message, un code et un status initialisé a "error"
*/
  public static $status = "error";

  public function __construct($message,$code = 0) {
      $this->message = $message;
      $this->code = $code;
      $this->status = self:$status;
  }

}
?>
