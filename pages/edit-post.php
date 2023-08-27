<?php
  session_start();

  require_once $_SERVER['DOCUMENT_ROOT'] . '/int/config.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/int/check-login.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/int/functions.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Post.php';

  if(isset($_GET['no_changes'])) {
		showAlert('warning', 'No changes made');
	}

  use Genert\BBCode\BBCode;
	$bbCode = new BBCode();

  if($_GET['post_id']) {
    $post_id = filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT);
    
    $post = Post::getById($user_id, $post_id);
  }

  if(!$post) {
    header('Location: admin.php');
  }

  if(isset($_POST['title'], $_POST['content'])) {
    $content_sanitized = $bbCode->stripBBCodeTags($_POST['content']);
    if(strlen($_POST['title']) >= 5) {
      if(strlen($content_sanitized) >= 500) {
        $post_id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);

        $thumbnail = setThumbnail($_FILES['thumbnail']['name']);
          
        $result = Post::edit($user_id, $post_id, $title, $content, $thumbnail);

        if($result != 'exists') {
          if($result) {
            // delete last post thumbnail
            if($post['thumbnail'] != 'default.jpg' && $post['thumbnail'] != $thumbnail) {
              $folder = $_SERVER['DOCUMENT_ROOT'] . '/assets/img/';
              unlink($folder.$post['thumbnail']);
            }
            header('Location: post.php?post=' . urlencode($title) . '&edited');
          } else {
            header('Location: ?post_id=' . $post_id . '&no_changes');
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