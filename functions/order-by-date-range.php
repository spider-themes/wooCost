<?php

/**
 * Date range function according to date range
 *
 * @return void
 */

function woocost_get_orders_by_date_range(): void
{

	global $wpdb;
	if (!current_user_can('manage_woocommerce')) {
		wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'woocost'));
	}

	// Get and sanitize input values
	$date_range = isset($_POST['date_range']) ? sanitize_text_field($_POST['date_range']) : 'today';

	// Define the date range based on the selected option
	switch ($date_range) {
		case 'today':
			$start_date = gmdate('Y-m-d');
			$end_date = gmdate('Y-m-d');
			break;

		case 'yesterday':
			$start_date = gmdate('Y-m-d', strtotime('-1 day'));
			$end_date = $start_date;
			break;

		case 'last-7-days':
			$start_date = gmdate('Y-m-d', strtotime('-7 days'));
			$end_date = gmdate('Y-m-d');
			break;

		case 'last-14-days':
			$start_date = gmdate('Y-m-d', strtotime('-14 days'));
			$end_date = gmdate('Y-m-d');
			break;

		case 'this-month':
			$start_date = gmdate('Y-m-01');  // First day of the current month
			$end_date = gmdate('Y-m-d');     // Current day of the month
			break;

		case 'last-month':
			$start_date = gmdate('Y-m-01', strtotime('first day of last month'));
			$end_date = gmdate('Y-m-t', strtotime('last day of last month'));
			break;
		case 'all-time':
			$start_date = '';
			$end_date 	= '';
			break;

		default:
			$start_date = !empty($_POST['start_date']) ? sanitize_text_field($_POST['start_date']) : gmdate('Y-m-d');
			$end_date = !empty($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : gmdate('Y-m-d');
			break;
	}


	// Initialize totals for the selected date range
	$total_sales = 0;
	$total_cost = 0;
	$total_orders = 0;
	// Initialize comparison variables
	$comp_total_sales = 0;
	$comp_total_cost = 0;
	$comp_total_orders = 0;
	$comp_total_profit = 0;
	$comp_average_daily_profit = 0;
	$comp_average_order_profit = 0;
	$total_stock = 0;
	$total_price = 0;

	// Fetch orders using the date_after and date_before structure
	// $orders = wc_get_orders(array(
	// 	'limit' => -1,
	// 	'status' => array('wc-completed', 'wc-processing', 'wc-on-hold'),
	// 	'date_after' => $start_date . ' 00:00:00',
	// 	'date_before' => $end_date . ' 23:59:59',
	// 	'return' => 'ids',
	// ));

	$args = array(
		'limit' => -1,
		'status' => array('wc-completed', 'wc-processing', 'wc-on-hold'),
		'return' => 'ids',
	);
	
	// Only add date filters if both start and end dates are provided
	if (!empty($start_date) && !empty($end_date)) {
		$args['date_after'] = $start_date . ' 00:00:00';
		$args['date_before'] = $end_date . ' 23:59:59';
	}
	
	$orders = wc_get_orders($args);
	

	$total_orders = count($orders);

	if (!empty($orders)) {
		foreach ($orders as $order_ids) {
			$order = wc_get_order($order_ids);
			$total_sales += $order->get_total();

			foreach ($order->get_items() as $item) {
				$product_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();
				$quantity = $item->get_quantity();

				// Get the product object
				$product = wc_get_product($product_id);
				if ($product) {
					// Calculate total stock (of the product, if it has stock management enabled)
					$total_stock += $product->get_stock_quantity() ? $product->get_stock_quantity() : 0;

					// Calculate total price (price * quantity in the order)
					$total_price += $product->get_price() * $quantity;

					// Get product cost
					$product_cost = (float) get_post_meta($product_id, '_woo_product_cost', true);

					// If 'Cost of Goods for WooCommerce' is activated, use '_alg_wc_cog_cost' instead
					if (class_exists('Alg_WC_Cost_of_Goods')) {
						$alg_cost = get_post_meta($product_id, '_alg_wc_cog_cost', true);
						if (!empty($alg_cost)) {
							$product_cost = (float) $alg_cost;
						}
					}

					// Calculate total cost (cost * quantity in the order)
					$total_cost += $product_cost * $quantity;
				}
			}
		}
	}

	// Calculate total potential profit
	$total_potential_profit = $total_price - $total_cost;

	// Calculation for total profit
	$total_profit = $total_sales - $total_cost;

	// Calculate average daily profit based on the date range length
	$date_diff = (strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24) + 1; // Add 1 to include the end date
	$average_daily_profit = ($date_diff > 0) ? ($total_profit / $date_diff) : $total_profit;

	// Calculate average order profit
	$average_order_profit = ($total_orders > 0) ? ($total_profit / $total_orders) : 0;

	// --------------- Comparision Values -----------------.
	// Set comparison dates based on user input or fallback to selected date range
	$comparison = !empty($_POST['comparison']) ? sanitize_text_field($_POST['comparison']) : 'custom-date';
	$prevStartDate = !empty($_POST['prevStartDate']) ? sanitize_text_field($_POST['prevStartDate']) : $start_date;
	$prevEndDate = !empty($_POST['prevEndDate']) ? sanitize_text_field($_POST['prevEndDate']) : $end_date;

	// Fetch comparison data
	$comparison_orders = wc_get_orders(array(
		'limit' => -1,
		'status' => array('wc-completed', 'wc-processing', 'wc-on-hold'),
		'date_after' => $prevStartDate . ' 00:00:00',
		'date_before' => $prevEndDate . ' 23:59:59',
		'return' => 'ids',
	));

	$comp_total_orders = count($comparison_orders);

	if (!empty($comparison_orders)) {
		foreach ($comparison_orders as $order_ids) {

			$order = wc_get_order($order_ids);
			$comp_total_sales += $order->get_total();
			foreach ($order->get_items() as $item) {
				$product_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();

				// Default to using '_woo_product_cost'
				$product_cost = (float) get_post_meta($product_id, '_woo_product_cost', true);

				// If 'Cost of Goods for WooCommerce' is activated, use '_alg_wc_cog_cost' instead
				if (class_exists('Alg_WC_Cost_of_Goods')) {
					$alg_cost = get_post_meta($product_id, '_alg_wc_cog_cost', true);
					if (!empty($alg_cost)) {
						$product_cost = (float) $alg_cost;
					}
				}

				$comp_total_cost += $product_cost * $item->get_quantity();
			}
		}

		// Comparison profit and averages
		$comp_total_profit = $comp_total_sales - $comp_total_cost;

		// Calculate average daily profit for comparison
		$comp_date_diff = (strtotime($prevEndDate) - strtotime($prevStartDate)) / (60 * 60 * 24) + 1;
		$comp_average_daily_profit = ($comp_date_diff > 0) ? ($comp_total_profit / $comp_date_diff) : $comp_total_profit;

		// Calculate average order profit for comparison
		$comp_average_order_profit = ($comp_total_orders > 0) ? ($comp_total_profit / $comp_total_orders) : 0;
	}

	// Function to calculate percentage difference
	function calculate_percentage_change($current, $previous)
	{
		if ($previous == 0) {
			return ($current > 0) ? 100 : 0;
		}
		return (($current - $previous) / $previous) * 100;
	}

	// Calculate percentage change for each metric
	$order_percentage_change = calculate_percentage_change($total_orders, $comp_total_orders);
	$total_sales_percentage_change = calculate_percentage_change($total_sales, $comp_total_sales);
	$total_cost_percentage_change = calculate_percentage_change($total_cost, $comp_total_cost);
	$total_profit_percentage_change = calculate_percentage_change($total_profit, $comp_total_profit);
	$average_daily_profit_percentage_change = calculate_percentage_change($average_daily_profit, $comp_average_daily_profit);
	$average_order_profit_percentage_change = calculate_percentage_change($average_order_profit, $comp_average_order_profit);

	// Prepare data to return
	$data = array(
		'total_orders' => $total_orders,
		'total_sales' => wc_price(($total_sales)),  // Format the total sales as a price
		'total_cost' => wc_price(($total_cost)),    // Format the total cost as a price
		'total_profit' => wc_price(($total_profit)), // Format the total profit as a price
		'average_daily_profit' => wc_price(($average_daily_profit)), // Format the average daily profit as a price
		'average_order_profit' => wc_price(($average_order_profit)), // Format the average order profit as a price
		'order_percentage_change' => number_format($order_percentage_change, 2),
		'total_sales_percentage_change' => number_format($total_sales_percentage_change, 2),
		'total_cost_percentage_change' => number_format($total_cost_percentage_change, 2),
		'total_profit_percentage_change' => number_format($total_profit_percentage_change, 2),
		'average_daily_profit_percentage_change' => number_format($average_daily_profit_percentage_change, 2),
		'average_order_profit_percentage_change' => number_format($average_order_profit_percentage_change, 2),
		'total_potential_profit' => number_format($total_potential_profit, 2),
		'total_stock' => number_format($total_stock, 2),
		'total_price' => number_format($total_price, 2),
		'start_date' => $start_date,
		'end_date' => $end_date,
		'comparison' => $comparison,
		'prev_start_date' => $prevStartDate,
		'prev_end_date' => $prevEndDate,
		'comp_total_orders' => $comp_total_orders,
		'comp_total_sales' => wc_price($comp_total_sales),
		'comp_total_cost' => wc_price($comp_total_cost),
		'comp_total_profit' => wc_price($comp_total_profit),
		'comp_average_daily_profit' => wc_price($comp_average_daily_profit),
		'comp_average_order_profit' => wc_price($comp_average_order_profit),
		'testing_info' => $comparison_orders,
	);

	// Send the data as JSON
	wp_send_json_success($data);

	wp_die();
}
add_action('wp_ajax_woocost_get_orders_by_date_range', 'woocost_get_orders_by_date_range');
add_action('wp_ajax_nopriv_woocost_get_orders_by_date_range', 'woocost_get_orders_by_date_range');


add_action( 'in_admin_header', function () {

    $current_screen = get_current_screen();

    if( $current_screen->id == 'toplevel_page_woocost' ) {
        remove_all_actions( 'admin_notices' );
        remove_all_actions( 'all_admin_notices' );
    }
}, 20 );




