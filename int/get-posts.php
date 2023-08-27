<?php
  header('Content-Type: application/json');

  session_start();

  require_once $_SERVER['DOCUMENT_ROOT'] . '/int/config.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Post.php';

  $user_posts = [];
  $data = Post::getAll();

  foreach($data as $post) {
    if($post['user_id'] == $user_id) {
      array_push($user_posts, $post);
    }
  }

  echo json_encode($user_posts);
