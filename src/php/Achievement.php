<?php

Class Achievement {
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
      "IDAchievement" => "",
      'name' => $this->name,
      'imgpath' => $this->imgpath,
      'brief' => $this->brief
    );
    Database::instance()->insert($table, $entries);
    }

    public function delete() {
      $table = "PlayerAchievement";
      $entries = array(
        "IDAchievement" => $this->id
      );
      Database::instance()->delete($table, $entries);
      $table = "Achievement";
      Database::instance()->delete($table, $entries);
    }
  }

?>
