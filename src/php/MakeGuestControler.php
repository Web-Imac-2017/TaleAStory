<?php
require('Player.php');

class MakeGuestControler{

  public function __construct(){
  }

  static public function MakeGuest(){
    //générer pseudo aléatoire où incrémentation sur pseudo guest
    $guest = Player::signup();
  }

}

?>
