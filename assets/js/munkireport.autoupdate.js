// Automatically refresh widgets
$(document).on('appReady', function(e, lang) {

	var delay = 60; // seconds
	var refresh = function(){

		$(document).trigger('appUpdate');

		setTimeout(refresh, delay * 1000);
	}

	refresh();

});