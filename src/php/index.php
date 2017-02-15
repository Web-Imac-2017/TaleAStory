<?php
/*  $options = array(
      PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
  );

  $pdo = new PDO('mysql:dbname=test;host=localhost', 'root', '', $options);

  $i = $pdo->query('SELECT COUNT(*) FROM test');
  //var_dump($i->fetchColumn(0));

  require '../index.html';*/

  require 'Form.php';
  //$_SERVER['HTTP_REFERER'] = 'toto';

 echo(Form::getForm("test_form","name"));
 echo(Form::getForm("test_form","comments"));
 Form::uploadFile("test_file");
//  Form::createTinyImg("../assets/images/test.jpg");

?>
