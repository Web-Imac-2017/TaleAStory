<?php
namespace Controller;
use \Model\Player;
use \Server\Response;
class SignOutControllers{


  static public function signOut() {
    $player = Player::connectSession();
    if(!$player) {
      Response::redirect("taleastory/");
      $error = new Error("T'étais pas connecté banouille");
      return Response::jsonResponse($error);
    }
    else {
      $player->disconnect();
      Response::redirect("taleastory/");
      $success = new Success("Joueur deconnecté");
      return Response::jsonResponse($success);
    }
  }

}

?>
