const absolutePath = window.location.origin;

function hideAlert() {
  $(".alert").delay(800).fadeTo(2000, 500).slideUp(400, function() {
    $(this).alert('close');
  });
}

function showAlert(type, message) {
  $('body').prepend("<div class='position-fixed z-3 top-0 start-50 translate-middle-x mt-3 row alert alert-" + type + "' role='alert'>" + message + "</div>");

  hideAlert();
}

function getPosts() {
  $.ajax({
    url: absolutePath + '/int/get-posts.php',
    method: 'POST',
    dataType: 'json'
  }).done(function(response) {
    response.forEach(function(i) {
      var status = (i.status == 'public') ? 'checked' : '';
      
      $('#postsContainer').append("<div class='card col-lg-4 col-md-6 col-sm-12 px-0 blog-card'><div class='card-body' style='background-image: url(/assets/img/" + i.thumbnail + ");'></div><div class='card-footer d-flex flex-column justify-content-between'><p class='fs-4 blog-article-title fw-bold text-dark-emphasis'>" + i.title + "</p><div class='d-flex justify-content-between'><div class='form-check form-switch'><input class='form-check-input' type='checkbox' role='switch' id='switch" + i.id + "' data-id='" + i.id + "' " + status + "><label class='form-check-label' for='switch" + i.id + "'>" + i.status + "</label></div><a href='../pages/edit-post.php?post_id=" + i.id + "' class='ms-auto me-4'><i class='bi bi-pencil fs-5 text-dark-emphasis'></i></a><a class='btn-delete' data-id='" + i.id + "' data-bs-toggle='modal' data-bs-target='#deleteModal'><i class='bi bi-trash fs-5 text-dark-emphasis'></i></a></div></div></div>");
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
        url: absolutePath + '/int/change-post-status.php',
        method: 'POST',
        dataType: 'json',
        data: JSON.stringify({
          post_id: postId,
          status: statusPost
        })
      }).done(function() {
        showAlert('success', 'Post status changed');
      }).fail(function() {
        showAlert('warning', 'Post status not changed');
      });
    });
  }).fail(function() {
    $('#postsContainer').append("<div class='position-absolute top-50 start-50 translate-middle d-flex align-items-center justify-content-center flex-column'><i class='bi bi-exclamation-circle display-1 text-center'></i><p class='fs-4 fw-medium text-center mt-2 px-0'>You have no posts to display</p><div>");
  });
}

$(window).ready(function() {
  getPosts();

  hideAlert();

  var allowedExtensions = ['image/png', 'image/jpeg', 'image/webp'];

  $('#thumbnail').on('change', function(e) {  
    var file = e.currentTarget.files[0];

    if(!allowedExtensions.includes(file.type)) {
      showAlert('warning', 'File extension not allowed (only: png, jpg, webp)');
      $(this).val('');
    } else if(file.size > 2097152) {
      showAlert('warning', 'File too large. Max size: 2 MB');
      $(this).val('');
    }
  });
  
  // checks if text-editor length is smaller than required 
  $('#addPostForm, #editPostForm').submit(function(e) {
    var contentLength = $('.blog-chars-counter').find('span').text();

    if(contentLength < 500) {
      e.preventDefault();

      showAlert('warning', (500 - contentLength) + ' characters left to reach minimum post length');
    } else if(contentLength > 5000) {
      e.preventDefault();

      showAlert('warning', 'Maximum post length exceeded');
    }
  });

  $('#deleteModal').submit(function(e) {
    e.preventDefault();
  
    var postId = $(this).find('input[name=post_id]').val(); 
  
    $.ajax({
      url: absolutePath + '/int/delete-post.php',
      method: 'POST',
      dataType: 'json',
      data: JSON.stringify({
        post_id: postId
      })
    }).done(function() {
      showAlert('light', 'Post deleted');
      $('#postsContainer').html('');
      getPosts();
    }).fail(function() {
      showAlert('danger', 'Post not deleted');
    });

    $(this).find('.btn-close').click();
  });
  
  // text-editor default styles
  var textEditorHtml = $("iframe").contents().find('html');
  var textEditorHead = $("iframe").contents().find('head');
  var textEditorBody = $("iframe").contents().find('body');

  textEditorHtml.css('height', '100%');
  textEditorBody.css('margin', '0');
  textEditorBody.css('height', '100%');
  textEditorBody.css('padding', '10px');
  textEditorBody.css('color', '#dee2e6');
  textEditorBody.css('font-size', '1.2rem');
  textEditorBody.css('box-sizing', 'border-box');
  textEditorBody.css('background-color', '#343a40');
  textEditorHead.append("<style> ::selection { background-color: rgba(33,37,41, 1); } a { color: #f8f9fa; } p:first-child { margin-top: 0; } </style>");
  textEditorBody.css('font-family', "system-ui,-apple-system,'Segoe UI',Roboto,'Helvetica Neue','Noto Sans','Liberation Sans',Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol','Noto Color Emoji'");
});

// text-editor plugin set options
sceditor.create($('#text-editor')[0], {
	format: 'bbcode',
  height: '500px',
  resizeEnabled: false,
  emoticonsEnabled: false,
  enablePasteFiltering: true,
  plugins: 'plaintext, undo, autosave',
  toolbar: 'bold,italic,underline,strike|left,center,right,justify|size,removeformat|bulletlist,orderedlist,indent,outdent|horizontalrule,image,link',
  style: 'https://cdn.jsdelivr.net/npm/sceditor@3/minified/themes/default.min.css',
});

// count chars of the text-editor
var regex = /\n|\[b\]|\[\/b\]|\[i\]|\[\/i\]|\[u\]|\[\/u\]|\[s\]|\[\/s\]|\[hr\]|\[ul\]|\[\/ul\]|\[ol\]|\[\/ol\]|\[li\]|\[\/li\]|\[left\]|\[\/left\]|\[right\]|\[\/right\]|\[center\]|\[\/center\]|\[justify\]|\[\/justify\]|\[size=1\]|\[size=2\]|\[size=3\]|\[size=4\]|\[size=5\]|\[size=6\]|\[size=7\]|\[\/size\]|\[url=([^\]]+)\]|\[\/url\]/g;

function getCharsLength() {
  var textEditorLenght = sceditor.instance($('#text-editor')[0]).val().replace(regex, '').length;
  
  $('.blog-chars-counter').find('span').text(textEditorLenght);

  if(textEditorLenght < 500 || textEditorLenght > 5000) {
    $('.blog-chars-counter').find('span').addClass('text-danger-emphasis');
  } else {
    $('.blog-chars-counter').find('span').removeClass('text-danger-emphasis');
  }
}

sceditor.instance($('#text-editor')[0]).nodeChanged(getCharsLength).keyUp(getCharsLength);