<?php

  session_start();

  require_once $_SERVER['DOCUMENT_ROOT'] . '/int/config.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/int/functions.php';

  if(isset($_GET['success'])) {
		showAlert('success', 'Post created');
	} elseif(isset($_GET['edited'])) {
		showAlert('light', 'Post edited');
	} elseif(isset($_GET['comment_created'])) {
		showAlert('success', 'Comment posted');
	} elseif(isset($_GET['comment_deleted'])) {
		showAlert('light', 'Comment deleted');
  }

  if(isset($_GET['post'])) {
    $post_title = urldecode(filter_input(INPUT_GET, 'post', FILTER_SANITIZE_SPECIAL_CHARS));
    
    $sql = $pdo->prepare("SELECT * FROM posts WHERE title = :p_t AND status = 'public'");
    $sql->bindValue(':p_t', $post_title);
    $sql->execute();

    if($sql->rowCount() > 0) {
      $post = $sql->fetch(PDO::FETCH_ASSOC);

      $page_title = ' | ' . $post['title'];

      $sql = $pdo->prepare("SELECT * FROM users WHERE id = :u_i");
      $sql->bindValue(':u_i', $post['user_id']);
      $sql->execute();

      $user = $sql->fetch(PDO::FETCH_ASSOC);

      $sql = $pdo->prepare("SELECT * FROM comments WHERE post_id = :p_i ORDER BY comments.created_at DESC");
      $sql->bindValue(':p_i', $post['id']);
      $sql->execute();

      $total_comments = $sql->rowCount();

      if($total_comments > 1) {
				$comments_quantity = $total_comments . ' comments';
			} elseif($total_comments == 1) {
				$comments_quantity = $total_comments . ' comment';
			} else {
				$comments_quantity = 'No comments';
			}

      if($total_comments > 0) {
        $comments = $sql->fetchAll(PDO::FETCH_ASSOC);
      }
    } else {
      header('Location: ../?page=1');
    }
  } else {
    header('Location: ../?page=1');
  }

  if(!empty($post['updated_at'])) { 
    $now = new DateTime('now');
    $update_at = new DateTime($post['updated_at']);
    $interval = date_diff($now, $update_at);

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
        $last_update = ($value == 1) ? "$value $key" : "$value $key".'s';

        if($key == 'day' && floor($value/7) >= 1) {
          $weeks = floor($value/7);
          $last_update = ($weeks == 1) ? "$weeks week" : "$weeks weeks";
        }
        break;
      }
    }
  }
  
  if(isset($_POST['comment'])) {
    if(!empty($_POST['comment'])) {
      $user_id = filter_var($_SESSION['user_id'], FILTER_SANITIZE_NUMBER_INT);
      $post_id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
      $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_SPECIAL_CHARS);

      $sql = $pdo->prepare("SELECT name FROM users WHERE id = :u_i");
      $sql->bindValue(':u_i', $user_id);
      $sql->execute();

      $user = $sql->fetch(PDO::FETCH_ASSOC);
      
      $sql = $pdo->prepare("INSERT INTO comments (post_id, user_id, author, text) VALUES (:p_i, :u_i, :a, :t)");
      $sql->bindValue(':p_i', $post_id);
      $sql->bindValue(':u_i', $user_id);
      $sql->bindValue(':a', $user['name']);
      $sql->bindValue(':t', $comment);
      $sql->execute();

      if($sql->rowCount() > 0) {
        header('Location: ?post=' . $post['title'] . '&comment_created');
      } else {
		    showAlert('danger', 'Comment post error');
      }
    } else {
		  showAlert('warning', 'Comment cannot be empty');
    }
  }

  if(isset($_POST['delete_comment_id'])) {
    $user_id = filter_var($_SESSION['user_id'], FILTER_SANITIZE_NUMBER_INT);
    $comment_id = filter_input(INPUT_POST, 'delete_comment_id', FILTER_SANITIZE_NUMBER_INT);
        
    $sql = $pdo->prepare("DELETE FROM comments WHERE id = :c_i AND user_id = :u_i");
    $sql->bindValue(':c_i', $comment_id);
    $sql->bindValue(':u_i', $user_id);
    $sql->execute();

    if($sql->rowCount() > 0) {
      header('Location: ?post=' . $post['title'] . '&comment_deleted');
    } else {
		  showAlert('danger', 'Comment delete error');
    }
  }

  require_once 'partials/header.php';
  
?>

<div class="row position-relative">
  <div class="blog-post-thumb rounded-3" style="background-image: url('<?= ROOT ?>/assets/img/<?= $post['thumbnail'] ?>');"></div>
  <h1 class="position-absolute top-50 start-50 translate-middle blog-primary-font display-1 fst-italic text-body-emphasis text-center px-4" style="font-size: calc(1.625rem + 1.5vw);"><?= $post['title']; ?></h1>
</div>

<div class="row mt-3 mb-3">
  <p class="col-sm-12 col-md-6 col-lg-6 p-0 fs-5 blog-post-info"><?php if(isset($last_update)) { echo "Updated $last_update ago · "; } ?><a class="text-secondary-emphasis <?= ($total_comments == 0) ? 'disabled' : ''?>" href="<?= $_SERVER['REQUEST_URI'] ?>#commentsSection"><?= $comments_quantity; ?></a></p>
  <p class="col-sm-12 col-md-6 col-lg-6 p-0 fs-5 blog-post-info text-md-end text-lg-end"><?= date('M d, Y', strtotime($post['created_at'])) ?> by <a class="text-secondary-emphasis" disabled><?= $user['name'] ?></a></p>
</div>

<div class="row mt-3 mb-4 gy-3 fs-4 text-body blog-post-content">
  <?php

    $special_chars = [
      '/\&\#13\;\&\#10\;/' => '<br>',
      '/\[hr\]/' => '<hr>',
      '/\[\/size\]/' => '</font>',
      '/\[size=1\]/' => '<font size="1">',
      '/\[size=2\]/' => '<font size="2">',
      '/\[size=3\]/' => '<font size="3">',
      '/\[size=4\]/' => '<font size="4">',
      '/\[size=5\]/' => '<font size="5">',
      '/\[size=6\]/' => '<font size="6">',
      '/\[size=7\]/' => '<font size="7">',
      '/\[\/url\]/' => '</a>',
      '/\[b\]/' => '<b>',
      '/\[\/b\]/' => '</b>',
      '/\[i\]/' => '<i>',
      '/\[\/i\]/' => '</i>',
      '/\[u\]/' => '<u>',
      '/\[\/u\]/' => '</u>',
      '/\[s\]/' => '<s>',
      '/\[\/s\]/' => '</s>',
      '/\[ul\]/' => '<ul>',
      '/\[\/ul\]/' => '</ul>',
      '/\[ol\]/' => '<ol>',
      '/\[\/ol\]/' => '</ol>',
      '/\[li\]/' => '<li>',
      '/\[\/li\]/' => '</li>',
      '/\[left\]/' => '<p style="text-align: left;">',
      '/\[\/left\]/' => '</p>',
      '/\[right\]/' => '<p style="text-align: right;">',
      '/\[\/right\]/' => '</p>',
      '/\[center\]/' => '<p style="text-align: center;">',
      '/\[\/center\]/' => '</p>',
      '/\[justify\]/' => '<p style="text-align: justify;">',
      '/\[\/justify\]/' => '</p>',
    ];
    
    $new_text = preg_replace(array_keys($special_chars), array_values($special_chars), $post['text']);
    
    preg_match_all('/\[url\=(.*?)\]/', $new_text, $matches, PREG_PATTERN_ORDER);
    
    foreach($matches[1] as $value) {
      $new_text = preg_replace('/\[url\=(.*?)\]/', '<a href="' . $value . '">', $new_text, 1);
    }

    echo nl2br($new_text);

  ?>
</div>

<div class="row mt-5 mb-2">
  <p class="d-flex justify-content-between align-items-center fs-2 fw-bold text-dark-emphasis border-bottom mb-5 pb-3 px-0" id="commentsSection">Comments <span class="fs-5 fw-normal blog-post-info"><?= $comments_quantity; ?></span></p>

  <?php
    
    if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != $post['user_id']) :
      $user_id = filter_var($_SESSION['user_id'], FILTER_SANITIZE_NUMBER_INT);

      $sql = $pdo->prepare("SELECT * FROM users WHERE id = :u_i");
      $sql->bindValue(':u_i', $user_id);
      $sql->execute();

      $user = $sql->fetch(PDO::FETCH_ASSOC);
    
  ?>
    <div class="col-12 d-flex mt-1 mb-4 px-0 blog-form-comment" style="gap: 24px;">
      <div class="d-flex fs-3 fst-italic text-body-emphasis bg-black blog-profile-photo blog-primary-font align-items-center justify-content-center rounded-circle"><?= substr($user['name'], 0,1); ?></div>
      <form class="position-relative w-100" method="POST" action="<?= $_SERVER['PHP_SELF'] . '?post=' . $post['title']; ?>">
        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
        <textarea class="form-control form-control-lg" type="text" placeholder="Type something cool" name="comment" rows="3" value="<?= (isset($_POST['comment'])) ? $_POST['comment'] : ''; ?>" minlength="8" required></textarea>
        <button class="btn btn-link bg-dark link-light link-offset-5-hover link-underline-opacity-0 link-underline-opacity-100-hover fs-5 position-absolute mt-1 me-1 top-0 end-0" type="submit">Post</button>
      </form>
    </div>
  <?php endif; ?>

  <?php

    if(isset($comments)) :
      foreach($comments as $comment) :
        $now = new DateTime('now');
        $created_at = new DateTime($comment['created_at']);
        $interval = date_diff($now, $created_at);

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
            $creation_time = ($value == 1) ? "$value $key" : "$value $key".'s';

            if($key == 'day' && floor($value/7) >= 1) {
              $weeks = floor($value/7);
              $creation_time = ($weeks == 1) ? "$weeks week" : "$weeks weeks";
            }
            break;
          }
        }

  ?>
    <div class="col-12 d-flex my-3 pb-2 border-bottom px-0 blog-comment">
      <div class="d-flex fs-3 fst-italic text-body-emphasis bg-secondary-subtle blog-profile-photo blog-primary-font align-items-center justify-content-center rounded-circle"><?= substr($comment['author'], 0,1); ?></div>
      <div class="row w-100" style="max-width: calc(100% - 88px)">
        <div class="blog-post-info fs-5 d-flex justify-content-between align-items-center mb-2">
          <p class="m-0"><span class="fs-4 fw-bold text-dark-emphasis"><?= $comment['author']; ?></span> · <?= (isset($creation_time)) ? $creation_time . ' ago' : 'Now' ; ?></p>
          <?php if($comment['user_id'] == $_SESSION['user_id']) : ?>
            <form method="POST" action="<?= $_SERVER['PHP_SELF'] . '?post=' . $post['title']; ?>">
              <input type="hidden" name="delete_comment_id" value="<?= $comment['id']; ?>">
              <button class="btn btn-link link-danger link-offset-5-hover link-underline-opacity-0 link-underline-opacity-100-hover fs-5 p-0 border-0" type="submit">delete</button>
            </form>
          <?php endif; ?>
        </div>
        <p class="fs-5"><?= $comment['text']; ?></p>
      </div>
    </div>
  <?php endforeach; else : ?>
    <div class="d-flex align-items-center justify-content-center flex-column mt-3 mb-5">
      <i class="bi bi-exclamation-circle display-1 text-center"></i>
      <p class="fs-4 fw-medium text-center mt-2 px-0">This post have no comments to display</p>
    <div>
  <?php endif; ?>
</div>

<?php require_once 'partials/footer.php'; ?>