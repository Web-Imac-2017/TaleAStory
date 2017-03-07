<?php
const ERR_NOT_CONNECTED = -1;

class CurrentUserController{

  public function __construct(){
    $this->loadModel('player');
  }

  static public function stats(){
    $player = $this->player->connectSession();
    if($player) {
      $stats = $player->stats();
      return json_encode($stats);
    } else {return ERR_NOT_CONNECTED;}
  }

  static public function items(){
    $player = $this->player->connectSession();
    if($player) {
      $items = $player->items();
      return json_encode($items);
    } else {return ERR_NOT_CONNECTED;}
  }

  static public function step(){
    $player = $this->player->connectSession();
    if($player) {
      //$story = $player->pastStep();
      //return json_encode($story);
    } else {return ERR_NOT_CONNECTED;}
  }

  static public function story(){
    $player = $this->player->connectSession();
    if($player) {
      $story = $player->pastStep();
      return json_encode($story);
    } else {return ERR_NOT_CONNECTED;}
  }


}
?>
