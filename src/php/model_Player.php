<?php
require "module_database.php";
require "Session.php";

class Player {
  public $id;
  public $pseudo;
  public $login;
  public $pwd;
  public $mail;
  public $imgpath;

  private function __construct(){
    }

  static public function signup($pseudo, $login, $pwd, $mail, $imgpath = NULL) {
    echo "SIGN UP!";
    $defaultImgpath = "../../defaultImg.jpg";
    //VERIFIER LE FORMAT DES CHAMPS
    $player = new Player();
    if(!$player->checkLogin($login)) {
      $player->pseudo = $pseudo;
      $player->login = $login;
      $player->pwd = Database::instance()->encode($pwd);
      $player->mail = $mail;
      $player->imgpath = (is_null($imgpath)) ? $defaultImgpath : $imgpath;
      $player->id = $player->save();
      if($player->id == NULL) {
        echo "signup failed";
        return NULL;
      }
      echo "signup completed";
      Session::connectUser($player->id, true);
      return $player;
    } else {
      echo "login deja pris";
      return NULL;
    }
  }

  static public function connect($login, $pwd) {
    echo "CONNECT!";
    $player = new Player();
    $login = $player->checkLogin($login);
    $pwd = $player->checkPwd($pwd, $login);
    echo "<pre>".var_export($login, true).var_export($pwd, true)."</pre>";
    if($login && $pwd) {
      $dataPlayer = Database::instance()->query("player",array("Login"=>$login, "*" => ""));
      $player->id = $dataPlayer[0]["IDPlayer"];
      $player->pseudo = $dataPlayer[0]["Pseudo"];
      $player->login = $dataPlayer[0]["Login"];
      $player->pwd = $dataPlayer[0]["Pwd"];
      $player->mail = $dataPlayer[0]["Mail"];
      $player->imgpath = $dataPlayer[0]["ImgPath"];

      Session::connectUser($player->id, true);
      echo "connected";
      return $player;
    } else {
      echo "connection failed";
      return NULL;
    }
  }

  static public function connectSession() {
    $id = Session::getCurrentUser();
    if($id){
      $player = new Player();
      $playerData = Database::instance()->query("Player", array("IDPlayer"=>$id, "*"=>""));
      $player->id = $id;
      $player->pseudo = $dataPlayer[0]["Pseudo"];
      $player->login = $dataPlayer[0]["Login"];
      $player->pwd = $dataPlayer[0]["Pwd"];
      $player->mail = $dataPlayer[0]["Mail"];
      $player->imgpath = $dataPlayer[0]["ImgPath"];
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
    echo "save";
    $table = "Player";
    $entries = array(
      "IDPlayer" => "",
      "ImgPath" => $this->imgpath,
      "Login" => $this->login,
      "Pwd" => $this->pwd,
      "Pseudo" => $this->pseudo,
      "Mail" => $this->mail,
      "IDCurrentStep" => NULL
    );
    Database::instance()->insert($table, $entries);
    echo "test";
    $id = Database::instance()->query($table, array("IDPlayer" =>"", "Login" =>$this->login));
    return $id[0][IDPlayer];
  }

  public function update() {
    echo "UPDATE";
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
    echo "update completed";
  }

  public function checkPwd($pwd, $login){
    $qry = Database::instance()->query("player",array("Login"=>$login, "Pwd" => ""));
    $hashed = $qry[0]["Pwd"];
    return Database::instance()->decode($pwd, $hashed);
  }

  public function checkLogin($login){
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
    echo "stats";
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
      Database::instance()->update("PlayerStat",array("Value" => $value), array("IDPlayer" => $this->id, "IDStat"=> $id));
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
    echo "<pre>".var_export($quantity, true)."</pre>";
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
      echo "<pre>".var_export($quantity, true)."</pre>";
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
      echo "<pre>".var_export($quantity, true)."</pre>";
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
    echo "<pre>".var_export($player_achievement, true)."</pre>";
    if($player_achievement == NULL) {
      Database::instance()->insert("PlayerAchievement", Array("IDPlayer"=>$this->id, "IDAchievement"=>$achievement->id, "isRead"=>0));
    }
  }

  public function addAchievements($achievements) {

    foreach($achievements as $achievement) {
      /*
      $player_achievement = Database::instance()->query("PlayerAchievement",Array("IDPlayer"=>$this->id, "IDAchievement"=>$achievement->id, "isRead"=>""));
      echo "<pre>".var_export($player_achievement, true)."</pre>";
      if($player_achievement == NULL) {
        Database::instance()->insert("PlayerAchievement", Array("IDPlayer"=>$this->id, "IDAchievement"=>$achievement->id, "isRead"=>0));
      }
      */
     echo "<pre>".var_export($achievement, true)."</pre>";
     $this->addAchievement($achievement);
    }
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
    $currentStep = Database::instance()->query("Player", array("IDPlayer"=>$this->id, "IDCurrentStep"=>""));
    Database::instance()->insert("paststep", array("IDPlayer"=>$this-id, "IDStep"=>$currentStep, "EndDate"=>"1994"));
    Database::instance()->update("Player", array("IDCurrentStep"=>$step->id), array("IDPlayer"=>$this->id));
  }


  public function changeImage($path){
    $this->imgpath = $path;
    Database::instance()->update("Player", array("ImgPath" => $path), array("IDPlayer"=>$this->id));
  }


  //////////-----FORMATS ENTREES-----////////////

  static public function formatMail($email){
    return (filter_var($email, FILTER_VALIDATE_EMAIL))?$email:NULL;
  }

  static public function validateLength($s){
    return (strlen($s)<15)?$s:NULL;
  }

}

class Admin {
  public $id;
  public $player;

  public function __construct($id, $player){
    $this->id = $id;
    $this->player = $player;
  }

  static public function signup($pseudo, $login, $pwd, $mail, $imgpath = NULL){
    $player = Player::signup($pseudo, $login, $pwd, $mail, $imgpath = NULL);
    echo "<pre>".var_export($player, true)."</pre>";
    Database::instance()->insert("admin", array("IDAdmin"=>"", "IDPLayer"=>$player->id));
    $id = Database::instance()->query("admin", array("IDPLayer"=>$player->id, "IDAdmin"=>""));
    $id = $id[0]['IDAdmin'];
    if(!$id || !$player) {return NULL;}
    $admin = new Admin($id, $player);
    return $admin;
  }

  static public function connect($login, $pwd) {
    $player = Player::connect($login, $pwd);
    $id = Database::instance()->query("admin", array("IDAdmin"=>"", "IDPLayer"=>$player->id));
    $id = $id[0]['IDAdmin'];
    if(!$id && !$player) {return NULL;}
    else if(!$id) {$admin = NULL;}
    else {$admin = new Admin($id, $player);}
    return array("admin"=>$admin, "player"=>$player);
  }
}

 ?>
