<?php
namespace Model;
use \Server\Database;
use \Server\Session;
use \View\Error;
use \View\Success;

const ERR_LOGIN = -1;
const ERR_PWD = -2;
const NON_VALID_ENTRY = -3;

class Player {
  public $id;
  public $pseudo;
  public $login;
  public $pwd;
  public $mail;
  public $imgpath;
  public $admin;
  public $defaultImgpath = "defaultImg.png";

  private function __construct($id, $pseudo, $login, $pwd=NULL, $mail, $imgpath = NULL){
    $this->pseudo = $pseudo;
    $this->login = $login;
    if($pwd)
      $this->pwd = Database::instance()->encode($pwd);
    $this->mail = $mail;
    $this->imgpath = (is_null($imgpath)) ? $this->defaultImgpath : $imgpath;
    $this->id = $id;
    $this->admin = NULL;
  }

  static public function getPlayer($id) {
    $playerData = Database::instance()->query("Player", array("IDPlayer"=>$id, "*"=>""));
    if ($playerData != NULL) {
        $player = new Player(
          $playerData[0]["IDPlayer"],
          $playerData[0]["Pseudo"],
          $playerData[0]["Login"],
          "",
          $playerData[0]["Mail"],
          $playerData[0]["ImgPath"]
        );
        return $player;
    }
    else return NULL;
  }

  static public function signUp($pseudo, $login, $pwd, $confirm, $mail, $imgpath = NULL) {
    //echo "SIGN UP!";
    $nb_error = 0;
    $error_mssg = array("mail"=>"ok", "pseudo"=>"ok", "pwd"=>"ok", "confirmpwd"=>"ok");
    //check function parameters
    if(!$mail){
      $error_mssg['mail']="paramètre maquant";
      $nb_error++;
    } else if(!Player::formatMail($mail)){
      $error_mssg['mail']="ce n'est pas une adresse mail valide";
      $nb_error++;
    } else if(Player::checkLogin($mail)){
      $error_mssg['mail']="cet identifiant est déjà utilisé";
      $nb_error++;
    }
    if(!$pwd){
      $error_mssg['pwd']="paramètre maquant";
      $nb_error++;
    } else if(!Player::validateEntry($pwd)){
      $error_mssg['pwd']="ce n'est pas un password valide";
      $nb_error++;
    } else if($confirm != $pwd){
      $error_mssg['confirmpwd']="le mot de passe ne correspond pas à la première saisie";
      $nb_error++;
    }

    if(!$pseudo){
      $error_mssg['pseudo']="paramètre maquant";
      $nb_error++;
    } else if(!Player::validateEntry($pseudo)){
      $error_mssg['pseudo']="ce n'est pas un pseudo valide";
      $nb_error++;
    }
    if($nb_error!= 0){
      $error_mssg['player']="les paramètres sont inadaptés, le joueur n'a pas pu être créé";
      $error = new Error($error_mssg);
      return $error;
    } else {
      $player = new Player(0, $pseudo, $login, $pwd, $mail, $imgpath);
      $player->id = $player->save();
      $player->admin = $player->isAdmin();
      if($player->id == NULL || $player == NULL) {
        $error_mssg['player']="inexplicablement, le joueur n'a pas pu être créé";
        $error = new Error($error_mssg);
        return $error;
      }
      Session::connectUser($player->id, true, $player->login);
      return $player;
    }
  }

  static public function connect($login, $pwd) {
    //echo "CONNECT!";
    $login = Player::checkLogin($login);
    if(!$login){return ERR_LOGIN;}
    $pwd = Player::checkPwd($pwd, $login);
    if(!$pwd){return ERR_PWD;}
      $playerData = Database::instance()->query("player",array("Login"=>$login, "*" => ""));
      $player = new Player(
        $playerData[0]["IDPlayer"],
        $playerData[0]["Pseudo"],
        $playerData[0]["Login"],
        "",
        $playerData[0]["Mail"],
        $playerData[0]["ImgPath"]
      );
      //var_dump($player);
      if(!$player){return NULL;}
      $player->admin = $player->isAdmin();
      Session::connectUser($player->id, true, $player->login);
      return $player;
    /*} else {
      return NULL;
    }*/
  }

  static public function connectSession() {
    $id = Session::getCurrentUser();
    if($id){
      //var_dump($id);
      $playerData = Database::instance()->query("Player", array("IDPlayer"=>$id, "*"=>""));
      $player = new Player(
        $playerData[0]["IDPlayer"],
        $playerData[0]["Pseudo"],
        $playerData[0]["Login"],
        "",
        $playerData[0]["Mail"],
        $playerData[0]["ImgPath"]
      );
      $player->admin = $player->isAdmin();
      return $player;
    } else {
      return NULL;
    }
  }

  public function isAdmin(){
    $id = Database::instance()->query("admin", array("IDAdmin"=>"", "IDPLayer"=>$this->id));
    $id = current($id)['IDAdmin'];
    return ($id)?$id:NULL;
  }

  public function disconnect(){
    Session::disconnectUser();
    return NULL;
  }

  public function save() {
    $table = "Player";
    $entries = array(
      "ImgPath" => $this->imgpath,
      "Login" => $this->login,
      "Pwd" => $this->pwd,
      "Pseudo" => $this->pseudo,
      "Mail" => $this->mail,
      "IDCurrentStep" => 0
    );
    try {
      Database::instance()->insert($table, $entries);
    }
    catch (RuntimeException $e) {
        //echo $e->getMessage();
        return NULL;
    }
    $id = Database::instance()->query($table, array("IDPlayer" =>"", "Login" =>$this->login));
    return current($id)['IDPlayer'];
  }

  public function update($entries) {
    Database::instance()->update("Player", $entries, array("IDPlayer"=>$this->id));
  }

  public function delete(){
    $entries = array(
      "IDPlayer" => $this->id
    );
   try {
      $table = "PlayerAchievement";
       Database::instance()->delete($table, array("IDPlayer"=>$this->id));
      $table = "Inventory";
       Database::instance()->delete($table, array("IDPlayer"=>$this->id));
      $table = "PlayerStat";
       Database::instance()->delete($table, array("IDPlayer"=>$this->id));
      $table = "PastStep";
       Database::instance()->delete($table, array("IDPlayer"=>$this->id));
      $table = "Admin";
      Database::instance()->delete($table, array("IDPlayer"=>$this->id));
      Database::instance()->delete("Player", $entries);
    }catch (RuntimeException $e) {
        return false;
    }
    return true;
  }

  public function setPassword($newPwd) {
    $new_pwd = Database::instance()->encode($newPwd);
    $this->pwd = $new_pwd;
    return $new_pwd;

  }

  static public function checkPwd($pwd, $login){
    $qry = Database::instance()->query("player",array("Login"=>$login, "Pwd" => ""));
    $hashed = current($qry)["Pwd"];
    return Database::instance()->decode($pwd, $hashed);
  }

  static public function checkLogin($login){
    $qry = Database::instance()->query("player",array("Login"=>$login, "IDPlayer"=>""));
    $login = current($qry)['Login'];
    return $login;
  }
  /*
  public function checkData($data, $type){
    echo "check data";
    $qry = Database::instance()->query("Player",array("Login"=>$this->login, $type => ""));
    //echo "<pre>".var_export($qry, true)."</pre>";
    $dbData = $qry[0][$type];
    return ($dbData == $data)?1:0;
  }
  */


///////------STATS------//////
  public function stats() {
    $tables = array(
      array(
        "PlayerStat" => "PlayerStat.IDStat",
        "Stat" => "Stat.IDStat"
      )
    );
    $stats = Database::instance()->query($tables,array("PlayerStat.IDPlayer"=>$this->id,"PlayerStat.Value"=>"", "Stat.*" => ""));
    //$stats = Database::instance()->arrayMap($stats, "IDStat", "Value");
    return $stats;
  }

  public function alterStats($newstats){
    foreach($newstats as $id => $value){
      $stat = Database::instance()->query("PlayerStat", array("IDPlayer" => $this->id, "IDStat"=> $id, "Value"=>""));
      if($stat==NULL) {
        if($value >=0) {
          Database::instance()->insert("PlayerStat", array("IDPlayer" => $this->id, "IDStat"=> $id, "Value" => $value));
        } else {
          Database::instance()->insert("PlayerStat", array("IDPlayer" => $this->id, "IDStat"=> $id, "Value" => 0));
        }
      } else {
        $currentValue = $stat[0]['Value'];
        $value = ($value+$currentValue>=0)?$value+$currentValue: 0;
        Database::instance()->update("PlayerStat",array("Value" => $value), array("IDPlayer" => $this->id, "IDStat"=> $id));
      }
    }
  }

///////------ITEMS------//////
  public function items() {
    $tables = array(
      array(
        "Inventory" => "Inventory.IDItem",
        "Item" => "Item.IDItem"
      )
    );
    $items = Database::instance()->query($tables,array("Inventory.IDPlayer"=>$this->id, "Inventory.quantity"=>"", "Item.*" => ""));
    return $items;
  }


  public function addItem($item) {

    $quantity = Database::instance()->query("Inventory",Array("IDPlayer"=>$this->id, "IDItem"=>key($item), "quantity"=>""));
    $quantity = current($quantity)['quantity'];
    if($quantity) {
      if($quantity<10){$quantity = (($quantity+current($item))<10)?$quantity+current($item):10;}
      Database::instance()->update("Inventory",Array("quantity"=>$quantity),Array("IDPlayer"=>$this->id, "IDItem"=>key($item)));
    } else {
      Database::instance()->insert("Inventory", Array("IDPlayer"=>$this->id, "IDItem"=>key($item), "quantity"=>current($item)));
    }

  }

  public function addItems($items) {
    foreach($items as $id => $number) {
      $quantity = Database::instance()->query("Inventory",Array("IDPlayer"=>$this->id, "IDItem"=>$id, "quantity"=>""));
      $quantity = current($quantity)['quantity'];
      if($quantity) {
        if($quantity<10){$quantity = (($quantity+$number)<10)?$quantity+$number:10;}
        Database::instance()->update("Inventory",Array("quantity"=>$quantity),Array("IDPlayer"=>$this->id, "IDItem"=>$id));
      }
        else {
        Database::instance()->insert("Inventory", Array("IDPlayer"=>$this->id, "IDItem"=>$id, "quantity"=>$number));
      }
    }
  }

  public function removeItems($items) {
    foreach($items as $id => $number) {
      $quantity = Database::instance()->query("Inventory",Array("IDPlayer"=>$this->id, "IDItem"=>$id, "quantity"=>""));
      $quantity = current($quantity)['quantity'];
      //echo "<pre>".var_export($quantity, true)."</pre>";
      if(!$quantity) {
        return NULL;
      } else if($quantity > $number) {
        $quantity = $quantity - $number;
        Database::instance()->update("Inventory",Array("quantity"=>$quantity),Array("IDPlayer"=>$this->id, "IDItem"=>$id));
      } else {
        Database::instance()->delete("Inventory",Array("IDPlayer"=>$this->id, "IDItem"=>$id));
      }
    }
  }

  ///////------ACHIEVEMENTS------//////
  public function achievements() {
    Database::instance()->update("PlayerAchievement", array("isRead"=>1), array("isRead"=>0, "IDPlayer"=>$this->id));
    $tables = array(
      array(
        "PlayerAchievement" => "PlayerAchievement.IDAchievement",
        "Achievement" => "Achievement.IDAchievement"
      )
    );
    $achievements = Database::instance()->query($tables,array("PlayerAchievement.IDPlayer"=>$this->id,"PlayerAchievement.isRead"=>"", "Achievement.*" => ""));
    return $achievements;
  }

  public function unreadAchievements() {
    $tables = array(
      array(
        "PlayerAchievement" => "PlayerAchievement.IDAchievement",
        "Achievement" => "Achievement.IDAchievement"
      )
    );
    $achievements = Database::instance()->query($tables,array("PlayerAchievement.IDPlayer"=>$this->id,"PlayerAchievement.isRead"=>"0", "Achievement.*" => ""));
    Database::instance()->update("PlayerAchievement", array("isRead"=>1), array("isRead"=>0, "IDPlayer"=>$this->id));
    return $achievements;
  }

  public function addAchievement($achievement) {

    $player_achievement = Database::instance()->query("PlayerAchievement",Array("IDPlayer"=>$this->id, "IDAchievement"=>$achievement->id, "isRead"=>""));
    if($player_achievement == NULL) {
      Database::instance()->insert("PlayerAchievement", Array("IDPlayer"=>$this->id, "IDAchievement"=>$achievement->id, "isRead"=>0));
    }
  }

  public function addAchievements($achievements) {

    foreach($achievements as $achievement) {
     $this->addAchievement($achievement);
    }
  }

  ///////------STEPS------//////

  public function currentStep() {
    $tables = array(
      array(
        "Player" => "Player.IDCurrentStep",
        "Step" => "Step.IDStep"
      )
    );
    $currentStep = Database::instance()->query($tables, array("Player.IDPlayer"=>$this->id, "Step.*"=> ""));
    return $currentStep;
    //return new Step($currentStep[0]['ImgPath'], $currentStep[0]['Body'], $currentStep[0]['Question'], $currentStep[0]['IDType']);
  }

  public function pastSteps() {
    $tables = array(
      array(
        "PastStep" => "PastStep.IDStep",
        "Step" => "Step.IDStep"
      )
    );
    $pastSteps = Database::instance()->query($tables,array("PastStep.IDPlayer"=>$this->id, "Step.*" => ""));
    return $pastSteps;
  }

  public function passStep($step){
    date_default_timezone_set('Europe/Paris');
    $today = date("y.m.d");
    $currentStep = Database::instance()->query("Player", array("IDPlayer"=>$this->id, "IDCurrentStep"=>""));
    $currentStep = $currentStep[0]['IDCurrentStep'];
    Database::instance()->insert("paststep", array("IDPlayer"=>$this->id, "IDStep"=>$currentStep, "EndDate"=>$today));
    Database::instance()->update("Player", array("IDCurrentStep"=>$step->id), array("IDPlayer"=>$this->id));
  }

  ///////------IMAGE------//////
  public function changeImage($path){
    $this->imgpath = $path;
    Database::instance()->update("Player", array("ImgPath" => $path), array("IDPlayer"=>$this->id));
  }


  //////////-----FORMATS ENTREES-----////////////

  static public function formatMail($email){
    return (filter_var($email, FILTER_VALIDATE_EMAIL))?$email:NULL;
  }

  static public function validateEntry($s){
    $regex = "#^\w{1,60}$#";
    return preg_match($regex,$s);
  }


}

 ?>
