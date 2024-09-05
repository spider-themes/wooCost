<?php
/**
 * meta box sidebar register for order items edit
 */
add_action( 'add_meta_boxes',  'woocost_add_cost_profit_meta_box');
/**
 * Meta box function
 *
 * @return void
 */
function woocost_add_cost_profit_meta_box(): void {
	global $pagenow;
	$current_screen = get_current_screen();
	// Check if we are on the WooCommerce order edit page
	if ( $pagenow == 'admin.php' && $current_screen->id == 'woocommerce_page_wc-orders' ) {
		add_meta_box(
			'cost_profit_meta_box',
			esc_html__( 'Cost and Profit', 'woocost' ),
			'woocost_cost_profit_meta_box_html',
			'woocommerce_page_wc-orders',
			'side',
			'high'
		);
	}
}

/**
 * Meta box output function
 *
 * @param $post
 *
 * @return void
 */
function woocost_cost_profit_meta_box_html( $post ): void {

	// Get the order object
	$order = wc_get_order( $post->ID );
	if ( ! $order ) {
		error_log( 'Order not found' ); // Debug log

		return;
	}

	$total_cost = 0;

	// Loop through order items to calculate the total cost
	foreach ( $order->get_items() as $item ) {
		$product = $item->get_product();
		if ( $product ) {
			$quantity   = $item->get_quantity();
			$cost       = (float) get_post_meta( $product->get_id(), '_woo_product_cost', true ) * $quantity;
			$total_cost += $cost;
		}
	}

	// Calculate total sales and profit
	$total_sales  = $order->get_total();
	$total_profit = $total_sales - $total_cost;

	// Display cost and profit
	echo '<div>';
	echo '<p><strong>' . esc_html__( 'Cost:', 'woocost' ) . '</strong> ' . '<span style="color: #FF0000FF ;">' . wc_price( $total_cost ) . '</span>' . '</p>';
	echo '<p><strong>' . esc_html__( 'Profit:', 'woocost' ) . '</strong> ' . '<span style="color: #008000FF;">' . wc_price( $total_profit ) . '</span>'
	     . '</p>';
	echo '</div>';

}