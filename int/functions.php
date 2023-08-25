<?php
  function showAlert($type, $message) {
    echo "<div class='position-fixed z-3 top-0 start-50 translate-middle-x mt-3 row alert alert-$type' role='alert'>$message</div>";
  }

  function getPosts($page, $search = NULL, $type = NULL) {
    global $DB;
    
    $results_limit = 10;
		$min_items_pagination = 5;

    if($type == 'search') {
      $current_page = $page ?? 1;
      
      $total_posts = $DB->raw("SELECT COUNT(id) count FROM posts WHERE status = 'public' AND (title LIKE :0 OR text LIKE :0)", ["%$search%"])->fetch(PDO::FETCH_ASSOC)['count'];
    } else {
      $current_page = $page;

      $total_posts = $DB->table('posts')->where('status', '=', 'public')->count();
    }

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
      $posts = $DB->raw("SELECT * FROM posts WHERE status = 'public' AND (title LIKE :0 OR text LIKE :0) ORDER BY posts.created_at DESC LIMIT $index, $results_limit", ["%$search%"])->fetchAll(PDO::FETCH_ASSOC);

    } else {
      $posts = $DB->table('posts')->where('status', '=', 'public')->orderBy('posts.created_at DESC')->limit("$index, $results_limit")->get();
    }
		
		if($posts) {
			return [$posts, $current_page, $total_pages, $total_items_pagination];
		}
  }

  function getCommentsQuantity($total_comments) {
    if($total_comments > 1) {
      return $total_comments . ' comments';
    } elseif($total_comments == 1) {
      return $total_comments . ' comment';
    }

    return 'No comments';
  }

  function setThumbnail($thumbnail) {
    $allowed_extensions = [
      'png',
      'jpg',
      'jpeg',
      'webp',
    ];

    if(!empty($thumbnail)) {
      $extension = pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
      $folder = $_SERVER['DOCUMENT_ROOT'] . '/assets/img/';
      $tmp_name = $_FILES['thumbnail']['tmp_name'];
      $new_name = uniqid().".$extension";

      if(in_array($extension, $allowed_extensions)) {
        if(move_uploaded_file($tmp_name, $folder.$new_name)) {
          return $new_name;
        } else {
          showAlert('danger', 'File upload error');
        }
      } else {
          showAlert('danger', 'File extension not allowed (only: png, jpg, webp)');
      }
    }

    return 'default.jpg';
  }

  function getElapsedTime($datetime) {
    $now = new DateTime('now');
    $last_datetime = new DateTime($datetime);
    $interval = date_diff($now, $last_datetime);
    $new_datetime = '';

    $date_interval = (object) [
      'year' => $interval->format('%y'),
      'month' => $interval->format('%m'),
      'day' => $interval->format('%a'),
      'hour' => $interval->format('%h'),
      'minute' => $interval->format('%i'),
      'second' => $interval->format('%s'),
    ];

    foreach($date_interval as $key => $value) {
      if($value > 0) {
        $new_datetime = ($value == 1) ? "$value $key" : "$value $key".'s';

        if($key == 'day' && floor($value/7) >= 1) {
          $weeks = floor($value/7);
          $new_datetime = ($weeks == 1) ? "$weeks week" : "$weeks weeks";
        }
        break;
      }
    }

    return $new_datetime;
  }
