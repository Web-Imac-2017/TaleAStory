<?php
  $options = array(
      PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
  );

  $pdo = new PDO('mysql:dbname=test;host=localhost', 'root', '', $options);

  $i = $pdo->query('SELECT COUNT(*) FROM test');
  //var_dump($i->fetchColumn(0));

  require '../index.html';
?>
