<?php

function woocost_get_product_names(): void {
	if (!isset($_POST['query'])) {
		wp_send_json_error(['message' => 'Invalid request']);
	}

	$query = sanitize_text_field($_POST['query']);

	// Query WooCommerce products
	$args = [
		'post_type' => 'product',
		'posts_per_page' => 10,
		's' => $query,
		'post_status' => 'publish',
	];

	$products = get_posts($args);
	$product_list = [];

	foreach ($products as $product) {
		$product_list[] = [
			'id' => $product->ID,
			'name' => $product->post_title . ' (#' . $product->ID . ')'
		];
	}

	wp_send_json_success($product_list);
}
add_action('wp_ajax_woocost_get_product_names', 'woocost_get_product_names');
add_action('wp_ajax_nopriv_woocost_get_product_names', 'woocost_get_product_names');
