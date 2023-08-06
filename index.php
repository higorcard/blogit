<?php

	require_once 'int/config.php';

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
		echo "<div class='position-fixed z-3 top-0 start-50 translate-middle-x mt-3 row alert alert-$alert->type' role='alert'>$alert->message</div>";
	}

	$sql = $pdo->prepare("SELECT * FROM posts WHERE status = 'public' ORDER BY posts.created_at DESC");
	$sql->execute();

	$posts = $sql->fetchAll(PDO::FETCH_ASSOC);

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

<?php

	if(isset($posts)) :
		foreach($posts as $post) :
			// TODO: refatorar todo o código (incluindo códigos repetitivos em funções - como o código para checar a última atualização) e exluindo linhas de código desnecessários como o exemplo abaixo (já que não é necessário sanitizar a ID, pois o dado vem do banco de dados).

			// TODO: adicionar feature de subir foto em add-post.php e edit-post.php

			$post_id = filter_var($post['id'], FILTER_SANITIZE_NUMBER_INT);

			$sql = $pdo->prepare("SELECT * FROM comments WHERE post_id = :p_i");
			$sql->bindValue(':p_i', $post_id);
			$sql->execute();

			$comments_quantity = $sql->rowCount();

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
	<div class="row mb-5 blog-article">
		<div class="col-sm-12 col-lg-5 col-md-6 p-0 rounded-3 border border-3 border-light" style="background-image: url(assets/img/<?= $post['thumbnail'] ?>);"></div>
		<div class="col-sm-12 col-lg-7 col-md-6 d-flex flex-column ps-4 pe-0">
			<h2 class="fs-1 blog-article-title blog-text-ellipsis fw-bold fst-italic text-dark-emphasis"><?= $post['title'] ?></h2>
			<p class="fs-5 text-secondary"><?= date('M d, Y', strtotime($post['created_at'])) . ' · '; ?><?= ($comments_quantity > 0) ? $comments_quantity . ' comments' : 'No comments'; ?></p>
			<p class="fs-5 blog-article-text"><?= nl2br($new_text); ?></p>
			<a class="fs-5 mt-auto text-dark-emphasis" href="pages/post.php?post_id=<?= $post['id'] ?>">Continue reading <i class="bi bi-arrow-right"></i></a>
		</div>
	</div>
<?php endforeach; endif; ?>

<?php require_once 'pages/partials/footer.php'; ?>