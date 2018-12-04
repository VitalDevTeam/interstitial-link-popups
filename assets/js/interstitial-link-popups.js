(function() {

	var links, intRows, ints = [], delay, modal, modalOverlay,
		modalClose, modalDest, modalCountdown, modalContent, redirectTimer;

	/**
	 * Initializes modal on all links which have an href
	 * that exists in the interstitials list
	 */
	function init() {

		for (var i = 0; i < intRows.length; i++) {
			if (intRows[i].status === true) {
				ints.push(intRows[i].destination);
			}
		}

		for (var i = 0; i < links.length; i++) {
			if (ints.indexOf(links[i].href) !== -1) {
				links[i].classList.add('interstitial-popup-link');
				links[i].addEventListener('click', function(e) {
					e.preventDefault();
					redirect(this);
				});
				links[i].addEventListener('touchend', function(e) {
					e.preventDefault();
					redirect(this);
				});
			}
		}
	}

	/**
	 * Opens modal
	 */
	function openModal() {
		if (!modal.classList.contains('open')) {
			modal.classList.add('open');
			modal.style.display = 'block';
			modalOverlay.style.display = 'block';
			modalContent.setAttribute('aria-hidden', 'false');
			setTimeout(function() {
				modalOverlay.style.opacity = 1;
			}, 50);
			setTimeout(function() {
				modal.style.opacity = 1;
			}, 250);
		}
	}

	/**
	 * Closes modal
	 */
	function closeModal() {
		if (modal.classList.contains('open')) {
			cancelCountdown();
			modal.classList.remove('open');
			modal.style.opacity = 0;
			modalOverlay.style.opacity = 0;
			setTimeout(function() {
				modal.style.display = 'none';
				modalOverlay.style.display = 'none';
				modalContent.setAttribute('aria-hidden', 'true');
			}, 250);
		}
	}

	/**
	 * Starts countdown
	 * @param {string} redirect URL to redirect to when countdown ends
	 */
	function startCountdown(redirect) {
		var countdown = delay;
		redirectTimer = setInterval(function() {
			countdown--;
			if (modalCountdown) {
				modalCountdown.textContent = countdown;
			}
			if (countdown <= 0) {
				clearInterval(redirectTimer);
				if (redirect) {
					document.location.href = redirect;
				}
			}
		}, 1000);
	}

	/**
	 * Resets countdown text in countdown container
	 */
	function resetCountdown() {
		if (modalCountdown) {
			modalCountdown.textContent = delay;
		}
	}

	/**
	 * Clears countdown timer
	 */
	function cancelCountdown() {
		clearInterval(redirectTimer);
		setTimeout(function() {
			resetCountdown();
		}, 200);
	}

	/**
	 * Opens modal and starts countdown to URL redirect
	 * @param {string} link URL to redirect to
	 */
	function redirect(link) {
		var dest = link.href;
		if (modalDest) {
			var destLink = document.createElement('a');
			destLink.setAttribute('href', dest);
			destLink.innerHTML = dest;
			modalDest.innerHTML = '';
			modalDest.appendChild(destLink);
		}
		openModal(dest);
		setTimeout(function() {
			startCountdown(dest);
		}, 600);
	}

    function onDocumentReady() {
		modal = document.getElementById('int-popup');

		if (modal) {
			links = document.getElementsByTagName('a');
			intRows = Interstitials.rows;
			delay = modal.dataset.redirect;
			modalOverlay = document.getElementById('int-popup-overlay');
			modalClose = document.querySelectorAll('.int-popup-close');
			modalDest = document.getElementById('int-popup-destination');
			modalCountdown = document.getElementById('int-popup-countdown');
			modalContent = document.getElementById('int-popup-content');

			init();

			if (modalCountdown) {
				modalCountdown.textContent = delay;
			}

			// Close modal on close button click
			for (var i = 0; i < modalClose.length; i++) {
				modalClose[i].addEventListener('click', closeModal);
			}

			// Close modal if ESC key pressed
			document.onkeyup = function(e) {
				e = e || window.event;
				if (e.keyCode === 27) {
					closeModal();
				}
			};
		}
    }

    document.addEventListener('DOMContentLoaded', function() {
        onDocumentReady();
    });

})();
