<?php
namespace Controller;
use \Model\Player;
use \Server\Response;
use \View\Error;
use \View\Success;

class SignOutController{


  static public function signOut() {
    $player = Player::connectSession();
    if(!$player) {
      //Response::redirect("taleastory/");
      $error = new Error("T'étais pas connecté banouille");
      Response::jsonResponse($error);
    }
    else {
      $player->disconnect();
      //Response::redirect("taleastory/");
      $success = new Success("Joueur deconnecté");
      Response::jsonResponse($success);
    }
  }

}

?>
