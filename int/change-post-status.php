<?php

  header('Content-Type: application/json');

  require_once 'config.php';

  session_start();

	$data = json_decode(file_get_contents('php://input'));

  if(isset($_SESSION['user_id'])) {
    $user_id = filter_var($_SESSION['user_id'], FILTER_SANITIZE_NUMBER_INT);
    $post_id = filter_var($data->post_id, FILTER_SANITIZE_NUMBER_INT);
    $status = filter_var($data->status, FILTER_SANITIZE_SPECIAL_CHARS);

    $sql = $pdo->prepare("SELECT updated_at FROM posts WHERE id = :p_i AND user_id = :u_i");
    $sql->bindValue(':p_i', $post_id);
    $sql->bindValue(':u_i', $user_id);
    $sql->execute();

    $last_update = $sql->fetch(PDO::FETCH_ASSOC)['updated_at'];

    $sql = $pdo->prepare("UPDATE posts SET status = :s, updated_at = :l_u WHERE id = :p_i AND user_id = :u_i");
    $sql->bindValue(':s', $status);
    $sql->bindValue(':l_u', $last_update);
    $sql->bindValue(':p_i', $post_id);
    $sql->bindValue(':u_i', $user_id);
    $sql->execute();

    if($sql->rowCount() > 0) {
      echo json_encode('changed');
    }
  }

?>