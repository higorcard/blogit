<?php
  date_default_timezone_set('America/Sao_Paulo');

  define('ROOT', 'http://localhost');

  $database = (object) [
    'dbname' => 'blogit',
    'host' => 'mysql',
    'username' => 'root',
    'password' => 'db_password'
  ];

  try {
    $pdo = new PDO("mysql:dbname=$database->dbname;host=$database->host", $database->username, $database->password);
  } catch(Exception $error) {
    echo $error->getMessage();
  }
