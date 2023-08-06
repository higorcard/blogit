<?php

	$page_name = basename($_SERVER['PHP_SELF']);

	switch ($page_name) {
		case 'index.php':
			$page_title = ' | Home';
			break;
		case 'admin.php':
			$page_title = ' | Admin';
			break;
		case 'add-post.php':
			$page_title = ' | Add Post';
			break;
		case 'edit-post.php':
			$page_title = ' | Edit Post';
			break;
		case 'post.php':
			break;
		case 'search-posts.php':
			$page_title = ' | Search';
			break;			
		default:
			$page_title = NULL;
			break;
	}

?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BlogIt<?= $page_title ?></title>
	<link rel="apple-touch-icon" sizes="180x180" href="<?= ROOT ?>/assets/favicon/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?= ROOT ?>/assets/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?= ROOT ?>/assets/favicon/favicon-16x16.png">
	<link rel="manifest" href="<?= ROOT ?>/assets/favicon/site.webmanifest">
	<link href="https://fonts.googleapis.com/css?family=Playfair+Display:700,900&amp;display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sceditor@3/minified/themes/default.min.css" />
	<link rel="stylesheet" href="<?= ROOT ?>/assets/css/style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js" defer></script>
	<script src="https://cdn.jsdelivr.net/npm/sceditor@3/minified/sceditor.min.js" defer></script>
	<script src="https://cdn.jsdelivr.net/npm/sceditor@3/minified/formats/bbcode.min.js" defer></script>
	<script src="https://remy.github.io/jquery.inview/jquery.inview.js" defer></script>
	<script src="<?= ROOT ?>/assets/js/script.js" defer></script>
</head>
<body class="container pb-5">
	<header class="row border-bottom py-3 mb-4">
		<div class="row flex-nowrap justify-content-end align-items-center px-0">
			<div class="col-3 blog-primary-font ps-0">
				<a class="fst-italic text-body-emphasis text-decoration-none" href="<?= ROOT ?>">BlogIt</a>
			</div>
			<div class="col-9 d-flex justify-content-end align-items-center pe-0">
				<?php if(!isset($_SESSION['user_id']))	: ?>
					<a class="btn btn-sm btn-dark fs-5 d-flex align-items-center ms-3" href="<?= ROOT ?>/pages/sign-in.php"><i class="bi bi-box-arrow-in-right fs-4"></i><span class="ms-2">Sign in</span></a>
				<?php else : ?>
					<?php if($page_name != 'add-post.php') : ?>
						<a class="btn btn-sm btn-dark fs-5 d-flex align-items-center ms-3" href="<?= ROOT ?>/pages/add-post.php"><i class="bi bi-plus-circle fs-4"></i><span class="ms-2">Post</span></a>
					<?php endif; if($page_name != 'index.php') : ?>
						<a class="btn btn-sm btn-dark fs-5 d-flex align-items-center ms-3" href="<?= ROOT ?>"><i class="bi bi-house fs-4 mb-1"></i><span class="ms-2">Home</span></a>
					<?php endif; if($page_name != 'admin.php')	: ?>
						<a class="btn btn-sm btn-dark fs-5 d-flex align-items-center ms-3" href="<?= ROOT ?>/pages/admin.php"><i class="bi bi-gear fs-4"></i><span class="ms-2">Admin</span></a>
					<?php endif; ?>
					<a class="btn btn-sm btn-dark fs-5 d-flex align-items-center ms-3" href="<?= ROOT ?>/int/logout.php"><i class="bi bi-box-arrow-right fs-4"></i> <span class="ms-2">Log out</span></a>
				<?php endif; ?>
			</div>
		</div>
	</header>