<?php
namespace Server;
class Form {

  static public function updatePOST(){
    if(empty($_POST))
      $_POST = json_decode(file_get_contents('php://input'), true);
  }

  static public function getField($field){
    self::updatePOST();
    // = array_merge($data, $_POST);
    //var_dump($_POST);
    if(isset($_POST[$field]))
      return $_POST[$field];

    return null;
  }

  static public function getFullForm(){
    self::updatePOST();
    return $_POST;
  }

  /*
  @function getFormPost
  @param string form  id du form concerné
  @param string index du champ demandé
  @return string  valeur correspondant au champ demandé
  Vérifie et retourne les données entrées par l'user et envoyées via $_POST
  */
  static public function getFormPost($form,$index) {
    self::updatePOST();
    $this->_getForm($form, $index, $_POST);
  }
  /*
  @function getFormGet
  @param string form  id du form concerné
  @param string index du champ demandé
  @return string  valeur correspondant au champ demandé
  Vérifie et retourne les données entrées par l'user et envoyées via $_GET
  */
  static public function getFormGet($form,$index) {
    $this->_getForm($form, $index, $_GET);
  }

  static private function _getForm($form, $index, $array){
    //on vérifie que le formulaire est authentique et que les inputs de $_GET sont ok
    if (self::verifyFormInputs($form, $array)) {
      //on vérifie que l'index demandé est dans $_GET et que sa valeur est bonne
      if(isset($array["$index"]) && !empty($array[$index]))
        return  htmlentities(trim(strip_tags(stripslashes($array[$index]))), ENT_NOQUOTES, "UTF-8");
      else
        return null;
    } else {
      echo "Hack-Attempt detected. Got ya!.";
      self::writeLog('Formtoken');
      return null;
    }
  }

  /*
  @function verifyFormInputs
  @return void
  Vérifie la liste d'inputs du formulaire
  */
  static public function verifyFormInputs($form, $array){
    // liste des inputs possibles
    $whitelist = array('token','name','email','likeit','comments');

    // liste des inputs présets dans $_POST
    foreach ($array as $key=>$item) {
      // On vérifie si $key (fieldname from $_POST) est présent dans la whitelist
      if (!in_array($key, $whitelist)) {
        self::writeLog('Unknown form fields');
        return false;
      }
    }
    return verifyFormToken($form);
  }

  /*
  @function generateFormToken
  @param  $form id du form
  @return void
  Génère un token pour un formulaire donné et stocke la valeur dans $_SESSION
  */
  static public function generateFormToken($form) {
    // generate a token from an unique value
    $token = md5(uniqid(microtime(), true));
    // Write the generated token to the session variable to check it against the hidden field when the form is sent
    $_SESSION[$form.'_token'] = $token;
    return $token;
  }

  /*
  @function verifyFormToken
  @param  $form id du form
  @return bool
  Vérifie que le token présent dans $_POST correspond bien à celui présent dans $_SESSION (le formulaire est authentique)
  */
  static public function verifyFormToken($form) {
    // check if a session is started and a token is transmitted, if not return an error
    if(!isset($_SESSION[$form.'_token']))
    return false;
    self::updatePOST();
    // check if the form is sent with token in it
    if(!isset($_POST['token']))
    return false;
    // compare the tokens against each other if they are still the same
    if ($_SESSION[$form.'_token'] !== $_POST['token'])
    return false;

    return true;
  }

  /*
  @function writeLog
  @param  $where  où l'erreur est arrivée
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

  /*
  @function uploadFile
  @param  $file  nom de l'input qui upload le fichier
  @return un objet avec un statut (soit "error", soit "ok"), avec soit un message d'erreur soit le nom de l'image dans un champ result
  Gestion uploads fichiers
  Si No file, renvoyer une chaine de caractère vide
  */
  static public function uploadFile($file_input){
    $whitelist = array('image/jpg', 'image/jpeg','image/png','image/gif','image/bmp');
    try {
        if(!isset($_FILES["$file_input"]))
            //throw new RuntimeException('No file.');
            return array('statut' => 'ok', 'result' => '');
        //Extension
        if(empty($_FILES[$file_input]['tmp_name'])
            || !in_array($_FILES[$file_input]['type'], $whitelist))
              throw new RuntimeException('Bad file extension...');
        //Taille > 10 MO
        if ($_FILES[$file_input]['size'] > 1000000)
              throw new RuntimeException('Exceeded filesize limit.');
        //Nom du fichier
        $ext = strtolower(pathinfo($_FILES[$file_input]['name'],PATHINFO_EXTENSION));
        do{
          $filename = sprintf('../assets/images/%s.%s',md5(uniqid(microtime(), true)),$ext);
        }while(file_exists($filename));
        //Upload
        if (!move_uploaded_file($_FILES["$file_input"]['tmp_name'], $filename))
            throw new RuntimeException('Failed to move uploaded file.');
        self::createTinyImg($filename);
      }catch (RuntimeException $e) {
          echo $e->getMessage();
          return '';
      }
      return $filename;
  }

  /*
  @function createTinyImg
  @param  $source_name path du ficihier source
  @return void
  Genere une miniature d'un fichier image
  */
  static public function createTinyImg($source_name){
    // Définition de la largeur et de la hauteur maximale
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
  Donne le chemin de la miniature d'une image donnée
  */
  static public function getTinyName($source_name){
    return preg_replace('/\\.[^.\\s]{3,4}$/', '', $source_name)."_tiny.jpg";
  }

  //POUR LES FORM
  static public function getField($field) {
    if(isset($_POST[$field]))
      return $_POST[$field];
    if(isset($_GET[$field]))
      return $_GET[$field];
    return null;
  }

}
?>
