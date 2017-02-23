<?php
class Joueur {
  public $ID;
  public $pseudo;
  public $login;
  public $pwd;
  public $mail;
  public $imgpath;
  public $connected;
  private $defaultImgpath = "../../defaultImg.jpg";

  public function __construct(){
    /*
    $this->pseudo = NULL;
    $this->login = NULL;
    $this->pwd = NULL;
    $this->mail = NULL;
    $this->imgpath = NULL;
    $this->connected = 0;
    $this->ID = NULL;
    */
   $arg = func_get_args();
   $num = func_num_args();
   if($num == 0) {
     echo "pas d'arg on passe a la suite";
     return $this;
   } else if($num>=4) {
     call_user_func_array(array($this,"signup"), $arg);
   } else {
     call_user_func_array(array($this,"connect"), $arg);
   }
  }

  public function connect($_login, $_pwd) {
    echo "CONNECT!";
    return $this;
  }

  public function signup($_pseudo, $_login, $_pwd, $_mail, $_imgpath = NULL) {
    echo "SIGN UP!";
    $this->pseudo = $_pseudo;
    $this->login = $_login;
    //$this->pwd = $db->encode($_pwd);
    $this->mail = $_mail;
    $this->imgpath = ($_imgpath)?$_imgpath:$defaultImgpath;
    $this->connected = 1;
    //$this->ID = $this->save();
    return $this;
  }
/*
  public function save() {
    $table = "Player";
    $entries = array(
      "ID" => "",
      "ImgPath" => $this->imgpath,
      "Login" => $this->login,
      "Pwd" => $this->pwd,
      "Pseudo" => $this->pseudo,
      "Mail" => $this->mail;
    )
    $db->insert($table, $entries);
    $id = $db->query($table, array("ID" =>"", "Login" =>$this->login));
    return $id[0][ID];
  }

  public function update() {
    $table = "Player";
    $entries = array(
      "ID" => $this->ID,
      "ImgPath" => $this->imgpath,
      "Login" => $this->login,
      "Pwd" => $this->pwd,
      "Pseudo" => $this->pseudo,
      "Mail" => $this->mail
    )
    $db->update($table, $entries);
  }
*/
}
 ?>
