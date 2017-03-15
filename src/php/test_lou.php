<?php
use \Server\Database;
use \Server\Form;
use \Model\Item;
use \Model\Player;
use \Server\Response;
use \View\Success;
use \View\Error;
use \Controller\AchievementController;
require_once("Server/Database.php");
require_once("View/Success.php");
require_once("View/Error.php");
require_once("Controller/AchievementController.php");

//$player = Player::signup("Louuuu", "Louuuuu", "Lou", "lou@gmailcom");


$in = array("IN", "Login", array("lou", "Dori", "kass"));
$like = array("LIKE", "Login", "kass");

//$test = Database::instance()->query("Player", array("IDPlayer"=>"", "Mail"=>"", "Login"=>"", "IDCurrentStep"=>""), GROUP);
//echo "<pre>".var_export($test, true)."</pre>";
/*
$test = Database::instance()->insert("Player", array("Login"=>"KASS5", "mail"=>"gmalalaletetet"));
echo "<pre>".var_export($test, true)."</pre>";
$test = Database::instance()->update("Player", array("mail"=>"unvraimail@lol.com"), array("Login"=>"KASS5"));
echo "<pre>".var_export($test, true)."</pre>";
$test = Database::instance()->delete("PLayer", array("Login"=>"KASS4"));
echo "<pre>".var_export($test, true)."</pre>";
//$test = Database::instance()->query("Player", array("IDPlayer"=>1, "Mail"=>"", "Login"=>"", "IDCurrentStep"=>2), $in);

//$test = Database::instance()->query("Player", array("IDPlayer"=>2, "Mail"=>"", "Login"=>"", "IDCurrentStep"=>2), $like);
//$test = AchievementController::getAchievementList(1,4);
//$player = Player::connect("login","pwd");
//$test = Database::instance()->arrayMap($test, "Login", "IDPlayer");
//$test = Database::instance();
//$user = Player::connect("login", "pwd");
//$user->addItem($arc);
//$user->removeItem($arc);
//$test = $user->items();
//echo "<pre>".var_export($player, true)."</pre>";
//$user->changeImage("./lala.jpg");
*/
try{
//$test = Database::instance()->query("Player", array("Login"=>"Dori", "IDPlayer"=>"3"));
$test = Database::instance()->insert("step", array("IDStep"=>"1","Body" => "Lou","Question"=>"", "IDType"=>4));
echo "<pre>".var_export($test, true)."</pre>";
//$test = Database::instance()->insert("step", array("IDStep"=>"1","Body" => "Lou","Question"=>"", "IDType"=>4));
//echo "<pre>".var_export($test, true)."</pre>";
//$player->addItems(array(4=>3, 2=>2, 1=>1));
//$player->addAchievements(array($arg, $arc));
//$test = $player->passStep($arc);
//echo "<pre>".var_export($test, true)."</pre>";
echo "TEST";
} catch (RouterException $error) {
  $error->send();
}

/*
signup OK sauf verifier les champs
connect OK
update OK
 */

?>
