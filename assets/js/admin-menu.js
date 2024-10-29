(function($) {

	$(function() {
		// Show / Hide options
		$( '#aham_enable' ).on( 'click', show_hide_options );

		function show_hide_options(  ) {
			if ( $( '#aham_enable' ).length ) {
				if ( $( '#aham_enable' ).is( ':checked' ) ) {
					$( 'table.form-table tr:not(.aham_always)' ).removeClass( 'hidden' );
				} else {
					$( 'table.form-table tr:not(.aham_always)' ).addClass( 'hidden' );
				}
			}
		};

		show_hide_options();

		if ( '1' === ahamSettings.show_menu_search ) {
			// Add search
			$( '#adminmenu' ).prepend('<li id="aham_search"><input type="text" placeholder="Search"></li>');

			$( '#aham_search input' ).on('keyup', function(){

				var search = $( this ).val().toLowerCase();

				if ( '' === search ){
					$( 'li.menu-top' ).show();
					show_hide_options();
				} else {
					$( '.wp-menu-name' ).each( function() {
						var wrap = $(this).closest("li.menu-top")
						var menu = $(this).text();
						if ( -1 === menu.toLowerCase().indexOf( search ) ) {
							if ( 'toplevel_page_aham_show_more' !== wrap.attr( 'id' ) ) {
								wrap.hide();
							}
							// search submenu
							$(this).closest( "li.menu-top" ).find( "ul.wp-submenu li" ).each( function() {
								var submenu = $(this).text();
								if ( -1 !== submenu.toLowerCase().indexOf( search ) ) {
									wrap.show();
								}
							});
						} else {
							wrap.show();
						}

					});
				}
			});
		}
	});

	if ( '0' === ahamSettings.enable ) {
		return;
	}

	// Show more menu items
	$( 'html' ).on( 'click', '#toplevel_page_aham_show_more a', function(e) {
		e.preventDefault();
		e.stopImmediatePropagation();
		var $showMore = $( e.currentTarget ).find( '.wp-menu-image' );

		// show/hide hidden menus
		$( '#adminmenu .aham_hidden' ).toggleClass( 'hidden' );
		//change icon
		$showMore.toggleClass( 'dashicons-arrow-up dashicons-arrow-down' );
		//change text
		if ( $showMore.hasClass( 'dashicons-arrow-up' ) ) {
			$showMore.siblings( '.wp-menu-name' ).text( ahamSettings.hideText );
		} else {
			$showMore.siblings( '.wp-menu-name' ).text( ahamSettings.showText );
		}
		$( window ).trigger( 'resize' );
	});

	// Save click on menu items
	$( 'html' ).on( 'click', '#adminmenu a', function(e) {
		var $current = $( e.currentTarget );
		var href = $current.attr( 'href' );
		var $li = $current.closest( 'li.menu-top' );
		var id = $li.attr( 'id' );
		var redirect = false;

		if ( $li.hasClass( 'aham_hidden' ) ) {
			// wait ajax for updating hidden menu
			e.preventDefault();
			redirect = true;
		}
		var data = {
			action: 'aham_click_on_menu',
			id: id
		};

		$.post( ajaxurl, data, function( response ) {
			if ( redirect ) {
				window.location = href;
			}
		});
	});

})(jQuery);
