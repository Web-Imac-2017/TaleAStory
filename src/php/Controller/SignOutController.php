<?php

use \Model\Player;
use \Server\Response;
class SignOutControllers{


  static public function signOut() {
    $player = Player::connectSession();
    if(!$player) {return Response::redirect("taleastory/");}
    else {
        echo "test";
        $player->disconnect();
        return Response::redirect("taleastory/");
    }
  }

}

?>
