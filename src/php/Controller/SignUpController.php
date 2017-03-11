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
    $login = Form::getField('login');
    $pwd = Form::getField('pwd');
    $pseudo = Form::getField('pseudo');
    if(!$mail || !$login || !$pseudo || !$pwd){
      $e = new Error("Impossible d'ajouter le player - champs manquants");
      return Response::jsonResponse($e);
    }
    $player = Player::signUp($pseudo, $login, $pwd, $mail);
    if(!$player){
      $error = new Error("tu sais pas rentrer ton login trou duc ni ton password d'ailleur... je suppose");
      return Response::jsonResponse($error);
    } else if(!is_object($player)){
      if($player == -1){
        $error = new Error("Login déjà existant");
        return Response::jsonResponse($error);
      } else if($player == -3){
        $error = new Error("Format des entrées non conformes");
        return Response::jsonResponse($error);
      }
    } else {
      //INIT STATS
      $statsQuery = Database::instance()->query("Stat",array("IDStat"=>""));
      $statsQuery = Database::instance()->arrayMap($statsQuery,0,'IDStat');
      $stats = array();
      foreach ($statsQuery as $s) {
        $stats[$s]=0;
      }
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
