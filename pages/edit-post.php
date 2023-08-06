<?php

  require_once $_SERVER['DOCUMENT_ROOT'] . '/int/config.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/int/check-login.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/int/functions.php';

	if(isset($_GET['no_changes'])) {
		showAlert('warning', 'No changes made');
	}

  $allowed_extensios = [
    'png',
    'jpg',
    'jpeg',
    'webp',
  ];

  $special_chars = [
    '[hr]',
    '[/size]',
    '[size=1]',
    '[size=2]',
    '[size=3]',
    '[size=4]',
    '[size=5]',
    '[size=6]',
    '[size=7]',
    '[b]', '[/b]',
    '[i]', '[/i]',
    '[u]', '[/u]',
    '[s]', '[/s]',
    '[ul]', '[/ul]',
    '[ol]', '[/ol]',
    '[li]', '[/li]',
    '[left]', '[/left]',
    '[right]', '[/right]',
    '[center]', '[/center]',
    '[justify]', '[/justify]'
  ];
  
  if(isset($_GET['post_id'])) {
    $post_id = filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT);
    
    $sql = $pdo->prepare("SELECT * FROM posts WHERE id = :p_i");
    $sql->bindValue(':p_i', $post_id);
    $sql->execute();

    if($sql->rowCount() > 0) {
      $post = $sql->fetch(PDO::FETCH_ASSOC);
    } else {
      header('Location: admin.php');
    }
  } else {
    header('Location: admin.php');
  }

  if(isset($_POST['title'], $_POST['content'])) {
    $content_sanitized = preg_replace(array('/\s{2,}/', '/\[url\=(.*?)\]/', '/\[\/url\]/', '/[\t\n]/'), '', str_replace($special_chars, '', $_POST['content']));
    
    if(strlen($_POST['title']) >= 5) {
      if(strlen($content_sanitized) >= 500) {
        $user_id = filter_var($_SESSION['user_id'], FILTER_SANITIZE_NUMBER_INT);
        $post_id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);

        $sql = $pdo->prepare("SELECT * FROM posts WHERE NOT id = :p_i AND title = :t");
        $sql->bindValue(':p_i', $post_id, PDO::PARAM_INT);
        $sql->bindValue(':t', $title);
        $sql->execute();

        if($sql->rowCount() == 0) {
          if($_FILES['thumbnail']['name'] != '') {
            $extension = pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
            $folder = $_SERVER['DOCUMENT_ROOT'] . '/assets/img/';
            $tmp_name = $_FILES['thumbnail']['tmp_name'];
            $new_name = uniqid().".$extension";

            if(in_array($extension, $allowed_extensios)) {
              if(move_uploaded_file($tmp_name, $folder.$new_name)) {
                $thumbnail = filter_var($new_name, FILTER_SANITIZE_SPECIAL_CHARS);
              } else {
                showAlert('danger', 'File upload error');
              }
            } else {
                showAlert('danger', 'File extension not allowed (only: png, jpg, webp)');
            }
          } else {
            $thumbnail = $post['thumbnail'];
          }
          
          if(!empty($thumbnail)) {
            $sql = $pdo->prepare("UPDATE posts SET title = :t, text = :c, thumbnail = :tn WHERE id = :p_i AND user_id = :u_i");
            $sql->bindValue(':t', $title);
            $sql->bindValue(':c', $content);
            $sql->bindValue(':tn', $thumbnail);
            $sql->bindValue(':p_i', $post_id);
            $sql->bindValue(':u_i', $user_id);
            $sql->execute();

            if($sql->rowCount() > 0) {
              // delete last post thumbnail
              if($post['thumbnail'] != 'default.jpg' && $post['thumbnail'] != $thumbnail) {
		            $folder = $_SERVER['DOCUMENT_ROOT'] . '/assets/img/';
								unlink($folder.$post['thumbnail']);
              }
              header('Location: post.php?post=' . urlencode($title) . '&edited');
            } else {
              header('Location: ?post_id=' . $post_id . '&no_changes');
            }
          }
        } else {
          showAlert('danger', 'Title is already in use'); 
        }
      } else {
        showAlert('warning', 'Minimum post length is 500 characters');
      }
    }
  }
  require_once 'partials/header.php';

?>

<form class="row w-100 mt-3 mb-5" method="post" id="editPostForm" action="<?= $_SERVER['PHP_SELF'] . '?post_id=' . $post['id'] ?>" enctype="multipart/form-data">
  <div class="col-12 d-flex justify-content-between align-items-center px-0 mb-4">
    <p class="fs-2 fw-bold text-dark-emphasis p-0 mb-0" id="commentsSection">Update post</p>
    <button class="btn btn-light fs-5" type="submit"><i class="bi bi-upload me-2"></i>Update</button>
  </div>

  <input type="hidden" name="post_id" value="<?= $post['id']; ?>">
  
  <?php require_once 'partials/inputs-post.php'; ?>
</form>

<?php require_once 'partials/footer.php'; ?>