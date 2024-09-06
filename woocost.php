<?php
/**
 * Plugin Name: WooCost
 * Plugin URI: https://spider-themes.net/woocost/
 * Description: Effortlessly track and analyze product costs and profits in WooCommerce, empowering smarter financial decisions and enhanced profitability.
 * Version: 1.0.0
 * Requires at least: 5.7
 * Requires PHP: 7.4
 * Author: spider-themes
 * Author URI: https://spider-themes.net
 * Text Domain: woocost
 * Domain Path: /languages
 * Copyright: © 2024 Spider Themes
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Requires Plugins:  woocommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

const WOOCOST_PLUGIN_FILE = __FILE__;

if ( ! class_exists( 'Woocost' ) ) {

	require_once __DIR__ . '/vendor/autoload.php';

	final class Woocost {

		const version = "1.0.0";

		private static $instance = null;

		/**
		 * Class construcotr
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'plugin_init' ) );
			register_activation_hook( plugin_basename( __FILE__ ), [ $this, 'activate' ] );
		}

		/**
		 * Initializes a singleton instance
		 *
		 * @return \Woocost
		 */
		public static function get_instance() {
			if ( self::$instance === null ) {
				self::$instance = new self();
			}

			return self::$instance;
		}


		public function activate(): void {
			flush_rewrite_rules();
			$this->cost_operation_table();
			$this->bulk_discounts_table();

		}

		/**
		 * Const Define
		 *
		 * @return void
		 */
		public function define_constants(): void {
			define( 'WOOCOST_VERSION', self::version );
			define( 'WOOCOST_FILE', __FILE__ );
			define( 'WOOCOST_PATH', __DIR__ );
			define( 'WOOCOST_URL', plugins_url( '', WOOCOST_FILE ) );
			define( 'WOOCOST_ASSETS', WOOCOST_URL . '/assets' );
		}

		public function plugin_init(): void {

			/**
			 * Settings filter
			 */
			add_filter( 'plugin_action_links_' . plugin_basename( WOOCOST_PLUGIN_FILE ), array( $this, 'settings_action_links' ) );

			if ( is_admin() ) {
				new wooCost\Admin();
				new wooCost\Notices\Notices();
			}
			$this->define_constants();
			$this->templates_include();
		}

		public function cost_operation_table(): void {
			global $wpdb;
			$table_name      = $wpdb->prefix . 'woocost';
			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE $table_name (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				cost_type_name varchar(255) NOT NULL,
				cost decimal(10,2) NOT NULL,
				account varchar(50) NOT NULL,
				notes text,
				memo varchar(255),
				date date NOT NULL,
				PRIMARY KEY (id)
			) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}


		public function bulk_discounts_table(): void {
			global $wpdb;

			// Table name with the WordPress prefix
			$table_name = $wpdb->prefix . 'woocost_bulk_discounts';

			// Get the character set from the current database
			$charset_collate = $wpdb->get_charset_collate();

			// Corrected SQL query to create the table
			$sql = "CREATE TABLE $table_name (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            active_rule TINYINT(1) NOT NULL DEFAULT 0,
            profit_rules TEXT NOT NULL,
            discount_rules TEXT NOT NULL,
            user_roles TEXT NOT NULL,
            exclude_products TINYINT(1) NOT NULL DEFAULT 0,
            discount_loop TINYINT(1) NOT NULL DEFAULT 0,
            PRIMARY KEY (id)
        ) $charset_collate;";

			// Include the WordPress file with the dbDelta function
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			error_log("Table creation SQL: $sql");
			// Execute the query
			dbDelta( $sql );
		}


		public function templates_include(): void {

			require_once plugin_dir_path( __FILE__ ) . '/functions/cost-profit-metabox.php';
			require_once plugin_dir_path( __FILE__ ) . '/functions/custom-columns-sortable.php';
			require_once plugin_dir_path( __FILE__ ) . '/functions/order-by-date-range.php';
			require_once plugin_dir_path( __FILE__ ) . '/functions/custom-cost-field.php';
			require_once plugin_dir_path( __FILE__ ) . '/functions/compare-monthly-orders.php';
			require_once plugin_dir_path( __FILE__ ) . '/functions/order-page-custom-cost.php';
			require_once plugin_dir_path( __FILE__ ) . '/functions/cost-operation-table.php';
			require_once plugin_dir_path( __FILE__ ) . '/functions/bulk-discount-table.php';
			require_once plugin_dir_path( __FILE__ ) . '/functions/product-search.php';

		}

		/**
		 * Plugins setting setup
		 *
		 * @param $links
		 *
		 * @return mixed
		 */
		public function settings_action_links( $links ): mixed {
			$settings_link = '<a href="' . esc_url( admin_url( 'admin.php?page=bulk-discounts' ) ) . '">' . esc_html__( 'Settings', 'woocost' ) . '</a>';
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

	Woocost::get_instance();

}