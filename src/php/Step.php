<?php

Class Step {
  public $imgpath;
  public $body;
  public $question;
  public $accepted;
  public $idType;

  public function __construct($imgpath, $body, $question, $accepted, $idType) {
    $this->imgpath=$imgpath;
    $this->body=$body;
    $this->question=$question;
    $this->accepted=$accepted;
    $this->idType=$idType;
  }

  public function save() {
   $table = "Step";
   $entries = array(
     "ID" => "",
     'imgpath' = $this->$imgpath;
     'body' = $this->$body;
     'question' = $this->$question;
     'accepted' = $this->$accepted;
     'idType' = $this->$idType;
   );
   $db->insert($table, $entries);
   }
}
?>
