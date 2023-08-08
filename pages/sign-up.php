<?php

  session_start();

  require_once $_SERVER['DOCUMENT_ROOT'] . '/int/config.php';

  if(isset($_SESSION['user_id'])) {
    header('Location: ../?logged');
  }

  if(isset($_POST['name'], $_POST['email'], $_POST['password']) && strlen($_POST['name']) >= 3 && strlen($_POST['email']) >= 5 && strlen($_POST['password']) >= 8) {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

    $password = password_hash($password, PASSWORD_DEFAULT);

    $sql = $pdo->prepare("SELECT * FROM users WHERE email = :e");
    $sql->bindValue(':e', $email);
    $sql->execute();

    if($sql->rowCount() == 0) {
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

?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BlogIt | Sign Up</title>
  <link rel="apple-touch-icon" sizes="180x180" href="<?= ROOT ?>/assets/favicon/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?= ROOT ?>/assets/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?= ROOT ?>/assets/favicon/favicon-16x16.png">
	<link rel="manifest" href="/site.webmanifest">
	<link href="https://fonts.googleapis.com/css?family=Playfair+Display:700,900&amp;display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
	<link rel="stylesheet" href="<?= ROOT ?>/assets/css/style.css">
  <style>
    html, body {
      height: 100%;
    }

    .form-signin {
      max-width: 330px;
      padding: 1rem;
    }

    .form-signin .form-floating:focus-within {
      z-index: 2;
    }

    .form-signin input[type="email"] {
      margin-bottom: -1px;
      border-bottom-right-radius: 0;
      border-bottom-left-radius: 0;
    }

    .form-signin input[type="password"] {
      margin-bottom: 10px;
      border-top-left-radius: 0;
      border-top-right-radius: 0;
    }
  </style>
</head>
<body class="container d-flex align-items-center justify-content-center">
	<a class="position-absolute top-0 start-0 m-4 btn btn-sm btn-dark fs-5 d-flex align-items-center" href="<?= ROOT ?>/pages/sign-in.php"><i class="bi bi-arrow-left fs-4 me-2"></i> Go back</a>
  <a class="position-absolute top-0 start-middle mt-4 blog-primary-font fst-italic text-body-emphasis text-decoration-none" href="<?= ROOT ?>">BlogIt</a>

  <main class="form-signin w-100 m-auto">
    <form method="POST" action="<?= $_SERVER['HTTP_SELF'] ?>">
      <p class="h3 mt-4 mb-3 fw-normal">Sign up</p>

      <div class="form-floating">
        <input type="text" class="form-control rounded-top rounded-0 border-bottom-0" id="floatingName" name="name" placeholder="Paul" minlength="3" required>
        <label for="floatingName">Name</label>
      </div>
      <div class="form-floating">
        <input type="email" class="form-control rounded-0" id="floatingInput" name="email" placeholder="name@example.com" minlength="5" required>
        <label for="floatingInput">Email address</label>
      </div>
      <div class="form-floating">
        <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password" minlength="8" required>
        <label for="floatingPassword">Password</label>
      </div>
      <?php if(isset($_GET['fail'])) { echo "<span class='text-warning-emphasis'>This email is unavailable.</span>"; } ?>
      <?php if(isset($_GET['short_input'])) { echo "<span class='text-warning-emphasis'>Input data is too short.</span>"; } ?>
      <button class="btn btn-light fs-5 w-100 my-3 py-2" type="submit">Register</button>
			<span class="text-dark-emphasis">or <a class="text-dark-emphasis" href="<?= ROOT ?>/pages/sign-in.php">sign in</a> to your account.</span>
    </form>
  </main>

	<script src="<?= ROOT ?>/assets/js/clear-url.js"></script>
</body>
</html>