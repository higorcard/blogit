<?php
  session_start();

  require_once $_SERVER['DOCUMENT_ROOT'] . '/int/config.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/User.php';

  if($user_id) {
    header('Location: ../?page=1&logged');
  }

  if($_POST['email'] && $_POST['password']) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

    if(strlen($email) >= 5 && strlen($password) >= 8) {
      $user = User::get($email);

      if(password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];

        header('Location: ../../?success');
      } else {
        header('Location: ?fail');
      }
    } else {
      header('Location: ?short_input');
    }
  }
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlogIt | Sign In</title>
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
    <a class="position-absolute top-0 start-0 m-4 btn btn-sm btn-dark fs-5 d-flex align-items-center" href="<?= ROOT ?>"><i class="bi bi-arrow-left fs-4 me-2"></i> Go home</a>
    <a class="position-absolute top-0 start-middle mt-4 blog-primary-font fst-italic text-body-emphasis text-decoration-none" href="<?= ROOT ?>">BlogIt</a>
    
    <main class="form-signin w-100 m-auto">
      <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
        <p class="h3 mt-4 mb-3 fw-normal">Sign in</p>

        <div class="form-floating">
          <input type="email" class="form-control" id="floatingInput" name="email" placeholder="name@example.com" minlength="5" required>
          <label for="floatingInput">Email address</label>
        </div>
        <div class="form-floating">
          <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password" minlength="8" required>
          <label for="floatingPassword">Password</label>
        </div>
        <?php if(isset($_GET['fail'])) { echo "<span class='text-danger-emphasis'>E-mail or password is incorrect!</span>"; } ?>
        <?php if(isset($_GET['short_input'])) { echo "<span class='text-warning-emphasis'>Input data is too short.</span>"; } ?>
        <button class="btn btn-light fs-5 w-100 my-3 py-2" type="submit">Login</button>
        <span class="text-dark-emphasis">or <a class="text-dark-emphasis" href="<?= ROOT ?>/pages/sign-up.php">create an account</a> for free!</span>
      </form>
    </main>  

    <script src="<?= ROOT ?>/assets/js/clear-url.js"></script>
  </body>
</html>