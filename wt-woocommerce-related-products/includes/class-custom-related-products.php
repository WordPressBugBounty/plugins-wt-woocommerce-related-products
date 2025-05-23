<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Custom_Related_Products
 * @subpackage Custom_Related_Products/includes
 * @author     markhf
 */
class Custom_Related_Products {

	protected $loader;
	protected $plugin_name;
	protected $VERSION;
	protected $plugin_base_name;

	const VERSION = '1.7.2';

	public function __construct() {

		$this->plugin_name		 = 'wt-woocommerce-related-products';
		$this->plugin_base_name	 = WT_CRP_BASE_NAME;

		$this->VERSION = '1.7.2';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		add_shortcode( 'wt-related-products', array( $this, 'render_wt_related_products' ) );
		add_action('plugins_loaded', array($this, 'woocommerce_addition_for_rp_shortcode'));
        add_action( 'woocommerce_after_cart', array( $this, 'render_related_products_in_cart' ) );
        add_filter( 'render_block', array( $this, 'render_related_products_in_block_cart' ), 10, 2 );
		
    }

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Custom_Related_Products_Loader. Orchestrates the hooks of the plugin.
	 * - Custom_Related_Products_i18n. Defines internationalization functionality.
	 * - Custom_Related_Products_Admin. Defines all hooks for the admin area.
	 * - Custom_Related_Products_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-custom-related-products-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-custom-related-products-i18n.php';

		/**
		 * The class responsible for the review banner in customer site 
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-custom-related-products-review-request.php';

		/**
		 * The class responsible for the survey banner in customer site 
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-custom-related-products-survey-request.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-custom-related-products-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-custom-related-products-public.php';
		
		$this->loader = new Custom_Related_Products_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Custom_Related_Products_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Custom_Related_Products_i18n();

		$this->loader->add_action( 'init', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Custom_Related_Products_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		if(!class_exists('Wt_Woocommerce_Product_Recommendations')){
			$this->loader->add_action( 'woocommerce_process_product_meta', $plugin_admin, 'crp_save_related_products', 10, 2 );
			$this->loader->add_action( 'woocommerce_product_options_related', $plugin_admin, 'crp_select_related_products' );
		}
		
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_options_page' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_setting' );

		$this->loader->add_filter( 'plugin_action_links_' . $this->get_plugin_base_name(), $plugin_admin, 'add_crp_action_links' );
		$this->loader->add_filter( 'plugin_row_meta', $plugin_admin, 'add_crp_plugin_row_meta', 10, 2);
		$this->loader->add_filter( 'woocommerce_screen_ids', $plugin_admin, 'set_wc_screen_ids', 10, 1);
		$plugin_admin->admin_modules();
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$wcversion		 = get_option( 'woocommerce_version', true );
		$plugin_public	 = new Custom_Related_Products_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );


		if ( isset( $wcversion ) && !empty( $wcversion ) ) {
			if ( $wcversion >= '2.3' && $wcversion < '3.0' ) {
				$this->loader->add_filter( 'woocommerce_related_products_args', $plugin_public, 'crp_filter_related_products' );
			} else if ( $wcversion >= '3.0' ) {
				$this->loader->add_filter( 'woocommerce_locate_template', $plugin_public, 'crp_woocommerce_locate_template', 10, 3 );

				$this->loader->add_filter( 'woocommerce_product_related_posts_force_display', $plugin_public, 'crp_display_ids', 10, 2 );
				$this->loader->add_filter( 'woocommerce_product_related_posts_relate_by_category', $plugin_public, 'crp_remove_taxonomy', 10, 2 );
				$this->loader->add_filter( 'woocommerce_product_related_posts_relate_by_tag', $plugin_public, 'crp_remove_taxonomy', 10, 2 );
				$this->loader->add_filter( 'woocommerce_product_related_posts_query', $plugin_public, 'crp_related_products_query', 20, 2 );
				$this->loader->add_filter('pre_render_block', $plugin_public, 'block_theme_single_product_page', 10, 2);

				/**
				 * Block theme compatability
				 * 
				 * @since 1.5.1
				 * 
				*/
				if ( function_exists('wp_is_block_theme') && wp_is_block_theme() ) { // Block theme		
					// $this->loader->add_filter('pre_render_block', $plugin_public, 'block_theme_single_product_page', 10, 2);
					$this->loader->add_action( 'woocommerce_after_single_product_summary',$plugin_public, 'wt_woocommerce_output_related_products', 20 );	
				}

			} else {
				$this->loader->add_filter( 'woocommerce_related_products_args', $plugin_public, 'crp_filter_related_products' );
			}
		}
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	public function get_plugin_name() {
		return $this->plugin_name;
	}

	public function get_plugin_base_name() {
		return $this->plugin_base_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return   Custom_Related_Products_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	public function get_version() {
		return $this->VERSION;
	}

	/*
	 * Related products shortcode
	 * int $product_id
	 */

	public function render_wt_related_products( $atts ) {

		$result = '';
		if ( !empty( $atts[ 'product_id' ] ) ) {
			$product_id = $atts[ 'product_id' ];
			$post = get_post($product_id);
			if( $post ) {
				$related_products = array();
				$related = wc_get_related_products( $product_id );
				if(!empty($related)){
					foreach ( $related as $related_id ) {
						$related_products[] = wc_get_product($related_id);					
					}
				}
				if (  function_exists( 'wc_get_template_html' ) ) {
					$result = wc_get_template_html( 'single-product/related.php', array( 'related_products' => $related_products, 'product_id' => $product_id, 'shortcode_post' =>  $post ), '', plugin_dir_path( __FILE__ ) . 'woocommerce/' );
				}
			}
		}

		return $result;
	}

	/**
	* Get the current working mode
	* @since 1.3.9
	* @return string  
	*/
    public static function get_current_working_mode() {
		
        $current_working_mode = get_option('custom_related_products_working_mode');
        if( !$current_working_mode ) {
            $current_working_mode = 'custom';
            // get the previous version settings.
            $wc_default_disabled	= get_option('custom_related_products_disable');
            $wt_custom_disabled	 	= get_option('custom_related_products_disable_custom');

            
            if( !empty($wc_default_disabled) && !empty($wt_custom_disabled) ) {
                // both options are disabled
                $current_working_mode = 'disable';
            } else if( !empty($wt_custom_disabled) && empty($wc_default_disabled)) {
                // custom related product option is disabled
                $current_working_mode = 'default';
            }
            update_option('custom_related_products_working_mode', $current_working_mode);
		    
        }
        
        return $current_working_mode;
    }

	/**
	* Get the custom orderby values
	* @since 1.4.1
	* @return array  
	*/
    public static function get_custom_order_by_values() {
		return array(
			'price' => array(
				'orderby' => 'meta_value_num',
				'meta_key' => '_price'
			),
			'popularity' => array(
				'orderby' => 'meta_value_num',
				'meta_key' => 'total_sales'
			),
			'rating' => array(
				'orderby' => 'meta_value_num',
				'meta_key' => '_wc_average_rating'
			),
		);
	}
        
        /**
	* Get the device check
	* @since 1.4.1
	* @return array  
	*/
    public static function wt_get_device_type() {
                $crp_view_port = get_option('custom_related_products_crp_banner_product_width') ;
                $slider_state	 = get_option('custom_related_products_slider','enable');
                $desktop_view = isset($crp_view_port[0]) && !empty($crp_view_port[0]) ?  $crp_view_port[0] : 3;
                $tab_view = isset($crp_view_port[1]) && !empty($crp_view_port[1]) ?  $crp_view_port[1] : 2;
                $mobile_view = isset($crp_view_port[2]) && !empty($crp_view_port[2]) ?  $crp_view_port[2] : 1;
                $desktop_view_default = isset($crp_view_port[3]) && !empty($crp_view_port[3]) ?  $crp_view_port[3] : 5;
                $tab_view_default = isset($crp_view_port[4]) && !empty($crp_view_port[4]) ?  $crp_view_port[4] : 2;
                $mobile_view_default = isset($crp_view_port[5]) && !empty($crp_view_port[5]) ?  $crp_view_port[5] : 1;

				$user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
				$http_accept = isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : '';

                $slider_count = 3;
		
                $tablet_browser = 0;
                $mobile_browser = 0;

                if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($user_agent))) {
                    $tablet_browser++;
                }

                if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($user_agent))) {
                    $mobile_browser++;
                }

                if ((strpos(strtolower($http_accept),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
                    $mobile_browser++;
                }

                $mobile_ua = strtolower(substr($user_agent, 0, 4));
                $mobile_agents = array(
                    'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
                    'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
                    'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
                    'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
                    'newt','noki','palm','pana','pant','phil','play','port','prox',
                    'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
                    'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
                    'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
                    'wapr','webc','winw','winw','xda ','xda-');

                if (in_array($mobile_ua,$mobile_agents)) {
                    $mobile_browser++;
                }

                if (strpos(strtolower($user_agent),'opera mini') > 0) {
                    $mobile_browser++;
                    //Check for tablets on opera mini alternative headers
                    $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
                    if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
                      $tablet_browser++;
                    }
                }

                if ($tablet_browser > 0) {
                   // do something for tablet devices
                   $slider_count = $tab_view;
                   if('enable' != $slider_state){
                       $slider_count = $tab_view_default;
                    }
                }
                else if ($mobile_browser > 0) {
                   // do something for mobile devices
                  $slider_count = $mobile_view;
                  if('enable' != $slider_state){
                       $slider_count = $mobile_view_default;
                   }
                }
                else {
                   // do something for everything else
                   $slider_count = $desktop_view;
                   if('enable' != $slider_state){
                       $slider_count = $desktop_view_default;
                   }
                } 
                return $slider_count;
	}
        
    /*
	 * Related products in cart
	 * 
	 */

	public function render_related_products_in_cart( ) {
		// Set the column count for related products displayed after the cart
		wc_set_loop_prop( 'columns', 3 );

        $slider_state = get_option( 'custom_related_products_cart_working_mode','cart_mode' );
		if('cart_mode' == $slider_state){
            include CRP_PLUGIN_DIR_PATH.'woocommerce/cart/wt-cart.php'; 
        }
    }

    /**
     * Render related products in block-based cart pages
     *
     * @param string $block_content The block content.
     * @param array  $block The block.
     * @return string The filtered block content.
     */
    public function render_related_products_in_block_cart($block_content, $block) 
    {
        // Only proceed if we're on a cart page
        if (!is_cart()) {
            return $block_content;
        }

        // Create our own block container
        if ('woocommerce/cart' === $block['blockName']) {
            ob_start();

            // Always add woocommerce class for consistent styling
            $woocommerce_class = 'woocommerce';
            ?>
            <div class="wp-block-wt-related-products <?php echo esc_attr($woocommerce_class); ?>" 
                 data-block="wt-related-products">
                <?php $this->render_related_products_in_cart(); ?>
            </div>
            <?php
            $related_products = ob_get_clean();
            
            // Add our block after the main cart content
            return $block_content . $related_products;
        }

        return $block_content;
    }

	/**
	 * Call to wrap related products via shortcode
	 *
	 * @return void
	 */
	public function woocommerce_addition_for_rp_shortcode() {
		if (class_exists('WooCommerce')) {
			add_filter('do_shortcode_tag', array($this, 'wrap_related_products_shortcode_output'), 10, 2);
		}
	}

	/**
	 * Add woocommerce class to related products shortcode output
	 * 
	 * @param  mixed $output
	 * @param  mixed $tag
	 * @return string $output
	 */
	public function wrap_related_products_shortcode_output($output, $tag) {
		if ('wt-related-products' === $tag) {
			$output = '<div class="woocommerce">' . $output . '</div>';
		}
		return $output;
	}
}

