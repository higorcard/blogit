<?php

	session_start();

	if(isset($_GET['success'])) {
		$alert = (object) [
			'type'=> 'success',
			'message'=> 'Login successfully!',
		];
	} elseif(isset($_GET['registered'])) {
		$alert = (object) [
			'type'=> 'success',
			'message'=> 'Account successfully created!',
		];
	} elseif(isset($_GET['logout'])) {
		$alert = (object) [
			'type'=> 'light',
			'message'=> 'You have been disconnected!',
		];
	}
	
	if(isset($alert)) {
		echo "<div class='position-absolute top-0 start-50 translate-middle-x mt-3 row alert alert-$alert->type' role='alert'>$alert->message</div>";
	}

?>

<?php require_once 'pages/partials/header.php'; ?>

<div class="row p-4 p-md-5 mb-5 rounded-3 bg-body-secondary align-items-center">
	<div class="col-lg-6 col-md-12 px-0">
		<h1 class="blog-primary-font fst-italic text-body-emphasis blog-hero-h1" style="font-size: 3rem;">Find what you <br class="d-none d-lg-block">want here!</h1>
	</div>
	<div class="col-lg-6 col-md-12 px-0 mt-3">				
		<form class="d-flex">
			<input type="text" class="form-control form-control-lg bg-body-tertiary me-2" placeholder="How to become a backend developer">
			<button class="btn btn-not-focus" type="submit"><i class="bi bi-search"></i></button>
		</form>
	</div>
</div>

<div class="row mb-5 blog-article">
	<div class="col-sm-12 col-lg-6 col-md-6 p-0 rounded-3 border border-3 border-light" style="background-image: url(assets/img/productive.png);"></div>
	<div class="col-sm-12 col-lg-6 col-md-6 d-flex flex-column ps-4 pe-0">
		<h2 class="blog-primary-font blog-text-ellipsis display-6 fst-italic text-dark-emphasis" style="overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; text-overflow: ellipsis; -webkit-box-orient: vertical;">How to be more productive?</h2>
		<p class="text-body-secondary">Last updated 3 mins ago · 3 comments</p>
		<p class="fs-4 blog-article-text">It took me 18 months to write <a class="text-dark-emphasis" href="#">The Subtle Art of Not Giving A Fuck</a>. Over that time period, I wrote somewhere in the vicinity of 150,000 words for the book (about 600 pages). Most of that came in the final three months. In fact, I can confidently say I got far more done in the final three months than I did in the first 12 combined.</p>
		<a class="fs-5 mt-auto text-dark-emphasis" href="pages/post.php">Continue reading <i class="bi bi-arrow-right"></i></a>
	</div>
</div>

<?php require_once 'pages/partials/footer.php'; ?>