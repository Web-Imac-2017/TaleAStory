<?php

class SignOutControlers{

  public function __construct(){

  }

  static public signout() {
    $player = Player::connectSession();
    if(!$player) {return NULL;}
    else {
        $player->disconnect();
        return Response::redirect("taleastory/");
    }
  }

}

?>
