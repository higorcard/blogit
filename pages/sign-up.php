<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
	<base href="/blogit/">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BlogIt</title>
	<link href="https://fonts.googleapis.com/css?family=Playfair+Display:700,900&amp;display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
	<link rel="stylesheet" href="assets/css/style.css">
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
	<a class="position-absolute top-0 start-0 m-4 btn btn-sm btn-dark fs-5 d-flex align-items-center" href="javascript:history.back()"><i class="bi bi-arrow-left fs-4 me-2"></i> Go home</a>
  <a class="position-absolute top-0 start-middle mt-4 blog-primary-font fst-italic text-body-emphasis text-decoration-none" href="#">BlogIt</a>

  <main class="form-signin w-100 m-auto">
    <form>
      <p class="h3 mt-4 mb-3 fw-normal">Sign up</p>

      <div class="form-floating">
        <input type="text" class="form-control rounded-top rounded-0 border-bottom-0" id="floatingName" placeholder="Paul">
        <label for="floatingName">Name</label>
      </div>
      <div class="form-floating">
        <input type="email" class="form-control rounded-0" id="floatingInput" placeholder="name@example.com">
        <label for="floatingInput">Email address</label>
      </div>
      <div class="form-floating">
        <input type="password" class="form-control" id="floatingPassword" placeholder="Password">
        <label for="floatingPassword">Password</label>
      </div>
      <button class="btn btn-light fs-5 w-100 my-3 py-2" type="submit">Register</button>
			<span class="text-dark-emphasis">or <a class="text-dark-emphasis" href="pages/sign-in.php">sign in</a> to your account.</span>
    </form>
  </main>  
</body>
</html>