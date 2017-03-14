<?php
namespace Server;

use \Server\RouterException;
use \View\Error;
use \View\Success;

class Form {
  /*
  @function updatePOST
  @return void
  update la variable $_POST avec les données envoyées au serveur
  */
  static public function updatePOST(){
    if(!empty($FILES) && empty($_POST)){
      $_POST = parse_raw_http_request($_POST);
    }
    else if(empty($_POST))
      $_POST = json_decode(file_get_contents('php://input'), true);
  }
  /*
  @function getField
  @param string field nom du champ demande
  @return string  valeur correspondant au champ demande, null si non trouvée
  Verifie et retourne les donnees entrees par l'user et envoyees via $_POST
  */
  static public function getField($field){
    self::updatePOST();
    if(isset($_POST[$field]))
      return $_POST[$field];

    return null;
  }
  /*
  @function getFullForm
  @return $_POST
  retourne toutes les données d'un formulaire
  */
  static public function getFullForm(){
    self::updatePOST();
    return $_POST;
  }

  /*
  @function getFormPost
  @param string form  id du form concerne
  @param string index du champ demande
  @return string  valeur correspondant au champ demande
  Verifie et retourne les donnees entrees par l'user et envoyees via $_POST
  */
  static public function getFormPost($form,$index) {
    self::updatePOST();
    $this->_getForm($form, $index, $_POST);
  }
  /*
  @function getFormGet
  @param string form  id du form concerne
  @param string index du champ demande
  @return string  valeur correspondant au champ demande
  Verifie et retourne les donnees entrees par l'user et envoyees via _GET*/
 static public function getFormGet($form,$index) {
    $this->_getForm($form, $index, $_GET);
  }

  static private function _getForm($form, $index, $array){
    //on verifie que le formulaire est authentique et que les inputs de $_GET sont ok
    if (self::verifyFormInputs($form, $array)) {
      //on verifie que l'index demande est dans $_GET et que sa valeur est bonne
      if(isset($array["$index"]) && !empty($array[$index]))
        return  htmlentities(trim(strip_tags(stripslashes($array[$index]))), ENT_NOQUOTES, "UTF-8");
      else
        return null;
    } else {
      //echo "Hack-Attempt detected. Got ya!.";
      self::writeLog('Formtoken');
      return null;
    }
  }

  /*
  @function verifyFormInputs
  @return void
  Verifie la liste d'inputs du formulaire
  */
  static public function verifyFormInputs($form, $array){
    // liste des inputs possibles
    $whitelist = array('token','name','email','likeit','comments');

    // liste des inputs presets dans $_POST
    foreach ($array as $key=>$item) {
      // On verifie si $key (fieldname from $_POST) est present dans la whitelist
      if (!in_array($key, $whitelist)) {
        self::writeLog('Unknown form fields');
        return false;
      }
    }
    return verifyFormToken($form);
  }

  /*
  @function generateToken
  @return void
  Genère un token et stocke la valeur dans $_SESSION
  */
  static public function generateToken() {
    // generate a token from an unique value
    $token = md5(uniqid(microtime(), true));
    // Write the generated token to the session variable to check it against the hidden field when the form is sent
    $_SESSION['token'] = $token;
    return $token;
  }

  /*
  @function verifyToken
  @return bool
  Verifie que le token present dans $_POST correspond bien à celui present dans $_SESSION (le formulaire est authentique)
  */
  static public function verifyToken() {
    // check if a session is started and a token is transmitted, if not return an error
    if(!isset($_SESSION['token']))
    return false;
    self::updatePOST();
    // check if the form is sent with token in it
    if(!isset($_POST['token']))
    return false;
    // compare the tokens against each other if they are still the same
    if ($_SESSION['token'] !== $_POST['token'])
    return false;

    return true;
  }

  /*
  @function writeLog
  @param  $where  où l'erreur est arrivee
  @return void
  Gestion d'un fichier d'erreurs
  */
  static public function writeLog($where) {
    $ip = $_SERVER["REMOTE_ADDR"]; // Get the IP from superglobal
    $host = gethostbyaddr($ip);    // Try to locate the host of the attack
    $date = date("d M Y");
    // create a logging message with php heredoc syntax
    $logging = " Date of Attack: ".$date." | IP-Adress: ".$ip." | Host of Attacker: ".$host." | Point of Attack: ".$where . "\n";
    file_put_contents('hacklog.log', $logging, FILE_APPEND);
  }

  /**
   * Parse raw HTTP request data
   * Pass in $a_data as an array. This is done by reference to avoid copying
   * the data around too much.
   * Any files found in the request will be added by their field name to the
   * $data['files'] array.
   * @param   array  Empty array to fill with data
   * @return  array  Associative array of request data
   */
  public static function parse_raw_http_request(array &$a_data)
  {
    // read incoming data
    $input = file_get_contents('php://input');
    //var_dump($input);
    // grab multipart boundary from content type header
    preg_match('/boundary=(.*)$/', $_SERVER['CONTENT_TYPE'], $matches);

    // content type is probably regular form-encoded
    if (!count($matches))
    {
      // we expect regular puts to containt a query string containing data
      parse_str(urldecode($input), $a_data);
      return $a_data;
    }

    $boundary = $matches[1];

    // split content by boundary and get rid of last -- element
    $a_blocks = preg_split("/-+$boundary/", $input);
    array_pop($a_blocks);

    // loop data blocks
    foreach ($a_blocks as $id => $block)
    {
      if (empty($block))
        continue;

      // you'll have to var_dump $block to understand this and maybe replace \n or \r with a visibile char

      // parse uploaded files
      if (strpos($block, 'application/octet-stream') !== FALSE)
      {
        // match "name", then everything after "stream" (optional) except for prepending newlines
        preg_match("/name=\"([^\"]*)\".*stream[\n|\r]+([^\n\r].*)?$/s", $block, $matches);
        $a_data['files'][$matches[1]] = $matches[2];
      }
      // parse all other fields
      else
      {
        // match "name" and optional value in between newline sequences
        preg_match('/name=\"([^\"]*)\"[\n|\r]+([^\n\r].*)?\r$/s', $block, $matches);
        $a_data[$matches[1]] = $matches[2];
      }
    }
  }
  /*
  @function uploadFile
  @param  $file  nom de l'input qui upload le fichier
  @return un objet avec un statut (soit "error", soit "ok"), avec soit un message d'erreur soit le nom de l'image dans un champ result
  Gestion uploads fichiers
  Si No file, renvoyer une chaine de caractère vide
  */
  static public function uploadFile($file_input){
    $whitelist = array('image/jpg', 'image/jpeg','image/png','image/gif','image/bmp');
    //on parse les data envoyées
    //var_dump($_FILES);

    if(empty($_FILES) || !isset($_FILES["$file_input"])){
      $e = new Error("Pas de fichier.");
      return $e;
    }
    //Extension
    if(empty($_FILES[$file_input]['tmp_name'])
        || !in_array($_FILES[$file_input]['type'], $whitelist))
      {
        $e = new Error("Mauvaise extension de fichier.");
        return $e;
      }
    //Taille > 10 MO
    if ($_FILES[$file_input]['size'] > 1000000)
    {
      $e = new Error("Taille du fichier > 10MO.");
      return $e;
    }
    //Nom du fichier
    $ext = strtolower(pathinfo($_FILES[$file_input]['name'],PATHINFO_EXTENSION));
    do{
      $filename = sprintf('../assets/images/%s.%s',md5(uniqid(microtime(), true)),$ext);
      $filename = str_replace('\\','',$filename);
    }while(file_exists($filename));
    //Upload
    if (!move_uploaded_file($_FILES["$file_input"]['tmp_name'], $filename))
    {
      $e = new Error("Impossible d'uploader le fichier.");
      return $e;
    }
    //self::createTinyImg($filename);
    $e = new Success(str_replace('\\','',$filename));
    return $e;
  }

  /*
  @function createTinyImg
  @param  $source_name path du ficihier source
  @return void
  Genere une miniature d'un fichier image
  */
  static public function createTinyImg($source_name){
    // Definition de la largeur et de la hauteur maximale
    $width = 100;
    $height = 100;

    $source = imagecreatefromjpeg($source_name);

    // Cacul des nouvelles dimensions
    list($width_orig, $height_orig) = getimagesize($source_name);
    $ratio_orig = $width_orig/$height_orig;

    if ($width/$height > $ratio_orig) {
       $width = $height*$ratio_orig;
    } else
       $height = $width/$ratio_orig;

    // Redimensionnement
    $dest = imagecreatetruecolor($width, $height);
    imagecopyresampled($dest, $source, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

    // On enregistre la miniature
    $dest_name = preg_replace('/\\.[^.\\s]{3,4}$/', '', $source_name);
    imagejpeg($dest, $dest_name."_tiny.jpg");
  }

  /*
  @function getTinyName
  @param  $source_name chemin de l'image source
  @return chemin de la miniature
  Donne le chemin de la miniature d'une image donnee
  */
  static public function getTinyName($source_name){
    return preg_replace('/\\.[^.\\s]{3,4}$/', '', $source_name)."_tiny.jpg";
  }


}
?>
