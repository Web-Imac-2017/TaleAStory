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
    echo 'connexion bdd OK';
    return $this->pdo;
  }



  /*
  Construit la requete, vérifie la validité du contenu entré grace à la fct check entry
  param : array : tables de la bdd dans laquelle on cherche (keys numériques)
  param : array : associant champs et entrées de l'utilisateur
  retour : data
  */
  public function query($tables, $entries){
    //fields
    $build_fields = $this->buildFields($entries);
    //tables
    $build_tables = $this->buildTables($tables);
    //where
    $build_entries = $this->buildEntries($entries);
    $arrayEntries = $this->buildArrayEntries($entries);
    return $this->sendQuery($build_fields, $build_tables, $build_entries, $arrayEntries);
  }

  private function sendQuery($fields, $tables, $entries, $arrayEntries) {
      $statement="SELECT ".$fields." FROM ".$tables;
      if($entries) {
        $statement .= " WHERE ".$entries;
      }
      echo $statement;
      $qry = $this->getPDO()->prepare($statement);
      $qry->execute($arrayEntries) or die(print_r($qry->errorInfo()));
      $data = array();
      while($d = $qry->fetch()) {
        array_push($data, $d);
      }
      $qry->closeCursor();
      return $data;
    }

  private function checkSQLInjection($entries) {

  }

  private function buildFields($entries) {
    $fields = array_keys($entries);
    $build_fields;
    foreach ($fields as $field) {
      $build_fields .= $field;
      if($field != end($fields)) {
        $build_fields .=", ";
      }
      if ($field == "*") {
        return "*";
      }
    }
    return $build_fields;
  }

  private function buildTables($tables) {
    //cas table unique
    if (count($tables) == 1) {
      $build_tables = $tables[0];
    } else {
      $build_tables = "";
      echo "multiples tables not supported yet";
    }
    return $build_tables;
  }

  /*renvoie string de type toto = ? AND tata = ? AND...*/
  private function buildEntries($entries) {
    $build_entries;
    $arrayEntries = $this->buildArrayEntries($entries);
    foreach ($arrayEntries as $entry) {
      $field = array_search($entry, $entries);
      $build_entries .= $field." = ? ";
      if($entry != end($arrayEntries)) {
        $build_entries .="AND ";
      }
    }
    return $build_entries;
  }

/*renvoie un array à index numérique des valeurs entrées*/
  private function buildArrayEntries($entries) {
    $tabEntries = array();
    foreach ($entries as $entry) {
      if ($entry != NULL) {
        //if (checkSQLInjection($entry) ==0)
        array_push($tabEntries, $entry);
      }
    }
    return $tabEntries;
  }

}


 ?>
