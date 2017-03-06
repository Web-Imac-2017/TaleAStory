<?php

Class Item {
  public $id;
  public $name;
  public $imgpath;
  public $brief;

  public function __construct($name, $imgpath, $brief) {
    $this->name=$name;
    $this->imgpath=$imgpath;
    $this->brief=$brief;
  }

  public function save($name, $imgpath, $brief) {
    $table = "Item";
    $entries = array(
      "IDItem" => "",
      'name' => $this->name,
      'imgpath' => $this->imgpath,
      'brief' => $this->brief
    );
    Database::instance()->insert($table, $entries);
    }

    public function delete() {
      $entries = array(
        "IDItem" => $this->id
      );
      try {
        $table = "Item";
        Database::instance()->delete($table, $entries);
      }catch (RuntimeException $e) {
          echo $e->getMessage();
          return false;
      }
      return true;
      }


    public function update($entries) {
      //AFFICHE UN MESSAGE D'ERREUR MAIS FONCTIONNE QUAND MEME ??
      Database::instance()->update(self::$table, $entries, array("IDItem"=>$this->id));
    }

  }

?>
