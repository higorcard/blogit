<?php
  function showAlert($type, $message) {
    echo "<div class='position-fixed z-3 top-0 start-50 translate-middle-x mt-3 row alert alert-$type' role='alert'>$message</div>";
  }

  function getPosts($page, $search = NULL, $type = NULL) {
    global $pdo;
    
    $results_limit = 10;
		$min_items_pagination = 5;

    if($type == 'search') {
      $current_page = $page ?? 1;

      $sql = $pdo->prepare("SELECT COUNT(id) count FROM posts WHERE status = 'public' AND (title LIKE :s OR text LIKE :s)");
      $sql->bindValue(':s', '%' . $search . '%');
      $sql->execute();
    } else {
      $current_page = $page;

      $sql = $pdo->query("SELECT COUNT(id) count FROM posts WHERE status = 'public'");
    }

		$total_posts = $sql->fetch(PDO::FETCH_ASSOC)['count'];
		$total_pages = ceil($total_posts / $results_limit);
		$total_items_pagination = ($total_pages < $min_items_pagination) ? $total_pages : $min_items_pagination;

		if($total_pages > 0 && (!isset($current_page) || $current_page > $total_pages || $current_page <= 0)) {
      if($type == 'search') {
        header('Location: ?search=' . urlencode($search));
      } else {
    		header('Location: ?page=1');
      }
		}

		$index = ($current_page * $results_limit) - $results_limit;

    if($type == 'search') {
      $sql = $pdo->prepare("SELECT * FROM posts WHERE status = 'public' AND (title LIKE :s OR text LIKE :s) ORDER BY posts.created_at DESC LIMIT :i, :r_l");
      $sql->bindValue(':s', '%' . $search . '%');
    } else {
  	  $sql = $pdo->prepare("SELECT * FROM posts WHERE   status = 'public' ORDER BY posts.created_at DESC LIMIT :i, :r_l");
    }

		$sql->bindValue(':i', $index, PDO::PARAM_INT);
		$sql->bindValue(':r_l', $results_limit, PDO::PARAM_INT);
		$sql->execute();
		
		if($sql->rowCount() > 0) {
			return [$sql->fetchAll(PDO::FETCH_ASSOC), $current_page, $total_pages, $total_items_pagination];
		}
  }