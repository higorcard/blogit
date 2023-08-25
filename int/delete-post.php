<?php
  header('Content-Type: application/json');

  session_start();

  require_once $_SERVER['DOCUMENT_ROOT'] . '/int/config.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/DB.php';

  $DB = new DB($pdo);

	$data = json_decode(file_get_contents('php://input'));

  if(isset($_SESSION['user_id'], $data->post_id)) {
    $user_id = filter_var($_SESSION['user_id'], FILTER_SANITIZE_NUMBER_INT);
	  $post_id = filter_var($data->post_id, FILTER_SANITIZE_NUMBER_INT);
    
    $thumbnail = $DB->table('posts')->getById($post_id)[0]['thumbnail'];
    $result = $DB->table('posts')->where('id', '=', $post_id)->where('user_id', '=', $user_id)->delete();

    if($result && $thumbnail != 'default.jpg') {
      $folder = $_SERVER['DOCUMENT_ROOT'] . '/assets/img/';
      unlink($folder.$thumbnail);
    }

    echo json_encode($result);
  }
