<?php

use \Model\Player;
use \Server\Response;
class SignOutControllers{


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
