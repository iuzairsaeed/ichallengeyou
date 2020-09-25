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
// var setEnabled;
// (setEnabled = function () {
// 	var e = true;
// 	for (const el of inputs) {
// 		if (el.oldValue !== el.value + el.checked) {
// 			e = false;
// 			break;
// 		}
// 	}
// 	if ($('button[type="submit"]').length) {
// 		document.querySelector("button[type='submit']").disabled = e;
// 	}
// 	if ($('input[type="submit"]').length) {
// 		document.querySelector("input[type='submit']").disabled = e;
// 	}
// })();

// document.oninput = setEnabled;
// document.onchange = setEnabled;

// $('form').submit(function () {
// 	$(this).find(':input[type=submit]').prop('disabled', true);
// });

var elem = document.documentElement;
$('#navbar-fullscreen').on('click', () => {
	if (elem.requestFullscreen) {
		elem.requestFullscreen();
	} else if (elem.mozRequestFullScreen) {
		/* Firefox */
		elem.mozRequestFullScreen();
	} else if (elem.webkitRequestFullscreen) {
		/* Chrome, Safari & Opera */
		elem.webkitRequestFullscreen();
	} else if (elem.msRequestFullscreen) {
		/* IE/Edge */
		elem.msRequestFullscreen();
	}
	$('#navbar-fullscreen').attr('id', 'exit-fullscreen');
	$('#navbar-fullscreen i').toggleClass('ft-maximize icon-size-actual');
});

$('.navbar-header').on('click', '#exit-fullscreen', () => {
	if (document.exitFullscreen) {
		document.exitFullscreen();
	} else if (document.mozCancelFullScreen) {
		document.mozCancelFullScreen();
	} else if (document.webkitExitFullscreen) {
		document.webkitExitFullscreen();
	} else if (document.msExitFullscreen) {
		document.msExitFullscreen();
	}
	$('#exit-fullscreen').attr('id', 'navbar-fullscreen');
	$('#navbar-fullscreen i').toggleClass('ft-maximize icon-size-actual');
});
