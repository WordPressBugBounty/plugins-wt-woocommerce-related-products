<?php
/**
 * BFCM 2026 Banner.
 *
 * @package Custom_Related_Products
 */

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals -- Legacy global/hook/function names kept for backward compatibility.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals -- Legacy hook and constant names kept for backward compatibility.

if ( ! class_exists( 'Wtrp_Bfcm_Twenty_Twenty_Six' ) ) {

	/**
	 * Class Wtrp_Bfcm_Twenty_Twenty_Six
	 *
	 * This class is responsible for displaying and handling the Black Friday and Cyber Monday CTA banners for 2026.
	 */
	class Wtrp_Bfcm_Twenty_Twenty_Six {

		/**
		 * Banner id.
		 *
		 * @var string
		 */
		private $banner_id = 'wtrp-bfcm-twenty-twenty-six';

		/**
		 * Banner state option name.
		 *
		 * @var string
		 */
		private static $banner_state_option_name = 'wtrp_bfcm_twenty_twenty_six_banner_state'; // Banner state, 1: Show, 2: Closed by user.

		/**
		 * Banner state.
		 *
		 * @var int
		 */
		private $banner_state = 1;

		/**
		 * Show banner.
		 *
		 * @var bool|null
		 */
		private static $show_banner = null;

		/**
		 * Ajax action name.
		 *
		 * @var string
		 */
		private static $ajax_action_name = 'wtrp_bfcm_twenty_twenty_six_update_banner_state';

		/**
		 * Promotion link.
		 *
		 * @var string
		 */
		private static $promotion_link = 'https://www.webtoffee.com/plugins/?utm_source=BFCM_promotion&utm_medium=Recommendations&utm_campaign=BFCM-Promotion';

		/**
		 * Banner version.
		 *
		 * @var string
		 */
		private static $banner_version = '';

		/**
		 * Constructor.
		 */
		public function __construct() {
			self::$banner_version = WT_RELATED_PRODUCTS_VERSION; // Plugin version.

			$this->banner_state = get_option( self::$banner_state_option_name ); // Current state of the banner.
			$this->banner_state = absint( false === $this->banner_state ? 1 : $this->banner_state );

			// Enqueue styles.
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles_and_scripts' ) );
			// Add banner.
			add_action( 'admin_notices', array( $this, 'show_banner' ) );

			// Ajax hook to save banner state.
			add_action( 'wp_ajax_' . self::$ajax_action_name, array( $this, 'update_banner_state' ) );
		}

		/**
		 * To add the banner styles
		 */
		public function enqueue_styles_and_scripts() {
			// Load assets only where the banner is shown (Related Products settings page, during the sale, not dismissed).
			if ( ! $this->is_show_banner() ) {
				return;
			}
			wp_enqueue_style( $this->banner_id . '-css', plugin_dir_url( __FILE__ ) . 'assets/css/wt-bfcm-twenty-twenty-six.css', array(), self::$banner_version, 'all' );
			$params = array(
				'ajax_url' => esc_url( admin_url( 'admin-ajax.php' ) ),
				'nonce'    => wp_create_nonce( 'wtrp_bfcm_twenty_twenty_six_banner_nonce' ),
				'action'   => self::$ajax_action_name,
			);
			wp_enqueue_script( $this->banner_id . '-js', plugin_dir_url( __FILE__ ) . 'assets/js/wt-bfcm-twenty-twenty-six.js', array( 'jquery' ), self::$banner_version, false );
			wp_localize_script( $this->banner_id . '-js', 'wtrp_bfcm_twenty_twenty_six_banner_js_params', $params );
		}


		/**
		 * Show the banner.
		 */
		public function show_banner() {
			if ( $this->is_show_banner() ) {
				?>
					<div class="wtrp-bfcm-banner-2026 notice is-dismissible">
						<div class="wt-bfcm-banner-body">
							<div class="wt-bfcm-banner-body-img-section">
								<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . 'assets/images/black-friday-2026.svg' ); ?>" alt="<?php esc_attr_e( 'Black Friday Cyber Monday 2026', 'wt-woocommerce-related-products' ); ?>">
							</div>
							<div class="wt-bfcm-banner-body-info">
								<div class="wt-bfcm-never-miss-this-deal">
									<p><?php echo esc_html__( 'Never Miss This Deal', 'wt-woocommerce-related-products' ); ?></p>
								</div>
								<div class="info">
									<p>
									<?php
										printf(
											// translators: 1: Discount text with span wrapper, e.g. <span>30% OFF</span>.
											esc_html__( 'Your Last Chance to Avail %1$s on WebToffee Plugins. Grab the deal before it\'s gone!', 'wt-woocommerce-related-products' ),
											'<span>30% ' . esc_html__( 'OFF', 'wt-woocommerce-related-products' ) . '</span>'
										);
									?>
									</p>
								</div>
								<div class="info-button">
									<a href="<?php echo esc_url( self::$promotion_link ); ?>" class="bfcm_cta_button" target="_blank"><?php echo esc_html__( 'View plugins', 'wt-woocommerce-related-products' ); ?> <span class="dashicons dashicons-arrow-right-alt"></span></a>
								</div>
							</div>
						</div>
					</div>
				<?php
			}
		}

		/**
		 * Check if the banner should be shown.
		 *
		 * @return bool
		 */
		public function is_show_banner() {

			// Check if the current date is less than the start date then wait for the start date.
			if ( ! method_exists( 'Custom_Related_Products_Admin', 'is_bfcm_season' ) || ! Custom_Related_Products_Admin::is_bfcm_season() ) {
				self::$show_banner = false;
				return self::$show_banner;
			}

			// Already checked.
			if ( ! is_null( self::$show_banner ) ) {
				return self::$show_banner;
			}

			// Check current banner state. Only closing the banner hides it.
			if ( 2 === $this->banner_state ) {
				self::$show_banner = false;
				return self::$show_banner;
			}

			// Show only on the Related Products settings page, not on other WebToffee plugin pages.
			$screen    = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
			$screen_id = $screen ? $screen->id : '';

			self::$show_banner = ( 'woocommerce_page_wt-woocommerce-related-products' === $screen_id );
			return self::$show_banner;
		}

		/**
		 *  Update banner state ajax hook
		 */
		public function update_banner_state() {
			check_ajax_referer( 'wtrp_bfcm_twenty_twenty_six_banner_nonce' );
			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				exit();
			}
			if ( isset( $_POST['wtrp_bfcm_twenty_twenty_six_banner_action_type'] ) ) {

				$action_type = absint( sanitize_text_field( wp_unslash( $_POST['wtrp_bfcm_twenty_twenty_six_banner_action_type'] ) ) );
				if ( 2 === $action_type ) { // Only the close button hides the banner.
					update_option( self::$banner_state_option_name, $action_type );
				}
			}
			exit();
		}
	}

	new Wtrp_Bfcm_Twenty_Twenty_Six();
}