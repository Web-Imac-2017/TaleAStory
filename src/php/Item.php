<?php

Class Item {
  public $id;
  public $name;
  public $imgpath;
  public $brief;
  public static $table = "Item";

  public function __construct($name, $imgpath, $brief) {
    $this->name=$name;
    $this->imgpath=$imgpath;
    $this->brief=$brief;
  }

  /*
  @function save
  @return $id de l'item créé, null si erreur
  Insère un nouvel item en base de données
  */

  public function save() {
    $entries = array(
      'name' => $this->name,
      'imgpath' => $this->imgpath,
      'brief' => $this->brief
    );
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
    Supprime un item donné, ainsi que ses "mentions" dans les tables qui sont liées à l'item
    */

    public function delete() {
      $entries = array(
        "IDItem" => $this->id
      );
      try {
        $table = "Inventory";
        Database::instance()->delete($table, $entries);
        $table = "ItemRequirement";
        Database::instance()->delete($table, $entries);
        $table = "Lose";
        Database::instance()->delete($table, $entries);
        $table = "Earn";
        Database::instance()->delete($table, $entries);
        Database::instance()->delete(self::$table, $entries);
      }catch (RuntimeException $e) {
          echo $e->getMessage();
          return false;
      }
      return true;
      }

      /*
      @function update
      @param  $entries array de la forme : "Champ à modifier"=>"nouvelle valeur"
      @return void
      Maj un item
      */

    public function update($entries) {
      Database::instance()->update(self::$table, $entries, array("IDItem"=>$this->id));
    }

  }

?>
