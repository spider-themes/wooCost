<?php
// Handle form submission
function wooprofit_handle_form_submission(): void {
	if (!isset($_POST['wooprofit_nonce']) || !wp_verify_nonce($_POST['wooprofit_nonce'], 'save_cost')) {
		return;
	}

	global $wpdb;
	$table_name = $wpdb->prefix . 'wooprofit_cost_table';

	$cost_type_name = sanitize_text_field($_POST['cost-type-name']);
	$cost = floatval($_POST['cost']);
	$account = sanitize_text_field($_POST['account']);
	$notes = sanitize_textarea_field($_POST['notes']);
	$date = sanitize_text_field($_POST['date']);

	$memo = '';
	if (!empty($_FILES['memo']['name'])) {
		$upload_dir = wp_upload_dir();
		$upload_file = $upload_dir['path'] . '/' . basename($_FILES['memo']['name']);
		if (move_uploaded_file($_FILES['memo']['tmp_name'], $upload_file)) {
			$memo = $upload_dir['url'] . '/' . basename($_FILES['memo']['name']);
		}
	}

	// Check if we are editing an existing entry or adding a new one
	if (isset($_POST['entry_id']) && !empty($_POST['entry_id'])) {
		$entry_id = intval($_POST['entry_id']);
		$wpdb->update(
			$table_name,
			[
				'cost_type_name' => $cost_type_name,
				'cost' => $cost,
				'account' => $account,
				'notes' => $notes,
				'memo' => $memo,
				'date' => $date
			],
			['id' => $entry_id]
		);
	} else {
		$wpdb->insert(
			$table_name,
			[
				'cost_type_name' => $cost_type_name,
				'cost' => $cost,
				'account' => $account,
				'notes' => $notes,
				'memo' => $memo,
				'date' => $date
			]
		);
	}

	wp_redirect(admin_url('admin.php?page=operation-cost'));
	exit;
}
add_action('admin_post_save_cost', 'wooprofit_handle_form_submission');


// Handle the deletion of a cost entry
function wooprofit_handle_delete_cost(): void {
	if (
		!isset($_GET['cost_id']) ||
		!isset($_GET['_wpnonce']) ||
		!wp_verify_nonce($_GET['_wpnonce'], 'delete_cost_' . intval($_GET['cost_id']))
	) {
		wp_die('Invalid request.');
	}

	global $wpdb;
	$table_name = $wpdb->prefix . 'wooprofit_cost_table';
	$cost_id = intval($_GET['cost_id']);

	// Delete the entry
	$wpdb->delete($table_name, ['id' => $cost_id]);

	// Redirect back to the main page
	wp_redirect(admin_url('admin.php?page=operation-cost'));
	exit;
}
add_action('admin_post_delete_cost', 'wooprofit_handle_delete_cost');
