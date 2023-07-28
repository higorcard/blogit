<?php

	$page_name = basename($_SERVER['PHP_SELF']);

?>

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
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sceditor@3/minified/themes/default.min.css" />
	<link rel="stylesheet" href="assets/css/style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js" defer></script>
	<script src="https://cdn.jsdelivr.net/npm/sceditor@3/minified/sceditor.min.js" defer></script>
	<script src="https://cdn.jsdelivr.net/npm/sceditor@3/minified/formats/bbcode.min.js" defer></script>
	<script src="https://remy.github.io/jquery.inview/jquery.inview.js" defer></script>
	<script src="assets/js/script.js" defer></script>
</head>
<body class="container pb-5">
	<header class="row border-bottom py-3 mb-4">
		<div class="row flex-nowrap justify-content-end align-items-center px-0">
			<div class="col-8 blog-primary-font ps-0">
				<a class="fst-italic text-body-emphasis text-decoration-none" href="./">BlogIt</a>
			</div>
			<div class="col-4 d-flex justify-content-end align-items-center pe-0">
				<?php if(!isset($_SESSION['user_id']))	: ?>
					<a class="btn btn-sm btn-dark fs-5 d-flex align-items-center ms-3" href="pages/sign-in.php"><i class="bi bi-box-arrow-in-right fs-4"></i><span class="ms-2">Sign up</span></a>
				<?php else : ?>
					<?php if($page_name != 'add-post.php') : ?>
						<a class="btn btn-sm btn-dark fs-5 d-flex align-items-center ms-3" href="pages/add-post.php"><i class="bi bi-plus-circle fs-4"></i><span class="ms-2">Post</span></a>
					<?php endif; if($page_name != 'index.php') : ?>
						<a class="btn btn-sm btn-dark fs-5 d-flex align-items-center ms-3" href="./"><i class="bi bi-house fs-4 mb-1"></i><span class="ms-2">Home</span></a>
					<?php endif; if($page_name != 'admin.php')	: ?>
						<a class="btn btn-sm btn-dark fs-5 d-flex align-items-center ms-3" href="pages/admin.php"><i class="bi bi-gear fs-4"></i><span class="ms-2">Admin</span></a>
					<?php endif; ?>
					<a class="btn btn-sm btn-dark fs-5 d-flex align-items-center ms-3" href="int/logout.php"><i class="bi bi-box-arrow-right fs-4"></i> <span class="ms-2">Log out</span></a>
				<?php endif; ?>
			</div>
		</div>
	</header>