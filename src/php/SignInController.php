<?php
const FAILED_CONNECTION = -3;
require_once('Admin.php');
require_once('Response.php');
class SignInController{

  static public function signIn(){
    $data;
    $user = Admin::connect($data);
    if(!$user){
      return FAILED_CONNECTION;
    } else {
      $user = ($user[0]==NULL)?$user[1]:$user[0];
      $userData = array();
      $userData['id']= $user->id;
      $userData['pseudo']= $user->pseudo;
      $userData['imgpath']= $user->imgpath;
      $userData['mail']= $user->mail;
      $json = array('success', $userData);
      return Response::jsonResponse($json);

    }

  }

}

?>
