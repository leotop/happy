(function ($) {
    'use strict';

	$(function () {

		// replace for hover effect 
		$(".touch .box-item:not(.box-item-video)").hover(function(e) { // Mouse Over
			$(this).addClass("event-hover");
			e.preventDefault();
		}, function(e) { // Mouse Out
			$(this).removeClass("event-hover");
			e.preventDefault();
		});

	});
	
})(jQuery);