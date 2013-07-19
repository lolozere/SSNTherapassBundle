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
				// prototype form
				this.element.find('[data-prototype]').each(function() {
					var collectionHolder = $(this);
					var init = function() {
						collectionHolder.find('.collapse:first').each(function() { $(this).collapse('toggle'); });
					};
					var getIndex = function() {
						if (typeof collectionHolder.find('.form-collections-row:last-child').attr('data-index') == 'undefined')
							return 0;
						return parseInt(collectionHolder.find('.form-collections-row:last-child').attr('data-index'))+1;
					};
					var add = function(e) {
						e.preventDefault();
						// Récupère l'élément ayant l'attribut data-prototype comme expliqué plus tôt
					    var prototype = collectionHolder.attr('data-prototype');
					    // Remplace '__name__' dans le HTML du prototype par un nombre basé sur
					    // la longueur de la collection courante
					    var index = getIndex();
					    var newForm = prototype.replace(/__name__/g, getIndex());
					    // Affiche le formulaire dans la page dans un li, avant le lien "ajouter un tag"
					    collectionHolder.append(newForm);
					    // Bind and scrollTo
					    collectionHolder.find('.form-collections-row:last-child').each(function() {
					    	if (typeof $(this).attr('data-uniqid') == 'string') {
					    		var uniqid = $.uniqid();
					    		$(this).find('[href$="'+$(this).attr('data-uniqid')+'"]').attr('href', '#'+uniqid);
					    		$(this).find('#'+$(this).attr('data-uniqid')).attr('id', uniqid);
					    	}
					    	$(this).find('.collapse').each(function() {
					    		collectionHolder.find('.collapse.in').collapse('hide');
					    		$(this).collapse('show');
					    	});
					    	$(this).attr('data-index', index);
					    	$(this).find('.form-collections-row-remove').each(function() {
								$(this).on('click', remove);
								if(typeof $(this).attr('title') != 'undefined')
									$(this).tooltip();
								$(this).css({'cursor': 'pointer'});
							});
					    	$.scrollTo($(this)); 
					    });
					};
					var remove = function(e) {
						var element = $(this); var i = 0;
						while(!element.hasClass('form-collections-row') && i < 10) {
							element = element.parent(); i++;
						}
						element.remove();
					};
					$(document).find('[data-target="'+$(this).attr('id')+'"]').on('click', add);
					$(document).ready(init);
					// Add index
					$(this).find('.form-collections-row').each(function(index, value) {
						$(this).attr('data-index', index);
					});
					// Bind remove
					$(this).find('.form-collections-row-remove').each(function() {
						$(this).on('click', remove);
						if(typeof $(this).attr('title') != 'undefined')
							$(this).tooltip();
						$(this).css({'cursor': 'pointer'});
					});
				});
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