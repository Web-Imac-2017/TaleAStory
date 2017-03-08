<?php
const ERR_NOT_CONNECTED = -4;
require_once('Player.php');
require_once('Response.php');
class CurrentUserController{


  static public function stats(){
    $player = Player::connectSession();
    if(!$player) return ERR_NOT_CONNECTED;}
    else {
      $stats = $player->stats();
      return Response::jsonResponse($stats);
    }
  }

  static public function items(){
    $player = Player::connectSession();
    if(!$player) {return ERR_NOT_CONNECTED;}
    else {
      $items = $player->items();
      return Response::jsonResponse($items);
    }
  }

  static public function currentStep(){
    $player = Player::connectSession();
    if(!$player) {return ERR_NOT_CONNECTED;}
    else {
      $current_step = $player->currentStep();
      return Response::jsonResponse($current_step);
    }
  }

  static public function story(){
    $player = Player::connectSession();
    if(!$player) {return ERR_NOT_CONNECTED;}
    else {
      $story = $player->pastStep();
      return Response::jsonResponse($story);
    }
  }


}
?>
