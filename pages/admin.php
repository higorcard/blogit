<?php 

	require_once '../int/check-login.php';

	require_once 'partials/header.php';
	
?>

<div class="row d-flex mb-5" id="postsContainer" style="gap: 24px;"></div>

<form class="modal fade" id="deleteModal" method="POST" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-4" id="exampleModalLabel">Delete post</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
				<input type="hidden" name="post_id">
				<p class="fs-5">Are you sure you want to delete this post?<br> This action <span class="text-decoration-underline">cannot be undone</span>.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-danger">Confirm</button>
      </div>
    </div>
  </div>
</form>

<?php require_once 'partials/footer.php'; ?>