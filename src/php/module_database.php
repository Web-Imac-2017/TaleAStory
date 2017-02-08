<?php
class Database {
  public $pdo;
  protected $server;
  protected $userName;
  protected $password;
  protected $dbName;
  protected $options;

  public function __construct($path){
    //$path = "../../../TaleAStory/src/php/database_config.json"
    $config_json = file_get_contents($path);
    $config_data = json_decode($config_json, TRUE);
    $this->server = $config_data[database][server];
    $this->userName = $config_data[database][user];
    $this->password = $config_data[database][password];
    $this->dbName = $config_data[database][name];
    $this->options = array(
      PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    );
  }

  private function getPDO() {
    if($this->pdo === null) {
      try{
        $pdo = new PDO('mysql:host='.$this->server.';dbname='.$this->dbName,$this->userName,$this->password,$this->options);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo = $pdo;
      }
      catch(Exception $e){
          die('Erreur : '.$e->getMessage());
      }
    }
    return $this->pdo;
  }

  //public pour test puis mettre en privé et coupler avec buildQuery
  public function sendQuery($statement) {
    $qry = $this->getPDO()->query($statement);
    $data = $qry->fetch();
    return $data;
  }

  /*
  Construit la requete, vérifie la validité du contenu entré grace à la fct check entry
  param : array : table de la bdd dans laquelle on cherche
  param : array : associant champs et entrées de l'utilisateur
  retour : query mysql
  */
  public function buildQuery( /*$table, $entries*/){
    /*
    query test :
    table : tableaux
    champs : titre, artiste
    recherche date = 1904
    */
    //params
    $tables = array ("tableaux");
    $entries = array (
      "titre" => "",
      "artiste" => "",
      "date" => "1904"
    );
    //fields
    $build_fields = $this->buildFields($entries);
    echo $build_fields;
    //tables
    $build_tables = $this->buildTables($tables);
    echo $build_tables;
    //where

    //$statement = "SELECT".$build_fields."FROM".$build_table."WHERE".$build_entry;

  }


  private function check_sql_injection($entries) {

  }

  private function buildFields($entries) {
    $fields = array_keys($entries);
    $build_fields;
    foreach ($fields as $field) {
      $build_fields .= $field;
      if($field != end($fields)) {
        $build_fields .=",";
      }
    }
    return $build_fields;
  }

  private function buildTables($tables) {
    //cas table unique
    if (count($tables) == 1) {
      $build_tables = $tables[0];
    } else {
      $build_tables = "joint not supported yet";
    }
    return $build_tables;
  }

}


 ?>
