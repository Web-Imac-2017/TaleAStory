<?php
namespace Server;
class Database {
  public $pdo;
  protected $server;
  protected $userName;
  protected $password;
  protected $dbName;
  protected $options;
  static private $_instance = NULL;

  /**
   * Constructeur : configure la connexion avec la base de donnée
   * @param [string] $path [chemin vers le fichier de configuration]
   */
  private function __construct($path){
    //$path = "../../../TaleAStory/src/php/database_config.json"
    $config_json = file_get_contents($path);
    $config_data = json_decode($config_json, TRUE);
    $this->server = $config_data['database']['server'];
    $this->userName = $config_data['database']['user'];
    $this->password = $config_data['database']['password'];
    $this->dbName = $config_data['database']['name'];
    $this->options = array(
      \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    );
  }

  static public function instance(){
    if(is_null(self::$_instance)){
      self::$_instance = new Database("Server/database_config.json");
    }
    return self::$_instance;
  }

  /**
   * getPDO : fait la connexion a la base de données si elle n'a jamais été faite avant. Fonction utilisée par sendQuery()
   * @return objet pdo
   */
  private function getPDO() {
    if($this->pdo === null) {
      try{
        $pdo = new \PDO('mysql:host='.$this->server.';dbname='.$this->dbName,$this->userName,$this->password,$this->options);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
        $this->pdo = $pdo;
      }
      catch(Exception $e){
          die('Erreur : '.$e->getMessage());
      }
    }
    //echo 'connexion bdd OK';
    return $this->pdo;
  }

    //////////*****ENCODAGE DECODAGE******//////////

  /**
   * encode :
   * @param  [string] $purestring [un mot lisible]
   * @return [string]             [une chaine hashée avec un salt aléatoire et une clée de décryption]
   */
  public function encode($purestring) {
     return password_hash($purestring, PASSWORD_BCRYPT);
  }

  /**
   * [decode description]
   * @param  [string] $pw     [un mot lisible]
   * @param  [string] $hashed [la chaine hashée à comparer]
   * @return [bool]         [true : les deux chaines correspondent, false non]
   */
  public function decode($pw, $hashed) {
    return password_verify($pw, $hashed);
  }

  //////////*****REQUETES******//////////

  /**
   * query : envoie une query et récupère les données. Utilise des fonctions tierces pour traiter les tableaux reçus,
   * concaténer les informations dans une string au format query, puis sendQuery pour l'envoyer au serveur
   * @param  [array de strings] $tables  voir fonction processFROM
   * @param  [array de strings] $entries [ "champ" => "entrée utilisateur"/peut etre vide]
   * @return [array]          [contient toutes les données retournées par la requête]
   */
  public function query($tables, $entries, $addEndStatement = NULL){
    $select = $this->processSELECT($entries);
    $from = $this->processFROM($tables);
    $where = $this->processWHERE($entries);
    $statement=$select.$from;
    if($where) {
      $statement .= $where;
    }
    if($addEndStatement) {
      $statement .= " ".$addEndStatement;
    }
    $array_entries = $this->processArrayEntries($entries);
    //echo $statement;
    return $this->sendQuery($statement, $array_entries);
  }

  /**
   * sendQuery : prépare la query puis l'execute en utilisant un objet \PDO
   * @param  [string] $statement     [texte de la query: variables de type "?"]
   * @param  [array] $array_entries [[0...n] => "variables à executer dans la query"]
   * @return [array]          [contient toutes les données retournées par la requête]
   */
  private function sendQuery($statement, $array_entries) {
    $data = array();
    try {
      $qry = $this->getPDO()->prepare($statement);
      $qry->execute($array_entries) or die(print_r($qry->errorInfo()));
    }
    catch(Exception $e){
        die('Erreur : '.$e->getMessage());
        return NULL;
    }
    $data = array();
    while($d = $qry->fetch()) {array_push($data, $d);}
    $qry->closeCursor();
    return $data;
    }

  public function insert($table, $entries) {
    $into = "INSERT INTO ".$table;
    $fields = $this->processFields($entries);
    $values = $this->processValues($entries);
    $statement = $into.$fields.$values;
    //echo "\n".$statement."\n";
    return $this->sendInsert($statement, $entries);
  }

  private function sendInsert($statement, $entries) {
    //echo '<pre>' . var_export($entries, true) . '</pre>';
    try {
    $insert = $this->getPDO()->prepare($statement);
    $insert->execute($entries) or die(print_r($insert->errorInfo()));
    $id = $this->getPDO()->lastInsertId();
    }
    catch(Exception $e){
        die('Erreur : '.$e->getMessage());
        return NULL;
    }
    //echo "insert ok";
    return $id;
  }

  /**
   * [update description]
   * @param  [type] $table          voir fonction processFROM
   * @param  [type] $entries        [description]
   * @param  [type] $identification [description]
   * @return [type]                 [description]
   */
  public function update($table, $entries, $identification) {
    $update = "UPDATE ";
    $from = $this->processFROM($table);
    $from = str_replace("FROM ", "", $from);
    $set = " SET ".$this->processUPDATE($entries);
    $where = $this->processWHERE($identification);
    $array_entries = array_merge($this->processArrayEntries($entries), $this->processArrayEntries($identification));
    $statement = $update.$from.$set.$where;
    //echo "\n".$statement."\n";
    return $this->sendUpdate($statement, $array_entries);
  }

  private function sendUpdate($statement, $array_entries) {
    var_dump($statement);
    var_dump($array_entries);
    try {
      $update = $this->getPDO()->prepare($statement);
      $update->execute($array_entries) or die(print_r($update->errorInfo()));
    }
    catch(Exception $e){
        die('Erreur : '.$e->getMessage());
    }
    //echo "update ok";
  }

  public function delete($table, $identification) {
    $delete = "DELETE ";
    $from = $this->processFROM($table);
    $where = $this->processWHERE($identification);
    $statement = $delete.$from.$where;
    $array_entries = $this->processArrayEntries($identification);
    echo $statement;
    if (strstr($statement, "WHERE") == FALSE) {
      //echo "BUG DELETE";
      return 0;
    }
    return $this->sendDelete($statement, $array_entries);
  }

  private function sendDelete($statement, $array_entries){
    try {
      $delete = $this->getPDO()->prepare($statement);
      $delete->execute($array_entries) or die(print_r($delete->errorInfo()));
    }
    catch(Exception $e){
        die('Erreur : '.$e->getMessage());
    }
  }

  public function count($table, $count, $entries = NULL){
    $count = 'SELECT COUNT('.$count.')';
    $from = $this->processFROM($table);
    $statement = $count.$from;
    $array_entries = [];
    if($entries) {
      $where = $this->processWHERE($entries);
      $statement .= $where;
      $array_entries = $this->processArrayEntries($entries);
    }
    //echo $statement;
    $data = $this->sendQuery($statement, $array_entries);
    return $data[0][0];
  }

  /**
   * [arrayMap description]
   * @param  [type] $entry [description]
   * @param  [string] $key   [champ de la table, si int le tableau retourné aura des index numériques incrémentés à partir de 0]
   * @param  [type] $value [description]
   * @return [type]        [description]
   */
  public function arrayMap($entry, $key=0, $value) {
    if(!$entry){
      return NULL;
    }
    $map = array();
    foreach($entry as $data){
      if($key){$map[$data[$key]] = $data[$value];}
      else {array_push($map, $data[$value]);}
    }
    return $map;
  }



  public function arrayClean($entries ,$key_alpha, $relevant=0){
    $array_clean = array();
    if($entries == NULL){
      return NULL;
    }
    if (!$key_alpha) {
      foreach($entries as $key=>$value) {
        if(is_int($key)){
          if($relevant) {
            if(in_array($key, $relevant)){array_push($array_clean, $value);}
          } else {array_push($array_clean, $value);}
        }
      }
    } else {
      foreach($entries as $key=>$value) {
        if(!is_int($key)){
          if($relevant) {
            if(in_array($key, $relevant)){$array_clean[$key] = $value;}
          } else {$array_clean[$key] = $value;}
        }
      }
    }
    return $array_clean;
  }
  /**
   * [dataClean Nettoie la réponse d'une query]
   * @param  [array]  $query_response [réponse envoyée par la query]
   * @param  [booléen]  $key_alpha        [true : ne garde que les index alphabétiques, false : ne garde que les index numériques]
   * @param  [array] $relevant       [facultatif, liste des champs à conserver]
   * @return [array]                  [une liste de tableaux soit indexés (!$key_alpha) soit associatif ($key_alpha)]
   */
  public function dataClean($query_response, $key_alpha, $relevant=0){
    if(!$query_response){
      return NULL;
    }
    $data_clean = array();
    foreach($query_response as $array) {
      array_push($data_clean, $this->arrayClean($array, $key_alpha, $relevant));
    }
    return $data_clean;
  }


  //////////*****TRAITEMENTS DES PARAMETRES POUR LA CONSTRUCTION DES REQUETES******//////////

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
   * cas une table : une string ou un arrayt à une valeur ou la clé d'un array à une valeurs
   * cas jointure : un array d'array ou chaque sous array contient une paire table gauche/droite à joindre avec les clés correspondantes
   * @param  [array d'array] $tables [array("tableLeft" => "tables.cléEtrangereLeft", "tablesRight" => "tables.cléEtrangereRight"]
   * @return [string]         ["FROM table"]
   */
  private function processFROM($tables) {
    $process_from = " FROM ";
    //echo $tables;
    if (!is_array($tables)) {
      $process_from .= $tables;
    } else if (count($tables) == 1 && !is_array(current($tables))){
      if (is_int(key($tables))) {
        $process_from .= current($tables);
      } else {
        $process_from .= key($tables);
      }
    } else if (count($tables) >= 1) {
      $process_from .= key(current($tables));
      foreach($tables as $joint) {
        $left = each($joint);
        $right = each($joint);
        $process_from .= " LEFT JOIN ".$right[0]." ON ".$right[1]." = ".$left[1];
      }
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
    $array_entries = $this->processArrayWhere($entries);
    foreach ($array_entries as $entry) {
      $field = array_search($entry, $entries);
      $process_where .= $field." = ? ";
      if($entry != end($array_entries)) {
        $process_where .="AND ";
      }
    }
    if($process_where == " WHERE ") {
      return 0;
    }
    return $process_where;
  }

/**
 * processArrayEntries: extrait les valeurs non nulles du tableau passé en param et en fait un nouveau tableau
 * @param  [array] $entries ["champ" => "entrée"]
 * @return [array]          [[champ] => "entrée différente de "" "]
 */
  private function processArrayWhere($entries) {
    $tabEntries = array();
    foreach ($entries as $champ => $entry) {
      if ($entry !== "") {
        $tabEntries[$champ] = $entry;
      }
    }
    //var_dump($tabEntries);
    return $tabEntries;
  }


/**
 * processArrayEntries: extrait les valeurs non nulles du tableau passé en param et en fait un nouveau tableau
 * @param  [array] $entries ["champ" => "entrée"]
 * @return [array]          [[0...n] => "entrée différente de """]
 */
  private function processArrayEntries($entries) {
    $tabEntries = array();
    foreach ($entries as $entry) {
      if ($entry !== "") {
        array_push($tabEntries, $entry);
      }
    }
    return $tabEntries;
  }

  private function processFields($entries) {
    $fields = array_keys($entries);
    $process_fields = "(";
    foreach ($fields as $field) {
      $process_fields .= $field;
      if($field != end($fields)) {
        $process_fields .=", ";
      }
    }
    $process_fields .=")";
    return $process_fields;
  }

  private function processValues($entries) {
    $fields = array_keys($entries);
    $process_values = " VALUES(";
    foreach ($fields as $field) {
      $process_values .= " :".$field;
      if($field != end($fields)) {
        $process_values .=", ";
      }
    }
    $process_values .=")";
    return $process_values;
  }


  private function processUPDATE($entries) {
    $entries = $this->processArrayWhere($entries);
    $process_set= "";
    foreach ($entries as $field => $entry) {
      if($entry !== ""){
        $process_set .= $field." = ?";
        if($entry != end($entries)) {
          $process_set .=", ";
        }
      }
    }
    return $process_set;
  }



}


 ?>
