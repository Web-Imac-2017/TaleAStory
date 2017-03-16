<?php
namespace Controller;

use \Model\Player;
use \Server\Database;
use \Server\Response;
use \Server\Form;
use \Server\Session;
use \View\Error;
use \View\Success;

const ERR_NOT_CONNECTED = -4;

class CurrentUserController{

  static public function stats(){
    $player = CurrentUserController::isConnected();
    $stats = $player->stats();
    $stats = Database::instance()->arrayMap($stats, 'Name', 'Value');
    if($stats==null){$stats=array();}
    $success = new Success($stats);
    Response::jsonResponse($success);
  }

  static public function items(){
    $player = CurrentUserController::isConnected();
      $items = $player->items();
      $items = Database::instance()->dataClean($items, true, array('Brief', 'ImgPath', 'Name', 'quantity', 'IDItem'));
      if($items == NULL){$items = array();}
      $success = new Success($items);
      Response::jsonResponse($success);

  }

  static public function currentStep(){
    $player = CurrentUserController::isConnected();
      $current_step = $player->currentStep();
      $current_step = Database::instance()->dataClean($current_step, true);
      if($current_step == NULL){$current_step = array();}
      $success = new Success($current_step);
      Response::jsonResponse($success);

  }

  static public function start(){
    $player = CurrentUserController::isConnected();
    $current_step = $player->currentStep();
    $current_step = Database::instance()->dataClean($current_step, true);
    if($current_step[0]['IDStep']==0 || $current_step[0]['IDStep']==null){
      $player->setStep(2); // 2 = first story
      $current_step = $player->currentStep();
      $current_step = Database::instance()->dataClean($current_step, true);
      Response::jsonResponse(new Success($current_step));
    }
    Response::jsonResponse(new Error('Vous avez déjà une partie en cours'));
  }

  static public function story(){
    $player = CurrentUserController::isConnected();
      $story = $player->pastSteps();
      $story = Database::instance()->dataClean($story, true);
      if($story == NULL){$story = array();}
      $success = new Success($story);
      Response::jsonResponse($success);

  }

  static public function currentUser() {
      $player = Player::connectSession();
      if($player){
        $e = new Success($player);
        Response::jsonResponse($e);
      }
      else
        return Response::jsonResponse(new Success(null));
  }

  static public function achievements(){
    $player = CurrentUserController::isConnected();
    $achievements = $player->achievements();
    $achievements = Database::instance()->dataClean($achievements, true);
    if($achievements == NULL){$achievements = array();}
    $success = new Success($achievements);
    Response::jsonResponse($success);
  }

  static public function unreadAchievements(){
    $player = CurrentUserController::isConnected();
    $achievements = $player->unreadAchievements();
    $achievements = Database::instance()->dataClean($achievements, true);
    if($achievements == NULL){$achievements = array();}
    $success = new Success($achievements);
    Response::jsonResponse($success);
  }

  static public function isAdmin(){
    $admin = Player::connectSession();
    if(!$admin || $admin->admin == 0 || $admin->isAdmin() == 0){
      $e = new Error("Tu n'as pas le droit d'effectuer cette action !");
      Response::jsonResponse($e);
    }
  }

  static public function isConnected(){
    $player = Player::connectSession();
    if(!$player) {
      $error = new Error("Vous n'êtes pas connectés");
      Response::jsonResponse($error);
    }
    else return $player;
  }

  public static function deletePlayer() {
    CurrentUserController::isAdmin();
    $id = Form::getField("IDPlayer");
    if(!$id){
      $e = new Error(array("IDPlayer"=>"ID Invalide ! Impossible de supprimer le joueur !"));
      Response::jsonResponse($e);
    }
    else{
      $player = Player::getPlayer($id);
      if($player){
        $oldimg =  $player->imgpath;
        $player = $player->delete();
        if($oldimg != '../assets/images/default_image_tiny.png' && file_exists ($oldimg)){
          try {
            unlink($oldimg);
          } catch(Exception $e) { }
        }
        $e = new Success("Player supprimé !");
      }
      else
        $e = new Error(array("all"=>"Impossible de supprimer le joueur !"));
      Response::jsonResponse($e);
    }
  }

/*  public static function updatePlayer() {
    $data = Form::getFullForm(); //si l'id n'est pas présent, on retourne null
    if(!isset($data["IDPlayer"]) || $data["IDPlayer"]== null ){
      $e = new Error(array("IDPlayer"=>"Id invalide ! Péripéthie invalide"));
      Response::jsonResponse($e);
    }

    $entries = array();
    $fields = array("ImgPath","Pwd","Pseudo");
    foreach ($fields as $field) {
      if(!isset($data[$field]) || $data[$field]=="") {
        if(substr($field,0,2) != "ID") //les champs ID inchangés (donc initialisés à null dans data) ne doivent pas être précisés dans entries
          $entries[$field]="";
      }
      else {
        $entries[$field]=$data[$field];
      }
    }
    $player = Player::getPlayer($data["IDPlayer"]);
    if(!$player){
      $e = new Error(array("IDPlayer"=>"Id non attribué!"));
      Response::jsonResponse($e);
    }
    $player->update($entries);
    $e = new Success("Joueur modifié !");
    Response::jsonResponse($e);
  }*/

  public static function updatePseudo(){
    $pseudo = Form::getField('pseudo');
    $player = CurrentUserController::isConnected();
    if(!$pseudo || !Player::validateEntry($pseudo)){
      $e = new Error(array("Pseudo"=>"nouveau pseudo invalide!"));
      Response::jsonResponse($e);
    }
    $player->update(array("Pseudo"=>$pseudo));
    $player->pseudo = $pseudo;
    $s = new Success("Pseudo modifié!");
    Response::jsonResponse($s);
  }

  public static function updatePwd(){
    $current_pwd = Form::getField('currentPwd');
    $new_pwd = Form::getField('newPwd');
    $player = CurrentUserController::isConnected();
    $check_pwd = Player::checkPwd($current_pwd, $player->login);
    if(!$check_pwd){
      $e = new Error(array("Pwd"=>"Mot de passe actuel incorrect!"));
      Response::jsonResponse($e);
    }
    if(!$new_pwd || !Player::validateEntry($new_pwd)){
      $e = new Error(array("Pwd"=>"Nouveau mot de passe invalide!"));
      Response::jsonResponse($e);
    }
    $hashed_pwd = $player->setPassword($new_pwd);
    $player->update(array("Pwd"=>$hashed_pwd));
    $s = new Success("Password modifié!");
    Response::jsonResponse($s);
  }

  public static function updateImage(){
    $imgpath=Form::uploadFile("image");
    $player = CurrentUserController::isConnected();
    if(is_object($imgpath)){
      Response::jsonResponse($imgpath);
    }
    else{
      $oldimg =  $player->imgpath;
      if($oldimg != '../assets/images/default_image_tiny.png' && file_exists ($oldimg)){
        try {
          unlink($oldimg);
        } catch(Exception $e) { }
      }
      $player->update(array("ImgPath"=>$imgpath));
      $s = new Success("Image modifiée!");
      Response::jsonResponse($s);
    }
  }

}
?>
