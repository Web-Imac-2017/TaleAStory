<?php
Class Session {

  /*
  @function setSession
  Crée une session et génere un id de session
  */
  static public function setSession(){
      session_start();
       /* FIREGREEN ** session_id doit être appelé avant session start
       session_regenerate_id Remplace l'identifiant de session courant par un nouveau
       tu ne peux pas le passer en paramètre de session_id
       vérifie que tu aie bien besoin de définir une id de session, il y en a sans doute
       un définit par défaut** */
      session_id(session_regenerate_id());
  }
  /*
  @function closeSession
  Ferme la session de l'user
  */
  static public function closeSession(){
    /* FIREGREEN ** session_destroy ferme la session sans enlever les variables de
    sessions, unset lui le fait mais on ne veut pas perdre les données de Session
    sauf cas exceptionnel ** */
    session_unset ();
    session_destroy();
  }
  /*
  @function getCurrentUser
  @return id de l'user courant, ou chaine vide si rien
  FIREGREEN ** pour les cookies, fais une vérification en plus,
  genre enregistre l'userid crypté et si en le décriptant en retrouve l'userid alors
  c'est bien un cookie qu'on a défini **
  */
  static public function getCurrentUser(){
    if(isset($_SESSION["userid"]))
      return $_SESSION["userid"];
    else if (isset($_COOKIE["userid"]))
      return $_COOKIE["userid"];
    else
      return null;
  }
  /*
  @function connectUser
  @param  $userid
  @return void
  Enregistre l'id de l'user dans $_SESSION et $_COOKIE
  FIREGREEN ** pour les cookies, rajoute l'userid crypté cf. getCurrentUser **
  */
  static public function connectUser($userid){
    $_SESSION["userid"]=$userid;
    setcookie("userid",$userid);
  }

}
?>
