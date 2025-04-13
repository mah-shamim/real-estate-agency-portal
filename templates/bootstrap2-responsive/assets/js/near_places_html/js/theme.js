
$('body').scrollspy({
    target: '.bs-sidebar',
});

$('.bs-sidebar').affix({
  offset: {
    top: 210
  }
});

/* Prevent disabled links from causing a page reload */
$("li.disabled a").click(function() {
    event.preventDefault();
});