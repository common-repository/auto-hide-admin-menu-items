(function ($) {
	$(function() {
		// initalise the dialog
		$('#aham-show-dialog').dialog({
			title: 'Always Show',
			dialogId: 'wp-dialog',
			dialogClass: 'wp-dialog',
			autoOpen: false,
			draggable: false,
			width: 'auto',
			modal: true,
			resizable: false,
			closeOnEscape: true,
			show: {
				duration: 500
			},
			hide: {
				duration: 500
			},
			position: {
				my: 'center',
				at: 'center',
				of: window
			},
			open: function () {
				// close dialog by clicking the overlay behind it
				$('.ui-widget-overlay').unbind( 'click' ).bind('click', () => $( '#aham-show-dialog' ).dialog('close') );
				// on save
				$( '#aham-show-dialog #aham_save' ).unbind( 'click' ).bind('click', () => {
					var values = [];
					$( 'input.always_show_checkbox:checked' ).each( function( index, element ) {
						values.push( $( this ).val() );
					} );
					$( 'input[name="always_show"]' ).val( JSON.stringify( values ) );
					$( '#aham-show-dialog' ).dialog('close');
				});
			},
			create: function () {
				$( '#adminmenu > li' ).each( function( index, element ) {
					let key = $( this ).attr( 'id' );
					if ( -1 !== $.inArray( key, [ 'toplevel_page_aham_show_more', 'collapse-menu', 'aham_search' ] ) || ! key ) return;
					$( '#aham-always-show' ).append(
						'<input type="checkbox" class="always_show_checkbox" value="' + key + '"' + ( ( -1 !== $.inArray( key, ahamSettings.always_show ) ) ? 'checked="checked"' : '' ) + '> ' + $( this ).find('.wp-menu-name').clone().children().remove().end().text() + '<br><br>'
					) } );
			},
		});

		// initalise the dialog
		$('#aham-hide-dialog').dialog({
			title: 'Always Hide',
			dialogId: 'wp-dialog',
			dialogClass: 'wp-dialog',
			autoOpen: false,
			draggable: false,
			width: 'auto',
			modal: true,
			resizable: false,
			closeOnEscape: true,
			show: {
				duration: 500
			},
			hide: {
				duration: 500
			},
			position: {
				my: 'center',
				at: 'center',
				of: window
			},
			open: function () {
				// close dialog by clicking the overlay behind it
				$('.ui-widget-overlay').unbind( 'click' ).bind('click', () => $( '#aham-hide-dialog' ).dialog('close') );
				// on save
				$( '#aham-hide-dialog #aham_save' ).unbind( 'click' ).bind('click', () => {
					var values = [];
					$( 'input.always_hide_checkbox:checked' ).each( function( index, element ) {
						values.push( $( this ).val() );
					} );
					$( 'input[name="always_hide"]' ).val( JSON.stringify( values ) );
					$( '#aham-hide-dialog' ).dialog('close');
				});
			},
			create: function () {
				$( '#adminmenu > li' ).each( function( index, element ) {
					let key = $( this ).attr( 'id' );
					if ( -1 !== $.inArray( key, [ 'toplevel_page_aham_show_more', 'collapse-menu', 'aham_search' ] ) || ! key ) return;
					$( '#aham-always-hide' ).append(
						'<input type="checkbox" class="always_hide_checkbox" value="' + key + '"' + ( ( -1 !== $.inArray( key, ahamSettings.always_hide ) ) ? 'checked="checked"' : '' ) + '> ' + $( this ).find('.wp-menu-name').clone().children().remove().end().text() + '<br><br>'
					) } );
			},
		});

		// bind a button or a link to open the dialog
		$('a.open-aham-dialog').on( 'click', function(e) {
			e.preventDefault();
			$('#' + $(this).data('dialog_name') ).dialog('open');
		});
	});

  })(jQuery);
