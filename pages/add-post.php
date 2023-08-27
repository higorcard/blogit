<?php
  session_start();

  require_once $_SERVER['DOCUMENT_ROOT'] . '/int/config.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/int/check-login.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/int/functions.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Post.php';

  use Genert\BBCode\BBCode;
	$bbCode = new BBCode();
  
  if(isset($_POST['title'], $_POST['content'])) {
    $content_sanitized = $bbCode->stripBBCodeTags($_POST['content']);
    if(strlen($_POST['title']) >= 5) {
      if(strlen($content_sanitized) >= 500) {
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);

        $thumbnail = setThumbnail($_FILES['thumbnail']['name']);

        $result = Post::create($user_id, $title, $content, $thumbnail);

        if($result != 'exists') {
          if($result) {
            header('Location: post.php?post=' . urlencode($title) . '&success');
          } else {
            showAlert('danger', 'Error creating post');
          }
        } else {
          showAlert('danger', 'Title is already in use'); 
        }
      } else {
        showAlert('warning', 'Minimum post length is 500 characters');
      }
    } else {
      showAlert('warning', 'Minimum title length is 5 characters');
    }
  }

  require_once 'partials/header.php';
?>

<form class="row w-100 mt-3 mb-5" method="post" id="addPostForm" action="<?= $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">
  <div class="col-12 d-flex justify-content-between align-items-center px-0 mb-4">
    <p class="fs-2 fw-bold text-dark-emphasis p-0 mb-0" id="commentsSection">New post</p>
    <button class="btn btn-light fs-5" type="submit"><i class="bi bi-plus-circle me-2"></i>Submit</button>
  </div>
  
  <?php require_once 'partials/inputs-post.php'; ?>
</form>

<?php require_once 'partials/footer.php'; ?>