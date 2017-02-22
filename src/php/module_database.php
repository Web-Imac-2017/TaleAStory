<?php
class Database {
  public $pdo;
  protected $server;
  protected $userName;
  protected $password;
  protected $dbName;
  protected $options;

  public function __construct(){
    //info login depuis un fichier à implémenter
    $this->server = 'localhost';
    $this->userName = 'root';
    $this->password = '';
    $this->dbName = 'test';
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
  param : table de la bdd dans laquelle on cherche
  param : tableau asociant champs et entrées de l'utilisateur
  retour : query mysql
  */
  public function buildQuery( $table, $entries){

  }

/* Intégré à build query ou pas
  public function check_entry($entries) {

  }
*/
}


 ?>
