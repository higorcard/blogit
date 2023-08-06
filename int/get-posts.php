<?php

  header('Content-Type: application/json');

  session_start();

  require_once $_SERVER['DOCUMENT_ROOT'] . '/int/config.php';

  if(isset($_SESSION['user_id'])) {
    $user_id = filter_var($_SESSION['user_id'], FILTER_SANITIZE_NUMBER_INT);
    
    $sql = $pdo->prepare("SELECT * FROM posts WHERE user_id = :u_i ORDER BY posts.created_at DESC");
    $sql->bindValue(':u_i', $user_id);
    $sql->execute();

    if($sql->rowCount() > 0) {
      echo json_encode($sql->fetchAll(PDO::FETCH_ASSOC));
    }
  }

?>