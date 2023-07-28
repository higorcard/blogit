function hideAlerts() {
  $(".alert").delay(800).fadeTo(2000, 500).slideUp(400, function() {
    $(this).alert('close');
  });
}

function getPosts() {
  $.ajax({
    url: 'int/get-posts.php',
    method: 'POST',
    dataType: 'json'
  }).done(function(response) {
    response.forEach(function(i) {
      var status = (i.status == 'public') ? 'checked' : '';
      
      $('#postsContainer').append("<div class='card col-lg-4 col-md-6 col-sm-12 px-0 blog-card'><div class='card-body' style='background-image: url(assets/img/" + i.thumb + ");'></div><div class='card-footer d-flex flex-column justify-content-between'><p class='fs-4 fw-bold text-dark-emphasis' style='overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; text-overflow: ellipsis; -webkit-box-orient: vertical;'>" + i.title + "</p><div class='d-flex justify-content-between'><div class='form-check form-switch'><input class='form-check-input' type='checkbox' role='switch' id='switch" + i.id + "' data-id='" + i.id + "' " + status + "><label class='form-check-label' for='switch" + i.id + "'>" + i.status + "</label></div><a href='pages/edit-post.php?post_id=" + i.id + "' class='ms-auto me-4'><i class='bi bi-pencil fs-5 text-dark-emphasis'></i></a><a class='btn-delete' data-id='" + i.id + "' data-bs-toggle='modal' data-bs-target='#deleteModal'><i class='bi bi-trash fs-5 text-dark-emphasis'></i></a></div></div></div>");
    });

    // set post id for delete form
    $('.btn-delete').on('click', function() {
      $('input[name=post_id]').val($(this).attr('data-id'));
    });

    // toggle post status label
    $('input[type=checkbox]').on('click', function() {
      if($(this).is(':checked')) {
        $(this).siblings('label').text('public');
      } else {
        $(this).siblings('label').text('private');
      }
    });

    // toggle post status in db
    $('input[type=checkbox]').on('click', function() {
      var postId = $(this).attr('data-id');
      var statusPost = ($(this).is(':checked')) ? 'public' : 'private';
  
      $.ajax({
        url: 'int/change-post-status.php',
        method: 'POST',
        dataType: 'json',
        data: JSON.stringify({
          post_id: postId,
          status: statusPost
        })
      }).done(function() {
        $('body').append("<div class='position-absolute top-0 start-50 translate-middle-x mt-3 row alert alert-success' role='alert'>Post status has been changed.</div>");
        hideAlerts();
      }).fail(function() {
        $('body').append("<div class='position-absolute top-0 start-50 translate-middle-x mt-3 row alert alert-warning' role='alert'>Unable to change post status!</div>");
        hideAlerts();
      });
    });
  }).fail(function() {
    $('#postsContainer').append("<div class='position-absolute top-50 start-50 translate-middle d-flex align-items-center justify-content-center flex-column'><i class='bi bi-exclamation-circle display-1 text-center'></i><p class='fs-4 fw-medium text-center mt-2 px-0'>You have no posts to display</p><div>");
  });
}

$(window).ready(function() {
  getPosts();

  hideAlerts();
  
  $('#deleteModal').submit(function(e) {
    e.preventDefault();
  
    var postId = $(this).find('input[name=post_id]').val(); 
  
    $.ajax({
      url: 'int/delete-post.php',
      method: 'POST',
      dataType: 'json',
      data: JSON.stringify({
        post_id: postId
      })
    }).done(function() {
      $('body').append("<div class='position-absolute top-0 start-50 translate-middle-x mt-3 row alert alert-success' role='alert'>Post has been deleted.</div>");
      $('#postsContainer').html('');
      getPosts();
      hideAlerts();
    }).fail(function() {
      $('body').append("<div class='position-absolute top-0 start-50 translate-middle-x mt-3 row alert alert-warning' role='alert'>Unable to delete post!</div>");
      hideAlerts();
    });

    $(this).find('.btn-close').click();
  });
  
  // edit text-editor default styles
  var textEditorHead = $("iframe").contents().find('head');
  var textEditorBody = $("iframe").contents().find('body');

  textEditorBody.css('color', '#dee2e6');
  textEditorBody.css('overflow', 'hidden');
  textEditorBody.css('font-size', '1.2rem');
  textEditorBody.css('background-color', '#2b3035');
  textEditorHead.append("<style> a { color: #f8f9fa; } </style>");
  textEditorBody.css('font-family', "system-ui,-apple-system,'Segoe UI',Roboto,'Helvetica Neue','Noto Sans','Liberation Sans',Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol','Noto Color Emoji'");
});

// text-editor plugin set options
sceditor.create($('#text-editor')[0], {
	format: 'bbcode',
  plugins: 'plaintext, undo, autosave',
	style: 'https://cdn.jsdelivr.net/npm/sceditor@3/minified/themes/default.min.css',
  height: '350px',
  resizeEnabled: false,
  toolbar: 'bold,italic,underline,strike|left,center,right,justify|size,removeformat|bulletlist,orderedlist,indent,outdent|horizontalrule,link'
});