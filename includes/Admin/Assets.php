<?php
namespace wooProfit\Admin;
/**
 * Class Assets
 * @package Wooprofit\Admin
 */
class Assets {
	/**
	 * Assets constructor.
	 */
    public function __construct() {
	    /**
	     * Enqueue Assets
	     */
	    add_action( 'admin_enqueue_scripts', [ $this, 'assets_load' ] );
    }

	/**
	 * Asset loading
	 *
	 * @param $hook
	 *
	 * @return void
	 */
	public function assets_load( $hook ): void {
		$screen         = get_current_screen();

		if ( $screen->id === 'wooprofit_page_bulk-discounts' ) {
			wp_enqueue_style( 'bulk-style', WOOPROFIT_ASSETS . '/css/bulk-dicount.css' );
			wp_enqueue_script( 'bulk-discount', WOOPROFIT_ASSETS . '/js/bulk-discount.js', array(), WOOPROFIT_VERSION,
				[ 'in_footer' => true, 'strategy' => 'defer' ] );
		}elseif ($screen->id === 'wooprofit_page_operation-cost') {
			wp_enqueue_style( 'bulk-cost-style', WOOPROFIT_ASSETS . '/css/cost-operation.css' );
		}elseif ( $screen->id === 'wooprofit_page_image-generate' ) {
			wp_enqueue_style( 'bulk-cost-style', WOOPROFIT_ASSETS . '/css/product-image-generate.css' );
			wp_enqueue_style( 'smartwizardcdn', '//cdn.jsdelivr.net/npm/smartwizard@6/dist/css/smart_wizard_all.min.css' );

			wp_enqueue_script( 'smartwizard', '//cdn.jsdelivr.net/npm/smartwizard@6/dist/js/jquery.smartWizard.min.js', array(), WOOPROFIT_VERSION,
				[ 'in_footer' => true, 'strategy' => 'defer' ] );
			wp_enqueue_script( 'smartwizard-custom', WOOPROFIT_ASSETS . '/js/product-image-generate.js', array(), WOOPROFIT_VERSION,
				[ 'in_footer' => true, 'strategy' => 'defer' ] );
		}

		if ( $screen->id === 'toplevel_page_wooprofit' ) {
			wp_enqueue_style( 'wooprofit-style', WOOPROFIT_ASSETS . '/css/style.css' );
			wp_enqueue_style( 'wooprofit-nice', WOOPROFIT_ASSETS . '/css/nice-select.css' );
			wp_enqueue_style( 'google-font', '//fonts.googleapis.com/css?family=Montserrat' );
			wp_enqueue_style( 'font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css' );
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_style( 'jquery-ui', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css' );
			wp_enqueue_script( 'tooltip', WOOPROFIT_ASSETS . '/js/tooltip.js', array( 'jquery' ), WOOPROFIT_VERSION,
				[ 'in_footer' => true, 'strategy' => 'defer' ] );
			wp_enqueue_script( 'custom-date-range-script', WOOPROFIT_ASSETS . '/js/custom-date-range.js', array( 'jquery' ), WOOPROFIT_VERSION,
				[ 'in_footer' => true, 'strategy' => 'defer' ] );
			wp_enqueue_script( 'nice-select', WOOPROFIT_ASSETS . '/js/jquery.nice-select.min.js', array(), WOOPROFIT_VERSION,
				[ 'in_footer' => true, 'strategy' => 'defer' ] );
			wp_localize_script( 'custom-date-range-script', 'ajax_params', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'wooprofit_compare_data' )
			) );
		}
		if ( $hook == 'post.php' || $hook == 'post-new.php' ) {
			global $post_type;
			if ( $post_type == 'product' ) {
				wp_enqueue_script( 'wooprofit', WOOPROFIT_ASSETS . '/js/profit-show.js', array( 'jquery' ), WOOPROFIT_VERSION,
					[ 'in_footer' => true, 'strategy' => 'defer' ] );
			}
		}
	}
}