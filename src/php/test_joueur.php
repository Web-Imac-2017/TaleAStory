<?php
require "model_Player.php";
require "Item.php";
require "Achievement.php";


$arc = new Item("arc","image","brief");
$arc->id = 1;

$arg = new Item("arg","image","brief");
$arg->id = 2;

$achiev = array($arc,$arg);

//$admin = Admin::signup("Nemo", "Dori", "poisson", "sea@bulb");
$player = Player::connect("login","pwd");
//$test = Database::instance()->query("Player", array("Login"=>"Dori", "IDPlayer"=>""));
//$test = Database::instance()->arrayMap($test, "Login", "IDPlayer");
//$test = Database::instance();
//$user = Player::connect("login", "pwd");
//$user->addItem($arc);
//$user->removeItem($arc);
//$test = $user->items();
//echo "<pre>".var_export($player, true)."</pre>";
echo '///////TEST';
//$user->changeImage("./lala.jpg");
//$test = Database::instance()->insert("step", array("IDStep"=>"","Body" => "Lou", "IDType"=>4));
//$player->alterStats(array(1=>7, 3=>18));
$player->addItems(array(4=>3, 2=>2, 1=>1));
//echo "<pre>".var_export($test, true)."</pre>";
echo "TEST";

/*
signup OK sauf verifier les champs
connect OK
update OK
 */

?>
