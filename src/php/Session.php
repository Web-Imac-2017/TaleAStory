<?php
Class Session {

  /*
  @function setSession
  Crée une session et génere un id de session
  */
  static public function setSession(){
      session_start();
  }
  /*
  @function closeSession
  Ferme la session de l'user
  */
  static public function closeSession(){
    session_destroy ();
  }
  /*
  @function getCurrentUser
  @return id de l'user courant, ou chaine vide si rien
  */
  static public function getCurrentUser(){
    if(isset($_SESSION["userid"]))
      return $_SESSION["userid"];
    else if (isset($_COOKIE["userid"]) && isset($_COOKIE["hashed_id"])){
        return password_verify($_COOKIE["hashed_id"],$_COOKIE["userid"]);
    }
    else
      return null;
  }
  /*
  @function connectUser
  @param  $userid
  @return void
  Enregistre l'id de l'user dans $_SESSION et $_COOKIE
  */
  static public function connectUser($userid){
    $_SESSION["userid"]=$userid;
    setcookie("userid",$userid);
    $hashed_id = password_hash($userid,PASSWORD_DEFAULT);
    setcookie("hash_id",$hashed_id);
  }

}
?>
