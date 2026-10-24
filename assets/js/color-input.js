	
	document.addEventListener('DOMContentLoaded', function () {

		document.querySelectorAll('.vidchlog-file').forEach(function (input) {

			input.addEventListener('change', function () {

				if (this.files.length > 0) {
					this.style.color = 'green';
				} else {
					this.style.color = '';
				}

			});

		});

	});