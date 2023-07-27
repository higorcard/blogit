<?php

  require_once '../int/check-login.php';

  require_once 'partials/header.php';

?>

<form class="row w-100 mt-5" method="post">
  <div class="col-12 d-flex justify-content-between align-items-center px-0 mb-4">
    <p class="fs-2 fw-bold text-dark-emphasis p-0 mb-0" id="commentsSection">Update post</p>
    <button class="btn btn-light fs-5" type="submit"><i class="bi bi-upload me-2"></i>Update</button>
  </div>
  
  <?php require_once 'partials/inputs-post.php'; ?>
</form>

<?php require_once 'partials/footer.php'; ?>