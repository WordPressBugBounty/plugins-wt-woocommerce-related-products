/**
 * Category sidebar tab switching for the "You May Also Need" admin page.
 *
 * @package WooCommerce Related Products
 * @since   1.7.7
 */
( function ( $ ) {
	'use strict';

	$( document ).ready( function () {
		var $links    = $( '.wt-crp-os-cat-link' );
		var $panels   = $( '.wt-crp-os-category-panel' );
		var $title    = $( '#wt-crp-os-cat-title' );
		var $subtitle = $( '#wt-crp-os-cat-subtitle' );

		$links.on( 'click', function ( e ) {
			e.preventDefault();

			var category = $( this ).data( 'category' );

			$links.removeClass( 'active' );
			$( this ).addClass( 'active' );

			$panels.removeClass( 'active' );
			var $panel = $( '#wt-crp-os-panel-' + category );
			$panel.addClass( 'active' );

			$title.text( $panel.data( 'title' ) );
			$subtitle.text( $panel.data( 'subtitle' ) );
		} );
	} );
}( jQuery ) );
