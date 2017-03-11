<?php
namespace Model;

use \Server\Database;

Class Achievement {
  public $id;
  public $name;
  public $imgpath;
  public $brief;
  public static $table = "Achievement";

  public function __construct($name, $imgpath, $brief) {
    $this->name=$name;
    $this->imgpath=$imgpath;
    $this->brief=$brief;
  }

  /*
  @function save
  @return $id de l'achievement créé, null si erreur
  Insère un nouvel achievement en base de données
  */

  public function save() {
    $entries = array(
      'Name' => $this->name,
      'ImgPath' => $this->imgpath,
      'Brief' => $this->brief
    );
    var_dump($entries);
    $id=Database::instance()->insert(self::$table, $entries);
    if($id != null){
      $this->id = $id;
      return $id;
    }
    return null;
    }

    /*
    @function delete
    @return $bool faux si erreur, vrai si ok
    Supprime un achievement donné, ainsi que ses "mentions" dans la tables liée à l'achievement
    */

    public function delete() {
      $entries = array(
        "IDAchievement" => $this->id
      );
      try {
        $table = "PlayerAchievement";
        Database::instance()->delete($table, $entries);
        Database::instance()->delete(self::$table, $entries);
      } catch (RuntimeException $e) {
          echo $e->getMessage();
          return false;
      }
      return true;
    }

    /*
    @function update
    @param  $entries array de la forme : "Champ à modifier"=>"nouvelle valeur"
    @return void
    Maj un achievement
    */

    public function update($entries) {
      Database::instance()->update(self::$table, $entries, array("IDAchievement"=>$this->id));
    }
  }

?>
