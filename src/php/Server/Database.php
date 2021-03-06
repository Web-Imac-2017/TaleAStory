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

  //////////*****DATABASE******//////////
  //class d'interaction avec la base de donnée
  //encodage et decodage des données
  //traitement des queries principales
  //formatage des informations retournées



  //////////*****CONFIGURATION******//////////

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
      \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
    );
  }

  /**
   * Crée l'objet Database la première fois qu'elle est appelée puis le renvoie seulement les autres fois
   * @return [objet Database] []
   */
  static public function instance(){
    if(is_null(self::$_instance)){
      self::$_instance = new Database("Server/database_config.json");
    }
    return self::$_instance;
  }

  /**
   * getPDO : fait la connexion a la base de données si elle n'a jamais été faite avant.
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
      catch(\PDOException $e){
          throw new RouterException($e->getMessage(), (int)$e->getCode());
          $e->send();
      }
    }
    //echo 'connexion bdd OK';
    return $this->pdo;
  }


  //////////*****REQUETES******//////////

  /**
   * query : envoie une query et récupère les données. Utilise des fonctions tierces pour traiter les tableaux reçus,
   * concaténer les informations dans une string au format query, puis sendQuery pour l'envoyer au serveur
   * @param  [array de strings] $tables  voir fonction processFROM
   * @param  [array de strings] $entries [ "champ" => "entrée utilisateur"/peut etre vide]
   * @param  [array de strings et d'arrays] $addEndStatement [liste d'array et de string, si il y a une seule
   *                                                           addition il faut tout de même la mettre dans un array ]
   * @return [array]          [contient toutes les données retournées par la requête]
   */
  public function query($tables, $entries, $addEndStatement = NULL){
    try{
      $select = $this->processSELECT($entries);
      $from = $this->processFROM($tables);
      $where = $this->processWHERE($entries);
      $where = (!$where)?"":$where;
      $statement=$select.$from;
      $array_entries = array();

      if(empty($addEndStatement)) {
        $statement .= $where;
        $array_entries = $this->processArrayEntries($entries);
        return $this->sendQuery($statement, $array_entries);
      }
      if (is_string($addEndStatement)){
        $statement .= $where." ".$addEndStatement;
        $array_entries = $this->processArrayEntries($entries);
        return $this->sendQuery($statement, $array_entries);
      }
      if (is_array($addEndStatement)){
        $additions = "";
        foreach($addEndStatement as $addition){
          if(is_string($addition)){
            $additions .= " ".$addition;
          }
          else if (current($addition) == "IN"){
            $in = $this->processIN($addition);
            $array_entries = array_merge($array_entries,$addition[2]);
            $additions .= $in;
            $where = " WHERE ";
          }
          else if (current($addition) == "LIKE"){
            $like = $this->processLIKE($addition);
            array_push($array_entries, "%".$addition[2]."%");
            //$array_entries = array_merge($array_entries,array("%".$addition[2]."%"));
            $additions .= $like;
            $where = " WHERE ";
          }
        }
      }
        $statement .= $where.$additions;
      } catch(\PDOException $e){
          throw new RouterException('Erreur lors de la requête',404);
          $e->send();
      }
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
    } catch(\PDOException $e){
        throw new RouterException('Erreur lors de l\'envoi de la requête',404);
        $e->send();
    }
    $data = array();
    while($d = $qry->fetch()) {array_push($data, $d);}
    $qry->closeCursor();
    return $data;
    }

  /**
   * insert: Prépare une query pour insérer quelque chose dans la bdd.
   * @param  [string] $table   [nom de la table]
   * @param  [array] $entries [ champs => valeur à insérer]
   * @return [int]          [id de la ligne insérée]
   */
  public function insert($table, $entries) {
    try{
      $into = "INSERT INTO ".$table;
      $fields = $this->processFields($entries);
      $values = $this->processValues($entries);
      $statement = $into.$fields.$values;
      //echo "\n".$statement."\n";
    } catch(\PDOException $e){
        throw new RouterException('Erreur lors de l\'insertion',404);
        $e->send();
    }
    return $this->sendInsert($statement, $entries);
  }

  private function sendInsert($statement, $entries) {
    try {
    $insert = $this->getPDO()->prepare($statement);
    $insert->execute($entries) or die(print_r($insert->errorInfo()));
    $id = $this->getPDO()->lastInsertId();
    }
    catch(\PDOException $e){
      throw new RouterException('Erreur lors de l\'envoie de l\'insertion dans la BDD',404);
      $e->send();
    }
    return $id;
  }

  /**
   * update: prépare une query pour faire un update dans la base de donnée
   * @param  [array] $table          table, ou tables, voir fonction processFROM
   * @param  [array] $entries        (champs => nouvelle_valeurs)
   * @param  [array] $identification (champs => valeurs), pour identifier la ligne à modifier
   * @return []                 []
   */
  public function update($table, $entries, $identification) {
    try{
    $update = "UPDATE ";
    $from = $this->processFROM($table);
    $from = str_replace("FROM ", "", $from);
    $set = " SET ".$this->processUPDATE($entries);
    $where = $this->processWHERE($identification);
    $array_entries = array_merge($this->processArrayEntries($entries), $this->processArrayEntries($identification));
    $statement = $update.$from.$set.$where;
    } catch(\PDOException $e){
      throw new RouterException('Erreur lors de la mise a jour de la table',404);
      $e->send();
    }
    return $this->sendUpdate($statement, $array_entries);
  }

  private function sendUpdate($statement, $array_entries) {
    try {
      $update = $this->getPDO()->prepare($statement);
      $update->execute($array_entries) or die(print_r($update->errorInfo()));
    }catch(\PDOException $e){
      throw new RouterException('Erreur lors de l\'envoie de la mise a jour de la table',404);
      $e->send();
    }
  }


  /**
   * delete: Prépare une query pour supprimer une ligne de la base de donnée.
   * @param  [string] $table          [table]
   * @param  [array] $identification [(champs => valeurs), pour identifier la ligne à modifier]
   * @return []                 []
   */
  public function delete($table, $identification) {
    $delete = "DELETE ";
    try{
    $from = $this->processFROM($table);
    $where = $this->processWHERE($identification);
    $statement = $delete.$from.$where;
    $array_entries = $this->processArrayEntries($identification);
    //echo $statement;
    if (strstr($statement, "WHERE") == FALSE) {
      //echo " bug DELETE";
      return 0;
    }
    } catch(\PDOException $e){
      throw new RouterException('Erreur lors de la suppression en table',404);
      $e->send();
    }
    return $this->sendDelete($statement, $array_entries);
  }

  private function sendDelete($statement, $array_entries){
    try {
      $delete = $this->getPDO()->prepare($statement);
      $delete->execute($array_entries) or die(print_r($delete->errorInfo()));
    }
    catch(\PDOException $e){
      throw new RouterException('Erreur lors de l\'envoie de la suppression en table',404);
      $e->send();
    }
  }


  /**
   * count: Compte le nombre d'entrée distinctes dans une table de la bdd en fonction d'un champ
   * @param  [string] $table   [nom de la table]
   * @param  [string] $count   [champ]
   * @param  [array] $entries [(champs=>valeurs), pour identifier les lignes concernées]
   * @return [int]          [nombre d'entrées différentes]
   */
  public function count($table, $count, $entries = NULL){
    try{
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
    }catch(\PDOException $e){
      throw new RouterException('Erreur lors du count de la requête',404);
      $e->send();
    }
    return $data[0][0];
  }


    //////////*****ENCODAGE DECODAGE******//////////

  /**
   * encode : hash une chaine pure
   * @param  [string] $purestring [un mot lisible]
   * @return [string]             [une chaine hashée]
   */
  public function encode($purestring) {
     return password_hash($purestring, PASSWORD_BCRYPT);
  }

  /**
   * decode : compare une chaine hashée et un chaine pure
   * @param  [string] $pw     [un mot lisible]
   * @param  [string] $hashed [la chaine hashée à comparer]
   * @return [bool]         [true : les deux chaines corresponden, false non]
   */
  public function decode($pw, $hashed) {
    return password_verify($pw, $hashed);
  }

  //////////*****TRAITEMENT DES DONNÉES******//////////

  /**
   * arrayMap: Formate le resultat d'une query sous la forme d'un tableau associant deux des champs de la table concernée.
   * @param  [arrays] $entry [resultat d'une query]
   * @param  [string] $key   [champ de la table]
   * @param  [string] $value [autre champ de la table]
   * @return [array]        [array($key=>$value)]
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

  /**
   * arrayClean: Nettoie un array pour ne garder que les clés numériques, ou que les clé alphabétiques
   * @param  [array]  $entries   [un array mixed]
   * @param  [bool]  $key_alpha [true : garde les clés alphan, false : garde les clés num]
   * @param  [array] $relevant  [facultatif, liste des clées à garder]
   * @return [array]             [le tableau nettoyé]
   */
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
   * dataClean : Nettoie la réponse d'une query, utilise arrayClean()
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
      foreach ($fields as $key => $field) {
        $process_select .= $field;
        if($key!= key(array_slice($fields, -1, 1, TRUE))) {
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
      foreach ($array_entries as $field => $entry) {
        $process_where .= $field." = ? ";
        if($field != key(array_slice($array_entries, -1, 1, TRUE))) {
          $process_where .="AND ";
        }
      }
      if($process_where == " WHERE ") {
        return 0;
      }
    return $process_where;
  }

/**
 * processArrayEntries: extrait les valeurs différentes "" du tableau passé en param et en fait un nouveau tableau associatif
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
 * processArrayEntries: extrait les valeurs différentes de "" du tableau passé en param et en fait un nouveau tableau à index numérique
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

  /**
   * processFields: extrait du tableau la liste des champs à insérer pour la query INSERT
   * @param  [array] $entries [(champs => valeur)]
   * @return [string]          ["(champ1, champ2, champ3,...)"]
   */
  private function processFields($entries) {
    $fields = array_keys($entries);
    $process_fields = "(";
    foreach ($fields as $key => $field) {
      $process_fields .= $field;
      if($key!= key(array_slice($fields, -1, 1, TRUE))) {
        $process_fields .=", ";
      }
    }
    $process_fields .=")";
    return $process_fields;
  }

  /**
   * processValues: extrait du tableau la liste des noms de variables associées aux valeurs à insérer pour la query INSERT
   * @param  [array] $entries [(champs => valeur)]
   * @return [string]          ["VALUES(:champ1, :champ2, :champ3,...)"]
   */
  private function processValues($entries) {
    $fields = array_keys($entries);
    $process_values = " VALUES(";
    foreach ($fields as $key => $field) {
      $process_values .= " :".$field;
      if($key!= key(array_slice($fields, -1, 1, TRUE))) {
        $process_values .=", ";
      }
    }
    $process_values .=")";
    return $process_values;
  }


  /**
   * processUPDATE: construit la partie SET de la query UPDATE à partir d'un tableau
   * @param  [array] $entries [(champ => valeur)]
   * @return [string]          ["champ1 = ?, champ2 = ?, champ3 = ?"]
   */
  private function processUPDATE($entries) {
    $entries = $this->processArrayWhere($entries);
    $process_set= "";
    foreach ($entries as $field => $entry) {
      if($entry !== ""){
        $process_set .= $field." = ?";
        if($field!= key(array_slice($entries, -1, 1, TRUE))) {
          $process_set .=", ";
        }
      }
    }
    return $process_set;
  }

  /**
   * processIN : construit la partie IN d'une query à partir le l'array passé en 3ème param de query()
   * @param  [array] $entry [array("IN", champ, array(comparaison1, comparaison2))]
   * @return [string]        ["champ IN(?,?)"]
   */
  private function processIN($entry) {
    $process_in = $entry[1]." IN ( ";
    foreach($entry[2] as $key => $value){
      $process_in .= "?";
      if($key!= key(array_slice($entry[2], -1, 1, TRUE))){
        $process_in .= ", ";
      }
    }
    $process_in .= " )";
    return $process_in;
  }

  /**
   * processLIKE: construit la partie LIKE d'une query à partir le l'array passé en 3ème param de query()
   * @param  [array] $entry [array("LIKE", champ, comparaison)]
   * @return [string]        ["champs LIKE comparaison"]
   */
  private function processLIKE($entry) {
    $process_in = $entry[1]." LIKE ?";
    return $process_in;
  }

}


 ?>
