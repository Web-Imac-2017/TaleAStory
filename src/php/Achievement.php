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
      "ID" => "",
      'name' = $this->$name;
      'imgpath' = $this->$imgpath;
      'brief' = $this->brief;
    );
    $db->insert($table, $entries);
    }
  }

?>
