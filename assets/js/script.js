(function($, undefined) {
  var animate = function(element, words) {
	  var wordIndex = 0;
	  
	  element.on("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function() {
		  if (element.hasClass("fadeInUp")) {
			  element.removeClass("fadeInUp").addClass('fadeOut');
		  }
		  else {
			  wordIndex = (wordIndex + 1) % words.length;
			  element.html(words[wordIndex]);
			  element.removeClass("fadeOut").addClass('fadeInUp');
		  }
	  });

	  element.addClass("animated fadeInUp");
  };
  $.fn.rotateText = function(words) {
	  animate(this, words);
    return this;
  };
})(window.jQuery);


$(document).ready(function() {
	$(".noun").rotateText(["designers", "clients", "the world"]);
	$(".verb").rotateText(["create", "edit", "see"]);
});
