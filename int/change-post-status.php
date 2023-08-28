<?php
  header('Content-Type: application/json');
  
  session_start();

  require_once $_SERVER['DOCUMENT_ROOT'] . '/int/config.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Post.php';
  
	$data = json_decode(file_get_contents('php://input'));
  
  $post_id = filter_var($data->post_id, FILTER_SANITIZE_NUMBER_INT);
  $status = filter_var($data->status, FILTER_SANITIZE_SPECIAL_CHARS);

  echo Post::changeStatus($user_id, $post_id, $status);
