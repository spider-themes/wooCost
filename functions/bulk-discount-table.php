<?php
/*if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	global $wpdb;

	// Prepare the data
	$active_rule = isset($_POST['active-rule']) ? intval($_POST['active-rule']) : 0;
	$profit_rules = sanitize_text_field($_POST['profit_rules']); // Adjust as needed
	$discount_rules = sanitize_text_field($_POST['discount_rules']); // Adjust as needed
	$user_roles = sanitize_text_field($_POST['user_roles']); // Adjust as needed
	$exclude_products = sanitize_text_field($_POST['exclude_products']); // Adjust as needed
	$discount_loop = isset($_POST['discount_loop']) ? intval($_POST['discount_loop']) : 0;

	// Table name
	$table_name = $wpdb->prefix . 'wooprofit_bulk_discounts';

	// Insert data
	$wpdb->insert(
		$table_name,
		[
			'active_rule' => $active_rule,
			'profit_rules' => $profit_rules,
			'discount_rules' => $discount_rules,
			'user_roles' => $user_roles,
			'exclude_products' => $exclude_products,
			'discount_loop' => $discount_loop
		]
	);
}*/

function save_bulk_discounts() {
	// Check nonce for security
	if (!isset($_POST['wooprofit_nonce']) || !wp_verify_nonce($_POST['wooprofit_nonce'], 'bulk_discounts')) {
		wp_die('Security check failed.');
	}

	global $wpdb;
	$table_name = $wpdb->prefix . 'wooprofit_bulk_discounts';

	// Validate and sanitize input data
	$active_rule = isset($_POST['active-rule']) ? intval($_POST['active-rule']) : 0;
	$profit_rules = isset($_POST['products']) ? sanitize_text_field($_POST['products']) : '';
	$discount_rules = isset($_POST['discount-application']) ? sanitize_text_field($_POST['discount-application']) : '';
	$user_roles = isset($_POST['user-roles']) ? sanitize_text_field($_POST['user-roles']) : '';
	$exclude_products = isset($_POST['active-exclude']) ? intval($_POST['active-exclude']) : 0;
	$discount_loop = isset($_POST['active-shop']) ? intval($_POST['active-shop']) : 0;

	// Insert data into the table
	$wpdb->insert(
		$table_name,
		[
			'active_rule' => $active_rule,
			'profit_rules' => $profit_rules,
			'discount_rules' => $discount_rules,
			'user_roles' => $user_roles,
			'exclude_products' => $exclude_products,
			'discount_loop' => $discount_loop
		],
		[
			'%d', '%s', '%s', '%s', '%d', '%d'
		]
	);

	// Redirect back to the plugin page with success notice
	wp_redirect(admin_url('admin.php?page=bulk-discounts&message=success'));
	exit;
}
add_action('admin_post_save_bulk_discounts',  'save_bulk_discounts');


function wooprofit_admin_notices() {
	if (isset($_GET['message']) && $_GET['message'] == 'success') {
		echo '<div class="notice notice-success" style="color: green;"><p>Discount rules saved successfully!</p></div>';
	}
}
add_action('admin_notices', 'wooprofit_admin_notices');