$('#loading').hide();

$(document).ajaxStart(function () {
	$('#loading').show();
});

$(document).ajaxStop(function () {
	$('#loading').hide();
});

$(document).on('change', 'input[type=file]', function () {
	$(this).parent().find('label').text(this.files[0].name);
});

$.fn.select2.defaults.set('theme', 'bootstrap');

const toasterAnimationObject = {
	showMethod: 'slideDown',
	hideMethod: 'slideUp',
	timeOut: 4000,
};

const inputs = document.querySelectorAll('input, select');
for (const el of inputs) {
	el.oldValue = el.value + el.checked;
}

// Declares function and call it directly
var setEnabled;
(setEnabled = function () {
	var e = true;
	for (const el of inputs) {
		if (el.oldValue !== el.value + el.checked) {
			e = false;
			break;
		}
	}
	if ($('button[type="submit"]').length) {
		document.querySelector("button[type='submit']").disabled = e;
	}
	if ($('input[type="submit"]').length) {
		document.querySelector("input[type='submit']").disabled = e;
	}
})();

document.oninput = setEnabled;
document.onchange = setEnabled;

$('form').submit(function () {
	$(this).find(':input[type=submit]').prop('disabled', true);
});
