<div class="col-lg-7 col-md-6 col-sm-12 p-0 pe-md-3">
  <label for="title" class="form-label px-0">Title</label>
  <input type="text" class="form-control" name="title" id="title" placeholder="Type a flasy title" minlength="5" required value="<?php if(isset($post['title'])) { echo $post['title']; } elseif(isset($_POST['title'])) { echo $_POST['title']; } else { echo ''; } ?>">
</div>
<div class="col-lg-5 col-md-6 col-sm-12 p-0 ps-md-3 mt-sm-4 mt-md-0">
  <label for="thumbnail" class="form-label px-0">Thumbnail</label>
  <input class="form-control" type="file" name="thumbnail" id="thumbnail">
</div>
<label for="text-editor" class="form-label px-0 mt-4">Content</label>
<textarea class="blog-text-editor" name="content" id="text-editor"><?php if(isset($post['text'])) { echo $post['text']; } elseif(isset($_POST['content'])) { echo $_POST['content']; } else { echo ''; } ?></textarea>
<div class="blog-chars-counter px-3 py-2 text-end bg-dark-subtle" style="border-radius: 0 0 0.75rem 0.75rem;">
  <p class="m-0">
    <span class="text-danger-emphasis">0</span> / 5000
  </p>
</div>