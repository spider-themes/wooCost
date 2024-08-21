<?php
namespace wooProfit\Admin;

class Menu{

	function __construct(){
		add_action( 'admin_menu', [ $this, 'admin_menu' ], 99 );
	}

	/**
	 * Set admin menu for wooprofit
	 * @return void
	 */
	public function admin_menu(): void {
		add_menu_page(
			esc_html( __( 'WooProfit', 'wooprofit' ) ),
			esc_html( __( 'WooProfit', 'wooprofit' ) ),
			'manage_woocommerce',
			'wooprofit',
			[ $this, 'wooprofit_page' ],
			'dashicons-money-alt',
			26
		);

		/**
		 * Rename the parent menu when displayed as a submenu
		 */
		add_submenu_page(
			'wooprofit',
			esc_html( __( 'Overview', 'wooprofit' ) ),
			esc_html( __( 'Overview', 'wooprofit' ) ),
			'manage_woocommerce',
			'wooprofit',
			[ $this, 'wooprofit_page' ]
		);

		/**
		 * Submenu: Bulk Discounts
		 */
		add_submenu_page(
			'wooprofit',
			esc_html( __( 'Bulk Discount', 'wooprofit' ) ),
			esc_html( __( 'Bulk Discount', 'wooprofit' ) ),
			'manage_woocommerce',
			'bulk-discounts',
			[ $this, 'bulk_discounts_page' ]
		);

		/**
		 * Submenu: Operation Cost
		 */
		add_submenu_page(
			'wooprofit',
			esc_html( __( 'Cost of Operation', 'wooprofit' ) ),
			esc_html( __( 'Cost of Operation', 'wooprofit' ) ),
			'manage_woocommerce',
			'operation-cost',
			[ $this, 'operation_cost_page' ]
		);

		/**
		 * Submenu: generate image
		 */
		add_submenu_page(
			'wooprofit',
			esc_html( __( 'Generate Product Image', 'wooprofit' ) ),
			esc_html( __( 'Generate Product Image', 'wooprofit' ) ),
			'manage_woocommerce',
			'image-generate',
			[ $this, 'image_generate_page' ]
		);
	}

	/**
	 * WooProfit Admin Page
	 * @return void
	 */
	public function wooprofit_page(): void {
		include_once plugin_dir_path( __FILE__ ) . 'templates/dashboard.php';
	}
	public function image_generate_page(): void {
		include_once plugin_dir_path( __FILE__ ) . 'templates/product-image-generate.php';
	}

	public function bulk_discounts_page(): void {
		include_once plugin_dir_path( __FILE__ ) . 'templates/bulk-discount.php';
	}

	public function operation_cost_page(): void {
		include_once plugin_dir_path( __FILE__ ) . 'templates/cost-operation.php';
	}
}