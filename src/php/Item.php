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
      $table = "Item";
      $entries = array(
        "IDItem" => $this->id
      );
      Database::instance()->delete($table, $entries);
    }

  }

?>
