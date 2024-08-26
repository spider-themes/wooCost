<?php
/**
 * Plugin Name: WooProfit
 * Plugin URI: https://spider-themes.net/wooprofit/
 * Description: Effortlessly track and analyze product costs and profits in WooCommerce, empowering smarter financial decisions and enhanced profitability.
 * Version: 1.0.0
 * Requires at least: 5.7
 * Requires PHP: 7.4
 * Author: spider-themes
 * Author URI: https://spider-themes.net
 * Text Domain: wooprofit
 * Domain Path: /languages
 * Copyright: © 2024 Spider Themes
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Requires Plugins:  woocommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

const WOOPROFIT_PLUGIN_FILE = __FILE__;

if ( ! class_exists( 'Wooprofit' ) ) {

	require_once __DIR__ . '/vendor/autoload.php';

	final class Wooprofit {

		const version = "1.0.0";

		private static $instance = null;

		/**
		 * Class construcotr
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'plugin_init' ) );
			register_activation_hook( __FILE__, [ $this, 'activate' ] );
		}
		/**
		 * Initializes a singleton instance
		 *
		 * @return \Wooprofit
		 */
		public static function get_instance() {
			if ( self::$instance === null ) {
				self::$instance = new self();
			}

			return self::$instance;
		}


		public function activate(): void {
			flush_rewrite_rules();
		}

		/**
		 * Const Define
		 * @return void
		 */
		public function define_constants(): void {
			define( 'WOOPROFIT_VERSION', self::version );
			define( 'WOOPROFIT_FILE', __FILE__ );
			define( 'WOOPROFIT_PATH', __DIR__ );
			define( 'WOOPROFIT_URL', plugins_url( '', WOOPROFIT_FILE ) );
			define( 'WOOPROFIT_ASSETS', WOOPROFIT_URL . '/assets' );
		}

		public function plugin_init(): void {

			/**
			 * Settings filter
			 */
			add_filter( 'plugin_action_links_' . plugin_basename( WOOPROFIT_PLUGIN_FILE ), array( $this, 'settings_action_links' ) );

			if ( is_admin() ) {
				new wooProfit\Admin();
				new wooProfit\Notices\Notices();
			}
			$this->define_constants();
			$this->templates_include();
		}

		public function templates_include(): void {
			require_once __DIR__ . '/templates/cost-profit-metabox.php';
			require_once __DIR__ . '/templates/custom-columns-sortable.php';
			require_once __DIR__ . '/templates/order-by-date-range.php';
			require_once __DIR__ . '/templates/custom-cost-field.php';
			require_once __DIR__ . '/templates/compare-monthly-orders.php';
			require_once __DIR__ . '/templates/order-page-custom-cost.php';

		}

		/**
		 * Plugins setting setup
		 *
		 * @param $links
		 *
		 * @return mixed
		 */
		public function settings_action_links( $links ): mixed {
			$settings_link = '<a href="' . esc_url( admin_url( 'admin.php?page=bulk-discounts' ) ) . '">' . esc_html__( 'Settings', 'wooprofit' ) . '</a>';
			array_unshift( $links, $settings_link );

			return $links;
		}

		/**
		 * Total Cost Calculation
		 *
		 * @return int|null
		 */
		public function total_stock(): ?int {
			$total_stock = 0;
			// Ensure WooCommerce is active
			if ( class_exists( 'WooCommerce' ) ) {
				$args = array(
					'post_type'      => 'product',
					'posts_per_page' => - 1,
					'post_status'    => 'publish',
				);

				$products = new WP_Query( $args );

				if ( $products->have_posts() ) {
					while ( $products->have_posts() ) {
						$products->the_post();
						$product     = wc_get_product( get_the_ID() );
						$total_stock += $product->get_stock_quantity();
					}
				}
				wp_reset_postdata();
			}

			return $total_stock;
		}

		/**
		 * Total Price Calculation
		 *
		 * @return float|int
		 */
		public function total_price(): float|int {
			$total_price = 0;

			if ( class_exists( 'WooCommerce' ) ) {
				$args = array(
					'post_type'      => 'product',
					'posts_per_page' => - 1,
					'post_status'    => 'publish',
				);

				$products = new WP_Query( $args );

				if ( $products->have_posts() ) {
					while ( $products->have_posts() ) {
						$products->the_post();
						$product     = wc_get_product( get_the_ID() );
						$total_price += (float) $product->get_price();
					}
				}
				wp_reset_postdata();
			}

			return $total_price;
		}

		/**
		 * Total cost calculation
		 *
		 * @return float|int
		 */

		public function total_cost(): float|int {
			$total_cost = 0;

			if ( class_exists( 'WooCommerce' ) ) {
				// Check if the 'Cost of Goods for WooCommerce' plugin is activated
				$is_cog_activated = class_exists( 'Alg_WC_Cost_of_Goods_Core' );

				$meta_key = '_woo_product_cost';

				if ( $is_cog_activated ) {
					$meta_key = '_alg_wc_cog_cost';
				}

				$args = array(
					'post_type'      => 'product',
					'posts_per_page' => - 1,
					'post_status'    => 'publish',
					'meta_key'       => $meta_key,
					'meta_value'     => '',
					'meta_compare'   => '!='
				);

				$products = new WP_Query( $args );

				if ( $products->have_posts() ) {
					while ( $products->have_posts() ) {
						$products->the_post();
						$cost = 0;

						// Check if '_woo_product_cost' is set
						$woo_cost = (float) get_post_meta( get_the_ID(), '_woo_product_cost', true );

						if ( $is_cog_activated ) {
							// If 'Cost of Goods for WooCommerce' is activated, use '_alg_wc_cog_cost'
							$cost = (float) get_post_meta( get_the_ID(), '_alg_wc_cog_cost', true );
						} elseif ( $woo_cost ) {
							// Otherwise, use '_woo_product_cost' if it's set
							$cost = $woo_cost;
						}

						$total_cost += $cost;
					}
				}
				wp_reset_postdata();
			}

			return $total_cost;
		}

		/**
		 * Total profit Calculate
		 *
		 * @return float|int
		 */
		public function total_profit(): float|int {
			return $this->total_price() - $this->total_cost();
		}

	}

	Wooprofit::get_instance();

}