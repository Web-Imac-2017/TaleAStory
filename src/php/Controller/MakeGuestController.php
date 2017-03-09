<?php
require('Player.php');
const FAILED_SIGNUP = -5;
class MakeGuestController{

  public function __construct(){
  }

  static public function MakeGuest(){
    $login = random_int(1000, 100000);
    while(Database::instance()->query("Player", array('Login'=>strval($login)))) {
      $login++;
    }
    $guest = Player::signup("Guest", strval($login), "Guest", "fake@mail.com", $imgpath = NULL);
    if(!$guest) {
      return FAILED_SIGNUP;
    } else {
      $guestData = array();
      $guestData['id']= $guest->id;
      $guestData['pseudo']= $guest->pseudo;
      $guestData['imgpath']= $guest->imgpath;
      $guestData['mail']= $guest->mail;
      $json = array('success', $guestData);
      return Response::jsonResponse($json);
    }
  }

}

?>
