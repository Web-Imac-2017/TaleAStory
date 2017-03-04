<?php
require "module_database.php";

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
      return $player;
      echo "connected";
    } else {
      echo "connection failed";
      return NULL;
    }
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

  /**
   * [arrayMap description]
   * @param  [type] $entry [description]
   * @param  [string] $key   [champ de la table, si int le tableau retourné aura des index numériques incrémentés à partir de 0]
   * @param  [type] $value [description]
   * @return [type]        [description]
   */
  public function arrayMap($entry, $key, $value) {
    $map = array();
    foreach($entry as $data){
      $map = array_merge($map, array($data[$key]=>$data[$value]));
    }
    return $map;
  }

  public function stats() {
    echo "stats";
    $tables = array(
      array(
        "PlayerStat" => "PlayerStat.IDStat",
        "Stat" => "Stat.IDStat"
      )
    );
    $stats = Database::instance()->query($tables,array("PlayerStat.IDPlayer"=>$this->id,"PlayerStat.Value"=>"", "Stat.*" => ""));
    return $stats;
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

    $quantity = Database::instance()->query("Inventory",Array("IDPlayer"=>$this->id, "IDItem"=>$item->id, "quantity"=>""));
    $quantity = $quantity[0]['quantity'];
    echo "<pre>".var_export($quantity, true)."</pre>";
    if($quantity) {
      if($quantity<10){$quantity++;}
      Database::instance()->update("Inventory",Array("quantity"=>$quantity),Array("IDPlayer"=>$this->id, "IDItem"=>$item->id));
    } else {
      Database::instance()->insert("Inventory", Array("IDPlayer"=>$this->id, "IDItem"=>$item->id, "quantity"=>1));
    }

  }

  public function addItems($items) {
    foreach($items as $item) {
      var_dump($item);
      $quantity = Database::instance()->query("Inventory",Array("IDPlayer"=>$this->id, "IDItem"=>$item->id, "quantity"=>""));
      $quantity = $quantity[0]['quantity'];
      echo "<pre>".var_export($quantity, true)."</pre>";
      if($quantity) {
        if($quantity<10){$quantity++;}
        Database::instance()->update("Inventory",Array("quantity"=>$quantity),Array("IDPlayer"=>$this->id, "IDItem"=>$item->id));
      }
        else {
        Database::instance()->insert("Inventory", Array("IDPlayer"=>$this->id, "IDItem"=>$item->id, "quantity"=>1));
      }
    }
  }

  public function removeItem($item) {
    $quantity = Database::instance()->query("Inventory",Array("IDPlayer"=>$this->id, "IDItem"=>$item->id, "quantity"=>""));
    $quantity = $quantity[0]['quantity'];
    echo "<pre>".var_export($quantity, true)."</pre>";
    if($quantity >1) {
      $quantity --;
      Database::instance()->update("Inventory",Array("quantity"=>$quantity),Array("IDPlayer"=>$this->id, "IDItem"=>$item->id));
    } else if ($quantity == 1) {
      Database::instance()->delete("Inventory",Array("IDPlayer"=>$this->id, "IDItem"=>$item->id));
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
    $player_achievement = Database::instance()->query("PlayerAchievement",Array("IDPlayer"=>$this->id, "IDAchivement"=>$achievement->id, "isRead"=>""));
    echo "<pre>".var_export($player_achievement, true)."</pre>";
    if($player_achivement == NULL) {
      Database::instance()->insert("PlayerAchievement", Array("IDPlayer"=>$this->id, "IDItem"=>$achievement->id, "isRead"=>false));
    }
  }

  public function addAchievements($achievements) {

    foreach($achievements as $player_achievement) {
      $player_achievement = Database::instance()->query("PlayerAchievement",Array("IDPlayer"=>$this->id, "IDAchivement"=>$achievement->id, "isRead"=>""));
      echo "<pre>".var_export($player_achievement, true)."</pre>";
      if($player_achivement == NULL) {
        Database::instance()->insert("PlayerAchievement", Array("IDPlayer"=>$this->id, "IDItem"=>$achievement->id, "isRead"=>false));
      }
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

  public function passStep($perpetie){
    $entries = array(
      ""
    );
    //Database::instance()->insert("Step",$entries);
   $peripethieID;
   $entries = array(
     "IDPlayer" => $this->id,
     "IDStep" =>$peripetieID
   );
   Database::instance()->insert("PastStep",$entries);

  }

  public function alterStats($newStats){
    //$newStats = array($stats => $valeur)

    $tables = array(
      array(
        "PlayerStat" => "PlayerStat.IDStat",
        "Stat" => "Stat.IDStat"
      )
    );
    $identification = array("PlayerStat.IDPlayer" => $this->id);
    $currentStats = $this->stats();
    foreach($newStats as $stat => $value) {
      if($currentStats[0][$stat]) {
        $entries = array($stat => $value);
        Database::instance()->update($tables, $entries, $identification);
      }
    }
  }

  public function changeImage($path){
    $this->imgpath = $path;
    Database::instance()->update("Player", array("ImgPath" => $path), array("IDPlayer"=>$this->id));
  }


  //////////-----FORMATS ENTREES-----////////////
  static function formatMail($entry){

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
    if(!$id || !$player) {return NULL;}
    $admin = new Admin($id, $player);
    return $admin;
  }
}

 ?>
