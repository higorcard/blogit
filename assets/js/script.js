$(window).ready(function() {
  // hide alerts
  $(".alert").delay(1000).fadeTo(2000, 500).slideUp(400, function() {
    $(this).alert('close');
  });

  // edit text-editor default styles
  var textEditorHead = $("iframe").contents().find('head');
  var textEditorBody = $("iframe").contents().find('body');

  textEditorBody.css('color', '#dee2e6');
  textEditorBody.css('overflow', 'hidden');
  textEditorBody.css('font-size', '1.2rem');
  textEditorBody.css('background-color', '#2b3035');
  textEditorHead.append("<style> a { color: #f8f9fa; }</style>");
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