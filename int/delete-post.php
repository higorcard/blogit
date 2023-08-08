<?php

  header('Content-Type: application/json');

  session_start();

  require_once $_SERVER['DOCUMENT_ROOT'] . '/int/config.php';

	$data = json_decode(file_get_contents('php://input'));

  if(isset($_SESSION['user_id'], $data->post_id)) {
    $user_id = filter_var($_SESSION['user_id'], FILTER_SANITIZE_NUMBER_INT);
	  $post_id = filter_var($data->post_id, FILTER_SANITIZE_NUMBER_INT);
    
    $sql = $pdo->prepare("DELETE FROM posts WHERE id = :p_i AND user_id = :u_i");
    $sql->bindValue(':p_i', $post_id);
    $sql->bindValue(':u_i', $user_id);
    $sql->execute();

    if($sql->rowCount() > 0) {
      echo json_encode('deleted');
    }
  }

?>