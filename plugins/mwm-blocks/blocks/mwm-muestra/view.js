(function () {
	function initMuestraFancybox() {
		if (typeof Fancybox === 'undefined') {
			return;
		}

		Fancybox.bind('.wp-block-mwm-muestra [data-fancybox="mwm-muestra-video"]', {
			Hash: false,
			Html: {
				video: {
					autoplay: true,
				},
			},
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initMuestraFancybox);
	} else {
		initMuestraFancybox();
	}
})();
