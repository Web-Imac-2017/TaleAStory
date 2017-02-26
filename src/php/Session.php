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
  /*
  @function disconnectUser
  @param  $userid
  @return void
  Supprime l'id de l'user dans la session et les cookies
  */
  static public function disconnectUser($userid){
    session_unset($_SESSION["userid"]);
    if (isset($_COOKIE["userid"]) && isset($_COOKIE["hashed_id"])){
      unset($_COOKIE["userid"]);
      unset($_COOKIE["hashed_id"]);
      setcookie($_COOKIE["userid"],time()-3600);
      setcookie($_COOKIE["hashed_id"],time()-3600);
    }
  }
  /*
  @function getSessionAttribute
  @param  $name nom de la variable que la fonction retourne
  @return la variable si elle existe dans la session ou les cookies, chaine vide sinon
  */
  static public function getSessionAttribute($name){
    if(isset($_SESSION["$name"]))
      return $_SESSION["$name"];
    else if (isset($_COOKIE["$name"])){
        return $_COOKIE["$name"];
    }
    else
      return null;
  }
  /*
  @function setSessionAttribute
  @param  $name nom de la variable à enregistrer
  @param  $value valeur de la variable à enregistrer
  @return void
  enregistre une variable dans la session
  */
  static public function setSessionAttribute($name,$value){
    $_SESSION["$name"]=$value;
  }
  /*
  @function setCookieAttribute
  @param  $name nom de la variable à enregistrer
  @param  $value valeur de la variable à enregistrer
  @param  $cookie_time date expiration du cookie
  @return void
  enregistre une variable dans les cookies
  */
  static public function setCookieAttribute($name,$value,$cookie_time=null){
    $cookie_time==null?time() + 3600:$cookie_time;
    setcookie("$name",$value,$cookie_time);
  }
}
?>
