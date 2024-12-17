<?php
namespace wooCost\Admin;

class Menu{

	public function __construct(){
		add_action( 'admin_menu', [ $this, 'admin_menu' ], 99 );
	}

	/**
	 * Set admin menu for woocost
	 * @return void
	 */
	public function admin_menu(): void {
		add_menu_page(
			esc_html( __( 'WooCost', 'woocost' ) ),
			esc_html( __( 'WooCost', 'woocost' ) ),
			'manage_woocommerce',
			'woocost',
			[ $this, 'woocost_page' ],
			'dashicons-money-alt',
			26
		);

		/**
		 * Rename the parent menu when displayed as a submenu
		 */
		add_submenu_page(
			'woocost',
			esc_html( __( 'Overview', 'woocost' ) ),
			esc_html( __( 'Overview', 'woocost' ) ),
			'manage_woocommerce',
			'woocost',
			[ $this, 'woocost_page' ],
			1
		);

		/**
		 * Submenu: Bulk Discounts
		 */
		add_submenu_page(
			'woocost',
			esc_html( __( 'Bulk Discount', 'woocost' ) ),
			esc_html( __( 'Bulk Discount', 'woocost' ) ),
			'manage_woocommerce',
			'bulk-discounts',
			[ $this, 'bulk_discounts_page' ],
			2
		);

		/**
		 * Submenu: Operation Cost
		 */
		// add_submenu_page(
		add_submenu_page(
        'woocost',
        'Cost Operation',
        'Cost Operation',
        'manage_options',
        'edit.php?post_type=cost_operation',
        '',
        3
    );

		/**
		 * Submenu: generate image
		 */
		add_submenu_page(
			'woocost',
			esc_html( __( 'Generate Product Image', 'woocost' ) ),
			esc_html( __( 'Generate Product Image', 'woocost' ) ),
			'manage_woocommerce',
			'image-generate',
			[ $this, 'image_generate_page' ],
			4
		);
	}

	/**
	 * WooCost Admin Page
	 * @return void
	 */
	public function woocost_page(): void {
		include_once plugin_dir_path( __FILE__ ) . 'templates/dashboard.php';
	}
	public function image_generate_page(): void {
		include_once plugin_dir_path( __FILE__ ) . 'templates/product-image-generate.php';
	}

	public function bulk_discounts_page(): void {

		?>

		<form method="post">
			
			<?php include_once plugin_dir_path( __FILE__ ) . 'templates/bulk-discount.php'; ?>

		</form>
		
		<?php


	}

	public function operation_cost_page(): void {
		include_once plugin_dir_path( __FILE__ ) . 'templates/cost-operation.php';
	}
}