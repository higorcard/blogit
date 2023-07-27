<?php

  require_once 'config.php';

  session_start();

  if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

    if(strlen($name) >= 3 && strlen($email) >= 5 && strlen($password) >= 8) {
      $password = password_hash($password, PASSWORD_DEFAULT);

      $sql = $pdo->prepare("SELECT * FROM users WHERE email = :e");
      $sql->bindValue(':e', $email);
      $sql->execute();

      if($sql->rowCount() === 0) {
        $sql = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:n, :e, :p)");
        $sql->bindValue(':n', $name);
        $sql->bindValue(':e', $email);
        $sql->bindValue(':p', $password);
        $sql->execute();

        $_SESSION['user_id'] = $pdo->lastInsertId();
        header('Location: ../?registered');
      } else {
        header('Location: ../pages/sign-up.php/?fail');
      }
    } else {
      header('Location: ../pages/sign-up.php/?short_input');
    }
  }

?>