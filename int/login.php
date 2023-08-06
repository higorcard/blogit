<?php

  require_once 'config.php';

  session_start();

  if(isset($_POST['email'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

    if(strlen($email) >= 5 && strlen($password) >= 8) {
      $sql = $pdo->prepare("SELECT * FROM users WHERE email = :e");
      $sql->bindValue(':e', $email);
      $sql->execute();

      $data = $sql->fetch(PDO::FETCH_ASSOC);

      if(password_verify($password, $data['password'])) {
        $_SESSION['user_id'] = $data['id'];

        // TODO: redirect to last page in history
        header('Location: ../?success');
      } else {
        header('Location: ../pages/sign-in.php/?fail');
      }
    } else {
      header('Location: ../pages/sign-in.php/?short_input');
    }
  }

?>