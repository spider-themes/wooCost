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
include_once (ABSPATH . 'wp-admin/includes/plugin.php');

const WOOPROFIT_COST_SETTINGS_PLUGIN_FILE = __FILE__;

class Wooprofit {

	public function __construct() {
		add_action( 'init', array( $this, 'wooprofit_init' ) );
		register_activation_hook( __FILE__, [ $this, 'activate' ] );
	}

	public function activate(): void {
		flush_rewrite_rules();
	}

	public function wooprofit_init(): void {

		add_action( 'admin_menu', [ $this, 'wooprofit_admin_menu' ], 99 );
		/**
		 * Enqueue Assets
		 */
		add_action( 'admin_enqueue_scripts', [ $this, 'wooprofit_assets_load' ] );
		/**
		 * add custom cost field
		 */
		add_action( 'woocommerce_product_options_general_product_data', array( $this, 'wooprofit_add_cost_field' ) );
		add_action( 'woocommerce_process_product_meta', array( $this, 'wooprofit_save_cost_field' ) );
		add_filter( 'manage_product_posts_columns', array( $this, 'wooprofit_add_cost_and_profit_column_header' ), 20 );
		add_action( 'manage_product_posts_custom_column', array( $this, 'wooprofit_populate_cost_and_profit_column_content' ), 20, 2 );
		/**
		 * Make the custom columns sortable
		 */
		add_filter( 'manage_edit-product_sortable_columns', array( $this, 'wooprofit_make_cost_and_profit_column_sortable' ) );
		/**
		 * Handle sorting by custom columns
		 */
		add_action( 'pre_get_posts', array( $this, 'wooprofit_sort_cost_and_profit_columns' ) );
		/**
		 * Add custom tab inside the woocommerce setting menu
		 */
		add_filter( 'woocommerce_get_settings_pages', array( $this, 'wooprofit_add_cost_tab_to_woocommerce_settings' ) );
		/**
		 * Settings filter
		 */
		add_filter( 'plugin_action_links_' . plugin_basename( WOOPROFIT_COST_SETTINGS_PLUGIN_FILE ), array( $this, 'wooprofit_settings_action_links' ) );

		/**
		 * Date range
		 */
		add_action( 'wp_ajax_wooprofit_get_orders_by_date_range', [ $this, 'wooprofit_get_orders_by_date_range' ] );
		add_action( 'wp_ajax_nopriv_wooprofit_get_orders_by_date_range', [ $this, 'wooprofit_get_orders_by_date_range' ] );

	}

	function wooprofit_settings_action_links( $links ) {
		$settings_link = '<a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=wooprofit' ) ) . '">' . esc_html( 'Settings', 'wooprofit' ) . '</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}

	/**
	 * Method for settings
	 */
	function wooprofit_add_cost_tab_to_woocommerce_settings( $settings ) {
		$settings[] = include( 'templates/class-wooprofit-settings-cost.php' );

		return $settings;
	}

	/**
	 * Asset loading
	 */
	function wooprofit_assets_load( $hook ): void {
		$plugin_data = get_plugin_data( __FILE__ );
		$plugin_version = $plugin_data['Version'];
		$assets_dir = plugins_url( 'assets/', __FILE__ );

		wp_enqueue_style( 'wooprofit-style', $assets_dir . 'css/style.css' );
		wp_enqueue_style( 'wooprofit-nice', $assets_dir . 'css/nice-select.css' );
		wp_enqueue_style( 'google-font', '//fonts.googleapis.com/css?family=Montserrat' );
		wp_enqueue_style( 'font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css' );

		if ( $hook == 'post.php' || $hook == 'post-new.php' ) {
			global $post_type;
			if ( $post_type == 'product' ) {
				wp_enqueue_script( 'wooprofit', $assets_dir . 'js/profit-show.js', array( 'jquery' ), $plugin_version, [ 'in_footer' => true, 'strategy' => 'defer'] );
			}
		}

		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_style( 'jquery-ui', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css' );
		wp_enqueue_script( 'apexcharts', '//cdn.jsdelivr.net/npm/apexcharts' );
		wp_enqueue_script('chart', $assets_dir . 'js/chart.js', array( 'jquery' ), $plugin_version, ['in_footer' => true, 'strategy' => 'defer'] );
//		wp_enqueue_script('compare', $assets_dir . 'js/order-compare.js', array(), $plugin_version, ['in_footer' => true, 'strategy' => 'defer'] );
		wp_enqueue_script('tooltip', $assets_dir . 'js/tooltip.js', array( 'jquery' ), $plugin_version, ['in_footer' => true, 'strategy' => 'defer'] );

		wp_enqueue_script( 'custom-date-range-script', $assets_dir . 'js/custom-date-range.js', array( 'jquery' ), $plugin_version, ['in_footer' => true, 'strategy' => 'defer'] );
		wp_enqueue_script( 'nice-select', $assets_dir . 'js/jquery.nice-select.min.js', array(), $plugin_version, ['in_footer' => true, 'strategy' => 'defer'] );
		wp_localize_script( 'custom-date-range-script', 'ajax_params', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
		) );
	}

	/**
	 * Set Submenu to Woocommerce Analytics
	 */
	function wooprofit_admin_menu(): void {
		add_submenu_page(
			'wc-admin&path=/analytics/overview',
			esc_html( 'Profit', 'wooprofit' ),
			esc_html( 'Profit Margin', 'wooprofit' ),
			'manage_woocommerce',
			'wc-analytics-profit',
			[ $this, 'wooprofit_page' ]
		);
	}

	/**
	* Total Cost Calculation
	*/

	function wooprofit_total_stock(): ?int {
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
	 */
	function wooprofit_total_price() {
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
	**/
	function wooprofit_total_cost(): float|int {
		$total_cost = 0;

		if ( class_exists( 'WooCommerce' ) ) {
			$args = array(
				'post_type'      => 'product',
				'posts_per_page' => - 1,
				'post_status'    => 'publish',
				'meta_key'       => '_product_cost',
				'meta_value'     => '',
				'meta_compare'   => '!='
			);

			$products = new WP_Query( $args );

			if ( $products->have_posts() ) {
				while ( $products->have_posts() ) {
					$products->the_post();
					$cost       = (float) get_post_meta( get_the_ID(), '_product_cost', true );
					$total_cost += $cost;
				}
			}
			wp_reset_postdata();
		}

		return $total_cost;
	}

	/**
	 *    Total profit Calculate
	 */
	function wooprofit_total_profit(): float|int {
		return $this->wooprofit_total_price() - $this->wooprofit_total_cost();
	}

	/**
	 * add cost field
	 */
	function wooprofit_add_cost_field(): void {
		woocommerce_wp_text_input(
			array(
				'id'          => '_product_cost',
				'label'       => esc_html( 'Cost', 'wooprofit' ),
				'placeholder' => 'Enter the cost price',
				'desc_tip'    => 'true',
				'description' => esc_html( 'Enter the cost price of the product.', 'wooprofit' )
			)
		);
		echo '<p id="product_profit_display" class="form-field description">'
		     . esc_html( 'Profit: 0.00 (' . esc_html( get_woocommerce_currency_symbol() ) . ' 0.00%)', 'wooprofit' ) . '</p>';

	}

	/**
	 * Save cost field
	 */
	function wooprofit_save_cost_field( $post_id ): void {
		$product_cost = isset( $_POST['_product_cost'] ) ? sanitize_text_field( $_POST['_product_cost'] ) : '';
		update_post_meta( $post_id, '_product_cost', $product_cost );
	}

	/**
	 * Add custom columns to the products admin page
	 **/
	function wooprofit_add_cost_and_profit_column_header( $columns ): array {
		// Remove the existing 'product_cost' and 'product_profit' columns if they already exist
		unset( $columns['product_cost'] );
		unset( $columns['product_profit'] );

		/**
		 *  Insert 'product_cost' and 'product_profit' after 'price' column
		 */

		$new_columns = array();
		foreach ( $columns as $key => $column ) {
			$new_columns[ $key ] = $column;
			if ( 'price' === $key ) {
				$new_columns['product_cost']   = esc_html( 'Cost', 'wooprofit' );
				$new_columns['product_profit'] = esc_html( 'Profit', 'wooprofit' );
			}
		}

		return $new_columns;
	}

	/**
	 * Populate custom columns with data
	 */
	function wooprofit_populate_cost_and_profit_column_content( $column, $post_id ): void {
		if ( 'product_cost' === $column ) {
			$product_cost = get_post_meta( $post_id, '_product_cost', true );
			if ( $product_cost !== '' ) {
				echo esc_html( number_format( (float) $product_cost, 2 ) . esc_html( get_woocommerce_currency_symbol() ) );
			} else {
				echo '-';
			}
		}

		if ( 'product_profit' === $column ) {
			$product_price = get_post_meta( $post_id, '_price', true );
			$product_cost  = get_post_meta( $post_id, '_product_cost', true );

			if ( $product_price && $product_cost !== '' ) {
				$profit            = $product_price - $product_cost;
				$profit_percentage = ( $product_cost > 0 ) ? ( $profit / $product_cost ) * 100 : 0;
				echo esc_html( number_format( (float) $profit, 2 ) . esc_html( get_woocommerce_currency_symbol() ) . ' (' . number_format( $profit_percentage,
						2 ) . '%)' );
			} else {
				echo '-';
			}
		}
	}

	/**
	 * create cost and profit columns
	 */
	function wooprofit_make_cost_and_profit_column_sortable( $columns ) {
		$columns['product_cost']   = 'product_cost';
		$columns['product_profit'] = 'product_profit';

		return $columns;
	}

	/**
	 *  cost and profit columns sorting
	 */
	function wooprofit_sort_cost_and_profit_columns( $query ): void {
		if ( ! is_admin() || ! $query->is_main_query() ) {
			return;
		}
		$orderby = $query->get( 'orderby' );

		if ( 'product_cost' === $orderby ) {
			$query->set( 'meta_key', '_product_cost' );
			$query->set( 'orderby', 'meta_value_num' );
		}

		if ( 'product_profit' === $orderby ) {
			$query->set( 'meta_key', '_product_profit' );
			$query->set( 'orderby', 'meta_value_num' );
		}
	}

	/**
	 *  WooProfit Margin Admin Page
	 */
	function wooprofit_page(): void {
		include_once plugin_dir_path( __FILE__ ) . 'templates/dashboard.php';
	}

	/**
	 * Date range
	 */
	function wooprofit_get_orders_by_date_range(): void {

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_die( esc_html( 'You do not have sufficient permissions to access this page.', 'wooprofit' ) );
		}

		$start_date = isset( $_POST['start_date'] ) ? sanitize_text_field( $_POST['start_date'] ) : '';
		$end_date   = isset( $_POST['end_date'] ) ? sanitize_text_field( $_POST['end_date'] ) : '';

		if ( ! $start_date || ! $end_date ) {
			echo json_encode( [
				'error'   => true,
				'message' => esc_html( 'Please select a valid date range.', 'wooprofit' ),
			] );
			wp_die();
		}

		$args = array(
			'limit'        => - 1,
			'status'       => array( 'wc-completed', 'wc-processing', 'wc-on-hold' ),
			'date_created' => $start_date . '...' . $end_date,
		);

		$orders = wc_get_orders( $args );

		if ( ! empty( $orders ) ) {
			$total_orders = count( $orders );
			$total_sales  = 0;
			$total_cost   = 0;

			foreach ( $orders as $order ) {
				$total_sales += $order->get_total();

				foreach ( $order->get_items() as $item ) {
					$product_id   = $item->get_product_id();
					$product_cost = get_post_meta( $product_id, '_product_cost', true );
					$product_cost = $product_cost ? floatval( $product_cost ) : 0;
					$total_cost   += $product_cost * $item->get_quantity();
				}
			}


			$total_profit         = $total_sales - $total_cost;
			$profit_percentage    = $total_sales ? ( $total_profit / $total_sales ) * 100 : 0;
			$profit_class         = $total_profit > 0 ? 'profit-positive' : 'profit-negative';
			$average_order_value  = $total_orders ? $total_sales / $total_orders : 0;
			$average_profit       = $total_profit / ( ( strtotime( $end_date ) - strtotime( $start_date ) ) / ( 60 * 60 * 24 ) + 1 );
			$average_order_profit = $total_orders ? $total_profit / $total_orders : 0;

			$chart_data = [
				'orders' => [$total_orders],
				'profits' => [$total_profit],
				'sales' => [$total_sales],
				'labels' => [$start_date, $end_date]
			];
			echo json_encode( array(
				'total_orders'         => $total_orders,
				'total_sales'          => wc_price( $total_sales ),
				'total_cost'           => wc_price( $total_cost ),
				'average_order_value'  => wc_price( $average_order_value ),
				'profit'               => wc_price( $total_profit ),
				'profit_percentage'    => round( $profit_percentage, 2 ) . '%',
				'profit_class'         => $profit_class,
				'average_profit'       => wc_price( $average_profit ),
				'average_order_profit' => wc_price( $average_order_profit ),
				'chart_data'           => $chart_data
			) );
		} else {

			echo json_encode( array(
				'total_orders'         => 0,
				'total_sales'          => wc_price( 0 ),
				'total_cost'           => wc_price( 0 ),
				'average_order_value'  => wc_price( 0 ),
				'profit'               => wc_price( 0 ),
				'profit_percentage'    => '0%',
				'profit_class'         => 'profit-negative',
				'average_profit'       => wc_price( 0 ),
				'average_order_profit' => wc_price( 0 ),
				'chart_data'           => $chart_data
			) );
		}
		wp_reset_postdata();
		wp_die();
	}
}

new Wooprofit();
