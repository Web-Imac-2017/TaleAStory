<?php
require "model_Player.php";
require "Item.php";

$arc = new Item("arc","image","brief");
$arc->id = 3;
//$admin = Admin::signup("Nemo", "Dori", "poisson", "sea@bulb");
$player = Player::connect("login","pwd");
//$test = Database::instance()->query("Player", array("Login"=>"Dori", "IDPlayer"=>""));
//$test = Database::instance()->arrayMap($test, "Login", "IDPlayer");
//$test = Database::instance();
//$user = Player::connect("login", "pwd");
//$user->addItem($arc);
//$user->removeItem($arc);
//$test = $user->items();
echo "<pre>".var_export($player, true)."</pre>";
echo '///////TEST';
//$user->changeImage("./lala.jpg");
//$test = Database::instance()->insert("step", array("Body" => "Lolo", "IDType"=>4));
$player->alterStats(array(1=>7, 3=>18));
//echo "<pre>".var_export($test, true)."</pre>";
echo "TEST";

/*
signup OK sauf verifier les champs
connect OK
update OK
 */

?>
