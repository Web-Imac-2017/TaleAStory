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
    $imgpath="bidon";
    $mail = Form::getField('Mail');
    $login = Form::getField('Login');
    $pwd = Form::getField('Pwd');
    $pseudo = Form::getField('Pseudo');
    if(!$mail || !$login || !$pseudo || !$pwd){
      $e = new Error("Impossible d'ajouter le player - champs manquants");
      return Response::jsonResponse($e);
    }
    $player = Player::connectSession();
    if(!$player){
      $error = new Error("Impossible d'ajouter le player");
      return Response::jsonResponse($error);
    } else {
      //INIT STATS
      $statsQuery = Database::instance()->query("Stat",array("IDStat"=>""));
      var_dump($statsQuery);
      $stats = array();
      foreach ($statsQuery as $s) {
        $stats[$s]=0;
      }
      var_dump($stats);
      $player->alterStats($stats);

      $playerData = array();
      $playerData['id']= $player->id;
      $playerData['pseudo']= $player->pseudo;
      $playerData['imgpath']= $player->imgpath;
      $playerData['mail']= $player->mail;
      $success = new Success($playerData);
      return Response::jsonResponse($success);
    }
  }

}

?>
