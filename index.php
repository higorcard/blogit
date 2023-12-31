<?php
	session_start();

	require_once $_SERVER['DOCUMENT_ROOT'] . '/int/config.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/int/functions.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Comment.php';

  use Genert\BBCode\BBCode;
	$bbCode = new BBCode();

  require_once $_SERVER['DOCUMENT_ROOT'] . '/int/parser-list.php';

	if(isset($_GET['success'])) {
		showAlert('success', 'Logged in');
	} elseif(isset($_GET['registered'])) {
		showAlert('success', 'Account created');
	} elseif(isset($_GET['logged'])) {
		showAlert('warning', 'Already logged in');
	} elseif(isset($_GET['logout'])) {
		showAlert('light', 'Logged out');
	}

	$page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT);
	
	list($posts, $current_page, $total_pages, $total_items_pagination) = getPosts($page ?? 1);

	require_once $_SERVER['DOCUMENT_ROOT'] . '/pages/partials/header.php';
?>

<div class="row p-4 p-md-5 mb-5 rounded-3 bg-body-secondary align-items-center">
	<div class="col-lg-6 col-md-12 px-0">
		<h1 class="blog-primary-font fst-italic text-body-emphasis blog-hero-h1" style="font-size: 3rem;">Find what you <br class="d-none d-lg-block">want here!</h1>
	</div>
	<div class="col-lg-6 col-md-12 px-0 mt-3">
		<form class="d-flex" method="GET" action="<?= ROOT ?>/pages/search-posts.php">
			<input type="text" class="form-control form-control-lg bg-body-tertiary me-2" name="search" placeholder="Tip: search for keywords" required>
			<button class="btn btn-not-focus" type="submit"><i class="bi bi-search"></i></button>
		</form>
	</div>
</div>

<?php
	if($posts) :
		foreach($posts as $post) :
			$total_comments = count(Comment::getAll($post['id']));

			$comments_quantity = getCommentsQuantity($total_comments);
?>
	<div class="row mb-5 blog-article">
		<div class="col-sm-12 col-lg-5 col-md-6 p-0 rounded-3 border border-3 border-light" style="background-image: url(assets/img/<?= $post['thumbnail'] ?>);"></div>
		<div class="col-sm-12 col-lg-7 col-md-6 d-flex flex-column ps-4 pe-0">
			<h2 class="fs-2 blog-article-title blog-text-ellipsis fw-bold fst-italic text-dark-emphasis"><?= $post['title'] ?></h2>
			<p class="fs-5 mb-4 text-secondary"><?= date('M d, Y', strtotime($post['created_at'])) . ' · ' . $comments_quantity ?></p>
			<p class="fs-5 blog-article-text"><?= nl2br($bbCode->stripBBCodeTags($post['text'])); ?></p>
			<a class="fs-5 mt-auto text-dark-emphasis" href="<?= ROOT ?>/pages/post.php?post=<?= urlencode($post['title']) ?>">Continue reading <i class="bi bi-arrow-right"></i></a>
		</div>
	</div>
<?php
		endforeach;

		if($total_pages > 1):
?>
	<nav class="mb-5">
		<ul class="pagination justify-content-center">
			<?php if($current_page != 1): ?>
				<li class="page-item"><a class="page-link fs-4 p-2" href="?page=<?= $current_page - 1 ?>"><i class="bi bi-chevron-left"></i></a></li>
			<?php endif; ?>
			<?php
				function printItem($item) {
					global $current_page;

					$status_item = ($item == $current_page) ? 'active' : '';

					echo "<li class='page-item " . $status_item . "'><a class='page-link fs-4 py-2 px-3' href='?page=" . $item . "'>" . $item . "</a></li>";
				}

				if($total_items_pagination < 5) {
					for($i = 1; $i <= $total_pages; $i++) {
						printItem($i);
					}
				} elseif($current_page < 3) {
					for($i = 1; $i <= $total_items_pagination; $i++) {
						printItem($i);
					}
				} elseif($current_page > ($total_pages - 2)) {
					for($i = ($total_pages - 4); $i <= $total_pages; $i++) {
						printItem($i);
					}
				} else {
					for($i = ($current_page - 2); $i <= ($current_page + 2); $i++) {
						printItem($i);
					}
				}	
			?>	
			<?php if($current_page != $total_pages): ?>
				<li class="page-item"><a class="page-link fs-4 p-2" href="?page=<?= $current_page + 1 ?>"><i class="bi bi-chevron-right"></i></a></li>
			<?php endif; ?>
		</ul>
	</nav>
<?php endif; else: ?>
	<div class="d-flex align-items-center justify-content-center flex-column my-5 py-5">
		<i class="bi bi-exclamation-circle display-1 text-center"></i>
		<p class="fs-4 fw-medium text-center mt-2 px-0">There are no posts to display</p>
	<div>
<?php
	endif;
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/pages/partials/footer.php';
?>