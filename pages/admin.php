<?php require_once 'partials/header.php'; ?>

<div class="row">
	<div class="card col-lg-4 col-md-6 col-sm-12 px-0 blog-card">
		<div class="card-body" style="background-image: url(assets/img/productive.png);"></div>
		<div class="card-footer d-flex flex-column">
			<p class="fs-4 fw-bold text-dark-emphasis">How to be more productive?</p>
			<div class="d-flex justify-content-between">
				<div class="form-check form-switch">
					<input class="form-check-input" type="checkbox" role="switch" id="switch" checked>
					<label class="form-check-label" for="switch">enabled</label>
				</div>
				<a href="pages/edit-post.php" class="ms-auto me-4"><i class="bi bi-pencil fs-5 text-dark-emphasis"></i></a>
				<a data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-trash fs-5 text-dark-emphasis"></i></a>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-4" id="exampleModalLabel">Delete post</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
				<p class="fs-5">Are you sure you want to delete this post?<br> This action <span class="text-decoration-underline">cannot be undone</span>.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger">Confirm</button>
      </div>
    </div>
  </div>
</div>

<?php require_once 'partials/footer.php'; ?>