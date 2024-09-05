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


?>