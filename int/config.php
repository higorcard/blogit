<?php

  date_default_timezone_set('America/Sao_Paulo');

  $database = (object) [
    'dbname' => 'blogit',
    'host' => 'localhost',
    'username' => 'root',
    'password' => ''
  ];

  try {
    $pdo = new PDO("mysql:dbname=$database->dbname;host=$database->host", $database->username, $database->password);
  } catch (Exception $error) {
    echo $error->getMessage();
  }

?>