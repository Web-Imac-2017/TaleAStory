<?php
require "Player.php";

class Admin {
  public $id;
  public $player;

  public function __construct($id, $player){
    $this->id = $id;
    $this->player = $player;
  }

  static public function signup($pseudo, $login, $pwd, $mail, $imgpath = NULL){
    $player = Player::signup($pseudo, $login, $pwd, $mail, $imgpath = NULL);
    echo "<pre>".var_export($player, true)."</pre>";
    Database::instance()->insert("admin", array("IDAdmin"=>"", "IDPLayer"=>$player->id));
    $id = Database::instance()->query("admin", array("IDPLayer"=>$player->id, "IDAdmin"=>""));
    $id = $id[0]['IDAdmin'];
    if(!$id || !$player) {return NULL;}
    $admin = new Admin($id, $player);
    return $admin;
  }

  static public function connect($login, $pwd) {
    $player = Player::connect($login, $pwd);
    $id = Database::instance()->query("admin", array("IDAdmin"=>"", "IDPLayer"=>$player->id));
    $id = $id[0]['IDAdmin'];
    if(!$id && !$player) {return NULL;}
    else if(!$id) {$admin = NULL;}
    else {$admin = new Admin($id, $player);}
    return array("admin"=>$admin, "player"=>$player);
  }
}
?>
