<?php

function woocost_save_bulk_discounts() {
	global $wpdb;

	// Verify nonce
	if ( ! isset( $_POST['woocost_nonce'] ) || ! wp_verify_nonce( $_POST['woocost_nonce'], 'bulk_discounts' ) ) {
		wp_die( __( 'Nonce verification failed!', 'woocost' ) );
	}


	$active_rule      = isset( $_POST['active-rule'] ) ? intval( $_POST['active-rule'] ) : 0;
	$profit_rules     = isset( $_POST['products'] ) ? sanitize_text_field( $_POST['products'] ) : '';
	$discount_rules   = isset( $_POST['discount-application'] ) ? sanitize_text_field( $_POST['discount-application'] ) : '';
	$user_roles       = isset( $_POST['user-role'] ) ? sanitize_text_field( $_POST['user-role'] ) : '';
	$exclude_products = isset( $_POST['active-exclude'] ) ? intval( $_POST['active-exclude'] ) : 0;
	$discount_loop    = isset( $_POST['active-shop'] ) ? intval( $_POST['active-shop'] ) : 0;

	// Table name with the WordPress prefix
	$table_name = $wpdb->prefix . 'bulk_discounts';

	$data = $wpdb->get_row( "SELECT * FROM $table_name LIMIT 1", ARRAY_A );
	// Check if any row already exists in the table
	$existing_id = $wpdb->get_var( "SELECT id FROM $table_name LIMIT 1" );

	if ( $existing_id ) {

		// Update existing row
		$wpdb->update(
			$table_name,
			[
				'active_rule'      => $active_rule,
				'profit_rules'     => $profit_rules,
				'discount_rules'   => $discount_rules,
				'user_roles'       => $user_roles,
				'exclude_products' => $exclude_products,
				'discount_loop'    => $discount_loop
			],
			[ 'id' => $existing_id ]

		);
	} else {
		// Insert new row
		$wpdb->insert(
			$table_name,
			[
				'active_rule'      => $active_rule,
				'profit_rules'     => $profit_rules,
				'discount_rules'   => $discount_rules,
				'user_roles'       => $user_roles,
				'exclude_products' => $exclude_products,
				'discount_loop'    => $discount_loop
			]
		);
	}

	// Redirect to the same page after processing the form
	wp_redirect( admin_url( 'admin.php?page=bulk-discounts' ) );
	exit;
}

add_action( 'admin_post_save_bulk_discounts', 'woocost_save_bulk_discounts' );


function woocost_admin_notices(): void {
	if ( isset( $_GET['message'] ) && $_GET['message'] == 'success' ) {
		echo '<div class="notice notice-success" style="color: green;"><p>Discount rules saved successfully!</p></div>';
	}
}

add_action( 'admin_notices', 'woocost_admin_notices' );



// Product search
function woocost_product_names(): void {
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

add_action('wp_ajax_woocost_product_names', 'woocost_product_names');
add_action('wp_ajax_nopriv_woocost_product_names', 'woocost_product_names');


// Exclude product search

function woocost_search_product_names(): void {
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
// Fetch product names
add_action('wp_ajax_woocost_search_product_names', 'woocost_search_product_names');
add_action('wp_ajax_nopriv_woocost_search_product_names', 'woocost_search_product_names');

function woocost_get_category_names(): void {
	if (!isset($_POST['query'])) {
		wp_send_json_error(['message' => 'Invalid request']);
	}

	$query = sanitize_text_field($_POST['query']);

	// Query WooCommerce categories
	$args = [
		'taxonomy' => 'product_cat',
		'hide_empty' => false,
		'search' => $query,
	];

	$categories = get_terms($args);
	$category_list = [];

	foreach ($categories as $category) {
		$category_list[] = [
			'id' => $category->term_id,
			'name' => $category->name
		];
	}

	wp_send_json_success($category_list);
}
// Fetch category names
add_action('wp_ajax_woocost_get_category_names', 'woocost_get_category_names');
add_action('wp_ajax_nopriv_woocost_get_category_names', 'woocost_get_category_names');

function woocost_get_tag_names(): void {
	if (!isset($_POST['query'])) {
		wp_send_json_error(['message' => 'Invalid request']);
	}

	$query = sanitize_text_field($_POST['query']);

	// Query WooCommerce tags
	$args = [
		'taxonomy' => 'product_tag',
		'hide_empty' => false,
		'search' => $query,
	];

	$tags = get_terms($args);
	$tag_list = [];

	foreach ($tags as $tag) {
		$tag_list[] = [
			'id' => $tag->term_id,
			'name' => $tag->name
		];
	}

	wp_send_json_success($tag_list);
}
// Fetch tag names
add_action('wp_ajax_woocost_get_tag_names', 'woocost_get_tag_names');
add_action('wp_ajax_nopriv_woocost_get_tag_names', 'woocost_get_tag_names');


?>