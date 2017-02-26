<?php
/*  $options = array(
      PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
  );

  $pdo = new PDO('mysql:dbname=test;host=localhost', 'root', '', $options);

  $i = $pdo->query('SELECT COUNT(*) FROM test');
  //var_dump($i->fetchColumn(0));

  require '../index.html';*/

  require 'Form.php';
  require 'Session.php';
  //$_SERVER['HTTP_REFERER'] = 'toto';

 //echo(Form::getFormPost("test_form","name"));
 //echo(Form::getFormPost("test_form","comments"));
 //$filename = Form::uploadFile("test_file");
//  Form::createTinyImg("../assets/images/test.jpg");
  //echo(Form::getTinyName($filename));
  Session::setSession();
  Session::connectUser(1);
  echo("Session : ");
  var_dump($_SESSION);
  echo("Cookie : ");
  var_dump($_COOKIE);
  //echo(Session::getCurrentUser());
  Session::setSessionAttribute("toto","valeur de toto");
  echo("Session : ");
  var_dump($_SESSION);
  echo("Cookie : ");
  var_dump($_COOKIE);
  $toto1 = Session::getSessionAttribute("toto");
  var_dump($toto1);
  Session::closeSession();
  //echo("Session : ");
  //var_dump($_SESSION);
  session_unset();
  $toto2 = Session::getSessionAttribute("toto");
  var_dump($toto2);
  Session::setSessionAttribute("titi","valeur de titi",time()+564889);


?>
