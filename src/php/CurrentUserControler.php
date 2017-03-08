<?php
const ERR_NOT_CONNECTED = -4;

class CurrentUserControler{

  public function __construct(){
    $this->loadModel('player');
  }



  static public signout() {
    $player = $this->player->connectSession();
    if(!$player) {return NULL;}
    else {
        $player->disconnect();
    }
  }

  static public function stats(){
    $player = $this->player->connectSession();
    if(!$player) return ERR_NOT_CONNECTED;}
    else {
      $stats = $player->stats();
      return json_encode($stats);
    }
  }

  static public function items(){
    $player = $this->player->connectSession();
    if(!$player) {return ERR_NOT_CONNECTED;}
    else {
      $items = $player->items();
      return json_encode($items);
    }
  }

  static public function currentStep(){
    $player = $this->player->connectSession();
    if(!$player) {return ERR_NOT_CONNECTED;}
    else {
      $current_step = $player->currentStep();
      return json_encode($current_step);
    }
  }

  static public function story(){
    $player = $this->player->connectSession();
    if(!$player) {return ERR_NOT_CONNECTED;}
    else {
      $story = $player->pastStep();
      return json_encode($story);
    }
  }


}
?>
