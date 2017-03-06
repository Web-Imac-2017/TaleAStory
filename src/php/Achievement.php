<?php

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

  public function save($name, $imgpath, $brief) {
    $entries = array(
      "IDAchievement" => "",
      'name' => $this->name,
      'imgpath' => $this->imgpath,
      'brief' => $this->brief
    );
    Database::instance()->insert(self::$table, $entries);
    }

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

    }

    public function update($entries) {
      Database::instance()->update(self::$table, $entries, array("IDAchievement"=>$this->id));
    }
  }

?>
