<?php
namespace wooCost\Notices;

class Notices {

	public function __construct() {
		/**
		 * Remove third-party plugins notice
		 */
		add_action( 'admin_notices', [ $this, 'remove_third_party_admin_notices' ], 100 );
		/**
		 * Remove Default WordPress notice
		 */
		add_action( 'admin_head', [ $this, 'remove_wp_default_notifications' ] );
	}

	/**
	 * Remove third-party notice
	 *
	 * @return void
	 *  remove third party notice
	 */
	public function remove_third_party_admin_notices(): void {
		// Check if we are on the specific plugin page
		$current_screen = get_current_screen();

		if ( $current_screen->id === 'toplevel_page_woocost' ) {
			remove_all_actions( 'admin_notices' );
		}
	}

	/**
	 * remove default notification
	 *
	 * @return void
	 */
	function remove_wp_default_notifications(): void {
		$screen = get_current_screen();
	if ( $screen->id === 'toplevel_page_woocost' || $screen->id === 'woocost_page_bulk-discounts'
		     || $screen->id === 'woocost_page_operation-cost' || $screen->id === 'woocost_page_image-generate'
		) {
			remove_action( 'admin_notices', 'update_nag', 3 );
			// Remove admin footer text
			add_filter( 'admin_footer_text', '__return_empty_string', 11 );
			add_filter( 'update_footer', '__return_empty_string', 11 );
		}

	}

}