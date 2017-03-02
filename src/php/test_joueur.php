<?php
require "model_Player.php";
//$admin = Admin::signup("Nemo", "Dori", "poisson", "sea@bulb");
$admin = Admin::connect("Dorie","poisson"); 
//$test = Database::instance();
//$user = Player::connect("login", "pwd");
//$test = $user->items();
//$user->changeImage("./lala.jpg");
echo "<pre>".var_export($admin, true)."</pre>";
echo "TEST";

/*
signup OK sauf verifier les champs
connect OK
update OK
 */

?>
