<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Custom_Related_Products
 * @subpackage Custom_Related_Products/admin
 * @author     markhf
 */
class Custom_Related_Products_Admin {

	private $plugin_name;
	private $version;
	private $option_name = 'custom_related_products';
	private $plugin_screen_hook_suffix;

	/*
	 * admin module list, Module folder and main file must be same as that of module name
	 * Please check the `admin_modules` method for more details
	 */
	private $modules                = array(
		'import-export',
	);
	public static $existing_modules = array();

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		add_action( 'wp_ajax_wt_crp_ajax_attribute_search', array( $this, 'wt_crp_ajax_attribute_search' ) );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/custom-related-products-admin.css', array( 'wc-admin-layout' ), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		if ( isset( $_GET['page'] ) && 'wt-woocommerce-related-products' === $_GET['page'] && current_user_can( 'manage_options' ) ) {

			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/custom-related-products-admin.js', array( 'jquery' ), $this->version, false );

		}
	}

	/**
	 * Add related products selector to edit product section
	 */
	function crp_select_related_products() {

		global $post, $woocommerce;

		$settings_url = admin_url( '/admin.php?page=wt-woocommerce-related-products' );
		$working_mode = Custom_Related_Products::get_current_working_mode();
		if ( $working_mode != 'custom' ) {
			?>
			<p style="background:#fcf8e3; padding:10px;margin-left:10px; margin-right:10px; color:#000;"> <?php printf( __( 'Please select %1$s Working mode %2$s as "Custom related products" to reflect the selected related products on the product page.', 'wt-woocommerce-related-products' ), "<a target='_blank' href=" . esc_url( $settings_url ) . '>', '</a>' ); ?></p>
			<?php
		}
		?>
		<div class="wt_crp_options_group">
			<div class="wt_crp_options_heading"><b><?php _e( 'Custom related product settings:', 'wt-woocommerce-related-products' ); ?></b> <?php _e( 'Related products for this item will be shown based on the selection made below.', 'wt-woocommerce-related-products' ); ?></div>
			
			<!-- Categories Start -->
			<p class="form-field"><label for="related_product_cat"><?php _e( 'Categories', 'wt-woocommerce-related-products' ); ?></label>
				<select id="crp_related_product_cats" class="wc-category-search" multiple="multiple"  name="crp_related_product_cats[]" data-return_id="id" data-placeholder="<?php esc_attr_e( 'Search for a category...', 'wt-woocommerce-related-products' ); ?>" style="width: 400px;">     
					<?php

						$category_ids = array_filter( array_map( 'absint', (array) get_post_meta( $post->ID, '_crp_related_product_cats', true ) ) );
					if ( is_array( $category_ids ) && ! empty( $category_ids ) ) {
						foreach ( $category_ids as $category_id ) {
							$category = get_term( $category_id, 'product_cat' );
							if ( ! is_object( $category ) ) {
								continue;
							}
							if ( ! empty( $category_id ) ) {
								echo '<option value="' . esc_attr( $category_id ) . '"' . selected( true, true, false ) . '>' . esc_html( wp_strip_all_tags( $category->name ) ) . '</option>';
							}
						}
					}

					?>
				</select>
				<?php echo wc_help_tip( __( 'Products from chosen categories will be displayed as related products on the product page.', 'wt-woocommerce-related-products' ) ); ?>
			</p>

			<!-- Categories End -->

			<!-- Added ajax search in tag selection -->
			<!-- Tags Start -->
			<p class="form-field"><label for="related_product_tag"><?php _e( 'Tags', 'wt-woocommerce-related-products' ); ?></label>
				<select id="crp_related_product_tags" name="crp_related_product_tags[]"  class="wc-taxonomy-term-search" data-taxonomy="product_tag" data-return_id="id" multiple="multiple" data-placeholder="<?php esc_attr_e( 'Select for a tag...', 'wt-woocommerce-related-products' ); ?>" style="width: 400px;">
					<?php
					$tag_ids = array_filter( array_map( 'absint', (array) get_post_meta( $post->ID, '_crp_related_product_tags', true ) ) );

					if ( is_array( $tag_ids ) && ! empty( $tag_ids ) ) {
						foreach ( $tag_ids as $tag_id ) {
							$term = get_term( $tag_id );

							if ( is_a( $term, 'WP_Term' ) ) {
								echo '<option value="' . esc_attr( $term->term_id ) . '" selected="selected">' . esc_html( $term->name ) . '</option>';

							}
						}
					}
					?>
				</select>
				<?php echo wc_help_tip( __( 'Products from chosen tags will be displayed as related products on the product page.', 'wt-woocommerce-related-products' ) ); ?>                                
			</p>
			
			<!-- Tags End -->

			<!-- Attr Start -->
			<?php
			$attr_data = (array) get_post_meta( $post->ID, '_crp_related_product_attr', true );

			?>
			<p class="form-field">
				<label for="related_product_attr"><?php _e( 'Attributes', 'wt-woocommerce-related-products' ); ?></label>
				<select id="crp_related_product_attr"
					name="crp_related_product_attr[]" 
					class="crp_related_product_attr_search"
					style="width: 400px;"
					multiple="multiple"
					>
					<?php
					if ( ! empty( $attr_data ) ) {
						foreach ( $attr_data as $attr_name => $attr_ids ) {
							if ( is_array( $attr_ids ) ) {
								foreach ( $attr_ids as $attr_id ) {
									$term = get_term( $attr_id );
									echo '<option value="' . esc_attr( $attr_name . ':' . $attr_id ) .
									( ( ! empty( $attr_data[ $attr_name ] ) && in_array( $term->term_id, $attr_data[ $attr_name ] ) ) ? '" selected="selected">' : '">' )
									. esc_html( $attr_name . ':' . $term->name ) . '</option>';
								}
							}
						}
					}
					?>
				</select>         
				<?php echo wc_help_tip( __( 'Products from chosen attributes will be displayed as related products on the product page.', 'wt-woocommerce-related-products' ) ); ?>                
			</p>
			<!-- Attr End -->

			<!-- Related Products Start -->
			<?php
			$product_ids = array_filter( array_map( 'absint', (array) get_post_meta( $post->ID, '_crp_related_ids', true ) ) );
			?>

			<?php if ( $woocommerce->version >= '2.3' && $woocommerce->version < '3.0' ) : ?>
				<p class="form-field"><label for="related_ids"><?php _e( 'Products', 'wt-woocommerce-related-products' ); ?></label>
					<input type="hidden" 
						class="wc-product-search" 
						style="width: 50%;" 
						id="crp_related_ids" 
						name="crp_related_ids" 
						data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'woocommerce' ); ?>" 
						data-action="woocommerce_json_search_products_and_variations" 
						data-multiple="true" 
						data-selected="
						<?php
							$json_ids = array();
						foreach ( $product_ids as $product_id ) {
							$product = wc_get_product( $product_id );

							if ( is_object( $product ) && is_callable( array( $product, 'get_formatted_name' ) ) ) {
								$json_ids[ $product_id ] = wp_kses_post( wp_strip_all_tags( $product->get_formatted_name() ) );                             }
						}
							echo esc_attr( json_encode( $json_ids ) );
						?>
					" value="<?php echo esc_attr( implode( ',', array_keys( $json_ids ) ) ); ?>" />
					<?php echo wc_help_tip( __( 'Choose products to be displayed as related products on the product page.', 'wt-woocommerce-related-products' ) ); ?>
				</p>
			<?php else : ?>
				<p class="form-field"><label for="related_ids"><?php _e( 'Products', 'wt-woocommerce-related-products' ); ?></label>
					<select id="crp_related_ids" 
							class="wc-product-search" 
							name="crp_related_ids[]" 
							multiple="multiple" 
							style="width: 400px;" 
							data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'wt-woocommerce-related-products' ); ?>" 
							data-action="woocommerce_json_search_products_and_variations">
								<?php
								foreach ( $product_ids as $product_id ) {

									$product = wc_get_product( $product_id );

									if ( is_object( $product ) ) {
										echo '<option value="' . esc_attr( $product_id ) . '" selected="selected">' . esc_html( wp_strip_all_tags( $product->get_formatted_name() ) ) . '</option>';
									}
								}
								?>
					</select>
					<?php echo wc_help_tip( __( 'Choose products to be displayed as related products on the product page.', 'wt-woocommerce-related-products' ) ); ?>                    
				</p>
			<?php endif; ?>

			<!-- Related Products End -->

			<!-- Exclude Category Start -->
			<p class="form-field"><label for="exclude_cat"><?php _e( 'Exclude categories', 'wt-woocommerce-related-products' ); ?></label>
				<select id="crp_exclude_cats" class="wc-category-search" multiple="multiple"  name="crp_exclude_cats[]" data-return_id="id" data-placeholder="<?php esc_attr_e( 'Search for a category...', 'wt-woocommerce-related-products' ); ?>" style="width: 400px;">     
				<?php

					$excluded_cat_ids = array_filter( array_map( 'absint', (array) get_post_meta( $post->ID, '_crp_excluded_cats', true ) ) );
				if ( is_array( $excluded_cat_ids ) && ! empty( $excluded_cat_ids ) ) {
					foreach ( $excluded_cat_ids as $excluded_cat_id ) {
						$excluded_category = get_term( $excluded_cat_id, 'product_cat' );
						if ( ! is_object( $excluded_category ) ) {
							continue;
						}
						if ( ! empty( $excluded_cat_id ) ) {
							echo '<option value="' . esc_attr( $excluded_cat_id ) . '"' . selected( true, true, false ) . '>' . esc_html( wp_strip_all_tags( $excluded_category->name ) ) . '</option>';
						}
					}
				}

				?>
				</select>
				<?php echo wc_help_tip( __( 'Products from chosen categories will be excluded from related products on the product page.', 'wt-woocommerce-related-products' ) ); ?>
			</p>
			<!-- Exclude Category End -->

		</div>
		<?php
		$this->crp_insert_scripts();
		$this->crp_insert_styles();
	}

	/**
	 * Load JavaScript functions on product edit screen
	 */
	function crp_insert_scripts() {
		?>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery('.crp_related_product_attr_search').select2({
						placeholder: "<?php _e( 'Search for an attribute...', 'wt-woocommerce-related-products' ); ?>",
						minimumInputLength: 2,
						multiple: true,
						noResults: "<?php _e( 'No results found', 'wt-woocommerce-related-products' ); ?>",
						ajax: {
							url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
							dataType: 'json',
							type: "POST",
							quietMillis: 50,
							data: function (terms) {
								return {
									term: terms.term,
									_wpnonce: '<?php echo esc_html( wp_create_nonce( 'ajax_search_nonce' ) ); ?>',
									action: 'wt_crp_ajax_attribute_search',
								};
							},
							processResults: function (data) {
								return {
									results: jQuery.map(data, function (item) {
										return {
											id: item.id,
											text: item.text
										}
									})
								};
							}
						}                
				});
			});
		</script>
		<?php
	}

	/**
	 * Load styles on product edit screen
	 */
	function crp_insert_styles() {
		?>
		<style>
			.wt_crp_options_group .wt_crp_options_heading {
				margin: 15px 0px 15px 11px;
			}
			.wt_crp_options_heading b{
				font-weight: 700;
			}
			.crp-attr-terms .select2{
				margin-left: 10px;
			}
		</style>
		<?php
	}

	/**
	 * Save related products on product edit screen
	 */
	function crp_save_related_products( $post_id, $post ) {

		global $woocommerce;

		if ( isset( $_POST['crp_related_ids'] ) && current_user_can( 'manage_woocommerce' ) ) {
			$custom_related_ids = ( isset( $_POST['crp_related_ids'] ) && is_array( $_POST['crp_related_ids'] ) ) ? array_map( 'absint', $_POST['crp_related_ids'] ) : array();

			if ( $woocommerce->version >= '2.3' && $woocommerce->version < '3.0' ) {
				$related = $custom_related_ids;
			} else {
				$related = array();
				$ids     = $custom_related_ids;
				foreach ( $ids as $id ) {
					if ( $id && $id > 0 ) {
						$related[] = $id;
					}
				}
			}

			update_post_meta( $post_id, '_crp_related_ids', $related );
		} else {
			delete_post_meta( $post_id, '_crp_related_ids' );
		}

		// save related categories
		if ( isset( $_POST['crp_related_product_cats'] ) && current_user_can( 'manage_woocommerce' ) ) {

			$custom_related_product_cat_id = ( isset( $_POST['crp_related_product_cats'] ) && is_array( $_POST['crp_related_product_cats'] ) ) ? array_map( 'absint', $_POST['crp_related_product_cats'] ) : array();

			if ( $woocommerce->version >= '2.3' && $woocommerce->version < '3.0' ) {
				$related = $custom_related_product_cat_id;
			} else {
				$related = array();
				$ids     = $custom_related_product_cat_id;
				foreach ( $ids as $id ) {
					if ( $id && $id > 0 ) {
						$related[] = $id;
					}
				}
			}

			update_post_meta( $post_id, '_crp_related_product_cats', $related );
		} else {
			delete_post_meta( $post_id, '_crp_related_product_cats' );
		}

		// save related tags
		if ( isset( $_POST['crp_related_product_tags'] ) && current_user_can( 'manage_woocommerce' ) ) {

			$custom_related_product_tag_id = ( isset( $_POST['crp_related_product_tags'] ) && is_array( $_POST['crp_related_product_tags'] ) ) ? array_map( 'absint', $_POST['crp_related_product_tags'] ) : array();

			if ( $woocommerce->version >= '2.3' && $woocommerce->version < '3.0' ) {
				$related = $custom_related_product_tag_id;
			} else {
				$related = array();
				$ids     = $custom_related_product_tag_id;
				foreach ( $ids as $id ) {
					if ( $id && $id > 0 ) {
						$related[] = $id;
					}
				}
			}

			update_post_meta( $post_id, '_crp_related_product_tags', $related );
		} else {
			delete_post_meta( $post_id, '_crp_related_product_tags' );
		}

		// save related attributes
		if ( isset( $_POST['crp_related_product_attr'] ) && current_user_can( 'manage_woocommerce' ) ) {

			$crp_related_atts_data = isset( $_POST['crp_related_product_attr'] ) ? Wt_Related_Product_Security_Helper::crp_sanitize_text( $_POST['crp_related_product_attr'] ) : array();

			$crp_related_atts_data = $this->process_related_attr_data( $crp_related_atts_data );

			update_post_meta( $post_id, '_crp_related_product_attr', $crp_related_atts_data );
		} else {
			delete_post_meta( $post_id, '_crp_related_product_attr' );
		}

		// save excluded categories
		if ( isset( $_POST['crp_exclude_cats'] ) && current_user_can( 'manage_woocommerce' ) ) {

			$custom_related_product_cat_id = ( isset( $_POST['crp_exclude_cats'] ) && is_array( $_POST['crp_exclude_cats'] ) ) ? array_map( 'absint', $_POST['crp_exclude_cats'] ) : array();

			if ( $woocommerce->version >= '2.3' && $woocommerce->version < '3.0' ) {
				$related = $custom_related_product_cat_id;
			} else {
				$related = array();
				$ids     = $custom_related_product_cat_id;
				foreach ( $ids as $id ) {
					if ( $id && $id > 0 ) {
						$related[] = $id;
					}
				}
			}

			update_post_meta( $post_id, '_crp_excluded_cats', $related );
		} else {
			delete_post_meta( $post_id, '_crp_excluded_cats' );
		}
	}

	public function add_options_page() {

		$this->plugin_screen_hook_suffix = add_submenu_page(
			'woocommerce',
			__( 'Related Products for WooCommerce', 'wt-woocommerce-related-products' ),
			__( 'Related Products', 'wt-woocommerce-related-products' ),
			apply_filters( 'woocommerce_custom_related_products_role', 'manage_woocommerce' ),
			$this->plugin_name,
			array( $this, 'display_options_page' )
		);
		add_action( 'wt_crp_before_settings_block', array( $this, 'crp_display_branding' ) );
	}

	public function display_options_page() {
		$tab = 'related-product';
		if ( ! empty( $_GET['tab'] ) ) {
			if ( $_GET['tab'] == 'other-solutions' ) {
				$tab = 'other-solutions';
			} elseif ( $_GET['tab'] == 'related-product' ) {
				$tab = 'related-product';
			}
		}
		include_once 'partials/custom-related-products-admin-tab-view.php';
	}

	/**
	 * Admin Page for exporting
	 */
	public function admin_related_product_page() {
		include_once 'partials/custom-related-products-admin-display.php';
	}

	/**
	 * Admin Page for exporting
	 */
	public function admin_other_solution_page() {
		include_once 'partials/admin-settings-other-solutions.php';
	}
	public function register_setting() {

		add_settings_section(
			$this->option_name . '_general',
			'',
			array( $this, $this->option_name . '_general_cb' ),
			$this->plugin_name
		);
		add_settings_field(
			$this->option_name . '_working_mode',
			__( 'Working mode', 'wt-woocommerce-related-products' ),
			array( $this, $this->option_name . '_working_mode_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_working_mode' ),
		);
			add_settings_field(
				$this->option_name . '_cart_working_mode',
				__( 'Show related products in cart', 'wt-woocommerce-related-products' ) . wc_help_tip( __( 'Enable to display the related products recommendation on the cart page.', 'wt-woocommerce-related-products' ) ),
				array( $this, $this->option_name . '_cart_working_mode_cb' ),
				$this->plugin_name,
				$this->option_name . '_general',
				array(
					'label_for' => $this->option_name . '_cart_working_mode',
					'class'     => 'crp-tr-field mode-default-disallow',
				),
			);
		add_settings_field(
			$this->option_name . '_slider',
			__( 'Slider', 'wt-woocommerce-related-products' ) . wc_help_tip( __( 'Turn on the product slider to allow customers to browse related products by swiping left or right.', 'wt-woocommerce-related-products' ) ),
			array( $this, $this->option_name . '_slider_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'label_for' => $this->option_name . '_slider',
				'class'     => 'crp-tr-field mode-default-disallow',
			),
		);
		add_settings_field(
			$this->option_name . '_crp_banner_product_width',
			__( 'Number of products to display on slider per page', 'wt-woocommerce-related-products' ),
			array( $this, $this->option_name . '_crp_banner_product_width' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'label_for' => $this->option_name . '_crp_banner_product_width',
				'class'     => 'crp-tr-field mode-default-disallow',
			),
		);
		add_settings_field(
			$this->option_name . '_crp_number',
			__( 'Number of products to display', 'wt-woocommerce-related-products' ) . wc_help_tip( sprintf( __( 'Choose the number of products to display as related products. This number must be greater than the %1$sNumber of products to display on the slider per page%2$s for the slider to function correctly. The default value is 4.', 'wt-woocommerce-related-products' ), '<b>', '</b>' ) ),
			array( $this, $this->option_name . '_crp_number' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'label_for' => $this->option_name . '_crp_number',
				'class'     => 'crp-tr-field mode-default-disallow wt-slider-mode',
			),
		);
		add_settings_field(
			$this->option_name . '_crp_banner_width',
			__( 'Slider width', 'wt-woocommerce-related-products' ) . wc_help_tip( __( 'Alter the width of the related product section.', 'wt-woocommerce-related-products' ) ),
			array( $this, $this->option_name . '_crp_banner_width' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'label_for' => $this->option_name . '_crp_banner_width',
				'class'     => 'crp-tr-field mode-default-disallow wt-slider-mode',
			),
		);
		add_settings_field(
			$this->option_name . '_crp_custom_slider_arrow',
			__( 'Use custom slider arrow', 'wt-woocommerce-related-products' ),
			array( $this, $this->option_name . '_crp_custom_slider_arrow' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'label_for' => $this->option_name . '_crp_custom_slider_arrow',
				'class'     => 'crp-tr-field mode-default-disallow wt-slider-mode',
			),
		);
		add_settings_section(
			$this->option_name . '_widget_settings',
			'',
			array( $this, $this->option_name . '_widget_settings_cb' ),
			$this->plugin_name,
			array( 'class' => 'wt-pr-settings' )
		);
		add_settings_field(
			$this->option_name . '_crp_title',
			__( 'Related products title', 'wt-woocommerce-related-products' ) . wc_help_tip( __( 'The entered text will be displayed as the title of related products recommendation on the store.', 'wt-woocommerce-related-products' ) ),
			array( $this, $this->option_name . '_crp_title' ),
			$this->plugin_name,
			$this->option_name . '_widget_settings',
			array(
				'label_for' => $this->option_name . '_crp_title',
				'class'     => 'crp-tr-field',
			),
		);

		add_settings_field(
			$this->option_name . '_crp_related_by',
			__( 'Display related products from', 'wt-woocommerce-related-products' ) . wc_help_tip( __( 'Based on the selection made, products from the same category and tag will be shown as recommendations.', 'wt-woocommerce-related-products' ) ),
			array( $this, $this->option_name . '_crp_related_by' ),
			$this->plugin_name,
			$this->option_name . '_widget_settings',
			array(
				'label_for' => $this->option_name . '_crp_related_by',
				'class'     => 'crp-tr-field mode-default-disallow',
			),
		);

		add_settings_field(
			$this->option_name . '_crp_exclude_widget_category',
			__( 'Hide related products for categories', 'wt-woocommerce-related-products' ) . wc_help_tip( __( 'Select which categories should be excluded from displaying related products.', 'wt-woocommerce-related-products' ) ),
			array( $this, $this->option_name . '_crp_exclude_widget_category' ),
			$this->plugin_name,
			$this->option_name . '_widget_settings',
			array(
				'label_for' => $this->option_name . '_crp_exclude_widget_category',
				'class'     => 'crp-tr-field mode-default-disallow wt-gloablly-relate crp-child',
			),
		);

		add_settings_field(
			$this->option_name . '_crp_exclude_widget_product',
			__( 'Hide related products for products', 'wt-woocommerce-related-products' ) . wc_help_tip( __( 'Select individual products that should not display related products.', 'wt-woocommerce-related-products' ) ),
			array( $this, $this->option_name . '_crp_exclude_widget_product' ),
			$this->plugin_name,
			$this->option_name . '_widget_settings',
			array(
				'label_for' => $this->option_name . '_crp_exclude_widget_product',
				'class'     => 'crp-tr-field mode-default-disallow wt-gloablly-relate crp-child',
			),
		);

		add_settings_field(
			$this->option_name . '_crp_linked_products_banner',
			'',
			array( $this, $this->option_name . '_crp_linked_products_banner' ),
			$this->plugin_name,
			$this->option_name . '_widget_settings',
			array(
				'label_for' => $this->option_name . '_crp_linked_products_banner',
				'class'     => 'crp-tr-field crp-linked-products-banner mode-default-disallow',
			),
		);

		add_settings_field(
			$this->option_name . '_crp_order_by',
			__( 'Sort by', 'wt-woocommerce-related-products' ) . wc_help_tip( __( 'Select the criteria for sorting related products.', 'wt-woocommerce-related-products' ) ),
			array( $this, $this->option_name . '_crp_order_by' ),
			$this->plugin_name,
			$this->option_name . '_widget_settings',
			array(
				'label_for' => $this->option_name . '_crp_order_by',
				'class'     => 'crp-tr-field mode-default-disallow',
			),
		);

		add_settings_field(
			$this->option_name . '_crp_order',
			__( 'Sort order', 'wt-woocommerce-related-products' ) . wc_help_tip( __( 'Choose whether to sort related products in ascending (A-Z, low to high) or descending (Z-A, high to low) order based on the selected criteria.', 'wt-woocommerce-related-products' ) ),
			array( $this, $this->option_name . '_crp_order' ),
			$this->plugin_name,
			$this->option_name . '_widget_settings',
			array(
				'label_for' => $this->option_name . '_crp_order',
				'class'     => 'crp-tr-field mode-default-disallow',
			),
		);

		// add_settings_field(
		// $this->option_name . '_slider_type', __( 'Choose slider type', 'wt-woocommerce-related-products'), array($this, $this->option_name . '_slider_type'), $this->plugin_name, $this->option_name . '_general', array('label_for' => $this->option_name . '_slider_type',
		// 'class' => 'crp-tr-field mode-default-disallow wt-slider-mode')
		// );

		add_settings_field(
			$this->option_name . '_exclude_os',
			__( 'Exclude out of stock products', 'wt-woocommerce-related-products' ) . wc_help_tip( __( 'Enable to exclude out of stock products from being displayed as related product recommendations.', 'wt-woocommerce-related-products' ) ),
			array( $this, $this->option_name . '_exclude_os_cb' ),
			$this->plugin_name,
			$this->option_name . '_widget_settings',
			array(
				'label_for' => $this->option_name . '_exclude_os',
				'class'     => 'crp-tr-field mode-default-disallow',
			),
		);

		// Exclude Backorder products
		add_settings_field(
			$this->option_name . '_rp_exclude_backorder',
			__( 'Exclude Backorder products', 'wt-woocommerce-related-products' ) . wc_help_tip( __( 'Enable to exclude backorder products from being displayed as related product recommendations.', 'wt-woocommerce-related-products' ) ),
			array( $this, $this->option_name . '_rp_exclude_backorder' ),
			$this->plugin_name,
			$this->option_name . '_widget_settings',
			array(
				'label_for' => $this->option_name . '_rp_exclude_backorder',
				'class'     => 'crp-tr-field mode-default-disallow',
			),
		);
		add_settings_section(
			$this->option_name . '_advanced_settings',
			'',
			array( $this, $this->option_name . '_advanced_settings_cb' ),
			$this->plugin_name
		);
		add_settings_field(
			$this->option_name . '_use_primary_id_wpml',
			__( 'Use original product ID(WPML)', 'wt-woocommerce-related-products' ),
			array( $this, $this->option_name . '_use_primary_id_wpml_cb' ),
			$this->plugin_name,
			$this->option_name . '_advanced_settings',
			array(
				'label_for' => $this->option_name . '_use_primary_id_wpml',
				'class'     => 'crp-tr-field mode-default-disallow',
			),
		);
		add_settings_field(
			$this->option_name . '_overide_theme_rp',
			__( "Override theme's template", 'wt-woocommerce-related-products' ) . wc_help_tip( __( 'Enable to override the theme’s existing template for related products.', 'wt-woocommerce-related-products' ) ),
			array( $this, $this->option_name . '_overide_theme_rp_cb' ),
			$this->plugin_name,
			$this->option_name . '_advanced_settings',
			array(
				'label_for' => $this->option_name . '_overide_theme_rp',
				'class'     => 'crp-tr-field mode-default-disallow',
			),
		);

		add_settings_field(
			$this->option_name . '_info_bottom',
			'',
			array( $this, $this->option_name . '_info_bottom_cb' ),
			$this->plugin_name,
			$this->option_name . '_advanced_settings',
			array(
				'label_for' => $this->option_name . '_info_bottom',
				'class'     => 'crp-tr-field crp-info-bottom-banner mode-default-disallow',
			),
		);

		register_setting(
			$this->plugin_name,
			$this->option_name . '_working_mode'
		);
		register_setting(
			$this->plugin_name,
			$this->option_name . '_cart_working_mode'
		);
		register_setting(
			$this->plugin_name,
			$this->option_name . '_disable'
		);
		register_setting(
			$this->plugin_name,
			$this->option_name . '_disable_custom'
		);

		register_setting(
			$this->plugin_name,
			$this->option_name . '_crp_title'
		);

		register_setting(
			$this->plugin_name,
			$this->option_name . '_crp_related_by'
		);

		register_setting(
			$this->plugin_name,
			$this->option_name . '_crp_exclude_widget_category'
		);

		register_setting(
			$this->plugin_name,
			$this->option_name . '_crp_exclude_widget_product'
		);
		register_setting(
			$this->plugin_name,
			$this->option_name . '_crp_order_by'
		);
		register_setting(
			$this->plugin_name,
			$this->option_name . '_crp_order'
		);
		register_setting(
			$this->plugin_name,
			$this->option_name . '_crp_number'
		);
		register_setting(
			$this->plugin_name,
			$this->option_name . '_crp_banner_width'
		);
		register_setting(
			$this->plugin_name,
			$this->option_name . '_crp_custom_slider_arrow'
		);
		register_setting(
			$this->plugin_name,
			$this->option_name . '_crp_banner_product_width'
		);
		register_setting(
			$this->plugin_name,
			$this->option_name . '_exclude_os'
		);
		// Exclude backorder
		register_setting(
			$this->plugin_name,
			$this->option_name . '_rp_exclude_backorder'
		);

		register_setting(
			$this->plugin_name,
			$this->option_name . '_slider'
		);
		// register_setting(
		// $this->plugin_name, $this->option_name . '_slider_type'
		// );
		register_setting(
			$this->plugin_name,
			$this->option_name . '_use_primary_id_wpml'
		);
		register_setting(
			$this->plugin_name,
			$this->option_name . '_overide_theme_rp',
			array( 'default' => 'enable' )
		);

		add_action( 'admin_head', array( $this, 'insert_main_settings_scripts' ), 999 );
	}

	public function custom_related_products_working_mode_cb() {

		$working_mode = Custom_Related_Products::get_current_working_mode();
		?>
		<fieldset class="working-mode-field">
			<input type="radio" name="<?php echo esc_attr( $this->option_name . '_working_mode' ); ?>" id="<?php echo esc_attr( $this->option_name . '_custom' ); ?>" value="custom" <?php checked( $working_mode, 'custom' ); ?>>
			<label for="<?php echo esc_attr( $this->option_name . '_custom' ); ?>">
				<?php _e( 'Custom related products', 'wt-woocommerce-related-products' ); ?>
			</label><br>
			<p class="description"><?php _e( 'Get full control over which products are displayed as related products and appearance using advanced customization options.(Recommended)', 'wt-woocommerce-related-products' ); ?></p>

			<input type="radio" name="<?php echo esc_attr( $this->option_name . '_working_mode' ); ?>" id="<?php echo esc_attr( $this->option_name . '_default' ); ?>" value="default" <?php checked( $working_mode, 'default' ); ?>>
			<label for="<?php echo esc_attr( $this->option_name . '_default' ); ?>">
				<?php _e( 'Default', 'wt-woocommerce-related-products' ); ?>
			</label><br>
			<p class="description"><?php _e( "Use WooCommerce's built-in related products settings with limited customization options.", 'wt-woocommerce-related-products' ); ?></p>

			

			<input type="radio" name="<?php echo esc_attr( $this->option_name . '_working_mode' ); ?>" id="<?php echo esc_attr( $this->option_name . '_disable_rp' ); ?>" value="disable" <?php checked( $working_mode, 'disable' ); ?>>
			<label for="<?php echo esc_attr( $this->option_name . '_disable_rp' ); ?>">
				<?php _e( 'Disable recommendation', 'wt-woocommerce-related-products' ); ?>
			</label><br>
			<p class="description"><?php _e( 'Completely remove the related products section from your store.', 'wt-woocommerce-related-products' ); ?></p>

		</fieldset>         
		<?php
	}

	public function custom_related_products_crp_title() {

		$crp_title = get_option( $this->option_name . '_crp_title', __( 'Related Products', 'wt-woocommerce-related-products' ) );
		?>
		<fieldset class="crp-title">
			<label>
				<input type="text" name="<?php echo esc_attr( $this->option_name . '_crp_title' ); ?>" id="<?php echo esc_attr( $this->option_name . '_crp_title' ); ?>" value="<?php echo esc_attr( $crp_title ); ?>" class="wt-crp-input">
			</label>
		</fieldset>         
		<?php
	}


	public function custom_related_products_crp_banner_width() {

		$crp_title = get_option( $this->option_name . '_crp_banner_width', __( '100', 'wt-woocommerce-related-products' ) );
		?>
		<fieldset class="crp-banner-width">
			<label>
				<input type="number" name="<?php echo esc_attr( $this->option_name . '_crp_banner_width' ); ?>" id="<?php echo esc_attr( $this->option_name . '_crp_banner_width' ); ?>" value="<?php echo esc_attr( $crp_title ); ?>" class="wt-crp-input"  min="50" max="150" required='required'>
			</label>
			<p class="description"><?php _e( '%', 'wt-woocommerce-related-products' ); ?></p>
		</fieldset>         
		<?php
	}

	public function custom_related_products_crp_banner_product_width() {

		$crp_view_port        = get_option( $this->option_name . '_crp_banner_product_width' );
		$desktop_view         = isset( $crp_view_port[0] ) && ! empty( $crp_view_port[0] ) ? $crp_view_port[0] : 3;
		$tab_view             = isset( $crp_view_port[1] ) && ! empty( $crp_view_port[1] ) ? $crp_view_port[1] : 2;
		$mobile_view          = isset( $crp_view_port[2] ) && ! empty( $crp_view_port[2] ) ? $crp_view_port[2] : 1;
		$desktop_view_default = isset( $crp_view_port[3] ) && ! empty( $crp_view_port[3] ) ? $crp_view_port[3] : 5;
		$tab_view_default     = isset( $crp_view_port[4] ) && ! empty( $crp_view_port[4] ) ? $crp_view_port[4] : 2;
		$mobile_view_default  = isset( $crp_view_port[5] ) && ! empty( $crp_view_port[5] ) ? $crp_view_port[5] : 1;
		?>
		<style> 
			.wt-with-out-slider input[type=number]::-webkit-inner-spin-button {
				opacity: 1
			}
			.wt_tooltip .wt_tooltiptext {
				visibility: hidden;
				width: 120px;
				background-color: black;
				color: #fff;
				text-align: center;
				border-radius: 6px;
				padding: 5px 0;
				position: absolute;
				z-index: 1;
				top: 150%;
				left: 50%;
				margin-left: -60px;
			}

			.wt_tooltip .wt_tooltiptext::after {
				content: "";
				position: absolute;
				bottom: 100%;
				left: 50%;
				margin-left: -5px;
				border-width: 5px;
				border-style: solid;
				border-color: transparent transparent black transparent;
			}

			.wt_tooltip:hover .wt_tooltiptext {
				visibility: visible;
			}
		</style>
		<fieldset class="crp-banner-product-width wt-with-slider">
			<label class="inner-addon left-addon wt_tooltip">
				<i class="glyphicon wt-preview-desktop wt_tooltip"><span class="wt_tooltiptext"><?php _e( 'Desktop', 'wt-woocommerce-related-products' ); ?></span></i>
				<input type="number" name="<?php echo esc_attr( $this->option_name . '_crp_banner_product_width[]' ); ?>" id="<?php echo esc_attr( $this->option_name . '_crp_banner_product_width_desk' ); ?>" value="<?php echo esc_attr( $desktop_view ); ?>" class="wt-crp-input" min="3" style="width: 102px">
				</label>
			<label class="inner-addon left-addon wt_tooltip">
				<i class="glyphicon wt-preview-tablet wt_tooltip"><span class="wt_tooltiptext"><?php _e( 'Tablet', 'wt-woocommerce-related-products' ); ?></span></i>
				<input type="number" name="<?php echo esc_attr( $this->option_name . '_crp_banner_product_width[]' ); ?>" id="<?php echo esc_attr( $this->option_name . '_crp_banner_product_width_tab' ); ?>" value="<?php echo esc_attr( $tab_view ); ?>" class="wt-crp-input" min="1" style="width: 102px">
				</label>
			<label class="inner-addon left-addon wt_tooltip">
				<i class="glyphicon wt-preview-mobile wt_tooltip"><span class="wt_tooltiptext"><?php _e( 'Mobile', 'wt-woocommerce-related-products' ); ?></span></i>
				<input type="number" name="<?php echo esc_attr( $this->option_name . '_crp_banner_product_width[]' ); ?>" id="<?php echo esc_attr( $this->option_name . '_crp_banner_product_width_mobile' ); ?>" value="<?php echo esc_attr( $mobile_view ); ?>" class="wt-crp-input" min="1" style="width: 102px">
				</label>
			<p class="description"><?php _e( 'Enter the number of products to be shown in the slider per view.', 'wt-woocommerce-related-products' ); ?></p>
		</fieldset>  
		<fieldset class="crp-banner-product-width wt-with-out-slider">          
			<label class="inner-addon left-addon wt_tooltip">
				<i class="glyphicon wt-preview-desktop wt_tooltip"><span class="wt_tooltiptext"><?php _e( 'Desktop', 'wt-woocommerce-related-products' ); ?></span></i>
				<input type="number" name="<?php echo esc_attr( $this->option_name . '_crp_banner_product_width[]' ); ?>" id="<?php echo esc_attr( $this->option_name . '_crp_banner_product_width_desk_default' ); ?>" value="<?php echo esc_attr( $desktop_view_default ); ?>" class="wt-crp-input" min="3" style="width: 102px">
				</label>
			<label class="inner-addon left-addon wt_tooltip">
				<i class="glyphicon wt-preview-tablet wt_tooltip"><span class="wt_tooltiptext"><?php _e( 'Tablet', 'wt-woocommerce-related-products' ); ?></span></i>
				<input type="number" name="<?php echo esc_attr( $this->option_name . '_crp_banner_product_width[]' ); ?>" id="<?php echo esc_attr( $this->option_name . '_crp_banner_product_width_tab_default' ); ?>" value="<?php echo esc_attr( $tab_view_default ); ?>" class="wt-crp-input" min="1" style="width: 102px">
				</label>
			<label class="inner-addon left-addon wt_tooltip">
				<i class="glyphicon wt-preview-mobile wt_tooltip"><span class="wt_tooltiptext"><?php _e( 'Mobile', 'wt-woocommerce-related-products' ); ?></span></i>
				<input type="number" name="<?php echo esc_attr( $this->option_name . '_crp_banner_product_width[]' ); ?>" id="<?php echo esc_attr( $this->option_name . '_crp_banner_product_width_mobile_default' ); ?>" value="<?php echo esc_attr( $mobile_view_default ); ?>" class="wt-crp-input" min="1" style="width: 102px">
				</label>
		</fieldset>
		<?php
	}

	public function custom_related_products_crp_related_by() {
		wp_enqueue_script( 'wc-enhanced-select' );
		if ( function_exists( 'WC' ) ) {
			wp_enqueue_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css' );
		}

		$crp_related_by = (array) get_option( $this->option_name . '_crp_related_by', array( 'category' ) );

		?>
		<fieldset class="crp-related-by">

			<label for="<?php esc_attr_e( $this->option_name . '_category' ); ?>">
				<input type="checkbox" name="<?php echo esc_attr( $this->option_name . '_crp_related_by[]' ); ?>" id="<?php echo esc_attr( $this->option_name . '_category' ); ?>" value="<?php echo esc_attr( 'category' ); ?>"  <?php ( is_array( $crp_related_by ) && in_array( 'category', $crp_related_by ) ? print esc_attr( 'checked' ) : '' ); ?>>
				<?php _e( 'Same category', 'wt-woocommerce-related-products' ); ?>
			</label>

			<label for="<?php esc_attr_e( $this->option_name . '_tag' ); ?>">
				
				<input type="checkbox" name="<?php echo esc_attr( $this->option_name . '_crp_related_by[]' ); ?>" id="<?php echo esc_attr( $this->option_name . '_tag' ); ?>" value="<?php echo esc_attr( 'tag' ); ?>" <?php ( is_array( $crp_related_by ) && in_array( 'tag', $crp_related_by ) ? print esc_attr( 'checked' ) : '' ); ?>>
				<?php _e( 'Same tag', 'wt-woocommerce-related-products' ); ?>
			</label>
			<!-- <p class="description crp-paragraph crp-sub-cat"><?php // printf( __( 'Use this %1$s code snippet %2$s to relate products by sub-category.', 'wt-woocommerce-related-products' ), '<a href="https://www.webtoffee.com/related-products-woocommerce-user-guide/#sub_category" target="_blank">', '</a>' ); ?></p> -->

		</fieldset>
		<?php
	}

	public function custom_related_products_crp_linked_products_banner() {
		?>
		<tr class="crp-tr-field">
			<td colspan="2" style="padding: 0px;">
				<div class="crp-banner wt-crp-info-box">
				<?php _e( "To override the 'Display related products from' selection or to set related products individually for each product:", 'wt-woocommerce-related-products' ); ?>
				<ol>
					<li><?php _e( 'Navigate to Products', 'wt-woocommerce-related-products' ); ?> > <?php _e( 'Edit Product', 'wt-woocommerce-related-products' ); ?> > <?php _e( 'Linked Products', 'wt-woocommerce-related-products' ); ?></li>
					<li><?php _e( 'Choose to display related products from specific categories, tags, attributes, or products.', 'wt-woocommerce-related-products' ); ?></li>
				</ol>
				
				</div>
			</td>
		</tr>
		
		<?php
	}


	public function custom_related_products_crp_exclude_widget_category() {
		?>
		<fieldset class="crp-exclude-category">
			<select name="<?php echo esc_attr( $this->option_name . '_crp_exclude_widget_category[]' ); ?>" id="<?php echo esc_attr( $this->option_name . '_crp_exclude_widget_category' ); ?>" class="wc-category-search" multiple="multiple"  data-return_id="id" data-placeholder="<?php esc_attr_e( 'Search for a category...', 'wt-woocommerce-related-products' ); ?>" style="width: 400px;">     
				<?php

					$category_ids = array_filter( array_map( 'absint', (array) get_option( 'custom_related_products_crp_exclude_widget_category', true ) ) );
				if ( is_array( $category_ids ) && ! empty( $category_ids ) ) {
					foreach ( $category_ids as $category_id ) {
						$category = get_term( $category_id, 'product_cat' );
						if ( ! is_object( $category ) ) {
							continue;
						}
						if ( ! empty( $category_id ) ) {
							echo '<option value="' . esc_attr( $category_id ) . '"' . selected( true, true, false ) . '>' . esc_html( wp_strip_all_tags( $category->name ) ) . '</option>';
						}
					}
				}

				?>
			</select>
		</fieldset>
		<?php
	}

	public function custom_related_products_crp_exclude_widget_product() {
		?>
		<fieldset class="crp-exclude-product">
			<select  name="<?php echo esc_attr( $this->option_name . '_crp_exclude_widget_product[]' ); ?>" id="<?php echo esc_attr( $this->option_name . '_crp_exclude_widget_product' ); ?>"  class="wc-product-search" multiple="multiple" style="width: 400px;" data-placeholder="<?php esc_attr_e( 'Search for a product...', 'wt-woocommerce-related-products' ); ?>" data-action="woocommerce_json_search_products">
				<?php
				$product_ids = array_filter( array_map( 'absint', (array) get_option( 'custom_related_products_crp_exclude_widget_product', true ) ) );

				foreach ( $product_ids as $product_id ) {

					$product = wc_get_product( $product_id );

					if ( $product ) {
						echo '<option value="' . esc_attr( $product_id ) . '" selected="selected">' . esc_html( $product->get_formatted_name() ) . '</option>';
					}
				}
				?>
			</select>
		</fieldset>
		<?php
	}

	public function custom_related_products_crp_order_by() {

		$crp_order_by = get_option( $this->option_name . '_crp_order_by', 'popularity' );
		?>
		<fieldset class="crp-order-by">
		<select name="<?php echo esc_attr( $this->option_name . '_crp_order_by' ); ?>" id="<?php echo esc_attr( $this->option_name . '_crp_order_by' ); ?>" class="wt-crp-select">
				<option value="title"><?php _e( 'Product title', 'wt-woocommerce-related-products' ); ?></option>
				<option value="date" 
				<?php
				if ( $crp_order_by == 'date' ) {
					echo 'selected="selected"';}
				?>
					><?php _e( 'Date', 'wt-woocommerce-related-products' ); ?></option>
				<option value="name" 
				<?php
				if ( $crp_order_by == 'name' ) {
					echo 'selected="selected"';}
				?>
					><?php _e( 'Slug name', 'wt-woocommerce-related-products' ); ?></option>
				<option value="rand" 
				<?php
				if ( $crp_order_by == 'rand' ) {
					echo 'selected="selected"';}
				?>
					><?php _e( 'Random', 'wt-woocommerce-related-products' ); ?></option>
				<option value="modified" 
				<?php
				if ( $crp_order_by == 'modified' ) {
					echo 'selected="selected"';}
				?>
					><?php _e( 'Last modified', 'wt-woocommerce-related-products' ); ?></option>
				<option value="price" 
				<?php
				if ( $crp_order_by == 'price' ) {
					echo 'selected="selected"';}
				?>
					><?php _e( 'Price', 'wt-woocommerce-related-products' ); ?></option>
				<option value="popularity" 
				<?php
				if ( $crp_order_by == 'popularity' ) {
					echo 'selected="selected"';}
				?>
					><?php _e( 'Popularity', 'wt-woocommerce-related-products' ); ?></option>
				<option value="rating" 
				<?php
				if ( $crp_order_by == 'rating' ) {
					echo 'selected="selected"';}
				?>
					><?php _e( 'Avg rating', 'wt-woocommerce-related-products' ); ?></option>
				<option value="relevance" 
				<?php
				if ( $crp_order_by == 'relevance' ) {
					echo 'selected="selected"';}
				?>
					><?php _e( 'Relevance', 'wt-woocommerce-related-products' ); ?></option>
			</select>
		</fieldset>         
		<?php
	}

	public function custom_related_products_crp_order() {

		$crp_order_by = get_option( $this->option_name . '_crp_order', 'DESC' );
		?>
		<fieldset class="crp-order">
		<select name="<?php echo esc_attr( $this->option_name . '_crp_order' ); ?>" id="<?php echo esc_attr( $this->option_name . '_crp_order' ); ?>" class="wt-crp-select">
				<option value="ASC"><?php _e( 'Ascending', 'wt-woocommerce-related-products' ); ?></option>
				<option value="DESC" 
				<?php
				if ( $crp_order_by == 'DESC' ) {
					echo 'selected="selected"';}
				?>
					><?php _e( 'Descending', 'wt-woocommerce-related-products' ); ?></option>
			</select>
		</fieldset>         
		<?php
	}

	public function custom_related_products_crp_number() {

		$crp_number = get_option( $this->option_name . '_crp_number', 10 );
		?>
		<fieldset class="crp-number">
			<label>
				<input type="number" name="<?php echo esc_attr( $this->option_name . '_crp_number' ); ?>" id="<?php echo esc_attr( $this->option_name . '_crp_number' ); ?>" value="<?php echo esc_attr( absint( $crp_number ) ); ?>" class="wt-crp-input" min="4" required="required">
			</label>
			<p class="description"><?php _e( 'products.', 'wt-woocommerce-related-products' ); ?></p>
		</fieldset>         
		<?php
	}

	public function custom_related_products_exclude_os_cb() {

		$exclude_os = get_option( $this->option_name . '_exclude_os' );
		?>
		<fieldset class="crp-exclude-os">
			<label>
				<input type="checkbox" name="<?php echo esc_attr( $this->option_name . '_exclude_os' ); ?>" id="<?php echo esc_attr( $this->option_name . '_exclude_os' ); ?>" value="exclude_os" <?php checked( $exclude_os, 'exclude_os' ); ?>>
				<?php esc_html_e( 'Prevent out of stock products from being displayed in the widget', 'wt-woocommerce-related-products' ); ?>
			</label>
		</fieldset>         
		<?php
	}


	// Exclude Backorder
	public function custom_related_products_rp_exclude_backorder() {

		$rp_exclude_backorder = get_option( $this->option_name . '_rp_exclude_backorder' );
		?>
		<fieldset class="crp-exclude-backorder">
			<label>
				<input type="checkbox" name="<?php echo esc_attr( $this->option_name . '_rp_exclude_backorder' ); ?>" id="<?php echo esc_attr( $this->option_name . '_rp_exclude_backorder' ); ?>" value="rp_exclude_backorder" <?php checked( $rp_exclude_backorder, 'rp_exclude_backorder' ); ?>>
				<?php esc_html_e( 'Prevent backorder products from being displayed in the widget', 'wt-woocommerce-related-products' ); ?>
			</label>
		</fieldset>      
		<?php
	}

	public function custom_related_products_slider_cb() {

		$slider = get_option( $this->option_name . '_slider', 'enable' );
		?>
		<fieldset class="crp-slider">
			<label>
				<input type="checkbox" name="<?php echo esc_attr( $this->option_name . '_slider' ); ?>" id="<?php echo esc_attr( $this->option_name . '_slider' ); ?>" value="enable" <?php checked( $slider, 'enable' ); ?>>
				<?php esc_html_e( 'Showcase products in a slidable widget', 'wt-woocommerce-related-products' ); ?>
			</label>
		</fieldset>         
		<?php
	}

	public function custom_related_products_slider_type() {

		$slider = get_option( $this->option_name . '_slider_type', 'swiper' );
		?>
		<fieldset class="crp-slider">
			<select name="<?php echo esc_attr( $this->option_name . '_slider_type' ); ?>" id="<?php echo esc_attr( $this->option_name . '_slider_type' ); ?>" class="wt-crp-select">
				<option value="bx" 
				<?php
				if ( $slider == 'bx' ) {
					echo 'selected="selected"';}
				?>
					><?php _e( 'bxSlider', 'wt-woocommerce-related-products' ); ?></option>
				<option value="swiper" 
				<?php
				if ( $slider == 'swiper' ) {
					echo 'selected="selected"';}
				?>
					><?php _e( 'Swiper slider', 'wt-woocommerce-related-products' ); ?></option>
			</select>
			<p class="description"><?php _e( 'Try switching the slider type if any conflicts with the site theme.', 'wt-woocommerce-related-products' ); ?></p>
		</fieldset>  
		
			<div class="crp-banner wt-crp-info-box">
			<?php if ( $slider == 'bx' ) { ?>
					<p id="crp-slider-type"><?php printf( __( 'bxSlider is a fully-loaded, responsive jQuery content slider.%1$s Know more. %2$s', 'wt-woocommerce-related-products' ), '<a href="https://github.com/stevenwanderski/bxslider-4" target="_blank">', '</a>' ); ?></p>
				<?php } elseif ( $slider == 'swiper' ) { ?> 
					<p id="crp-slider-type"> <?php printf( __( 'Swiper - is the free and most modern mobile touch slider with hardware accelerated transitions and amazing native behavior.%1$s Know more. %2$s', 'wt-woocommerce-related-products' ), '<a href="https://github.com/nolimits4web/swiper" target="_blank">', '</a>' ); ?></p>
				<?php } ?> 

			</div>
		<?php
	}

	public function custom_related_products_use_primary_id_wpml_cb() {

		$primary_id_wpml = get_option( $this->option_name . '_use_primary_id_wpml' );
		?>
		<fieldset class="crp-wpml">
			<label>
				<input type="checkbox" name="<?php echo esc_attr( $this->option_name . '_use_primary_id_wpml' ); ?>" id="<?php echo esc_attr( $this->option_name . '_use_primary_id_wpml' ); ?>" value="enable" <?php checked( $primary_id_wpml, 'enable' ); ?>>
			</label>
			<p class="description">
				<?php _e( 'Enable to display related products for translated products based on original product ID.', 'wt-woocommerce-related-products' ); ?>
			</p>
			<p class="description wt-crp-note">
				<?php printf( __( '%1$s Note:%2$s Ensure that each product has a corresponding translated product in your site.', 'wt-woocommerce-related-products' ), '<b>', '</b>' ); ?>
			</p>
		</fieldset>         
		<?php
	}

	public function custom_related_products_overide_theme_rp_cb() {

		$overide_theme = get_option( $this->option_name . '_overide_theme_rp' );
		?>
		<fieldset class="crp-overide-theme">
			<label>
				<input type="checkbox"  name="<?php echo esc_attr( $this->option_name . '_overide_theme_rp' ); ?>" id="<?php echo esc_attr( $this->option_name . '_overide_theme_rp' ); ?>" value="enable" <?php checked( $overide_theme, 'enable' ); ?>>
			</label>
			<div class="crp-alert crp-warning-alert">
			<span>&#9888;</span>
				<?php _e( 'If disabled, the above settings may not be reflected in the front end.', 'wt-woocommerce-related-products' ); ?>
			</div>
		</fieldset>         
		<?php
	}

	public function custom_related_products_general_cb() {
		?>
		<!-- <p>
			<b><?php _e( 'Displays custom related products based on category, tag, attribute or product.', 'wt-woocommerce-related-products' ); ?></b>
		</p>
		<p>
			<a target="_blank" href="https://www.webtoffee.com/related-products-woocommerce-user-guide/"><?php _e( 'Read documentation', 'wt-woocommerce-related-products' ); ?></a>
		</p>
		<p style="border-top: 1px dashed rgb(204, 204, 204); padding-top: 5px; width: 95%;"></p>-->
		<?php
	}
	public function custom_related_products_info_bottom_cb() {
		?>
		<tr class="crp-tr-field">
			<td colspan="2" style="padding: 0px;">
				<div class="crp-alert crp-seconday-alert wt-crp-info">
					<?php
					echo wp_kses_post(
						sprintf(
							__( 'Use the shortcode %1$s[wt-related-products product_id=XX]%2$s to show related products on custom posts/pages.<br>Replace the %3$sXX%4$s placeholder with the product ID of the product you are basing the recommendation on.', 'wt-woocommerce-related-products' ),
							'<code>',
							'</code>',
							'<code>',
							'</code>'
						)
					);
					?>

				</div>
			</td>
		</tr>
		<?php
	}
	public function custom_related_products_advanced_settings_cb() {
		?>
		<div class="wt-crp-advanced-settings-toggle">
			<p style="font-size: 16px;">
				<b style="cursor: pointer;">
					<span class="dashicons dashicons-arrow-right wt-crp-arrow"></span> 
					<?php esc_html_e( 'Advanced', 'wt-woocommerce-related-products' ); ?>
				</b>
			</p>
			<p style="border-top: 1px dashed rgb(204, 204, 204); padding-top: 5px;"></p>
		</div>
		<?php
	}
	public function custom_related_products_widget_settings_cb() {
		?>
		<div class="wt-crp-widget-settings-toggle active">
			<p style="font-size: 16px;">
				<b style="cursor: pointer;">
					<span class="dashicons dashicons-arrow-down wt-crp-arrow"></span> 
					<?php esc_html_e( 'Widget Settings', 'wt-woocommerce-related-products' ); ?>
				</b>
			</p>
			<p style="border-top: 1px dashed rgb(204, 204, 204); padding-top: 5px;"></p>
		</div>
		<?php
	}

	/**
	 * Show action links on the plugin screen.
	 *
	 * @param mixed $links Plugin action links.
	 *
	 * @return array
	 */
	public function add_crp_action_links( $links ) {

		$plugin_links = array(
			'<a href="' . esc_url( admin_url( '/admin.php?page=wt-woocommerce-related-products' ) ) . '">' . __( 'Settings', 'wt-woocommerce-related-products' ) . '</a>',
			'<a target="_blank" href="https://www.webtoffee.com/related-products-woocommerce-user-guide/">' . __( 'Documentation', 'wt-woocommerce-related-products' ) . '</a>',
			'<a target="_blank" href="https://wordpress.org/support/plugin/wt-woocommerce-related-products/">' . __( 'Support', 'wt-woocommerce-related-products' ) . '</a>',
			'<a target="_blank" href="https://wordpress.org/support/plugin/wt-woocommerce-related-products/reviews#new-post">' . __( 'Review', 'wt-woocommerce-related-products' ) . '</a>',
		);
		if ( array_key_exists( 'deactivate', $links ) ) {
			$links['deactivate'] = str_replace( '<a', '<a class="relatedproducts-deactivate-link"', $links['deactivate'] );
		}
		return array_merge( $plugin_links, $links );
	}

	/**
	 * Show row meta on the plugin screen.
	 *
	 * @param mixed $links Plugin Row Meta.
	 * @param mixed $file  Plugin Base file.
	 *
	 * @return array
	 */
	public static function add_crp_plugin_row_meta( $links, $file ) {
		if ( WT_CRP_BASE_NAME !== $file ) {
			return $links;
		}

		$row_meta = array(
			'support' => '<a target="_blank" href="' . esc_url( apply_filters( 'wt_crp_community_support_url', 'https://wordpress.org/support/plugin/wt-woocommerce-related-products/' ) ) . '" aria-label="' . esc_attr__( 'Visit support forums', 'wt-woocommerce-related-products' ) . '">' . esc_html__( 'Support', 'wt-woocommerce-related-products' ) . '</a>',
			'export'  => '<a target="_blank" href="' . esc_url( apply_filters( 'wt_crp_export_url', 'https://www.webtoffee.com/how-to-export-related-products-using-woocommerce-export/' ) ) . '" aria-label="' . esc_attr__( 'Export Related Products', 'wt-woocommerce-related-products' ) . '">' . esc_html__( 'Export', 'wt-woocommerce-related-products' ) . '</a>',
			'import'  => '<a target="_blank" href="' . esc_url( apply_filters( 'wt_crp_import_url', 'https://www.webtoffee.com/how-to-import-related-products-using-woocommerce-importer/' ) ) . '" aria-label="' . esc_attr__( 'Import Related Products', 'wt-woocommerce-related-products' ) . '">' . esc_html__( 'Import', 'wt-woocommerce-related-products' ) . '</a>',
		);

		return array_merge( $links, $row_meta );
	}

	/**
	 * Insert scripts for the main settings page
	 *
	 * @since 1.3.9
	 * @return void
	 */
	public function insert_main_settings_scripts() {
		$slide_width = get_option( 'custom_related_products_crp_banner_width' ) ? get_option( 'custom_related_products_crp_banner_width' ) : 300;
		?>
		<script>
			jQuery(document).ready(function() {
				disallow_options_by_working_mode();
				function disallow_options_by_working_mode() {
					var working_mode = jQuery('input[name="custom_related_products_working_mode"]:checked').val();
					if( working_mode == 'default' ) {
						remove_disable_mode_restrictions();
						add_default_mode_restrictions();
					}else if( working_mode == 'disable' ) {
						remove_default_mode_restrictions();
						add_disable_mode_restrictions();
					}else {
						// working mode is custom
						remove_disable_mode_restrictions();
						remove_default_mode_restrictions();   
					}
				}
				function add_disable_mode_restrictions() {
					jQuery('.crp-tr-field th label,.crp-tr-field fieldset, .crp-banner, .wt-crp-info').addClass("crp-disallow").prop('disabled', true);
					jQuery('.crp_related_by_search').prop('disabled', true);
					jQuery('.wt-crp-advanced-settings-toggle, .wt-crp-widget-settings-toggle').addClass("crp-toggle-disallow");
				}
				function remove_disable_mode_restrictions() {
					jQuery('.crp-tr-field .crp-disallow, .crp-banner, .wt-crp-info').removeClass("crp-disallow").prop('disabled', false);
					jQuery('.wt-crp-advanced-settings-toggle, .wt-crp-widget-settings-toggle').removeClass("crp-toggle-disallow");
				}
				function add_default_mode_restrictions() {
					jQuery('.mode-default-disallow th label,.mode-default-disallow fieldset, .crp-banner, .wt-crp-info').addClass("crp-disallow").prop('disabled', true);
					jQuery('.crp_related_by_search').prop('disabled', true);
					jQuery('.wt-crp-advanced-settings-toggle').addClass("crp-toggle-disallow");
				}
				function remove_default_mode_restrictions() {
					jQuery('.mode-default-disallow .crp-disallow, .crp-banner, .wt-crp-info').removeClass("crp-disallow").prop('disabled', false);
					jQuery('.crp_related_by_search').prop('disabled', false);
				}
				
				jQuery('input[name="custom_related_products_working_mode"]').on('change', function() {
					disallow_options_by_working_mode();
				});

				jQuery('form').submit(function(e) {                   
					jQuery('.mode-default-disallow .crp-disallow').prop('disabled', false);
					jQuery('.crp-tr-field .crp-disallow').prop('disabled', false);
					jQuery('.crp_related_by_search').prop('disabled', false);
				});

				if( jQuery('#custom_related_products_overide_theme_rp').is(":checked") ) {
					jQuery('.crp-overide-theme .crp-alert').hide();
				}else {
					jQuery('.crp-overide-theme .crp-alert').show();
				}
				if( jQuery('#custom_related_products_slider').is(":checked") ) {
					jQuery('.wt-slider-mode').show();
					jQuery('.wt-with-slider').show();
					jQuery('.wt-with-out-slider').hide();
				}else {
					jQuery('.wt-slider-mode').hide();                   
					jQuery('.wt-with-slider').hide();
					jQuery('.wt-with-out-slider').show();
					jQuery("label[for='custom_related_products_crp_banner_product_width']").text("Number of products to display on a page");
				}
				jQuery("#custom_related_products_slider").on('change', function() {
					if( jQuery('#custom_related_products_slider').is(":checked") ) {
						jQuery('.wt-slider-mode').show();
						jQuery('.wt-with-slider').show();
						jQuery('.wt-with-out-slider').hide();
						jQuery("label[for='custom_related_products_crp_banner_product_width']").text("Number of products to display on slider per page");
					}else {
						jQuery('.wt-slider-mode').hide();                   
						jQuery('.wt-with-slider').hide();
						jQuery('.wt-with-out-slider').show();
						jQuery("label[for='custom_related_products_crp_banner_product_width']").text("Number of products to display on a page");
					}
				});
				var check = jQuery('.crp-related-by #custom_related_products_category , .crp-related-by #custom_related_products_tag');               
				/* Display Exclude widget section */
				check.on('click', function() {
					var is_checked = check.is(":checked");
					if(is_checked) {
						jQuery('.wt-gloablly-relate').show();   
						jQuery('#custom_related_products_crp_order_by option[value="relevance"]').show();
					} else {
						jQuery('.wt-gloablly-relate').hide();
						jQuery('#custom_related_products_crp_order_by option[value="relevance"]').hide();

						var select = jQuery('#custom_related_products_crp_order_by');
						if (select.val() === 'relevance') {
							select.val('popularity'); // fallback default
						} 
					}
				});

				if(check.is(":checked")) {
					jQuery('.wt-gloablly-relate').show();   
				} else {
					jQuery('.wt-gloablly-relate').hide();
				}
				
				var sub_cat_check = jQuery('.crp-related-by #custom_related_products_category');               
				sub_cat_check.on('click', function() {
					var is_checked = sub_cat_check.is(":checked");
					if(is_checked) {
						jQuery(".crp-sub-cat").show();   
					} else {
						jQuery(".crp-sub-cat").hide();
					}
				});
				
				jQuery("#custom_related_products_slider_type").on('change', function() {
					var opt = jQuery("#custom_related_products_slider_type :selected").val();
					if(opt == 'bx'){
						jQuery('#crp-slider-type').html('bxSlider is a fully-loaded, responsive jQuery content slider.<a href="https://github.com/stevenwanderski/bxslider-4" target="_blank"> Know more. </a>');
					}else if(opt == 'swiper'){
						jQuery('#crp-slider-type').html('Swiper - is the free and most modern mobile touch slider with hardware accelerated transitions and amazing native behavior.<a href="https://github.com/nolimits4web/swiper" target="_blank"> Know more. </a>');
					}
				});  
				const $sortByDropdown = jQuery('#custom_related_products_crp_order_by');
				const $sortOrderRow = jQuery('#custom_related_products_crp_order').closest('tr');

				// Function to toggle visibility of the "Sort order" field
				function toggleSortOrderVisibility() {
					if ('relevance' === $sortByDropdown.val() || 'rand' === $sortByDropdown.val()) {
						$sortOrderRow.hide(); // Hide the sort order field
					} else {
						$sortOrderRow.show(); // Show the sort order field
					}
				}

				// Initial check on page load
				toggleSortOrderVisibility();

				// Event listener for changes in the "Sort by" dropdown
				$sortByDropdown.on('change', toggleSortOrderVisibility);
			});
		</script>

		<?php
	}


	/**
	 * Search for attributes and return json.
	 */
	public static function wt_crp_ajax_attribute_search() {

		check_ajax_referer( 'ajax_search_nonce' );

		$search_term = isset( $_POST['term'] ) ? (string) sanitize_text_field( wp_unslash( $_POST['term'] ) ) : '';

		if ( empty( $search_term ) ) {
				wp_die();
		}
		// TODO: Implement filtered list - as of now all attributes are taken on search

		$attr_taxonomies = function_exists( 'wc_get_attribute_taxonomies' ) ? wc_get_attribute_taxonomies() : array();
		$attributes_list = array();
		if ( ! empty( $attr_taxonomies ) ) {
			foreach ( $attr_taxonomies as $attr ) {
				$terms = get_terms(
					array(
						'taxonomy'   => 'pa_' . $attr->attribute_name,
						'hide_empty' => false,
					)
				);
				foreach ( $terms as $term ) {
					$attributes_list[] = array(
						'id'   => $attr->attribute_name . ':' . $term->term_id,
						'text' => $attr->attribute_label . ':' . $term->name,
					);
				}
			}
		}

		wp_send_json( apply_filters( 'wt_json_search_found_attrs', $attributes_list ) );
	}

	/**
	 * process related attribute data before storing to db
	 *
	 * @since 1.4.0
	 * @return array
	 */
	public function process_related_attr_data( $related_attr ) {
		$attr_data = array();
		foreach ( $related_attr as $attr ) {
			$exploded = explode( ':', $attr );
			if ( ! empty( $exploded[0] ) && ! empty( $exploded[1] ) ) {
				$attr_data[ $exploded[0] ][] = $exploded[1];
			}
		}

		return $attr_data;
	}

	/**
	 * Display branding section
	 *
	 * @since 1.4.1
	 * @return void
	 */
	public function crp_display_branding() {
		$webtoffee_logo_url = CRP_PLUGIN_URL . 'admin/img/wt_logo.png';
		?>
		<div class="wt_crp_branding">
			<div class="wt_crp_brand_label">
				<?php _e( 'Related Products for WooCommerce | Developed by', 'wt-woocommerce-related-products' ); ?>
			</div>
			<div class="wt_crp_brand_logo">
				<a href="https://www.webtoffee.com/" target="_blank"><img src="<?php echo esc_url( $webtoffee_logo_url ); ?>"></a>
			</div>
		</div>
		<?php
	}

	public function custom_related_products_cart_working_mode_cb() {

		$include_cart = get_option( 'custom_related_products_cart_working_mode', '' );
		?>
		<fieldset class="include-cart">
			<label>
				<input type="checkbox" name="<?php echo esc_attr( $this->option_name . '_cart_working_mode' ); ?>" id="<?php echo esc_attr( $this->option_name . '_cart_working_mode' ); ?>" value="cart_mode" <?php checked( $include_cart, 'cart_mode' ); ?>>
				<?php esc_html_e( 'Display ‘Related products’ widget on the cart page', 'wt-woocommerce-related-products' ); ?>
			</label>
		</fieldset>         
		<?php
	}

	/**
	 * Registers admin modules
	 *
	 * @since 1.4.2
	 */
	public function admin_modules() {
		foreach ( $this->modules as $module ) {
			$module_file = plugin_dir_path( __FILE__ ) . "modules/$module/$module.php";
			if ( file_exists( $module_file ) ) {
				self::$existing_modules[] = $module; // this is for module_exits checking
				require_once $module_file;
			}
		}
	}

	/**
	 * Option for Custom slider Arrow
	 *
	 * @since 1.5.1
	 */
	public function custom_related_products_crp_custom_slider_arrow() {

		$crp_custom_slider_arrow = get_option( $this->option_name . '_crp_custom_slider_arrow' );
		?>
				<fieldset class="crp-custom-slider-arrow" style="display: flex; align-items: center; gap: 5px;">
			<input type="checkbox" name="<?php echo esc_attr( $this->option_name . '_crp_custom_slider_arrow' ); ?>" id="<?php echo esc_attr( $this->option_name . '_crp_custom_slider_arrow' ); ?>" value="slider_arrow" <?php checked( $crp_custom_slider_arrow, 'slider_arrow' ); ?>>
			<label for="<?php echo esc_attr( $this->option_name . '_crp_custom_slider_arrow' ); ?>">
				<?php esc_html_e( 'Use this option if you encounter issues in displaying the default arrow icons on the slider', 'wt-woocommerce-related-products' ); ?>
			</label>
		</fieldset>
  
		<?php
	}

	/**
	 * Add screen id
	 *
	 * @since 1.5.3
	 */
	public function set_wc_screen_ids( $screen ) {
		$screen[] = 'woocommerce_page_wt-woocommerce-related-products';
		return $screen;
	}
}
