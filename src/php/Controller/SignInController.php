<?php
namespace Controller;
use \Server\Database;
use \Server\Response;
use \Server\Form;
use \Model\Player;
use \Server\Session;
use \View\Error;
use \View\Success;

class SignInController{

  static public function signIn(){

    $login = Form::getField('login');
    $pwd = Form::getField('pwd');
    $player = Player::connect($login, $pwd);
    //echo "<pre>".var_export($user, true)."</pre>";
    if(!$player){
      $error = new Error("tu sais pas rentrer ton login trou duc ni ton password d'ailleur... je suppose");
      Response::jsonResponse($error);
    } else if(!is_object($player)){
      if($player == -1){
        $error = new Error("Ton login c'est dla merde");
        Response::jsonResponse($error);
      } else if($player == -2){
        $error = new Error("Bah alors t'as oublié ton mot de passe?");
        Response::jsonResponse($error);
      }
    } else {
      $success = new Success($player);
      Response::jsonResponse($success);
    }
  /*
  Response::jsonResponse(array(
  'status' => "error",
  'login ' => $login,
  'pwd' => $pwd,
  'message' => "si tu t'affiche pas je nique ta mère !"

));
*/
  }

}

?>
