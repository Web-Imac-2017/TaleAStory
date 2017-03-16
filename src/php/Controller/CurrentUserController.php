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
  /*
  @function stats
  @return Success avec tableau vide si rien, tableau de stat 'name'=>'value' sinon
  Retourne les stats  de l'user connecté
  */
  static public function stats(){
    $player = CurrentUserController::isConnected();
    $stats = $player->stats();
    $stats = Database::instance()->arrayMap($stats, 'Name', 'Value');
    if($stats==null){$stats=array();}
    $success = new Success($stats);
    Response::jsonResponse($success);
  }
  /*
  @function item
  @return Success avec tableau vide si rien, tableau de item 'name'=>'quantity' sinon
  Retourne les item  de l'user connecté
  */
  static public function items(){
    $player = CurrentUserController::isConnected();
      $items = $player->items();
      $items = Database::instance()->dataClean($items, true, array('Brief', 'ImgPath', 'Name', 'quantity', 'IDItem'));
      if($items == NULL){$items = array();}
      $success = new Success($items);
      Response::jsonResponse($success);

  }
  /*
  @function currentstep
  @return Success avec tableau vide si rien, objet Step sinon
  Retourne la step courante de l'user connecté
  */
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
  /*
  @function currentuser
  @return Success avec tableau vide si rien, objet Player sinon
  Retourne l'user connecté
  */
  static public function currentUser() {
      $player = Player::connectSession();
      if($player){
        $e = new Success($player);
        Response::jsonResponse($e);
      }
      else
        return Response::jsonResponse(new Success(null));
  }
  /*
  @function achivements
  @return Success avec tableau vide si rien, tableau de objet Achievement sinon
  Retourne les trophées de l'user connecté
  */
  static public function achievements(){
    $player = CurrentUserController::isConnected();
    $achievements = $player->achievements();
    $achievements = Database::instance()->dataClean($achievements, true);
    if($achievements == NULL){$achievements = array();}
    $success = new Success($achievements);
    Response::jsonResponse($success);
  }
  /*
  @function unreadAchievements
  @return Success avec tableau vide si rien, tableau de objet Achievement sinon
  Retourne les trophées pas encore gagnés de l'user connecté
  */
  static public function unreadAchievements(){
    $player = CurrentUserController::isConnected();
    $achievements = $player->unreadAchievements();
    $achievements = Database::instance()->dataClean($achievements, true);
    if($achievements == NULL){$achievements = array();}
    $success = new Success($achievements);
    Response::jsonResponse($success);
  }
  /*
  @function isAdmin
  @return Error si l'user courant n'est pas admin, objet Player sinon
  Vérifie que l'user courant est admin
  */
  static public function isAdmin(){
    $admin = Player::connectSession();
    if(!$admin || $admin->admin == 0 || $admin->isAdmin() == 0){
      $e = new Error("Tu n'as pas le droit d'effectuer cette action !");
      Response::jsonResponse($e);
    }
  }
  /*
  @function isConnected
  @return Error si aucun user est co, objet Player sinon
  Vérifie qu'il y a un user connecté
  */
  static public function isConnected(){
    $player = Player::connectSession();
    if(!$player) {
      $error = new Error("Vous n'êtes pas connectés");
      Response::jsonResponse($error);
    }
    else return $player;
  }
  /*
  @function deletePlayer
  @param  idplayer
  @return Error avec array("champ"=>"message erreur"), Success sinon
  Supprime l'user courant et ses attributs
  */
  public static function deletePlayer() {
    CurrentUserController::isAdmin();
    $id = Form::getField("idplayer");
    if(!$id){
      $e = new Error(array("idplayer"=>"ID Invalide ! Impossible de supprimer le joueur !"));
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
  /*
  @function updatePseudo
  @param pseudo
  @return Error avec array("champ"=>"message erreur"), Success sinon
  Modifie le pseudo de l'user courant
  */
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
  /*
  @function updatePwd
  @param currentPwd newPwd
  @return Error avec array("champ"=>"message erreur"), Success sinon
  Vérifie que le mot de passe courant est bon puis le modifie avec le nouveau mot de passe
  */
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
  /*
  @function updateImage
  @param image
  @return Error avec array("champ"=>"message erreur"), Success sinon
  Modifie l'image de l'user courant
  */
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
