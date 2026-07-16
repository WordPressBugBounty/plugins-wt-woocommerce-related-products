(function ($) {
	'use strict';
	$(
		function () {
			jQuery( ".wt-crp-container #setting-error-settings_updated:eq(1)" ).hide();
		}
	);

	// Copy to clipboard promo code
	jQuery( document ).ready(
		function () {
			jQuery( '.wt_rp_copy_content' ).click(
				function (e) {
					e.preventDefault();
					var promo_code = 'PROMO30';
					if (window.clipboardData && window.clipboardData.setData) {
						// IE specific code path to prevent textarea being shown while dialog is visible.
						clipboardData.setData( "Text", promo_code );

					} else if (document.queryCommandSupported && document.queryCommandSupported( "copy" )) {
						var textarea            = document.createElement( "textarea" );
						textarea.textContent    = promo_code;
						textarea.style.position = "fixed";  // Prevent scrolling to bottom of page in MS Edge.
						document.body.appendChild( textarea );
						textarea.select();
						try {
							document.execCommand( "copy" );  // Security exception may be thrown by some browsers.
						} catch (ex) {
							console.warn( "Copy to clipboard failed.", ex );
						} finally {
							document.body.removeChild( textarea );
						}
					}

					jQuery( '.wt_rp_copied' ).show();
					jQuery( '.wt_rp_copied' ).css( 'color', 'green' );
					setTimeout(
						function () {
							$( ".wt_rp_copied" ).hide();
						},
						500
					);
				}
			);

			// jQuery for toggle of sections in related products plugin settings
			var crpAdvancedSettingsTable = jQuery( '.wt-crp-advanced-settings-toggle' ).nextAll( 'table.form-table:first' );
			crpAdvancedSettingsTable.hide();
			jQuery( '.wt-crp-advanced-settings-toggle , .wt-crp-widget-settings-toggle' ).on(
				'click',
				function () {
					var crpArrow = $( this ).find( '.wt-crp-arrow' );
					var crpTable = $( this ).nextAll( 'table.form-table:first' );

					crpTable.toggle();

					if ( crpTable.is( ':visible' ) ) {
						crpArrow.removeClass( 'dashicons-arrow-right' ).addClass( 'dashicons-arrow-down' );
						$( this ).addClass( 'active' );
					} else {
						crpArrow.removeClass( 'dashicons-arrow-down' ).addClass( 'dashicons-arrow-right' );
						$( this ).removeClass( 'active' );
					}
				}
			);

		}
	);

	/** Newsletter: show spinner on Subscribe click, hide when Mailchimp responds; on success call our AJAX to hide banner. */
	jQuery( document ).ready(
		function () {
			var newsletterBoxSelector = '.wt_crp_newsletter_subscription_box';
			var newsletterHideRequestInProgress = false;

			function showNewsletterLoader( $box ) {
				if ( $box.hasClass( 'wt_newsletter_loading' ) ) {
					return;
				}
				$box.addClass( 'wt_newsletter_loading' ).append(
					'<div class="wt_newsletter_loader"><span class="spinner"></span></div>'
				);
				$box.find( '.wt_newsletter_loader .spinner' ).css( 'visibility', 'visible' );
			}

			function removeNewsletterLoader( $box ) {
				$box.removeClass( 'wt_newsletter_loading' ).find( '.wt_newsletter_loader' ).remove();
			}

			function checkForNewsletterSuccess() {
				var $box = $( newsletterBoxSelector );
				var successResponse = $box.find( '#mce-success-response' );
				var errorResponse   = $box.find( '#mce-error-response' );
				var successHasContent = successResponse.length && successResponse.text().trim().length > 0;
				var errorHasContent   = errorResponse.length && errorResponse.text().trim().length > 0;
				var isSuccessVisible = successHasContent && successResponse.is( ':visible' );

				if ( newsletterHideRequestInProgress || ! isSuccessVisible || errorHasContent ) {
					return;
				}
				newsletterHideRequestInProgress = true;
				$.ajax({
					url: wt_crp_params.ajax_url,
					type: 'POST',
					dataType: 'json',
					data: {
						action: 'wt_crp_hide_newsletter_banner',
						_wpnonce: wt_crp_params.nonce
					},
					success: function( response ) {
						if ( response && response.success ) {
							removeNewsletterLoader( $box );
							setTimeout(function () {
								$box.fadeOut( 500, function () {
									$( this ).remove();
								} );
							}, 3000);
						}
					},
					error: function () {
						removeNewsletterLoader( $box );
					}
				});
			}

			if ( 'undefined' !== typeof wt_crp_params && -1 !== window.location.href.indexOf( 'wt-woocommerce-related-products' ) && $( newsletterBoxSelector ).length ) {
				var $box = $( newsletterBoxSelector );
				$box.on( 'click', '.wbte-newsletter-subscribe-button', function () {
					showNewsletterLoader( $box );
				} );
				checkForNewsletterSuccess();
				var newsletterCheckInterval = setInterval( function () {
					if ( ! $( newsletterBoxSelector ).length ) {
						clearInterval( newsletterCheckInterval );
						return;
					}
					checkForNewsletterSuccess();
				}, 1000 );
			}
		}
	);
})( jQuery );
