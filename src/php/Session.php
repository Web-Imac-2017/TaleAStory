<?php
Class Session {

  static public function setSession(){
      session_start();
      session_id(session_regenerate_id());
  }

  static public function closeSession(){
    session_start();
    session_unset ();
    session_destroy();
  }

  static public function getCurrentUser(){
    if(isset($_SESSION["userid"]))
      return $_SESSION["userid"];
    else if (isset($_COOKIE["userid"]))
      return $_COOKIE["userid"];
    else
      return null;
  }

  static public function connectUser($userid){
    $_SESSION["userid"]=$userid;
    setcookie("userid",$userid);
  }

}
?>
