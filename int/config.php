<?php
  date_default_timezone_set('America/Sao_Paulo');

  define('ROOT', 'http://localhost');

	require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/DB.php';

  $user_id = $_SESSION['user_id'];
