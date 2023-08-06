<?php

  require_once '../int/config.php';
  require_once '../int/check-login.php';
  require_once 'partials/header.php';

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
  
  if(isset($_POST['title']) && isset($_POST['content'])) {
    $content_sanitized = preg_replace(array('/\s{2,}/', '/\[url\=(.*?)\]/', '/\[\/url\]/', '/[\t\n]/'), '', str_replace($special_chars, '', $_POST['content']));
    
    if(strlen($_POST['title']) >= 5) {
      if(strlen($content_sanitized) >= 500) {
        $user_id = filter_var($_SESSION['user_id'], FILTER_SANITIZE_NUMBER_INT);
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);

        // if(isset($_FILES['thumbnail'])) {
        //   $extension = pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);

        //   if(in_array($extension, $allowed_extensios) && move_uploaded_file($_FILES['thumbnail']['tmp_name'], 'assets/img/'.uniqid().".$extension")) {
        //     echo "upload";
        //   } else {
        //     echo "erro";
        //   }
        // }
        
        $sql = $pdo->prepare("INSERT INTO posts (user_id, title, text) VALUES (:u_i, :t, :c)");
        $sql->bindValue(':u_i', $user_id);
        $sql->bindValue(':t', $title);
        $sql->bindValue(':c', $content);
        $sql->execute();

        if($sql->rowCount() > 0) {
          header('Location: post.php?post_id='.$pdo->lastInsertId().'&success');
        }
      } else {
        echo "<div class='position-fixed z-3 top-0 start-50 translate-middle-x mt-3 row alert alert-warning' role='alert'>Post content is too short. Enter at least 500 characters.</div>";
      }
    }
  }

?>

<form class="row w-100 mt-3 mb-5" method="post" id="addPostForm" action="<?= $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">
  <div class="col-12 d-flex justify-content-between align-items-center px-0 mb-4">
    <p class="fs-2 fw-bold text-dark-emphasis p-0 mb-0" id="commentsSection">New post</p>
    <button class="btn btn-light fs-5" type="submit"><i class="bi bi-plus-circle me-2"></i>Submit</button>
  </div>
  
  <?php require_once 'partials/inputs-post.php'; ?>
</form>

<?php require_once 'partials/footer.php'; ?>