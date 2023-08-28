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
		showAlert('success', 'Post created');
	} elseif(isset($_GET['edited'])) {
		showAlert('light', 'Post edited');
	} elseif(isset($_GET['comment_created'])) {
		showAlert('success', 'Comment posted');
	} elseif(isset($_GET['comment_deleted'])) {
    showAlert('light', 'Comment deleted');
  }
  
  $post_title = urldecode(filter_input(INPUT_GET, 'post', FILTER_SANITIZE_SPECIAL_CHARS));
  
  $post = DB::table('posts')->columns('posts.id, posts.user_id, posts.title, posts.text, posts.thumbnail, posts.created_at, posts.updated_at, users.name AS author')->where('title', '=', $post_title)->where('status', '=', 'public')->join('posts', 'users', 'user_id', 'id')[0];

  if($post) {
    $page_title = ' | ' . $post['title'];

    $last_update = getElapsedTime($post['updated_at']);

    $comments = Comment::getAll($post['id']);
    
    $total_comments = count($comments);

    $comments_quantity = getCommentsQuantity($total_comments);
  } else {
    header('Location: ../?page=1');
  }
  
  if($_POST['post_id'] && $_POST['comment']) {
    if(!empty($_POST['comment'])) {
      $post_id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
      $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_SPECIAL_CHARS);

      if(Comment::create($user_id, $post_id, $comment)) {
        header('Location: ?post=' . $post['title'] . '&comment_created');
      } else {
		    showAlert('danger', 'Comment post error');
      }
    } else {
		  showAlert('warning', 'Comment cannot be empty');
    }
  }

  if($_POST['delete_comment_id']) {
    $comment_id = filter_input(INPUT_POST, 'delete_comment_id', FILTER_SANITIZE_NUMBER_INT);

    if(Comment::delete($user_id, $comment_id)) {
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
  <p class="col-sm-12 col-md-6 col-lg-6 p-0 fs-5 blog-post-info"><?php if(!empty($last_update)) { echo "Updated $last_update ago · "; } ?><a class="text-secondary-emphasis <?= ($total_comments == 0) ? 'disabled' : ''?>" href="<?= $_SERVER['REQUEST_URI'] ?>#commentsSection"><?= $comments_quantity; ?></a></p>
  <p class="col-sm-12 col-md-6 col-lg-6 p-0 fs-5 blog-post-info text-md-end text-lg-end"><?= date('M d, Y', strtotime($post['created_at'])) ?> by <a class="text-secondary-emphasis" disabled><?= $post['author'] ?></a></p>
</div>

<div class="row mt-3 mb-4 gy-3 fs-4 text-body blog-post-content">
  <?= nl2br($bbCode->convertToHtml($post['text'])); ?>
</div>

<div class="row mt-5 mb-2">
  <p class="d-flex justify-content-between align-items-center fs-2 fw-bold text-dark-emphasis border-bottom mb-5 pb-3 px-0" id="commentsSection">Comments <span class="fs-5 fw-normal blog-post-info"><?= $comments_quantity; ?></span></p>

  <?php
    if($user_id AND $user_id != $post['user_id']) :
      $name = User::getById($user_id)['name'];
  ?>
    <div class="col-12 d-flex mt-1 mb-4 px-0 blog-form-comment" style="gap: 24px;">
      <div class="d-flex fs-3 fst-italic text-body-emphasis bg-black blog-profile-photo blog-primary-font align-items-center justify-content-center rounded-circle"><?= substr($name, 0,1); ?></div>
      <form class="position-relative w-100" method="POST" action="<?= $_SERVER['PHP_SELF'] . '?post=' . $post['title']; ?>">
        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
        <textarea class="form-control form-control-lg" type="text" placeholder="Type something cool" name="comment" rows="3" value="<?= (isset($_POST['comment'])) ? $_POST['comment'] : ''; ?>" minlength="8" required></textarea>
        <button class="btn btn-link bg-dark link-light link-offset-5-hover link-underline-opacity-0 link-underline-opacity-100-hover fs-5 position-absolute mt-1 me-1 top-0 end-0" type="submit">Post</button>
      </form>
    </div>
  <?php endif; ?>

  <?php
    if($comments) :
      foreach($comments as $comment) :
        $creation_time = getElapsedTime($comment['created_at']);
  ?>
    <div class="col-12 d-flex my-3 pb-2 border-bottom px-0 blog-comment">
      <div class="d-flex fs-3 fst-italic text-body-emphasis bg-secondary-subtle blog-profile-photo blog-primary-font align-items-center justify-content-center rounded-circle"><?= substr($comment['author'], 0,1); ?></div>
      <div class="row w-100" style="max-width: calc(100% - 88px)">
        <div class="blog-post-info fs-5 d-flex justify-content-between align-items-center mb-2">
          <p class="m-0"><span class="fs-4 fw-bold text-dark-emphasis"><?= $comment['author']; ?></span> · <?= (!empty($creation_time)) ? $creation_time . ' ago' : 'Now' ; ?></p>
          <?php if($comment['user_id'] == $user_id) : ?>
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