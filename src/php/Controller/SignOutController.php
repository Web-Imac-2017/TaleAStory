<?php

require_once('Response.php');
require_once('Player.php');
class SignOutControllers{


  static public function signOut() {
    $player = Player::connectSession();
    if(!$player) {return Response::redirect("taleastory/");}
    else {
        $player->disconnect();
        return Response::redirect("taleastory/");
    }
  }

}

?>
