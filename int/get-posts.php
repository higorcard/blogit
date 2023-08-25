<?php
  header('Content-Type: application/json');

  session_start();

  require_once $_SERVER['DOCUMENT_ROOT'] . '/int/config.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/DB.php';

  $DB = new DB($pdo);

  if(isset($_SESSION['user_id'])) {
    $user_id = filter_var($_SESSION['user_id'], FILTER_SANITIZE_NUMBER_INT);
    
    $result = $DB->table('posts')->where('user_id', '=', $user_id)->orderBy('posts.created_at DESC')->get();

    echo json_encode($result);
  }
