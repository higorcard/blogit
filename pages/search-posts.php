<?php

	session_start();

  require_once $_SERVER['DOCUMENT_ROOT'] . '/int/config.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/int/functions.php';

  if(isset($_GET['logged'])) {
		showAlert('warning', 'Already logged in');
	} elseif(isset($_GET['logout'])) {
		showAlert('light', 'Logged out');
	}

	if(isset($_GET['search']) && !empty($_GET['search'])) {
		$search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_SPECIAL_CHARS);

		$data = getPosts($_GET['page'], $search, 'search');
		list($posts, $current_page, $total_pages, $total_items_pagination) = $data;
	}

	require_once $_SERVER['DOCUMENT_ROOT'] . '/pages/partials/header.php'; 

	if(isset($posts)) :
		foreach($posts as $post) :
			$post_id = filter_var($post['id'], FILTER_SANITIZE_NUMBER_INT);

			$sql = $pdo->prepare("SELECT * FROM comments WHERE post_id = :p_i");
			$sql->bindValue(':p_i', $post_id);
			$sql->execute();

			$total_comments = $sql->rowCount();

			if($total_comments > 1) {
				$comments_quantity = $total_comments . ' comments';
			} elseif($total_comments == 1) {
				$comments_quantity = $total_comments . ' comment';
			} else {
				$comments_quantity = 'No comments';
			}

			$special_chars = [
				'/\&\#13\;\&\#10\;/',
				'/\[hr\]/',
				'/\[\/size\]/',
				'/\[size=1\]/',
				'/\[size=2\]/',
				'/\[size=3\]/',
				'/\[size=4\]/',
				'/\[size=5\]/',
				'/\[size=6\]/',
				'/\[size=7\]/',
				'/\[\/url\]/',
				'/\[b\]/',
				'/\[\/b\]/',
				'/\[i\]/',
				'/\[\/i\]/',
				'/\[u\]/',
				'/\[\/u\]/',
				'/\[s\]/',
				'/\[\/s\]/',
				'/\[ul\]/',
				'/\[\/ul\]/',
				'/\[ol\]/',
				'/\[\/ol\]/',
				'/\[li\]/',
				'/\[\/li\]/',
				'/\[left\]/',
				'/\[\/left\]/',
				'/\[right\]/',
				'/\[\/right\]/',
				'/\[center\]/',
				'/\[\/center\]/',
				'/\[justify\]/',
				'/\[\/justify\]/',
			];
			
			$new_text = preg_replace($special_chars, ' ', $post['text']);
			
			preg_match_all('/\[url\=(.*?)\]/', $new_text, $matches, PREG_PATTERN_ORDER);
			
			foreach($matches[1] as $value) {
				$new_text = preg_replace('/\[url\=(.*?)\]/', ' ', $new_text, 1);
			}

?>
	<div class="row my-5 blog-article">
		<div class="col-sm-12 col-lg-5 col-md-6 p-0 rounded-3 border border-3 border-light" style="background-image: url(<?= ROOT ?>/assets/img/<?= $post['thumbnail'] ?>);"></div>
		<div class="col-sm-12 col-lg-7 col-md-6 d-flex flex-column ps-4 pe-0">
			<h2 class="fs-2 blog-article-title blog-text-ellipsis fw-bold fst-italic text-dark-emphasis"><?= $post['title'] ?></h2>
			<p class="fs-5 mb-4 text-secondary"><?= date('M d, Y', strtotime($post['created_at'])) . ' · ' . $comments_quantity; ?></p>
			<p class="fs-5 blog-article-text"><?= nl2br($new_text); ?></p>
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