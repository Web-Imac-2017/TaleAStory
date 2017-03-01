<?php
require "model_Player.php";
$test = Database::instance();
echo "<pre>".var_export($test, true)."</pre>";
//$user = User::signup("pseudo", "login", "password", "mail");
$user = User::connect("login","password");
//echo "ID player = ".$player->ID
$user->mail = "adraissmeuille";
echo "<pre>".var_export($user, true)."</pre>";
$user->update();
echo "<pre>".var_export($user, true)."</pre>";
echo "TEST";

/*
signup OK sauf verifier les champs
connect OK
update OK
 */

?>
