<?php

require_once('Response.php');

class SignOutControlers{


  static public signout() {
    $player = Player::connectSession();
    if(!$player) {return Response::redirect("taleastory/");}
    else {
        $player->disconnect();
        return Response::redirect("taleastory/");
    }
  }

}

?>
