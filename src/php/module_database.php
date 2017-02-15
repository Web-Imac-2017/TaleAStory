<?php
class Database {
  public $pdo;
  protected $server;
  protected $userName;
  protected $password;
  protected $dbName;
  protected $options;

  /**
   * Constructeur : configure la connexion avec la base de donnée
   * @param [string] $path [chemin vers le fichier de configuration]
   */
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

  /**
   * getPDO : fait la connexion a la base de données si elle n'a jamais été faite avant. Fonction utilisée par sendQuery()
   * @return objet pdo
   */
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



  /**
   * query : envoie une query et récupère les données. Utilise des fonctions tierces pour traiter les tableaux reçus,
   * concaténer les informations dans une string au format query, puis sendQuery pour l'envoyer au serveur
   * @param  [array de strings] $tables  [ [0...n] => "table"]
   * @param  [array de strings] $entries [ "champ" => "entrée utilisateur"/peut etre vide]
   * @return [array]          [contient toutes les données retournées par la requête]
   */
  public function query($tables, $entries){
    $select = $this->processSELECT($entries);
    $from = $this->processFROM($tables);
    $where = $this->processWHERE($entries);
    $statement=$select.$from;
    if($where) {
      $statement .= $where;
    }
    $array_entries = $this->processArrayEntries($entries);
    echo $statement;
    return $this->sendQuery($statement, $array_entries);
  }

  /**
   * sendQuery : prépare la query puis l'execute en utilisant un objet PDO
   * @param  [string] $statement     [texte de la query: variables de type "?"]
   * @param  [array] $array_entries [[0...n] => "variables à executer dans la query"]
   * @return [array]          [contient toutes les données retournées par la requête]
   */
  private function sendQuery($statement, $array_entries) {
    $qry = $this->getPDO()->prepare($statement);
    $qry->execute($array_entries) or die(print_r($qry->errorInfo()));
    $data = array();
    while($d = $qry->fetch()) {
      array_push($data, $d);
    }
    $qry->closeCursor();
    return $data;
    }

  /**
   * processSELECT : extrait les clés (champs) du tableau passé en param et les concatène dans une string contenant la partie SELECT de la query.
   * Si le tableau contient "*" renvoie "SELECT *"
   * @param  [array] $entries ["champ" => "entrée"]
   * @return [string]          ["SELECT champs1,champ2"]
   */
  private function processSELECT($entries) {
    $fields = array_keys($entries);
    $process_select = "SELECT ";
    foreach ($fields as $field) {
      $process_select .= $field;
      if($field != end($fields)) {
        $process_select .=", ";
      }
      if ($field == "*") {
        return "SELECT *";
      }
    }
    return $process_select;
  }

  /**
   * processFROM: extrait les valeurs du tableau passé en param et les concatène dans une string contenant la partie FROM de la query.
   * les queries sur des tables jointes ne sont pas encore prises en compte
   * @param  [array] $tables [[0...n] => "table"]
   * @return [string]         ["FROM table"]
   */
  private function processFROM($tables) {
    $process_from = " FROM ";
    if (count($tables) == 1) {
      $process_from .= $tables[0];
    } else {
      $process_from .= "";
      echo "multiples tables not supported yet";
    }
    return $process_from;
  }


  /**
   * processWHERE: extrait les valeurs du tableau passé en param et les concatène dans une string contenant la partie WHERE de la query.
   * @param  [array] $entries ["champ" => "entrée"]
   * @return [string]          ["WHERE champs1 = ? AND champs2 = ?"]
   */
  private function processWHERE($entries) {
    $process_where = " WHERE ";
    $array_entries = $this->processArrayEntries($entries);
    foreach ($array_entries as $entry) {
      $field = array_search($entry, $entries);
      $process_where .= $field." = ? ";
      if($entry != end($array_entries)) {
        $process_where .="AND ";
      }
    }
    return $process_where;
  }

/**
 * processArrayEntries: extrait les valeurs non nulles du tableau passé en param et en fait un nouveau tableau
 * @param  [array] $entries ["champ" => "entrée"]
 * @return [array]          [[0...n] => "entrée non nulle"]
 */
  private function processArrayEntries($entries) {
    $tabEntries = array();
    foreach ($entries as $entry) {
      if ($entry != NULL) {
        array_push($tabEntries, $entry);
      }
    }
    var_dump($tabEntries);
    return $tabEntries;
  }

}


 ?>
