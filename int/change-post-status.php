<?php
  header('Content-Type: application/json');
  
  session_start();

  require_once $_SERVER['DOCUMENT_ROOT'] . '/int/config.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/DB.php';

  $DB = new DB($pdo);
  
	$data = json_decode(file_get_contents('php://input'));

  if(isset($_SESSION['user_id'])) {
    $user_id = filter_var($_SESSION['user_id'], FILTER_SANITIZE_NUMBER_INT);
    $post_id = filter_var($data->post_id, FILTER_SANITIZE_NUMBER_INT);
    $status = filter_var($data->status, FILTER_SANITIZE_SPECIAL_CHARS);

    $last_update = $DB->table('posts')->where('id', '=', $post_id)->where('user_id', '=', $user_id)->get()[0]['updated_at'];

    $result = $DB->table('posts')->where('id', '=', $post_id)->where('user_id', '=', $user_id)->update([
      'status' => $status,
      'updated_at' => $last_update,
    ]);

    echo json_encode($result);
  }
