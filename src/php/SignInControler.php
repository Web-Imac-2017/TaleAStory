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
      $userData['id']= $user[0]['IDPlayer'];
      $userData['pseudo']= $user[0]['Pseudo'];
      $userData['imgpath']= $user[0]['ImgPath'];
      $userData['mail']= $user[0]['Mail'];
      $json = array('success', $userData);
      Response::jsonResponse($json);

    }

  }

}

?>
