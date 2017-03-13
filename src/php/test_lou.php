<?php
use \Server\Database;
require_once("Server/Database.php");

//$player = Player::signup("Louuuu", "Louuuuu", "Lou", "lou@gmailcom");
$in = array("IN", "Login", array("lou", "Dori", "lol"));
$test = Database::instance()->query("Player", array("Login"=>"login", "Mail"=>""), $in);
//$player = Player::connect("login","pwd");
//$test = Database::instance()->query("Player", array("Login"=>"Dori", "IDPlayer"=>""));
//$test = Database::instance()->arrayMap($test, "Login", "IDPlayer");
//$test = Database::instance();
//$user = Player::connect("login", "pwd");
//$user->addItem($arc);
//$user->removeItem($arc);
//$test = $user->items();
//echo "<pre>".var_export($player, true)."</pre>";
//$user->changeImage("./lala.jpg");
//$test = Database::instance()->insert("step", array("IDStep"=>"","Body" => "Lou", "IDType"=>4));
//$player->addItems(array(4=>3, 2=>2, 1=>1));
//$player->addAchievements(array($arg, $arc));
//$test = $player->passStep($arc);
echo "<pre>".var_export($test, true)."</pre>";
echo "TEST";

/*
signup OK sauf verifier les champs
connect OK
update OK
 */

?>
