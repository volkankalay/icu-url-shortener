// Vooky some js functions
// copy to clipboard
function copyToClipboard(element) {
var $temp = $("<input>");
$("body").append($temp);
$temp.val($(element).text()).select();
document.execCommand("copy");
$temp.remove();
}

//popover animation
$('[data-toggle="popover"]').popover().click(function () {
  setTimeout(function () {
	  $('[data-toggle="popover"]').popover('hide');
  }, 1000);
});
$(window).scroll(function(event){
var scroll = $(window).scrollTop();
if (scroll >= 50) {
	$(".go-top").addClass("show");
} else {
	$(".go-top").removeClass("show");
}
});

//Animation anchor
$('#topBtn').click(function(){
    $('html, body').animate({
        scrollTop: $( $(this).attr('href') ).offset().top
    }, 1000);
});

// unlink the scroll
$('a[href*="#"]:not([href="#"])').on("click", function (e) {
  if (
    location.pathname.replace(/^\//, "") == this.pathname.replace(/^\//, "") &&
    location.hostname == this.hostname
  ) {
    var target = $(this.hash);
    target = target.length ? target : $("[name=" + this.hash.slice(1) + "]");
    var position = target.offset().top;
    if ( $("#navbar").hasClass("fixed")){
      position = position - $('#navbar').height();
    }
    $("html, body").animate(
      {
        scrollTop: position
      },
      225
    );
    return false;
  }
});
// File Uploader
  $("[type=file]").on("change", function(){
    // Name of file and placeholder
    var file = this.files[0].name;
    var dflt = $(this).attr("placeholder");
    if($(this).val()!=""){
      $(this).next().text(file);
    } else {
      $(this).next().text(dflt);
    }
  });