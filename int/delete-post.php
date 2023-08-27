<?php
  header('Content-Type: application/json');

  session_start();

  require_once $_SERVER['DOCUMENT_ROOT'] . '/int/config.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Post.php';

	$data = json_decode(file_get_contents('php://input'));

  if($data->post_id) {
	  $post_id = filter_var($data->post_id, FILTER_SANITIZE_NUMBER_INT);

    $thumbnail = Post::getById($user_id, $postId)['thumbnail'];
    $result = Post::delete($user_id, $post_id);

    if($result && $thumbnail != 'default.jpg') {
      $folder = $_SERVER['DOCUMENT_ROOT'] . '/assets/img/';
      unlink($folder.$thumbnail);
    }

    echo $result;
  }
