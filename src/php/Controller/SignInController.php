<?php
namespace Controller;
use \Server\Database;
use \Model\Admin;
use \Server\Response;
use \Server\Form;

const FAILED_CONNECTION = -3;
class SignInController{

  static public function signIn(){

    $login = Form::getField('login');
    $pwd = Form::getField('password');
    $user = Admin::connect($login, $pwd);
    if(!$user){
      $json = array('status'=>'error');
      return Response::jsonResponse($json);
    } else {
      $admin = $user[0];
      $adminData = ($admin)?array('id'=>$admin->id):NULL;
      $player = $user[1];
      $playerData = array();
      $playerData['id']= $player->id;
      $playerData['pseudo']= $player->pseudo;
      $playerData['imgpath']= $player->imgpath;
      $playerData['mail']= $player->mail;
      $json = array('status'=>'success', 'player'=>$playerData, 'admin'=>$admin);
      return Response::jsonResponse($json);
    }

   echo 'Sign In';
  }

}

?>
