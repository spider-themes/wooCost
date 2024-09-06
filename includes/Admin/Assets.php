<?php
namespace wooCost\Admin;
/**
 * Class Assets
 * @package WooCost\Admin
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

		if ( $screen->id === 'woocost_page_bulk-discounts' ) {
			wp_enqueue_style( 'bulk-style', WOOCOST_ASSETS . '/css/bulk-dicount.css' );
			wp_enqueue_script( 'bulk-discount', WOOCOST_ASSETS . '/js/bulk-discount.js', array(), WOOCOST_VERSION,
				[ 'in_footer' => true, 'strategy' => 'defer' ] );

		}elseif ($screen->id === 'woocost_page_operation-cost') {

			wp_enqueue_style( 'bulk-cost-style', WOOCOST_ASSETS . '/css/cost-operation.css' );

		}elseif ( $screen->id === 'woocost_page_image-generate' ) {

			wp_enqueue_style( 'bulk-cost-style', WOOCOST_ASSETS . '/css/product-image-generate.css' );
			wp_enqueue_style( 'smartwizardcdn', '//cdn.jsdelivr.net/npm/smartwizard@6/dist/css/smart_wizard_all.min.css' );

			wp_enqueue_script( 'smartwizard', '//cdn.jsdelivr.net/npm/smartwizard@6/dist/js/jquery.smartWizard.min.js', array(), WOOCOST_VERSION,
				[ 'in_footer' => true, 'strategy' => 'defer' ] );
			wp_enqueue_script( 'smartwizard-custom', WOOCOST_ASSETS . '/js/product-image-generate.js', array(), WOOCOST_VERSION,
				[ 'in_footer' => true, 'strategy' => 'defer' ] );
			wp_enqueue_script( 'fabric', '//cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js', array(), WOOCOST_VERSION,
				[ 'in_footer' => true, 'strategy' => 'defer' ] );
			wp_enqueue_script( 'imageEditor', WOOCOST_ASSETS . '/js/imageEditor.js', array(), WOOCOST_VERSION,
				[ 'in_footer' => true, 'strategy' => 'defer' ] );
			wp_enqueue_style('select2-css', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css');
			wp_enqueue_script('select2-js', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', array(), WOOCOST_VERSION,
				[ 'in_footer' => true, 'strategy' => 'defer' ] );

			wp_enqueue_script( 'product-search', WOOCOST_ASSETS . '/js/product-search.js', array(), WOOCOST_VERSION,
				[ 'in_footer' => true, 'strategy' => 'defer' ] );
			wp_localize_script('product-search', 'product_object', [
				'ajax_url' => admin_url('admin-ajax.php')
			]);

		}elseif ( $screen->id === 'toplevel_page_woocost' ) {
			wp_enqueue_style( 'woocost-style', WOOCOST_ASSETS . '/css/style.css' );
			wp_enqueue_style( 'woocost-nice', WOOCOST_ASSETS . '/css/nice-select.css' );
			wp_enqueue_style( 'google-font', '//fonts.googleapis.com/css?family=Montserrat' );
			wp_enqueue_style( 'font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css' );
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_style( 'jquery-ui', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css' );
			wp_enqueue_script( 'tooltip', WOOCOST_ASSETS . '/js/tooltip.js', array( 'jquery' ), WOOCOST_VERSION,
				[ 'in_footer' => true, 'strategy' => 'defer' ] );
			wp_enqueue_script( 'custom-date-range-script', WOOCOST_ASSETS . '/js/custom-date-range.js', array( 'jquery' ), WOOCOST_VERSION,
				[ 'in_footer' => true, 'strategy' => 'defer' ] );
			wp_enqueue_script( 'nice-select', WOOCOST_ASSETS . '/js/jquery.nice-select.min.js', array(), WOOCOST_VERSION,
				[ 'in_footer' => true, 'strategy' => 'defer' ] );
			wp_localize_script( 'custom-date-range-script', 'ajax_params', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'woocost_compare_data' )
			) );
		}elseif ( $hook == 'post.php' || $hook == 'post-new.php' ) {
			global $post_type;
			if ( $post_type == 'product' ) {
				wp_enqueue_script( 'woocost', WOOCOST_ASSETS . '/js/profit-show.js', array( 'jquery' ), WOOCOST_VERSION,
					[ 'in_footer' => true, 'strategy' => 'defer' ] );
			}
		}else return;
	}
}