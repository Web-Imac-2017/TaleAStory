<?php
Class Session {

  static public function connect(){
    session_start();
  }

  static public function disconnect(){

  }

  static public function setCookies(){
    setcookie("name","value",time()+$int);
  }

  static public function cleanCookies(){
    unset($_COOKIE["yourcookie"]);
  }

}
?>
