$('#loading').hide();

$(document).ajaxStart(function() {
	$('#loading').show();
});

$(document).ajaxStop(function() {
	$('#loading').hide();
});

$(document).on('change', 'input[type=file]', function() {
	$(this)
		.parent()
		.find('label')
		.text(this.files[0].name);
});

// $.fn.select2.defaults.set('theme', 'bootstrap');
