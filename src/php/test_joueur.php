<?php
require "model_Player.php";
//$user = Player::signup("Nemo", "Dori", "poisson", "sea@bulb");
$user = User::connect("login","password");
$test = $user->items();
$test2 = $test[0]['ID'];
echo "<pre>".var_export($test, true)."</pre>";
echo "TEST";

/*
signup OK sauf verifier les champs
connect OK
update OK
 */

?>
