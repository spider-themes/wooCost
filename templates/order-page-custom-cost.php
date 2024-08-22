<?php

/**
 * add custom cost in Order
 */
add_action( 'woocommerce_admin_order_item_headers', 'add_cost_column_header' );
add_action( 'woocommerce_admin_order_item_values', 'add_cost_column_value',  10, 3 );
add_action( 'woocommerce_before_order_itemmeta_update',  'save_custom_order_item_cost' , 10, 1 );

add_filter( 'manage_edit-shop_order_columns', 'add_order_cost_column' , 20 );
add_action( 'manage_shop_order_posts_custom_column',  'populate_order_cost_column' , 10, 2 );


/**
 * add Cost column in order items page
 *
 * @return void
 */
function add_cost_column_header(): void {
	echo '<th class="cost">' . esc_html__( 'Woo Cost', 'wooprofit' ) . '</th>';
}
/**
 * Add cost column value
 *
 * @param $product
 * @param $item
 * @param $item_id
 *
 * @return void
 */
function add_cost_column_value( $product, $item, $item_id ): void {
	// Ensure $product is a valid WC_Product object
	if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
		echo '<td class="cost">' . esc_html__( 'N/A', 'wooprofit' ) . '</td>';

		return;
	}

	$product_id = $product->get_id();
	$cost       = get_post_meta( $product_id, '_woo_product_cost', true );
	echo '<td class="cost">' . wc_price( $cost ) . '</td>';
}


/**
 * Save column order item cost
 *
 * @param $item_id
 *
 * @return void
 * @throws Exception
 */
function save_custom_order_item_cost( $item_id ): void {
	if ( isset( $_POST['order_item_cost'][ $item_id ] ) ) {
		$cost = wc_clean( $_POST['order_item_cost'][ $item_id ] );
		wc_update_order_item_meta( $item_id, '_woo_product_cost', $cost );
	}
}

/**
 * Add order cost column
 *
 * @param $columns
 *
 * @return mixed
 */
function add_order_cost_column( $columns ): mixed {
	$columns['order_cost'] = esc_html__( 'New Cost', 'wooprofit' );

	return $columns;
}

/**
 * Populate order cost column
 *
 * @param $column
 * @param $post_id
 *
 * @return void
 */
 function populate_order_cost_column( $column, $post_id ): void {
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