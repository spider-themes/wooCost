<?php

/**
 * add Cost column in order items page
 *
 * @return void
 */
function woocost_add_cost_column_header(): void {
	echo '<th class="cost">' . esc_html__( 'Woo Cost', 'woocost' ) . '</th>';
}
add_action( 'woocommerce_admin_order_item_headers', 'woocost_add_cost_column_header' );
/**
 * Add cost column value
 *
 * @param $product
 * @param $item
 * @param $item_id
 *
 * @return void
 */
function woocost_add_cost_column_value( $product, $item, $item_id ): void {
	// Ensure $product is a valid WC_Product object
	if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
		echo '<td class="cost">' . esc_html__( 'N/A', 'woocost' ) . '</td>';

		return;
	}

	$product_id = $product->get_id();
	$cost       = get_post_meta( $product_id, '_woo_product_cost', true );
	echo '<td class="cost">' . wc_price( $cost ) . '</td>';
}
add_action( 'woocommerce_admin_order_item_values', 'woocost_add_cost_column_value',  10, 3 );


/**
 * Save column order item cost
 *
 * @param $item_id
 *
 * @return void
 * @throws Exception
 */
function woocost_save_custom_order_item_cost( $item_id ): void {
	if ( isset( $_POST['order_item_cost'][ $item_id ] ) ) {
		$cost = wc_clean( $_POST['order_item_cost'][ $item_id ] );
		wc_update_order_item_meta( $item_id, '_woo_product_cost', $cost );
	}
}
add_action( 'woocommerce_before_order_itemmeta_update',  'woocost_save_custom_order_item_cost' , 10, 1 );

/**
 * Add order cost column
 *
 * @param $columns
 *
 * @return mixed
 */
function woocost_add_order_cost_column( $columns ): mixed {
	$columns['order_cost'] = esc_html__( 'New Cost', 'woocost' );

	return $columns;
}
add_filter( 'manage_edit-shop_order_columns', 'woocost_add_order_cost_column' , 20 );

/**
 * Populate order cost column
 *
 * @param $column
 * @param $post_id
 *
 * @return void
 */
 function woocost_populate_order_cost_column( $column, $post_id ): void {
	if ( $column === 'order_cost' ) {
		$order      = wc_get_order( $post_id );
		$items      = $order->get_items();
		$total_cost = 0;

		foreach ( $items as $item_id => $item ) {
			$product = $item->get_product();
			if ( $product ) {
				$cost       = get_post_meta( $product->get_id(), '_woo_product_cost', true );
				$total_cost += $cost * $item->get_quantity();
			}
		}

		echo wc_price( $total_cost );
	}
}
add_action( 'manage_shop_order_posts_custom_column',  'woocost_populate_order_cost_column' , 10, 2 );
