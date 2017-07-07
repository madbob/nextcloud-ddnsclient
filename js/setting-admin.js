$(document).ready(function () {

	$('.show-ddnsclient-credentials').click(function() {
		var row = $(this).parent();
		var code = $(row).find('code');
		if(code.text() === '****') {
			code.text(row.data('value'));
			$(this).css('opacity', 0.9);
		} else {
			code.text('****');
			$(this).css('opacity', 0.3);
		}
	})

});
