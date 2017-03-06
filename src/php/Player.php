<?php
require "Database.php";
require "Session.php";
const UNAVAILABLE_LOGIN = -2;
const NON_VALID_ENTRY = -1;

class Player {
  public $id;
  public $pseudo;
  public $login;
  public $pwd;
  public $mail;
  public $imgpath;
  public $defaultImgpath = "../../defaultImg.jpg";

  private function __construct($id, $pseudo, $login, $pwd, $mail, $imgpath = NULL){
    $this->pseudo = $pseudo;
    $this->login = $login;
    $this->pwd = Database::instance()->encode($pwd);
    $this->mail = $mail;
    $this->imgpath = (is_null($imgpath)) ? $this->defaultImgpath : $imgpath;
    $this->id = $id;
  }

  static public function signup($pseudo, $login, $pwd, $mail, $imgpath = NULL) {
    //echo "SIGN UP!";
    if(Player::checkLogin($login)) {return UNAVAILABLE_LOGIN;}
    else if
    (
      !Player::formatMail($mail) ||
      !Player::validateEntry($login) ||
      !Player::validateEntry($pseudo) ||
      !Player::validateEntry($pwd)
    )
    {return NON_VALID_ENTRY;}
    else {
      $player = new Player(0, $pseudo, $login, $pwd, $mail, $imgpath);
      $player->id = $player->save();
      if($player->id == NULL) {
        return NULL;
      }
      Session::connectUser($player->id, true, $player->login);
      return $player;
    }
  }

  static public function connect($login, $pwd) {
    //echo "CONNECT!";
    $login = Player::checkLogin($login);
    $pwd = Player::checkPwd($pwd, $login);
    //echo "<pre>".var_export($login, true).var_export($pwd, true)."</pre>";
    if($login && $pwd) {
      $playerData = Database::instance()->query("player",array("Login"=>$login, "*" => ""));
      $player = new Player(
        $playerData[0]["IDPlayer"],
        $playerData[0]["Pseudo"],
        $playerData[0]["Login"],
        $playerData[0]["Pwd"],
        $playerData[0]["Mail"],
        $playerData[0]["ImgPath"]
      );
      Session::connectUser($player->id, true, $player->login);
      return $player;
    } else {
      return NULL;
    }
  }

  static public function connectSession() {
    $id = Session::getCurrentUser();
    if($id){

      $playerData = Database::instance()->query("Player", array("IDPlayer"=>$id, "*"=>""));
      $player = new Player(
        $playerData[0]["IDPlayer"],
        $playerData[0]["Pseudo"],
        $playerData[0]["Login"],
        $playerData[0]["Pwd"],
        $playerData[0]["Mail"],
        $playerData[0]["ImgPath"]
      );
      return $player;
    } else {
      return NULL;
    }
  }

  public function disconnect(){
    Session::disconnectUser();
    return NULL;
  }

  public function save() {
    $table = "Player";
    $entries = array(
      "IDPlayer" => "",
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
        echo $e->getMessage();
        return NULL;
    }
    $id = Database::instance()->query($table, array("IDPlayer" =>"", "Login" =>$this->login));
    return $id[0][IDPlayer];
  }

  public function update() {
    //echo "UPDATE";
    $table = "Player";
    $entries = array(
      "IDPlayer" => $this->id,
      "ImgPath" => $this->imgpath,
      "Login" => $this->login,
      "Pwd" => $this->pwd,
      "Pseudo" => $this->pseudo,
      "Mail" => $this->mail
    );
    $where = array("IDPlayer" => $this->id);
    Database::instance()->update($table, $entries, $where);
  }

  public function setPassword($newPwd) {
    $this->pwd = Database::instance()->encode($newPwd);
  }

  static public function checkPwd($pwd, $login){
    $qry = Database::instance()->query("player",array("Login"=>$login, "Pwd" => ""));
    $hashed = $qry[0]["Pwd"];
    return Database::instance()->decode($pwd, $hashed);
  }

  static public function checkLogin($login){
    $qry = Database::instance()->query("player",array("Login"=>$login, "IDPlayer"=>""));
    $login = $qry[0]['Login'];
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
    $stats = Database::instance()->arrayMap($stats, "IDStat", "Value");
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
    $quantity = $quantity[0]['quantity'];
    if($quantity) {
      if($quantity<10){$quantity = (($quantity+current($item))<10)?$quantity+current($item):10;}
      Database::instance()->update("Inventory",Array("quantity"=>$quantity),Array("IDPlayer"=>$this->id, "IDItem"=>key($item)));
    } else {
      Database::instance()->insert("Inventory", Array("IDPlayer"=>$this->id, "IDItem"=>key($item), "quantity"=>current($item)));
    }

  }

  public function addItems($items) {
    foreach($items as $id => $number) {
      var_dump($item);
      $quantity = Database::instance()->query("Inventory",Array("IDPlayer"=>$this->id, "IDItem"=>$id, "quantity"=>""));
      $quantity = $quantity[0]['quantity'];
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
      $quantity = $quantity[0]['quantity'];
      //echo "<pre>".var_export($quantity, true)."</pre>";
      if($quantity > $number) {
        $quantity = $quantity - $number;
        Database::instance()->update("Inventory",Array("quantity"=>$quantity),Array("IDPlayer"=>$this->id, "IDItem"=>$id));
      } else if ($quantity == 1) {
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
