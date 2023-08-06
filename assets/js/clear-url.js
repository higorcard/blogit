// clear url for get method
var pageName = document.location.href.match(/[^\/]+$/)[0].split("?")[0];

var deniedPages = ['admin.php', 'sign-in.php', 'sign-up.php'];

if(deniedPages.includes(pageName)) {
  var newURL = location.href.split("?")[0];
} else if(pageName != 'search-posts.php') {
  var newURL = location.href.split("&")[0];
}

window.history.pushState('object', document.title, newURL);