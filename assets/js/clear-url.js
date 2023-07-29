// clear url for get method
var pageName = document.location.href.match(/[^\/]+$/)[0].split("?")[0];

if(pageName == 'post.php') {
  var newURL = location.href.split("&")[0];
} else {
  var newURL = location.href.split("?")[0];
}

window.history.pushState('object', document.title, newURL);