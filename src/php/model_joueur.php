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
  public $database;

  public function __construct($_db){
   $this->database = $_db;
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


  public function signup($_pseudo, $_login, $_pwd, $_mail, $_imgpath = NULL) {
    echo "SIGN UP!";
    $this->pseudo = $_pseudo;
    $this->login = $_login;
    $this->pwd = $this->database->encode($_pwd);
    $this->mail = $_mail;
    $this->imgpath = ($_imgpath)?$_imgpath:$defaultImgpath;
    $this->connected = 1;
    $this->ID = $this->save();
    return $this;
  }

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
    $this->database->insert($table, $entries);
    $id = $this->database->query($table, array("ID" =>"", "Login" =>$this->login));
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
    $this->database->update($table, $entries);
  }

  public function connect($_login, $_pwd) {
    echo "CONNECT!";
    //if(keepConnection)
    if(checkData($_login, "Login") && checkPwd($pwd)) {
      $dataPlayer = $this->database->query("Player",array("Login"=>$this->login, "*" => ""));
      $this->ID = $dataPlayer[0]["ID"];
      $this->pseudo = $dataPlayer[0]["Pseudo"];
      $this->login = $dataPlayer[0]["Login"];
      $this->pwd = $dataPlayer[0]["Pwd"];
      $this->mail = $dataPlayer[0]["Mail"];
      $this->imgpath = $dataPlayer[0]["ImgPath"];
      $this->connected = 1;
      return $this
    } else {
      $this = NULL;
      return $this;
    }
  }

  public function checkPwd($pwd){
    $qry = $this->database->query("Player",array("Login"=>$this->login, "Pwd" => ""));
    $hashed = $qry[0]["Pwd"];
    return $database->decode($pwd, $hashed);
  }

  public function checkData($data, $type){
    $qry = $this->database->query("Player",array("Login"=>$this->login, $type => ""));
    $dbData = $qry[0][$type];
    return ($dbData == $data)?1:0;
  }

  public function stats() {
    $tables = array(
      array(
        "PlayerStat" => "PlayerStat.IDStat",
        "Stat" => "Stat.ID"
      )
    );
    //CHECKER SI CA MARCHE AVEC table.*
    $stats = $this->database->query($tables,array("PlayerStat.IDPlayer"=>$this->ID, "Stat.*" => ""))
    return $stats;
  }

  public function items() {
    $tables = array(
      array(
        "Inventory" => "Inventory.IDItem",
        "Item" => "Item.ID"
      )
    );
    $items = $this->database->query($tables,array("Inventory.IDPlayer"=>$this->ID, "Item.*" => ""))
    return $items;
  }

  public function achievements() {
    $tables = array(
      array(
        "PlayerAchievement" => "PlayerAchievement.IDAchievement",
        "Achievement" => "Achievement.ID"
      )
    );
    $achievements = $this->database->query($tables,array("PlayerAchievement.IDPlayer"=>$this->ID, "Achievement.*" => ""))
    return $achievements;
  }

  public function pastSteps() {
    $tables = array(
      array(
        "PastStep" => "PastStep.IDStep",
        "Step" => "Step.ID"
      )
    );
    $pastSteps = $this->database->query($tables,array("PastStep.IDPlayer"=>$this->ID, "Step.*" => ""))
    return $pastSteps;
  }

  public function passStep($perpetie){
    /*
    $entries = array(
      ""
    )
    $database->insert("Step",$entries);
    */
   $peripethieID;
   $entries = array(
     "IDPlayer" => $this->ID,
     "IDStep" =>$peripetieID
   );
   $database->insert("PastStep",$entries);

  }

  public function alterStats($newStats){
    /*
    $newStats = array($stats => $valeur)
     */
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
        $database->update($tables, $entries, $identification);
      }
    }
  }

  public function changeImage(){
    //enregistrer img dans le bon truc
    $path;
    $database->update("Player", array("ImgPath" => $path), array("ID"=>$this->ID));

  }

}
 ?>
