(function( $ ) {
	$.widget( "ui.application", {
		_create: function() {},
		_init: function() {
			try {
				// Disable certain links in docs
				this.element.find('section [href^=#]').click(function (e) {
					if (!($(this).hasClass('internal')))
						e.preventDefault();
			    });
				this.element.find('.input-required').each(function() {
					$(this).css({'cursor': 'pointer' });
					$(this).tooltip({ placement: 'top' });
				});
			    // tooltip / popover
				this.element.find('[data-toggle="tooltip"]').tooltip();
				this.element.find('[data-toggle="popover"]').popover();
				this.element.find('[data-toggle="collapse"]').collapse({toggle: false});
			} catch (e) {
				(typeof(console) !== "undefined" && console.log) && console.log(e);
			}
		},
	});
})( jQuery );

// JAVASCRIPT INIT UI
// ++++++++++++++++++++++++++++++++++++++++++
$(document).ready(function() {
	// make code pretty
    window.prettyPrint && prettyPrint();
    $(this).application();
});