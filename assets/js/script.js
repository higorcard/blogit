window.onload = function(){
  var frameElement = document.querySelector("iframe");
  var doc = frameElement.contentDocument;
  doc.body.contentEditable = true;
  doc.body.style.color = '#f8f9fa';
  doc.body.style.fontSize = '1rem';
  doc.body.style.overflow = 'hidden';
  doc.body.style.backgroundColor = '#2b3035';
  doc.body.style.fontFamily = "system-ui,-apple-system,'Segoe UI',Roboto,'Helvetica Neue','Noto Sans','Liberation Sans',Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol','Noto Color Emoji'";
}

var textarea = $('#text-editor')[0];

sceditor.create(textarea, {
	format: 'bbcode',
  plugins: 'plaintext, undo, autosave',
	style: 'https://cdn.jsdelivr.net/npm/sceditor@3/minified/themes/default.min.css',
  height: '350px',
  resizeEnabled: false,
  toolbar: 'bold,italic,underline,strike|left,center,right,justify|size,removeformat|bulletlist,orderedlist,indent,outdent|horizontalrule,link'
});