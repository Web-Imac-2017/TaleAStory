<?php
require "module_database.php";

class User {
  public $ID;
  public $pseudo;
  public $login;
  public $pwd;
  public $mail;
  public $imgpath;

private function __construct(/*$ID, $pseudo, $login, $pwd, $mail, $imgpath*/){
    /*
    $this->ID = $ID;
    $this->pseudo = $pseudo;
    $this->login = $login;
    $this->pwd = $pwd;
    $this->mail = $mail;
    $this->imgpath = $imgpath;
    */
  }


  static public function signup($pseudo, $login, $pwd, $mail, $imgpath = NULL) {
    echo "SIGN UP!";
    $defaultImgpath = "../../defaultImg.jpg";
    //VERIFIER LE FORMAT DES CHAMPS
    $user = new User();
    if(!$user->checkLogin($login)) {
      $user->pseudo = $pseudo;
      $user->login = $login;
      $user->pwd = Database::instance()->encode($pwd);
      $user->mail = $mail;
      $user->imgpath = (is_null($imgpath)) ? $defaultImgpath : $imgpath;
      $user->ID = $user->save();
      if($user->ID == NULL) {
        echo "signup failed";
        return NULL;
      }
      echo "signup completed";
      return $user;
    } else {
      echo "login deja pris";
      return NULL;
    }
  }

  static public function connect($login, $pwd) {
    echo "CONNECT!";
    $user = new User();
    $login = $user->checkLogin($login);
    $pwd = $user->checkPwd($pwd, $login);
    echo "<pre>".var_export($login, true).var_export($pwd, true)."</pre>";
    if($login && $pwd) {
      $dataPlayer = Database::instance()->query("Player",array("Login"=>$login, "*" => ""));
      $user->ID = $dataPlayer[0]["ID"];
      $user->pseudo = $dataPlayer[0]["Pseudo"];
      $user->login = $dataPlayer[0]["Login"];
      $user->pwd = $dataPlayer[0]["Pwd"];
      $user->mail = $dataPlayer[0]["Mail"];
      $user->imgpath = $dataPlayer[0]["ImgPath"];
      $user->connected = 1;
      return $user;
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
      "ID" => "",
      "ImgPath" => $this->imgpath,
      "Login" => $this->login,
      "Pwd" => $this->pwd,
      "Pseudo" => $this->pseudo,
      "Mail" => $this->mail,
      "IDCurrentStep" => 2
    );
    Database::instance()->insert($table, $entries);
    echo "test";
    $id = Database::instance()->query($table, array("ID" =>"", "Login" =>$this->login));
    return $id[0][ID];
  }

  public function update() {
    echo "UPDATE";
    $table = "Player";
    $entries = array(
      "ID" => $this->ID,
      "ImgPath" => $this->imgpath,
      "Login" => $this->login,
      "Pwd" => $this->pwd,
      "Pseudo" => $this->pseudo,
      "Mail" => $this->mail
    );
    $where = array("ID" => $this->ID);
    Database::instance()->update($table, $entries, $where);
    echo "update completed";
  }

  public function checkPwd($pwd, $login){
    $qry = Database::instance()->query("Player",array("Login"=>$login, "Pwd" => ""));
    $hashed = $qry[0]["Pwd"];
    return Database::instance()->decode($pwd, $hashed);
  }

  public function checkLogin($login){
    $qry = Database::instance()->query("Player",array("Login"=>$login, "ID"=>""));
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
/*
}

class Player extends User {
  public $stats = 24;
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
        "Stat" => "Stat.ID"
      )
    );
    $statsQuery = Database::instance()->query($tables,array("PlayerStat.IDPlayer"=>$this->ID, "Stat.*" => ""));
    $stats = $this->arrayMap($statsQuery, 'Name', 'Value');
    return $stats;
  }

  public function items() {
    $tables = array(
      array(
        "Inventory" => "Inventory.IDItem",
        "Item" => "Item.ID"
      )
    );
    $items = Database::instance()->query($tables,array("Inventory.IDPlayer"=>$this->ID, "Item.*" => ""));
    $items = $this->arrayMap($items, 'ID', 'Name');
    return $items;
  }

  public function achievements() {
    $tables = array(
      array(
        "PlayerAchievement" => "PlayerAchievement.IDAchievement",
        "Achievement" => "Achievement.ID"
      )
    );
    $achievements = Database::instance()->query($tables,array("PlayerAchievement.IDPlayer"=>$this->ID, "Achievement.*" => ""));
    $achievements = $this->arrayMap($achievements, 'ID', 'Name');
    return $achievements;
  }

  public function pastSteps() {
    $tables = array(
      array(
        "PastStep" => "PastStep.IDStep",
        "Step" => "Step.ID"
      )
    );
    $pastSteps = Database::instance()->query($tables,array("PastStep.IDPlayer"=>$this->ID, "Step.*" => ""));
    return $pastSteps;
  }

  public function passStep($perpetie){
    $entries = array(
      ""
    );
    //Database::instance()->insert("Step",$entries);
   $peripethieID;
   $entries = array(
     "IDPlayer" => $this->ID,
     "IDStep" =>$peripetieID
   );
   Database::instance()->insert("PastStep",$entries);

  }

  public function alterStats($newStats){
    //$newStats = array($stats => $valeur)

    $tables = array(
      array(
        "PlayerStat" => "PlayerStat.IDStat",
        "Stat" => "Stat.ID"
      )
    );
    $identification = array("PlayerStat.IDPlayer" => $this->ID);
    $currentStats = $this->stats();
    foreach($newStats as $stat => $value) {
      if($currentStats[0][$stat]) {
        $entries = array($stat => $value);
        Database::instance()->update($tables, $entries, $identification);
      }
    }
  }

  public function changeImage(){
    //enregistrer img dans le bon truc
    $path;
    Database::instance()->update("Player", array("ImgPath" => $path), array("ID"=>$this->ID));

  }

  public function map($obj) {

  }

  //////////-----FORMATS ENTREES-----////////////
  static function formatMail($entry){

  }
}
 ?>
