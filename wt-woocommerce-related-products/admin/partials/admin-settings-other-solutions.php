<?php
/**
 * "You May Also Need" (Other Solutions) admin tab.
 *
 * @package WooCommerce Related Products
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

$img_base = esc_url( CRP_PLUGIN_URL . 'admin/img/other_solutions' );

$categories = array(
	'ecommerce-promotions' => array(
		'label'    => __( 'E-commerce Promotions', 'wt-woocommerce-related-products' ),
		'subtitle' => __( 'Create and run successful promotional campaigns with the best marketing tools for WooCommerce', 'wt-woocommerce-related-products' ),
		'icon'  => 'sidebar-ecommerce-promotions.svg',
		'hero'  => null,
		'plugins' => array(
			array(
				'type'     => 'standard',
				'name'     => __( 'Smart Coupons for WooCommerce', 'wt-woocommerce-related-products' ),
				'icon'     => 'smart-coupons-plugin.png',
				'rating'   => '4.9',
				'features' => array(
					__( 'Advanced BOGO Coupons', 'wt-woocommerce-related-products' ),
					__( 'Offer store credits', 'wt-woocommerce-related-products' ),
					__( 'Create attractive gift cards', 'wt-woocommerce-related-products' ),
				),
				'url'      => 'https://www.webtoffee.com/product/smart-coupons-for-woocommerce/?utm_source=other_solution_page&utm_medium=free_plugin&utm_campaign=smart_coupons',
			),
			array(
				'type'     => 'standard',
				'name'     => __( 'URL Coupons for WooCommerce', 'wt-woocommerce-related-products' ),
				'icon'     => 'url-coupons-plugin.png',
				'rating'   => '5.0',
				'features' => array(
					__( 'Generate custom coupon URLs', 'wt-woocommerce-related-products' ),
					__( 'Set up a redirect page', 'wt-woocommerce-related-products' ),
					__( 'Automatically add products', 'wt-woocommerce-related-products' ),
				),
				'url'      => 'https://www.webtoffee.com/product/url-coupons-for-woocommerce/?utm_source=other_solution_page&utm_medium=free_plugin&utm_campaign=URL_Coupons',
			),
			array(
				'type'     => 'standard',
				'name'     => __( 'WooCommerce Product Recommendations', 'wt-woocommerce-related-products' ),
				'icon'     => 'product-recommendation-plugin.png',
				'rating'   => '5.0',
				'features' => array(
					__( 'Automatically generate suggestions based on order history', 'wt-woocommerce-related-products' ),
					__( 'Display recommended products on the product pages', 'wt-woocommerce-related-products' ),
					__( 'Multiple product recommendation templates', 'wt-woocommerce-related-products' ),
				),
				'url'      => 'https://www.webtoffee.com/product/woocommerce-product-recommendations/?utm_source=other_solution_page&utm_medium=free_plugin&utm_campaign=Product_Recommendations',
			),
			array(
				'type'     => 'standard',
				'name'     => __( 'WooCommerce Coupon Generator', 'wt-woocommerce-related-products' ),
				'icon'     => 'coupon-generator-plugin.png',
				'rating'   => '5.0',
				'features' => array(
					__( 'Bulk generate WooCommerce coupons', 'wt-woocommerce-related-products' ),
					__( 'Bulk export WooCommerce coupons to CSV', 'wt-woocommerce-related-products' ),
					__( 'Add usage restrictions to coupons', 'wt-woocommerce-related-products' ),
				),
				'url'      => 'https://www.webtoffee.com/product/woocommerce-coupon-generator/?utm_source=other_solution_page&utm_medium=free_plugin_related_products&utm_campaign=Coupon_Generator',
			),
			array(
				'type'      => 'standard-with-image',
				'name'      => __( 'WooCommerce Gift Cards', 'wt-woocommerce-related-products' ),
				'icon'      => 'gift-card-plugin.png',
				'rating'    => 'stars',
				'features'  => array(
					__( 'Create unlimited gift cards', 'wt-woocommerce-related-products' ),
					__( 'Email gift cards to customers', 'wt-woocommerce-related-products' ),
					__( 'Provide refunds to store credit', 'wt-woocommerce-related-products' ),
					__( '20+ predefined gift card templates', 'wt-woocommerce-related-products' ),
				),
				'url'       => 'https://www.webtoffee.com/product/woocommerce-gift-cards/?utm_source=other_solution_page&utm_medium=free_plugin&utm_campaign=WooCommerce_Gift_Cards',
				'image_src' => 'gift-cards-illustration.png',
			),
		),
		'standalone' => array(
			'name'       => __( 'ECommerce Marketing Automation App', 'wt-woocommerce-related-products' ),
			'icon'       => 'ema-app-plugin.png',
			'desc'       => __( 'Create signup forms, popups, and automated email campaigns with pre-built workflow templates to capture leads, recover abandoned carts, and grow sales.', 'wt-woocommerce-related-products' ),
			'screenshot' => 'ema-screenshot.svg',
			'url'        => 'https://www.webtoffee.com/product/ecommerce-marketing-automation/?utm_source=other_solution_page&utm_medium=free_plugin_related_products&utm_campaign=EMA',
		),
		'bundle' => array(
			'tag_emoji'    => '📣',
			'tag_color'    => 'yellow',
			'tag'          => __( 'Promotion Bundle', 'wt-woocommerce-related-products' ),
			'title'        => __( 'WooCommerce Promotion Bundle', 'wt-woocommerce-related-products' ),
			'url'          => 'https://www.webtoffee.com/woocommerce-promotions/?utm_source=other_solution_page&utm_medium=free_plugin_related_products&utm_campaign=Promotion_Bundle',
			'desc'         => __( 'Make powerful promotional campaigns with our WooCommerce promotion bundle. Create coupon promotions, set up gift cards, and implement popular product recommendation strategies.', 'wt-woocommerce-related-products' ),
			'pills'        => array(
				__( 'Smart Coupons', 'wt-woocommerce-related-products' ),
				__( 'Product recommendation', 'wt-woocommerce-related-products' ),
				__( 'Gift cards', 'wt-woocommerce-related-products' ),
			),
			'price_orig'   => '$277',
			'price_sale'   => '$194',
			'savings'      => __( 'Save up to 30% off', 'wt-woocommerce-related-products' ),
			'illustration' => 'promotion-bundle.png',
		),
	),
	'privacy-compliance' => array(
		'label'    => __( 'Privacy Compliance', 'wt-woocommerce-related-products' ),
		'subtitle' => __( 'Ensure compliance with major cookie laws, including, GDPR, CCPA, LGPD, CNIL, and more', 'wt-woocommerce-related-products' ),
		'icon'  => 'sidebar-privacy-compliance.svg',
		'hero'  => array(
			'name'     => __( 'GDPR Cookie Consent Plugin (CCPA Ready)', 'wt-woocommerce-related-products' ),
			'icon'     => 'gdpr-plugin.png',
			'rating'   => 'stars',
			'image'    => 'cookie-consent.svg',
			'desc'     => __( 'This Google-certified CMP lets you create a customizable cookie banner, manage user consent, and ensure global privacy compliance with automatic script blocking.', 'wt-woocommerce-related-products' ),
			'features' => array(),
			'url'      => 'https://www.webtoffee.com/product/gdpr-cookie-consent/?utm_source=other_solution_page&utm_medium=_free_plugin_&utm_campaign=GDPR',
		),
		'plugins'    => array(),
		'standalone' => null,
		'bundle'     => null,
	),
	'data-import-export' => array(
		'label'    => __( 'Data Import & Export', 'wt-woocommerce-related-products' ),
		'subtitle' => __( 'The best-in-class import, export, and migration solutions for your WooCommerce data', 'wt-woocommerce-related-products' ),
		'icon'  => 'sidebar-data-import-export.svg',
		'hero'  => null,
		'plugins' => array(
			array(
				'type'     => 'standard',
				'name'     => __( 'Product Import Export Plugin', 'wt-woocommerce-related-products' ),
				'icon'     => 'product-ie-plugin.png',
				'rating'   => '4.9',
				'features' => array(
					__( 'Supports Excel, XML, CSV and TSV file formats', 'wt-woocommerce-related-products' ),
					__( 'Schedule automated import and export', 'wt-woocommerce-related-products' ),
					__( 'Support for multiple product types', 'wt-woocommerce-related-products' ),
				),
				'url'      => 'https://www.webtoffee.com/product/product-import-export-woocommerce/?utm_source=other_solution_page&utm_medium=free_plugin&utm_campaign=Product_Import_Export',
			),
			array(
				'type'     => 'standard',
				'name'     => __( 'Order, Coupon, Subscription Export Import', 'wt-woocommerce-related-products' ),
				'icon'     => 'order-ie-plugin.png',
				'rating'   => '4.6',
				'features' => array(
					__( 'Supports Excel, XML, CSV and TSV file formats', 'wt-woocommerce-related-products' ),
					__( 'Schedule automated import and export', 'wt-woocommerce-related-products' ),
					__( 'Email customers on order status change', 'wt-woocommerce-related-products' ),
				),
				'url'      => 'https://www.webtoffee.com/product/order-import-export-plugin-for-woocommerce/?utm_source=other_solution_page&utm_medium=free_plugin&utm_campaign=Order_Import_Export',
			),
			array(
				'type'     => 'standard',
				'name'     => __( 'User Import Export Plugin', 'wt-woocommerce-related-products' ),
				'icon'     => 'user-ie-plugin.png',
				'rating'   => '5.0',
				'features' => array(
					__( 'Supports Excel, XML, CSV and TSV file formats', 'wt-woocommerce-related-products' ),
					__( 'Schedule automated import and export', 'wt-woocommerce-related-products' ),
					__( 'Customize and send emails to new users on import', 'wt-woocommerce-related-products' ),
				),
				'url'      => 'https://www.webtoffee.com/product/wordpress-users-woocommerce-customers-import-export/?utm_source=other_solution_page&utm_medium=free_plugin&utm_campaign=User_Import_Export',
			),
			array(
				'type'     => 'standard',
				'name'     => __( 'Product Feed & Sync Manager', 'wt-woocommerce-related-products' ),
				'icon'     => 'product-feed-plugin.png',
				'rating'   => '5.0',
				'features' => array(
					__( 'Generate WooCommerce product feeds for Google Shopping, Facebook Shop, and Instagram Shop', 'wt-woocommerce-related-products' ),
					__( 'Supports 25+ sales channels and marketplaces', 'wt-woocommerce-related-products' ),
				),
				'url'      => 'https://www.webtoffee.com/product/woocommerce-product-feed/?utm_source=other_solution_page&utm_medium=free_plugin_related_products&utm_campaign=WooCommerce_Product_Feed',
			),
			array(
				'type'      => 'standard-with-image',
				'name'      => __( 'Import Export Suite for WooCommerce', 'wt-woocommerce-related-products' ),
				'icon'      => 'ie-suite-plugin.png',
				'rating'    => 'stars',
				'features'  => array(
					__( 'Import/export Products, Orders, Subscriptions, Coupons, Customers, WordPress Users, Categories & Tags, Reviews', 'wt-woocommerce-related-products' ),
					__( 'Supports Excel, XML, CSV and TSV file formats', 'wt-woocommerce-related-products' ),
				),
				'url'       => 'https://www.webtoffee.com/product/woocommerce-import-export-suite/?utm_source=other_solution_page&utm_medium=free_plugin&utm_campaign=Import_Export_Suite',
				'image_src' => 'data-io-illustration.png',
			),
		),
		'standalone' => null,
		'bundle'     => null,
	),
	'accounting-invoicing' => array(
		'label'    => __( 'Accounting & Invoicing', 'wt-woocommerce-related-products' ),
		'subtitle' => __( 'Automatically generate professional WooCommerce invoices and documents for all your orders', 'wt-woocommerce-related-products' ),
		'icon'  => 'sidebar-accounting-invoicing.svg',
		'hero'  => array(
			'name'     => __( 'PDF Invoices, Packing Slips, & Credit Notes', 'wt-woocommerce-related-products' ),
			'icon'     => 'pdf-invoices-plugin.png',
			'rating'   => 'stars',
			'image'    => 'pdf-invoices-screenshot.png',
			'desc'     => __( 'Automatically generate, customize, and manage professional WooCommerce invoices, packing slips, and credit notes with advanced automation and tax compliance features.', 'wt-woocommerce-related-products' ),
			'features' => array(),
			'url'      => 'https://www.webtoffee.com/product/woocommerce-pdf-invoices-packing-slips/?utm_source=other_solution_page&utm_medium=free_plugin&utm_campaign=PDF_invoice',
		),
		'plugins' => array(
			array(
				'type'     => 'standard',
				'name'     => __( 'Shipping Labels, Dispatch Labels, & Delivery Notes', 'wt-woocommerce-related-products' ),
				'icon'     => 'shipping-labels-plugin.png',
				'rating'   => '5.0',
				'features' => array(
					__( 'Create delivery notes, shipping & dispatch labels', 'wt-woocommerce-related-products' ),
					__( 'Enable customers to print the documents from order emails', 'wt-woocommerce-related-products' ),
					__( 'Customize shipping label size', 'wt-woocommerce-related-products' ),
				),
				'url'      => 'https://www.webtoffee.com/product/woocommerce-shipping-labels-delivery-notes/?utm_source=other_solution_page&utm_medium=free_plugin&utm_campaign=Shipping_Label',
			),
			array(
				'type'     => 'standard',
				'name'     => __( 'WooCommerce Picklists plugin', 'wt-woocommerce-related-products' ),
				'icon'     => 'picklists-plugin.png',
				'rating'   => '4.0',
				'features' => array(
					__( 'Bulk print picklists from the admin order page', 'wt-woocommerce-related-products' ),
					__( 'Automatically email picklists based on order status', 'wt-woocommerce-related-products' ),
					__( 'Create or customize picklist templates', 'wt-woocommerce-related-products' ),
				),
				'url'      => 'https://www.webtoffee.com/product/woocommerce-picklist/?utm_source=other_solution_page&utm_medium=free_plugin&utm_campaign=Picklist',
			),
			array(
				'type'     => 'standard',
				'name'     => __( 'Customizer for WooCommerce PDF Invoices', 'wt-woocommerce-related-products' ),
				'icon'     => 'pdf-customizer-plugin.png',
				'rating'   => '5.0',
				'features' => array(
					__( 'Drag-and-drop easy customization', 'wt-woocommerce-related-products' ),
					__( 'Customize individual elements using block editors', 'wt-woocommerce-related-products' ),
					__( 'Advanced visual and code editor', 'wt-woocommerce-related-products' ),
				),
				'url'      => 'https://www.webtoffee.com/product/customizer-for-woocommerce-pdf-invoice/?utm_source=other_solution_page&utm_medium=free_plugin_related_products&utm_campaign=PDF_Customizer',
			),
			array(
				'type'     => 'standard',
				'name'     => __( 'WooCommerce Address Labels plugin', 'wt-woocommerce-related-products' ),
				'icon'     => 'address-labels-plugin.png',
				'rating'   => '5.0',
				'features' => array(
					__( 'Generate \'Shipping Address\', \'Billing Address\', \'From Address\', and \'Return Address\' labels', 'wt-woocommerce-related-products' ),
					__( 'Customize label sizes', 'wt-woocommerce-related-products' ),
					__( 'Bulk print address labels', 'wt-woocommerce-related-products' ),
				),
				'url'      => 'https://www.webtoffee.com/product/woocommerce-address-label/?utm_source=other_solution_page&utm_medium=free_plugin&utm_campaign=Address_Label',
			),
			array(
				'type'     => 'standard',
				'name'     => __( 'Proforma Invoice', 'wt-woocommerce-related-products' ),
				'icon'     => 'proforma-invoice-plugin.png',
				'rating'   => '5.0',
				'features' => array(
					__( 'Create proforma invoices automatically', 'wt-woocommerce-related-products' ),
					__( 'Pre-built proforma invoice layouts', 'wt-woocommerce-related-products' ),
					__( 'Attach proforma invoice PDF to order emails', 'wt-woocommerce-related-products' ),
				),
				'url'      => 'https://www.webtoffee.com/product/woocommerce-proforma-invoice/?utm_source=other_solution_page&utm_medium=free_plugin&utm_campaign=Proforma_Invoice',
			),
			array(
				'type'     => 'standard',
				'name'     => __( 'QR Code Add-on for WooCommerce PDF Invoices', 'wt-woocommerce-related-products' ),
				'icon'     => 'qr-code-plugin.png',
				'rating'   => '5.0',
				'features' => array(
					__( 'Assign QR codes to all generated invoices', 'wt-woocommerce-related-products' ),
					__( 'Create QR code that reads order or invoice number', 'wt-woocommerce-related-products' ),
					__( 'Add custom data to invoices', 'wt-woocommerce-related-products' ),
				),
				'url'      => 'https://www.webtoffee.com/product/qr-code-addon-for-woocommerce-pdf-invoices/?utm_source=other_solution_page&utm_medium=free_plugin_related_products&utm_campaign=QR_Code',
			),
			array(
				'type'     => 'standard',
				'name'     => __( 'WooCommerce Request a Quote', 'wt-woocommerce-related-products' ),
				'icon'     => 'request-quote-plugin.png',
				'rating'   => '5.0',
				'features' => array(
					__( 'Add quote button to the product & shop pages', 'wt-woocommerce-related-products' ),
					__( 'Enable quotation request for selected products', 'wt-woocommerce-related-products' ),
					__( 'Automatically send quotes to users', 'wt-woocommerce-related-products' ),
				),
				'url'      => 'https://www.webtoffee.com/product/woocommerce-request-a-quote/?utm_source=other_solution_page&utm_medium=free_plugin_related_products&utm_campaign=Request_Quote',
			),
			array(
				'type'     => 'standard',
				'name'     => __( 'Sequential Order Numbers', 'wt-woocommerce-related-products' ),
				'icon'     => 'sequential-orders-plugin.png',
				'rating'   => '5.0',
				'features' => array(
					__( 'Auto reset sequence per month/year etc', 'wt-woocommerce-related-products' ),
					__( 'Add a custom suffix for order numbers', 'wt-woocommerce-related-products' ),
					__( 'Date suffix in order numbers', 'wt-woocommerce-related-products' ),
				),
				'url'      => 'https://www.webtoffee.com/product/woocommerce-sequential-order-numbers/?utm_source=other_solution_page&utm_medium=free_plugin&utm_campaign=Sequential_Order_Numbers',
			),
			array(
				'type' => 'image',
				'src'  => 'seq-orders-illustration.png',
			),
		),
		'standalone' => null,
		'bundle' => array(
			'tag_emoji'    => '📄',
			'tag_color'    => 'green',
			'tag'          => __( 'Invoice Bundle', 'wt-woocommerce-related-products' ),
			'title'        => __( 'All in one Invoice bundle', 'wt-woocommerce-related-products' ),
			'url'          => 'https://www.webtoffee.com/pdf-invoices-packing-slips-suite-woocommerce/?utm_source=other_solution_page&utm_medium=free_plugin_related_products&utm_campaign=Invoice_bundle',
			'desc'         => __( 'A complete suite of invoices and shipping documents bundle to create and print PDF invoices, packing slips, shipping and delivery documents in WooCommerce.', 'wt-woocommerce-related-products' ),
			'pills'        => array(
				__( 'Invoice', 'wt-woocommerce-related-products' ),
				__( 'Packing Slip', 'wt-woocommerce-related-products' ),
				__( 'Address Labels', 'wt-woocommerce-related-products' ),
				__( 'Dispatch Labels', 'wt-woocommerce-related-products' ),
				__( 'Shipping Labels', 'wt-woocommerce-related-products' ),
				__( 'Delivery Notes', 'wt-woocommerce-related-products' ),
				__( 'Picklists', 'wt-woocommerce-related-products' ),
				__( 'Proforma Invoice', 'wt-woocommerce-related-products' ),
			),
			'price_orig'   => '$279',
			'price_sale'   => '$179',
			'savings'      => __( 'Save up to 30% off', 'wt-woocommerce-related-products' ),
			'illustration' => 'invoice-bundle.png',
		),
	),
);

$first_category = array_key_first( $categories );
?>

<div class="wt-crp-os-page">

	<?php
	$first_cat        = $categories[ $first_category ];
	?>
	<div class="wt-crp-os-header">
		<h1 class="wt-crp-os-page-title" id="wt-crp-os-cat-title"><?php echo esc_html( $first_cat['label'] ); ?></h1>
		<p class="wt-crp-os-page-subtitle" id="wt-crp-os-cat-subtitle"><?php echo esc_html( $first_cat['subtitle'] ); ?></p>
	</div>

	<div class="wt-crp-os-layout">

		<?php /* ---- Sidebar ---- */ ?>
		<div class="wt-crp-os-sidebar">
			<ul class="wt-crp-os-sidebar-nav">
				<?php foreach ( $categories as $cat_id => $cat ) : ?>
					<li>
						<a href="#"
							class="wt-crp-os-cat-link<?php echo ( $cat_id === $first_category ) ? ' active' : ''; ?>"
							data-category="<?php echo esc_attr( $cat_id ); ?>">
							<img class="wt-crp-os-cat-icon"
								src="<?php echo esc_url( $img_base . '/' . $cat['icon'] ); ?>"
								alt="">
							<?php echo esc_html( $cat['label'] ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>

			<div class="wt-crp-os-trust-badges">
				<div class="wt-crp-os-trust-badge">
					<img src="<?php echo esc_url( $img_base . '/thirty-day-guarantee.png' ); ?>"
						alt="<?php esc_attr_e( '30 Day Money Back Guarantee', 'wt-woocommerce-related-products' ); ?>">
					<span><?php esc_html_e( '30 Day No Risk Money Back Guarantee', 'wt-woocommerce-related-products' ); ?></span>
				</div>
				<div class="wt-crp-os-trust-badge">
					<img src="<?php echo esc_url( $img_base . '/satisfaction-badge.png' ); ?>"
						alt="<?php esc_attr_e( '99% Satisfaction Rating', 'wt-woocommerce-related-products' ); ?>">
					<span><?php esc_html_e( 'Fast Support with 99% Satisfaction Rating', 'wt-woocommerce-related-products' ); ?></span>
				</div>
			</div>
		</div>

		<?php /* ---- Main content ---- */ ?>
		<div class="wt-crp-os-main">

			<?php foreach ( $categories as $cat_id => $cat ) : ?>
				<div id="wt-crp-os-panel-<?php echo esc_attr( $cat_id ); ?>"
					class="wt-crp-os-category-panel<?php echo ( $cat_id === $first_category ) ? ' active' : ''; ?>"
					data-title="<?php echo esc_attr( $cat['label'] ); ?>"
					data-subtitle="<?php echo esc_attr( $cat['subtitle'] ); ?>">

					<?php /* -- Hero card -- */ ?>
					<?php if ( ! empty( $cat['hero'] ) ) : $hero = $cat['hero']; ?>
						<div class="wt-crp-os-hero-card">
							<div class="wt-crp-os-hero-left">
								<div class="wt-crp-os-hero-title-row">
									<img class="wt-crp-os-hero-icon"
										src="<?php echo esc_url( $img_base . '/' . $hero['icon'] ); ?>"
										alt="<?php echo esc_attr( $hero['name'] ); ?>">
									<h3 class="wt-crp-os-hero-name"><?php echo esc_html( $hero['name'] ); ?></h3>
								</div>
								<div class="wt-crp-os-hero-stars" aria-label="<?php echo esc_attr( sprintf( /* translators: %s rating */ __( '%s out of 5 stars', 'wt-woocommerce-related-products' ), $hero['rating'] ) ); ?>">
									<?php for ( $i = 0; $i < 5; $i++ ) : ?>
										<span class="wt-crp-os-star">&#9733;</span>
									<?php endfor; ?>
								</div>
								<p class="wt-crp-os-hero-desc"><?php echo esc_html( $hero['desc'] ); ?></p>
								<a href="<?php echo esc_url( $hero['url'] ); ?>"
									target="_blank"
									rel="noopener noreferrer"
									class="wt-crp-os-btn-premium wt-crp-os-btn-premium--block">
									<span class="dashicons dashicons-star-filled"></span>
									<?php esc_html_e( 'Get premium', 'wt-woocommerce-related-products' ); ?>
								</a>
							</div>
							<?php if ( ! empty( $hero['image'] ) ) : ?>
								<div class="wt-crp-os-hero-right">
									<img src="<?php echo esc_url( $img_base . '/' . $hero['image'] ); ?>"
										alt="<?php echo esc_attr( $hero['name'] ); ?>">
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php /* -- Plugin card grid -- */ ?>
					<?php if ( ! empty( $cat['plugins'] ) ) : ?>
						<?php
						$chunks = array_chunk( $cat['plugins'], 3 );
						foreach ( $chunks as $row ) :
						?>
							<div class="wt-crp-os-card-grid">
								<?php foreach ( $row as $plugin ) : ?>

									<?php if ( 'image' === $plugin['type'] ) : ?>

										<div class="wt-crp-os-card-image">
											<img src="<?php echo esc_url( $img_base . '/' . $plugin['src'] ); ?>"
												alt="">
										</div>

									<?php else :
										$with_image = ( 'standard-with-image' === $plugin['type'] && ! empty( $plugin['image_src'] ) );
									?>

										<div class="wt-crp-os-card<?php echo $with_image ? ' wt-crp-os-card--with-image' : ''; ?>">
											<div class="wt-crp-os-card-body">
												<div class="wt-crp-os-card-header">
													<div class="wt-crp-os-card-icon-name">
														<img class="wt-crp-os-card-icon"
															src="<?php echo esc_url( $img_base . '/' . $plugin['icon'] ); ?>"
															alt="<?php echo esc_attr( $plugin['name'] ); ?>">
														<span class="wt-crp-os-card-name"><?php echo esc_html( $plugin['name'] ); ?></span>
													</div>
													<?php if ( 'stars' === $plugin['rating'] ) : ?>
														<span class="wt-crp-os-card-rating wt-crp-os-card-rating--stars">
															<span class="wt-crp-os-star">&#9733;</span>
															<span class="wt-crp-os-star">&#9733;</span>
															<span class="wt-crp-os-star">&#9733;</span>
															<span class="wt-crp-os-star">&#9733;</span>
															<span class="wt-crp-os-star">&#9733;</span>
														</span>
													<?php else : ?>
														<span class="wt-crp-os-card-rating">
															<?php echo esc_html( $plugin['rating'] ); ?>
															<span class="wt-crp-os-star">&#9733;</span>
														</span>
													<?php endif; ?>
												</div>
												<ul class="wt-crp-os-card-features">
													<?php foreach ( $plugin['features'] as $feature ) : ?>
														<li>
															<span class="dashicons dashicons-yes-alt"></span>
															<?php echo esc_html( $feature ); ?>
														</li>
													<?php endforeach; ?>
												</ul>
												<a href="<?php echo esc_url( $plugin['url'] ); ?>"
													target="_blank"
													rel="noopener noreferrer"
													class="wt-crp-os-btn-premium">
													<span class="dashicons dashicons-star-filled"></span>
													<?php esc_html_e( 'Get premium', 'wt-woocommerce-related-products' ); ?>
												</a>
											</div>
											<?php if ( $with_image ) : ?>
												<div class="wt-crp-os-card-image-side">
													<img src="<?php echo esc_url( $img_base . '/' . $plugin['image_src'] ); ?>"
														alt="">
												</div>
											<?php endif; ?>
										</div>

									<?php endif; ?>

								<?php endforeach; ?>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>

					<?php /* -- Bundle section (renders BEFORE the standalone, per Figma order) -- */ ?>
					<?php if ( ! empty( $cat['bundle'] ) ) : $bundle = $cat['bundle']; ?>
						<div class="wt-crp-os-bundle">
							<div class="wt-crp-os-bundle-content">
								<?php $tag_color = ! empty( $bundle['tag_color'] ) ? $bundle['tag_color'] : 'green'; ?>
								<span class="wt-crp-os-bundle-tag wt-crp-os-bundle-tag--<?php echo esc_attr( $tag_color ); ?>">
									<?php if ( ! empty( $bundle['tag_emoji'] ) ) : ?>
										<span class="wt-crp-os-bundle-tag-emoji"><?php echo esc_html( $bundle['tag_emoji'] ); ?></span>
									<?php endif; ?>
									<?php echo esc_html( $bundle['tag'] ); ?>
								</span>
								<div class="wt-crp-os-bundle-title">
									<a href="<?php echo esc_url( $bundle['url'] ); ?>"
										target="_blank"
										rel="noopener noreferrer">
										<?php echo esc_html( $bundle['title'] ); ?>
									</a>
									<span class="dashicons dashicons-external"></span>
								</div>
								<p class="wt-crp-os-bundle-desc"><?php echo esc_html( $bundle['desc'] ); ?></p>
								<div class="wt-crp-os-bundle-pills">
									<?php foreach ( $bundle['pills'] as $pill ) : ?>
										<span class="wt-crp-os-bundle-pill">
											<span class="dashicons dashicons-yes-alt"></span>
											<?php echo esc_html( $pill ); ?>
										</span>
									<?php endforeach; ?>
								</div>
								<p class="wt-crp-os-bundle-pricing">
									<?php
									printf(
										wp_kses(
											/* translators: 1: strikethrough original price, 2: bold sale price, 3: green savings text */
											__( 'Total: <s>%1$s</s> <strong>%2$s</strong> <span class="wt-crp-os-savings">(%3$s)</span>', 'wt-woocommerce-related-products' ),
											array(
												's'      => array(),
												'strong' => array(),
												'span'   => array( 'class' => array() ),
											)
										),
										esc_html( $bundle['price_orig'] ),
										esc_html( $bundle['price_sale'] ),
										esc_html( $bundle['savings'] )
									);
									?>
								</p>
								<a href="<?php echo esc_url( $bundle['url'] ); ?>"
									target="_blank"
									rel="noopener noreferrer"
									class="wt-crp-os-btn-bundle">
									<?php esc_html_e( 'View Bundle', 'wt-woocommerce-related-products' ); ?>
									<span class="dashicons dashicons-external"></span>
								</a>
							</div>
							<?php if ( ! empty( $bundle['illustration'] ) ) : ?>
								<div class="wt-crp-os-bundle-illustration">
									<img src="<?php echo esc_url( $img_base . '/' . $bundle['illustration'] ); ?>"
										alt="<?php echo esc_attr( $bundle['title'] ); ?>">
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php /* -- Standalone card (e.g. EMA App) — renders AFTER the bundle, per Figma order -- */ ?>
					<?php if ( ! empty( $cat['standalone'] ) ) : $solo = $cat['standalone']; ?>
						<div class="wt-crp-os-standalone">
							<div class="wt-crp-os-standalone-content">
								<div class="wt-crp-os-standalone-header">
									<img class="wt-crp-os-standalone-icon"
										src="<?php echo esc_url( $img_base . '/' . $solo['icon'] ); ?>"
										alt="<?php echo esc_attr( $solo['name'] ); ?>">
									<h3 class="wt-crp-os-standalone-name"><?php echo esc_html( $solo['name'] ); ?></h3>
								</div>
								<p class="wt-crp-os-standalone-desc"><?php echo esc_html( $solo['desc'] ); ?></p>
								<a href="<?php echo esc_url( $solo['url'] ); ?>"
									target="_blank"
									rel="noopener noreferrer"
									class="wt-crp-os-btn-premium wt-crp-os-btn-premium--block">
									<?php esc_html_e( 'Try Now', 'wt-woocommerce-related-products' ); ?>
								</a>
							</div>
							<?php if ( ! empty( $solo['screenshot'] ) ) : ?>
								<div class="wt-crp-os-standalone-screenshot">
									<img src="<?php echo esc_url( $img_base . '/' . $solo['screenshot'] ); ?>"
										alt="<?php echo esc_attr( $solo['name'] ); ?>">
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>

				</div>
			<?php endforeach; ?>

		</div>
	</div>
</div>
