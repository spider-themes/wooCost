<?php

/**
 * Date range function according to date range
 *
 * @return void
 */

function woocost_get_orders_by_date_range(): void {

	global $wpdb;
	if ( ! current_user_can( 'manage_woocommerce' ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'woocost' ) );
	}

	$start_date = isset( $_POST['start_date'] ) ? sanitize_text_field( $_POST['start_date'] ) : '';
	$end_date   = isset( $_POST['end_date'] ) ? sanitize_text_field( $_POST['end_date'] ) : '';

	if ( ! $start_date || ! $end_date ) {
		echo json_encode( [
			'error'   => true,
			'message' => esc_html__( 'Please select a valid date range.', 'woocost' ),
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
				$product_id = $item->get_product_id();

				// Default to using '_woo_product_cost'
				$product_cost = (float) get_post_meta( $product_id, '_woo_product_cost', true );

				// If 'Cost of Goods for WooCommerce' is activated, use '_alg_wc_cog_cost' instead
				if ( class_exists( 'Alg_WC_Cost_of_Goods' ) ) {
					$alg_cost = get_post_meta( $product_id, '_alg_wc_cog_cost', true );
					if ( ! empty( $alg_cost ) ) {
						$product_cost = (float) $alg_cost;
					}
				}

				$total_cost += $product_cost * $item->get_quantity();
			}
		}

		$currency_symbol = get_woocommerce_currency_symbol();

		$total_profit      = $total_sales - $total_cost;
		$profit_percentage = $total_sales ? ( $total_profit / $total_sales ) * 100 : 0;
		$profit_class      = $total_profit > 0 ? 'profit-positive' : 'profit-negative';

		$average_profit       = $total_profit / ( ( strtotime( $end_date ) - strtotime( $start_date ) ) / ( 60 * 60 * 24 ) + 1 );
		$average_order_profit = $total_orders ? $total_profit / $total_orders : 0;

		$chart_data = [
			'orders'  => [ $total_orders ],
			'profits' => [ $total_profit ],
			'sales'   => [ $total_sales ],
			'labels'  => [ $start_date, $end_date ]
		];

		echo json_encode( array(
			'total_orders'         => $total_orders,
			'total_sales'          => $currency_symbol . number_format( $total_sales, 2 ),
			'total_cost'           => $currency_symbol . number_format( $total_cost, 2 ),
			'profit'               => $currency_symbol . number_format( $total_profit, 2 ),
			'profit_percentage'    => round( $profit_percentage, 2 ) . '%',
			'profit_class'         => $profit_class,
			'average_profit'       => $currency_symbol . number_format( $average_profit, 2 ),
			'average_order_profit' => $currency_symbol . number_format( $average_order_profit, 2 ),
			'chart_data'           => $chart_data
		) );
	} else {
		$currency_symbol = get_woocommerce_currency_symbol();

		echo json_encode( array(
			'total_orders'         => '0',
			'total_sales'          => $currency_symbol . '0.00',
			'total_cost'           => $currency_symbol . '0.00',
			'profit'               => $currency_symbol . '0.00',
			'profit_percentage'    => '0%',
			'profit_class'         => 'profit-negative',
			'average_profit'       => $currency_symbol . '0.00',
			'average_order_profit' => $currency_symbol . '0.00',
			'chart_data'           => []
		) );
	}
	wp_reset_postdata();
	wp_die();
}
add_action( 'wp_ajax_woocost_get_orders_by_date_range', 'woocost_get_orders_by_date_range' );
add_action( 'wp_ajax_nopriv_woocost_get_orders_by_date_range', 'woocost_get_orders_by_date_range' );