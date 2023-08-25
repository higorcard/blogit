<?php
	session_start();

  require_once $_SERVER['DOCUMENT_ROOT'] . '/int/config.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/int/functions.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/DB.php';

  $DB = new DB($pdo);

  use Genert\BBCode\BBCode;
	$bbCode = new BBCode();

  require_once $_SERVER['DOCUMENT_ROOT'] . '/int/parser-list.php';

	if(isset($_GET['search']) && !empty($_GET['search'])) {
		$page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT);
		$search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_SPECIAL_CHARS);

		list($posts, $current_page, $total_pages, $total_items_pagination) = getPosts($page, $search, 'search');
	}

	require_once $_SERVER['DOCUMENT_ROOT'] . '/pages/partials/header.php'; 

	if($posts) :
		foreach($posts as $post) :
			$total_comments = $DB->table('comments')->where('post_id', '=', $post['id'])->count();

			$comments_quantity = getCommentsQuantity($total_comments);
?>
	<div class="row my-5 blog-article">
		<div class="col-sm-12 col-lg-5 col-md-6 p-0 rounded-3 border border-3 border-light" style="background-image: url(<?= ROOT ?>/assets/img/<?= $post['thumbnail'] ?>);"></div>
		<div class="col-sm-12 col-lg-7 col-md-6 d-flex flex-column ps-4 pe-0">
			<h2 class="fs-2 blog-article-title blog-text-ellipsis fw-bold fst-italic text-dark-emphasis"><?= $post['title'] ?></h2>
			<p class="fs-5 mb-4 text-secondary"><?= date('M d, Y', strtotime($post['created_at'])) . ' · ' . $comments_quantity; ?></p>
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
				<li class="page-item"><a class="page-link fs-4 p-2" href="?search=<?= urlencode($search) ?>&page=<?= $current_page - 1 ?>"><i class="bi bi-chevron-left"></i></a></li>
			<?php endif; ?>
			<?php
				function printItem($item) {
					global $search;
					global $current_page;

					$status_item = ($item == $current_page) ? 'active' : '';

					echo "<li class='page-item " . $status_item . "'><a class='page-link fs-4 py-2 px-3' href='?search=" . urlencode($search) . "&page=" . $item . "'>" . $item . "</a></li>";
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
				<li class="page-item"><a class="page-link fs-4 p-2" href="?search=<?= urlencode($search) ?>&page=<?= $current_page + 1 ?>"><i class="bi bi-chevron-right"></i></a></li>
			<?php endif; ?>
		</ul>
	</nav>
<?php endif; else: ?>
	<div class="d-flex align-items-center justify-content-center flex-column my-5 py-5">
		<i class="bi bi-exclamation-circle display-1 text-center"></i>
		<p class="fs-4 fw-medium text-center mt-2 px-0">No results found. Please, try again</p>
	<div>
<?php
  endif;

  require_once $_SERVER['DOCUMENT_ROOT'] . '/pages/partials/footer.php';
?>