(function() {
	var term = $('#term');
	var termLightbox = $('#term__lightbox');
	var lightboxClose = $('.lightbox__close');

	term.on('click', handleTermLightbox);

	function handleTermLightbox() {
		termLightbox.addClass('lightbox--open');
	}

	termLightbox.on('click', handleCloseLightbox);

	function handleCloseLightbox(ev) {
		if ($(ev.target).closest('.lightbox__main').length === 0) {
			$(this).removeClass('lightbox--open');
		}
	}

	lightboxClose.on('click', handleCloseLightbox);

	function handleCloseLightbox() {
		$(this).closest('.lightbox').removeClass('lightbox--open');
	}
})();

function showLoading()  {
	$("#loading").css('display', 'flex');
}

function hideLoading()  {
	$("#loading").hide();
}
