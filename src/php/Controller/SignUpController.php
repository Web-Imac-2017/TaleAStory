<?php
namespace Controller;

use \Server\Database;
use \Server\Response;
use \Server\Form;
use \Model\Player;
use \Server\Session;
use \View\Error;
use \View\Success;

class SignUpController{

  static public function signUp(){
    $mail = Form::getField('mail');
    $pwd = Form::getField('pwd');
    $confirm = Form::getField('confirmpwd');
    $pseudo = Form::getField('pseudo');
    $login = $mail;
    $player = Player::signUp($pseudo, $login, $pwd, $confirm, $mail);
    if (get_class($player)=="Player"){
      //INIT STATS
      $statsQuery = Database::instance()->query("Stat",array("IDStat"=>""));
      $statsQuery = Database::instance()->arrayMap($statsQuery,0,'IDStat');
      $stats = array();
      foreach ($statsQuery as $s) {
        $stats[$s]=0;
      }
      $player->alterStats($stats);
      \Server\Session::connectUser($player->id, false, $player->mail);
      $playerData = array();
      $playerData['id']= $player->id;
      $playerData['pseudo']= $player->pseudo;
      $playerData['imgpath']= $player->imgpath;
      $playerData['mail']= $player->mail;
      $success = new Success($playerData);
      Response::jsonResponse($success);
    } else {
      Response::jsonResponse($player);
    }
  }

}

?>
