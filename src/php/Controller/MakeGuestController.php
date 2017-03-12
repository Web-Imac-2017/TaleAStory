<?php
namespace Controller;
use \Server\Database;
use \Server\Response;
use \Server\Form;
use \Model\Player;
use \Server\Session;
use \View\Error;
use \View\Success;


class MakeGuestController{

  public function __construct(){
  }

  static public function MakeGuest(){
    //$login = random_int(1000, 100000);
    $login = rand(1000, 100000);
    while(Database::instance()->query("Player", array('Login'=>strval($login)))) {
      $login++;
    }
    $guest = Player::signup("Guest", strval($login), "Guest", "fake@mail.com", $imgpath = NULL);
    if(!$guest) {
      $error = new Error("Oh bah ça n'a pas marché!");
      Response::jsonResponse($error);
    } else {
      $success = new Success($guest);
      Response::jsonResponse($success);
    }
  }


}

?>
