<?php
$woocost = Woocost::get_instance();

/**
 * Comparison Query method
 *
 * @return void
 */
function woocost_compare_monthly_orders(): void {
	check_ajax_referer( 'woocost_compare_data', 'nonce' );

	// Retrieve date ranges from POST data
	$current_start_date = isset( $_POST['current_start_date'] ) ? sanitize_text_field( $_POST['current_start_date'] ) : date( 'Y-m-01' );
	$current_end_date   = isset( $_POST['current_end_date'] ) ? sanitize_text_field( $_POST['current_end_date'] ) : date( 'Y-m-t' );
	$prev_start_date    = sanitize_text_field( $_POST['prev_start_date'] );
	$prev_end_date      = sanitize_text_field( $_POST['prev_end_date'] );

	if ( ! empty( $prev_start_date ) && ! empty( $prev_end_date ) ) {
		$current_data  = woocost_get_order_data( $current_start_date, $current_end_date );
		$previous_data = woocost_get_order_data( $prev_start_date, $prev_end_date );

		$order_percentage_change                = woocost_calculate_percentage_change( $current_data['total_orders'], $previous_data['total_orders'] );
		$total_sales_percentage_change          = woocost_calculate_percentage_change( $current_data['total_sales'], $previous_data['total_sales'] );
		$total_cost_percentage_change           = woocost_calculate_percentage_change( $current_data['total_cost'], $previous_data['total_cost'] );
		$average_order_profit_percentage_change = woocost_calculate_percentage_change( $current_data['average_order_profit'],
			$previous_data['average_order_profit'] );
		$total_profit_percentage_change         = woocost_calculate_percentage_change( $current_data['total_profit'], $previous_data['total_profit'] );
		$average_daily_profit_percentage_change = woocost_calculate_percentage_change( $current_data['average_daily_profit'],
			$previous_data['average_daily_profit'] );

		$currency_symbol = get_woocommerce_currency_symbol();

		$result = [
			'success' => true,
			'data'    => [
				'current_month'                          => $current_data,
				'previous_month'                         => $previous_data,
				'order_percentage_change'                => $order_percentage_change,
				'total_sales_percentage_change'          => $total_sales_percentage_change,
				'total_cost_percentage_change'           => $total_cost_percentage_change,
				'average_order_profit_percentage_change' => $average_order_profit_percentage_change,
				'total_profit_percentage_change'         => $total_profit_percentage_change,
				'average_daily_profit_percentage_change' => $average_daily_profit_percentage_change,
				'currency_symbol'                        => $currency_symbol
			]
		];

		wp_send_json( $result );
	} else {
		wp_send_json( [
			'success' => false,
			'message' => 'Invalid date ranges.'
		] );
	}
}

add_action( 'wp_ajax_woocost_compare_monthly_orders', 'woocost_compare_monthly_orders' );
add_action( 'wp_ajax_nopriv_woocost_compare_monthly_orders',  'woocost_compare_monthly_orders' );
/**
 * Get order data according to date range
 *
 * @param $start_date
 * @param $end_date
 *
 * @return array
 */
function woocost_get_order_data( $start_date, $end_date ): array {
	$orders = wc_get_orders( [
		'limit'        => - 1,
		'orderby'      => 'date',
		'order'        => 'DESC',
		'date_created' => $start_date . '...' . $end_date,
	] );

	$total_sales  = 0;
	$total_cost   = 0;
	$total_profit = 0;
	$order_count  = count( $orders );

	foreach ( $orders as $order ) {
		$total  = $order->get_total();
		$cost   = woocost_get_order_cost( $order );
		$profit = $total - $cost;

		$total_sales  += $total;
		$total_cost   += $cost;
		$total_profit += $profit;
	}

	$average_order_profit = $order_count > 0 ? $total_profit / $order_count : 0;
	$average_daily_profit = $total_profit / ( ( strtotime( $end_date ) - strtotime( $start_date ) ) / ( 60 * 60 * 24 ) + 1 );

	return [
		'total_orders'         => $order_count,
		'total_sales'          => $total_sales,
		'total_cost'           => $total_cost,
		'total_profit'         => $total_profit,
		'average_order_profit' => $average_order_profit,
		'average_daily_profit' => $average_daily_profit,
	];
}

/**
 * Calculate percentage change between two values
 *
 * @param $current_value
 * @param $previous_value
 *
 * @return float|int
 */
function woocost_calculate_percentage_change( $current_value, $previous_value ): float|int {
	if ( $previous_value == 0 ) {
		return $current_value > 0 ? 100 : 0; // Avoid division by zero, handle cases where previous value is zero
	}

	return ( ( $current_value - $previous_value ) / $previous_value ) * 100;
}


/**
 * Custom order cost function
 *
 * @param $order
 *
 * @return float|int
 */
function woocost_get_order_cost( $order ): float|int {
	$cost = 0;
	foreach ( $order->get_items() as $item_id => $item ) {
		$product_id = $item->get_product_id();
		$product    = wc_get_product( $product_id );
		if ( $product ) {
			$cost += $product->get_meta( '_woo_product_cost', true ) * $item->get_quantity();
		}
	}

	return $cost;
}