<?php
namespace View;
class Success {

/**
* Permet de définir une erreur comme étant un message, un code et un status initialisé a "error"
*/
  public static $etat = "ok";

  public function __construct($result,$code = 0) {
      $this->result = json_encode($result);
      $this->code = $code;
      $this->status = self::$etat;
  }

}
?>
