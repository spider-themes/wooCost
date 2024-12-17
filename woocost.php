<?php
/**
 * Plugin Name: WooCost
 * Plugin URI: https://spider-themes.net/woocost/
 * Description: Effortlessly track and analyze product costs and profits in WooCommerce, empowering smarter financial decisions and enhanced profitability.
 * Version: 1.0.0
 * Requires at least: 5.7
 * Requires PHP: 7.4
 * Author: spider-themes
 * Author URI: https://spider-themes.net
 * Text Domain: woocost
 * Domain Path: /languages
 * Copyright: © 2024 Spider Themes
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Requires Plugins:  woocommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	// exit; // Exit if accessed directly
}



const WOOCOST_PLUGIN_FILE = __FILE__;

if ( ! class_exists( 'Woocost' ) ) {

	require_once __DIR__ . '/vendor/autoload.php';

	final class Woocost {

		const version = "1.0.0";

		private static $instance = null;

		/**
		 * Class construcotr
		 */
		public function __construct() {
			
			add_action( 'init', array( $this, 'plugin_init' ) );
			
			add_action( 'init', array( $this, 'bulk_discount_post_type' ), 0 );
			
			register_activation_hook( plugin_basename( __FILE__ ), [ $this, 'activate' ] );
			
			add_action('wp_loaded', array( $this, 'save_bulk_discount_general_setting'));

			if ( ! is_admin() ) {

				include plugin_dir_path( __FILE__ ) . 'includes/class-bulk-discount-front.php';
			}

			add_action( 'all_admin_notices', array( $this, 'cost_of_operation_custom_post' ) );

			add_action('restrict_manage_posts', array( $this, 'cost_of_operation_filters' ) );

			add_action('pre_get_posts', array( $this, 'filter_cost_operation_by_meta' ),1 );

			add_filter('manage_cost_operation_posts_columns', array( $this, 'set_custom_cost_operation_columns' ) );

			add_action('manage_cost_operation_posts_custom_column', array($this, 'custom_cost_operation_column' ), 10, 2);

			add_action('add_meta_boxes', array( $this, 'add_cost_operation_meta_box' ) );

			add_action('save_post', array( $this, 'save_cost_operation' ) );

			function add_enctype_script() {
			    // Check if we're on the edit screen for your custom post type
			    $screen = get_current_screen();
			    if ($screen->post_type === 'cost_operation') {
			        ?>
			        <script type="text/javascript">
			            document.addEventListener('DOMContentLoaded', function() {
			                var form = document.getElementById('post');
			                if (form) {
			                    form.enctype = 'multipart/form-data';
			                }
			            });
			        </script>
			        <?php
			    }
			}
			
			add_action('admin_enqueue_scripts', 'add_enctype_script');

			add_action('wp_loaded', function(){

				if(isset($_POST['save_cost_operation_data'])){

					if (isset($_GET['id'])) {

						$post_id = $_GET['id'];

						$post_id = wp_update_post(
							[
								'ID' => $post_id,
								'post_type' => 'cost_operation',
								'post_status' => 'publish',
								'post_title' => $_POST['cost-type-name']
							]
						);

						$cost_account = isset($_POST['cost']) ? $_POST['cost'] : '';
						update_post_meta( $post_id, 'cost_number', $cost_account);

						$cost_notes = isset($_POST['account']) ? $_POST['account'] : '';
						update_post_meta( $post_id, 'cost_account', $cost_notes);

						$cost_notes = isset($_POST['notes']) ? $_POST['notes'] : '';
						update_post_meta( $post_id, 'cost_notes', $cost_notes);

						if (!empty($_FILES['memo']['name'])) {

					        $uploaded_file = $_FILES['memo'];

					        // Define the upload directory path
					        $upload_dir = wp_upload_dir();
					        $target_directory = $upload_dir['basedir'] . '/memo/'; // Example custom directory

					        // Create the custom directory if it doesn't exist
					        if (!file_exists($target_directory)) {
					            mkdir($target_directory, 0755, true); // Creates directory with proper permissions
					        }

					        // Set the target file path
					        $target_file = $target_directory . basename($uploaded_file['name']);

					        // Move the uploaded file to the target directory
					        if (move_uploaded_file($uploaded_file['tmp_name'], $target_file)) {
					            // Save the file URL or path to the database
					            $file_url = $upload_dir['baseurl'] . '/memo/' . basename($uploaded_file['name']);
					            update_post_meta($post_id, 'cost_file', $file_url);
					        } else {
					            // Handle the error if file could not be moved
					            // error_log('File upload failed.');
					        }
					    }

						$cost_date = $_POST['date'] ?? '';
						update_post_meta( $post_id, 'cost_date', $cost_date);

						wp_safe_redirect('edit.php?post_type=cost_operation');
						exit();
					
					 }else {

						$post_id = wp_insert_post(
							[
								'post_type' => 'cost_operation',
								'post_status' => 'publish',
								'post_title' => $_POST['cost-type-name']
							]
						);

						$cost_account = isset($_POST['cost']) ? $_POST['cost'] : '';
						update_post_meta( $post_id, 'cost_number', $cost_account);

						$cost_notes = $_POST['account'] ?? '';
						update_post_meta( $post_id, 'cost_account', $cost_notes);

						$cost_notes = $_POST['notes'] ?? '';
						update_post_meta( $post_id, 'cost_notes', $cost_notes);

						if (!empty($_FILES['memo']['name'])) {

					        $uploaded_file = $_FILES['memo'];

					        // Define the upload directory path
					        $upload_dir = wp_upload_dir();
					        $target_directory = $upload_dir['basedir'] . '/memo/'; // Example custom directory

					        // Create the custom directory if it doesn't exist
					        if (!file_exists($target_directory)) {
					            mkdir($target_directory, 0755, true); // Creates directory with proper permissions
					        }

					        // Set the target file path
					        $target_file = $target_directory . basename($uploaded_file['name']);

					        // Move the uploaded file to the target directory
					        if (move_uploaded_file($uploaded_file['tmp_name'], $target_file)) {
					            // Save the file URL or path to the database
					            $file_url = $upload_dir['baseurl'] . '/memo/' . basename($uploaded_file['name']);
					            update_post_meta($post_id, 'cost_file', $file_url);
					        } else {
					            // Handle the error if file could not be moved
					            // error_log('File upload failed.');
					        }
					    }

						$cost_date = isset($_POST['date']) ? $_POST['date'] : '';
						update_post_meta( $post_id, 'cost_date', $cost_date);

						wp_safe_redirect('edit.php?post_type=cost_operation');
						exit();
					}
				}
			});

            /**
			 * Removes admin notices on the WooCost plugin pages.
			 *
			 * This function is hooked to the 'admin_head' action and checks if the current
			 * admin page is a WooCost plugin page. If it is, it removes all admin notices
			 * to provide a cleaner interface for the plugin's pages.
			 *
			 * @return void
			 */
			add_action( 'admin_head', function () {
				// Get the current screen
				$screen = get_current_screen();

				// Check if the current screen is for your plugin page
				if ( isset( $_GET['page'] ) && in_array( $_GET['page'], [ 'woocost', 'bulk-discounts' ] ) ) {
					// Remove admin notices
					remove_all_actions( 'admin_notices' );
					remove_all_actions( 'all_admin_notices' );
				}
			} );
		}

		public function add_cost_operation_meta_box(){

			add_meta_box(
		        'cost_operation_meta_box',                // Unique ID
		        __('Operation Cost', 'textdomain'),       // Meta box title
		        array( $this, 'display_cost_operation_meta_box' ),        // Callback function
		        'cost_operation',                         // Post type where the box will be added
		        'normal',                                 // Context: 'normal', 'side', or 'advanced'
		        'high'                                    // Priority
		    );
		}

		public function display_cost_operation_meta_box(){

			$cost_number = get_post_meta( get_the_ID(), 'cost_number', true );
			$cost_account = get_post_meta( get_the_ID(), 'cost_account', true );
			$cost_notes = get_post_meta( get_the_ID(), 'cost_notes', true );
			$cost_file = get_post_meta( get_the_ID(), 'cost_file', true );
			$cost_date = get_post_meta( get_the_ID(), 'cost_date', true );

			?>
			<div style="width: 100%; display: flex;">
				<div style="width: 30%; float: left;">
					<h3>Cost</h3>
				</div>
				<div style="width: 70%; float: right;">
					<input type="number" name="cost_number" style="width: 50%; height: 40px;" value="<?php echo $cost_number ?>">
				</div>
			</div>

			<div style="width: 100%; display: flex;">
				<div style="width: 30%; float: left;">
					<h3>Account</h3>
				</div>
				<div style="width: 70%; float: right;">
					<select name="cost_account" style="width: 50%; height: 40px;">
						<option value="" <?php echo selected($cost_account, '') ?>>All</option>
						<option value="cash" <?php echo selected($cost_account, 'cash') ?>>Cash</option>
						<option value="bank-account" <?php echo selected($cost_account, 'bank-account') ?>>Bank Account</option>
						<option value="card" <?php echo selected($cost_account, 'card') ?>>Card</option>
					</select>
				</div>
			</div>

			<div style="width: 100%; display: flex;">
				<div style="width: 30%; float: left;">
					<h3>Notes</h3>
				</div>
				<div style="width: 70%; float: right;">
					<textarea style="width: 50%; height: 80px;" name="cost_notes"><?php echo $cost_notes ?></textarea>
				</div>
			</div>

			<div style="width: 100%; display: flex;">
				<div style="width: 30%; float: left;">
					<h3>Cost Memo</h3>
				</div>
				<div style="width: 70%; float: right;">
					<input type="file" name="cost_file" accept=".pdf,.doc,.docx,.txt,.ppt,.png,.jpg,.jpeg">

					<?php
					if ($cost_file) {
				        echo '<p>Uploaded File: <a href="' . esc_url($cost_file) . '" target="_blank">View File</a></p>';
				    }
					?>
				</div>
			</div>

			<div style="width: 100%; display: flex;">
				<div style="width: 30%; float: left;">
					<h3>Date</h3>
				</div>
				<div style="width: 70%; float: right;">
					<input type="date" name="cost_date" value="<?php echo $cost_date ?>">
				</div>
			</div>
			<?php
		}

		public function save_cost_operation( $post_id ){

			// error_log(print_r($_POST,true));

			$cost_number = $_POST['cost_number'] ?? '';
			update_post_meta( $post_id, 'cost_number', $cost_number);

			$cost_account = $_POST['cost_account'] ?? '';
			update_post_meta( $post_id, 'cost_account', $cost_account);

			$cost_notes = $_POST['cost_notes'] ?? '';
			update_post_meta( $post_id, 'cost_notes', $cost_notes);

			if (!empty($_FILES['cost_file']['name'])) {

				$uploaded_file = $_FILES['cost_file'];

		        // Define the upload directory path
		        $upload_dir = wp_upload_dir();
		        $target_directory = $upload_dir['basedir'] . '/cost_file/'; // Example custom directory

		        // Create the custom directory if it doesn't exist
		        if (!file_exists($target_directory)) {
		            mkdir($target_directory, 0755, true); // Creates directory with proper permissions
		        }

		        // Set the target file path
		        $target_file = $target_directory . basename($uploaded_file['name']);

		        // Move the uploaded file to the target directory
		        if (move_uploaded_file($uploaded_file['tmp_name'], $target_file)) {
		            // Save the file URL or path to the database
		            $file_url = $upload_dir['baseurl'] . '/cost_file/' . basename($uploaded_file['name']);
		            update_post_meta($post_id, 'cost_file', $file_url);
		        } else {
		            // Handle the error if file could not be moved
		            // error_log('File upload failed.');
		        }

		    } else {

		    	update_post_meta($post_id, 'cost_file', '');
		    }

			$cost_date = isset($_POST['cost_date']) ? $_POST['cost_date'] : '';
			update_post_meta( $post_id, 'cost_date', $cost_date);
		}

		public function cost_of_operation_custom_post() {

			global $post, $typenow;

			$screen = get_current_screen();

			if ( $screen && $screen->id == 'edit-cost_operation' ) {

				$cost_type_name = '';

				$cost = '';

				$account = '';

				$notes = '';

				$memo = '';

				$date = '';

				if ( isset( $_GET['id'] ) ) {

					$rule_id = $_GET['id'];
					
					$cost_type_name = get_the_title( $rule_id );

					$cost = get_post_meta( $rule_id, 'cost_number', true );

					$account = get_post_meta( $rule_id, 'cost_account', true );

					$notes = get_post_meta( $rule_id, 'cost_notes', true );

					$memo = get_post_meta( $rule_id, 'cost_file', true );

					$date = get_post_meta( $rule_id, 'cost_date', true );
				}

				?>
				<div class="wrap">
    			<h2>Add New Cost</h2>
			    <form id="costForm" action="" method="post" enctype="multipart/form-data">
			        <input type="hidden" name="action" value="save_cost">
			        <input type="hidden" name="woocost_nonce" value="<?php echo wp_create_nonce( 'save_cost' ); ?>">

			        <table id="costTable">
			            <thead>
			            <tr>
			                <th style="padding: 10px; text-align: left; border: 1px solid #ccc; vertical-align: middle; background-color: #f2f2f2;">Cost Type Name *</th>
			                <th style="padding: 10px; text-align: left; border: 1px solid #ccc; vertical-align: middle; background-color: #f2f2f2;">Cost *</th>
			                <th style="padding: 10px; text-align: left; border: 1px solid #ccc; vertical-align: middle; background-color: #f2f2f2;">Account *</th>
			                <th style="padding: 10px; text-align: left; border: 1px solid #ccc; vertical-align: middle; background-color: #f2f2f2;">Notes</th>
			                <th style="padding: 10px; text-align: left; border: 1px solid #ccc; vertical-align: middle; background-color: #f2f2f2;">Cost Memo</th>
			                <th style="padding: 10px; text-align: left; border: 1px solid #ccc; vertical-align: middle; background-color: #f2f2f2;">Date</th>
			            </tr>
			            </thead>
			            <tbody>
			            <tr>

			                <td style="padding: 10px; text-align: left; border: 1px solid #ccc; vertical-align: middle;">
			                	<input type="text" name="cost-type-name" placeholder="Enter cost type name" required value="<?php echo $cost_type_name ?>"> 
			                </td>

			                <td style="padding: 10px; text-align: left; border: 1px solid #ccc; vertical-align: middle;">
			                	<input type="number" name="cost" placeholder="Enter cost" class="cost-input" required value="<?php echo $cost ?>"> 
			                </td>

			                <td style="padding: 10px; text-align: left; border: 1px solid #ccc; vertical-align: middle;">
			                    <select name="account" class="account-selection" required>
			                        <option value="" <?php echo selected( $account, ''); ?> >Choose Account Type</option>
			                        <option value="cash" <?php echo selected( $account, 'cash'); ?>>Cash</option>
			                        <option value="bank-account" <?php echo selected( $account, 'bank-account'); ?>>Bank Accounts</option>
			                        <option value="card" <?php echo selected( $account, 'card'); ?>>Card</option>
			                    </select>
			                </td>
			                <td style="padding: 10px; text-align: left; border: 1px solid #ccc; vertical-align: middle;"><textarea id="notes" name="notes" class="notes-area"><?php echo $notes ?></textarea></td>
			                <td style="padding: 10px; text-align: left; border: 1px solid #ccc; vertical-align: middle;">
			                    <input type="file" id="memo" name="memo" class="memo-input" accept=".pdf,.doc,.docx,.txt,.ppt,.png,.jpg,.jpeg" value="<?php echo esc_url($memo) ?>">
			                    <?php if ( ! empty( $memo ) ): ?>
			                    	<a href="" style="color: red" class="cost_operation_remove_file_btn">Remove</a>
			                    <?php endif ?>
								
			                </td>
			                <td style="padding: 10px; text-align: left; border: 1px solid #ccc; vertical-align: middle;"><input type="date" name="date" class="input-date" value="<?php echo $date ?>"></td>

			            </tr>

			            </tbody>

			        </table>
			        <div class="cost-btn-group">
			            <button type="submit" name="save_cost_operation_data" class="button-primary">Save</button>
			        </div>
			    </form>
				<?php
			}
		}

		public function cost_of_operation_filters(){

			global $typenow;

		    if ($typenow == 'cost_operation') {

		        // Start Date Filter
		        $start_date = isset($_GET['filter_start_date']) ? $_GET['filter_start_date'] : '';
		        echo '<input type="date" name="filter_start_date" value="' . esc_attr($start_date) . '" placeholder="Start Date" />';

		        // End Date Filter
		        $end_date = isset($_GET['filter_end_date']) ? $_GET['filter_end_date'] : '';
		        echo '<input type="date" name="filter_end_date" value="' . esc_attr($end_date) . '" placeholder="End Date" />';

		        // Account Type Filter
		        $account_type = isset($_GET['filter_account_type']) ? $_GET['filter_account_type'] : '';
		        $account_types = [
		            '' => 'All Account Types',
		            'cash' => 'Cash',
		            'bank-account' => 'Bank Account',
		            'card' => 'Card'
		        ];

		        echo '<select name="filter_account_type">';
		        foreach ($account_types as $key => $label) {
		            echo '<option value="' . esc_attr($key) . '"' . selected($account_type, $key, false) . '>' . esc_html($label) . '</option>';
		        }
		        echo '</select>';
		    }
		}

		public function filter_cost_operation_by_meta($query){

			global $pagenow, $typenow;

		    if (is_admin() && $pagenow == 'edit.php' && $typenow == 'cost_operation') {

		        if (!empty($_GET['filter_start_date'])) {
		            $start_date = sanitize_text_field($_GET['filter_start_date']);
		            $query->set('meta_query', array_merge($query->get('meta_query', []), [
		                [
		                    'key' => 'cost_date', // Replace with the actual meta key for start date
		                    'value' => $start_date,
		                    'compare' => '>=',
		                    'type' => 'DATE'
		                ]
		            ]));
		        }

		        if (!empty($_GET['filter_end_date'])) {
		            $end_date = sanitize_text_field($_GET['filter_end_date']);
		            $query->set('meta_query', array_merge($query->get('meta_query', []), [
		                [
		                    'key' => 'cost_date', // Replace with the actual meta key for end date
		                    'value' => $end_date,
		                    'compare' => '<=',
		                    'type' => 'DATE'
		                ]
		            ]));
		        }

		        if (!empty($_GET['filter_account_type']) && $_GET['filter_account_type'] != '') {
		            $account_type = sanitize_text_field($_GET['filter_account_type']);
		            $query->set('meta_query', array_merge($query->get('meta_query', []), [
		                [
		                    'key' => 'cost_account', // Replace with the actual meta key for account type
		                    'value' => $account_type,
		                    'compare' => '='
		                ]
		            ]));
		        }
		    }
		}

		public function set_custom_cost_operation_columns( $columns ){

		    unset($columns['date']);

		    $columns['title'] 	= __('Cost Type Name', 'woocost');
		    $columns['operation_cost'] 		= __('Cost', 'woocost');
		    $columns['operation_account'] 	= __('Account', 'woocost');
		    $columns['operation_notes'] 	= __('Notes', 'woocost');
		    $columns['operation_cost_memo'] = __('Cost Memo', 'woocost');
		    $columns['operation_date'] 		= __('Date', 'woocost');

		    return $columns;
		}

		public function custom_cost_operation_column($column, $post_id) {

		    switch ($column) {

		        case 'operation_cost':

		            echo get_post_meta( $post_id, 'cost_number', true );
		            break;

		        case 'operation_account':

		            echo get_post_meta( $post_id, 'cost_account', true );
		            break;

		        case 'operation_notes':

		            echo get_post_meta( $post_id, 'cost_notes', true );
		            break;

		        case 'operation_cost_memo':

					$file_url = get_post_meta($post_id, 'cost_file', true);
					$filename = basename($file_url);
				
					if ($filename) {
						echo '<a href="' . esc_url($file_url) . '" target="_blank">' . esc_html($filename) . '</a>';
					} 
					break;

		        case 'operation_date':

		            echo get_post_meta( $post_id, 'cost_date', true );
		            break;

		    }
		}

		public function save_bulk_discount_general_setting(){

			if ( isset( $_POST['save_bulk_discount_rules'] ) ) {
				
				$post_id = isset( $_POST['bulk_post_id'] )? $_POST['bulk_post_id']:'';

				if('' != $post_id){
					wp_update_post(array(
						'ID'    		=>  $post_id,
						'post_status'   =>  'publish',
						'post_type'     =>  'bulk_discount'
					));
				}


				$bulk_discount_rules = get_posts(
				    array(

				        'post_type' => 'bulk_discount',
				        'post_status' => 'publish',
				        'numberposts' => -1,
				        'orderby' => 'menu_order',
				        'order' => 'ASC',
				        'fields' => 'ids'

				    )
				);

				foreach ($bulk_discount_rules as $rule_id ) {

					$bulk_discount_rule_title = isset($_POST['bulk_discount_rule_title'][$rule_id]) ? $_POST['bulk_discount_rule_title'][$rule_id] : '';

					wp_update_post(
						array(
							'post_type'   => 'bulk_discount',
							'ID'          => $rule_id,
							'post_title'  => $bulk_discount_rule_title,
						)
					);

					$bulk_discount_ativate_rule = isset($_POST['bulk_discount_ativate_rule'][$rule_id]) ? $_POST['bulk_discount_ativate_rule'][$rule_id] : '';
					error_log(print_r($bulk_discount_ativate_rule,true));
					update_post_meta( $rule_id, 'bulk_discount_ativate_rule', $bulk_discount_ativate_rule );

					$insert_field = wp_update_post(
						array(
							'post_type'   => 'bulk_discount',
							'ID'          => $rule_id,
							// 'menu_order'  => $rule_priority,
						)
					);

					$products = isset($_POST['products'][$rule_id]) ? $_POST['products'][$rule_id] : 'all_products';
					update_post_meta( $rule_id, 'products', $products );

					$specific_products = isset($_POST['specific_products'][$rule_id]) ? $_POST['specific_products'][$rule_id] : [];
					update_post_meta( $rule_id, 'specific_products', $specific_products );

					$exclude_products = isset($_POST['exclude_products'][$rule_id]) ? $_POST['exclude_products'][$rule_id] : 'specific_products';
					update_post_meta( $rule_id, 'exclude_products', $exclude_products );

					// error_log(print_r($_POST,true));
					$exclude_specific_products = isset($_POST['exclude_specific_products'][$rule_id]) ? $_POST['exclude_specific_products'][$rule_id] : [];
					update_post_meta( $rule_id, 'exclude_specific_products', $exclude_specific_products );

					$exclude_specific_categories = isset($_POST['exclude_specific_categories'][$rule_id]) ? $_POST['exclude_specific_categories'][$rule_id] : [];
					update_post_meta( $rule_id, 'exclude_specific_categories', $exclude_specific_categories );

					$exclude_specific_tags = isset($_POST['exclude_specific_tags'][$rule_id]) ? $_POST['exclude_specific_tags'][$rule_id] : [];
					update_post_meta( $rule_id, 'exclude_specific_tags', $exclude_specific_tags );

					$user_role = isset($_POST['user_role'][$rule_id]) ? $_POST['user_role'][$rule_id] : 'all_users';
					update_post_meta( $rule_id, 'user_role', $user_role );

					$specific_users = isset($_POST['specific_users'][$rule_id]) ? $_POST['specific_users'][$rule_id] : [];
					update_post_meta( $rule_id, 'specific_users', $specific_users );

					$specific_user_roles = isset($_POST['specific_user_roles'][$rule_id]) ? $_POST['specific_user_roles'][$rule_id] : [];
					update_post_meta( $rule_id, 'specific_user_roles', $specific_user_roles );

					$show_discount_in_loop = isset($_POST['show_discount_in_loop'][$rule_id]) ? $_POST['show_discount_in_loop'][$rule_id] : '';
					update_post_meta( $rule_id, 'show_discount_in_loop', $show_discount_in_loop );

					$exluclude_products_checkbox = isset($_POST['exluclude_products_checkbox'][$rule_id]) ? $_POST['exluclude_products_checkbox'][$rule_id] : '';
					update_post_meta( $rule_id, 'exluclude_products_checkbox', $exluclude_products_checkbox );

					$bulk_discount_rules = get_posts(
                        array(

                            'post_type' => 'bulk_discount_rules',
                            'post_status' => 'publish',
                            'numberposts' => -1,
                            'orderby' => 'menu_order',
                            'order' => 'ASC',
                            'post_parent' => $rule_id,
                            'fields' => 'ids'

                        )
                    );

                    foreach ($bulk_discount_rules as $discount_rule_id) {
                    	
                    	$bulk_discount_from = isset($_POST['bulk_discount_from'][$rule_id][$discount_rule_id]) ? $_POST['bulk_discount_from'][$rule_id][$discount_rule_id] : '';
						update_post_meta( $discount_rule_id, 'bulk_discount_from', $bulk_discount_from );

						$bulk_discount_to = isset($_POST['bulk_discount_to'][$rule_id][$discount_rule_id]) ? $_POST['bulk_discount_to'][$rule_id][$discount_rule_id] : '';
						update_post_meta( $discount_rule_id, 'bulk_discount_to', $bulk_discount_to );

						$discount_type = isset($_POST['discount_type'][$rule_id][$discount_rule_id]) ? $_POST['discount_type'][$rule_id][$discount_rule_id] : '';
						update_post_meta( $discount_rule_id, 'discount_type', $discount_type );

						$discount_amount = isset($_POST['discount_amount'][$rule_id][$discount_rule_id]) ? $_POST['discount_amount'][$rule_id][$discount_rule_id] : '';
						update_post_meta( $discount_rule_id, 'discount_amount', $discount_amount );
                    }
	            }
			}
		}

		/**
		 * Initializes a singleton instance
		 *
		 * @return \Woocost
		 */
		public static function get_instance() {
			if ( self::$instance === null ) {
				self::$instance = new self();
			}

			return self::$instance;
		}


		public function activate(): void {
			flush_rewrite_rules();
			$this->cost_operation_table();
			$this->bulk_discounts_table();

		}

		/**
		 * Const Define
		 *
		 * @return void
		 */
		public function define_constants(): void {
			define( 'WOOCOST_VERSION', self::version );
			define( 'WOOCOST_FILE', __FILE__ );
			define( 'WOOCOST_PATH', __DIR__ );
			define( 'WOOCOST_URL', plugins_url( '', WOOCOST_FILE ) );
			define( 'WOOCOST_ASSETS', WOOCOST_URL . '/assets' );
		}

		public function bulk_discount_post_type(){

			$labels = array(
		        'name'                  => _x( 'Bulk Discounts', 'woocost' ),
		        'singular_name'         => _x( 'Bulk Discount', 'woocost' ),
		        'menu_name'             => __( 'Bulk Discounts', 'woocost' ),
		        'name_admin_bar'        => __( 'Bulk Discounts', 'woocost' ),
		        'all_items'             => __( 'Bulk Discount', 'woocost' ),
		        'add_new_item'          => __( 'Add New Rule', 'woocost' ),
		        'add_new'               => __( 'Add New', 'woocost' ),
		        'new_item'              => __( 'New Rule', 'woocost' ),
		        'edit_item'             => __( 'Edit Rule', 'woocost' ),
		        'update_item'           => __( 'Update Rule', 'woocost' ),
		        'view_item'             => __( 'View Rule', 'woocost' ),
		        'view_items'            => __( 'View Rules', 'woocost' ),
		        'search_items'          => __( 'Search Rule', 'woocost' ),
		        'not_found'             => __( 'Not found', 'woocost' ),
		        'not_found_in_trash'    => __( 'Not found in Trash', 'woocost' ),
		    );
		    
		    $args = array(
		        'label'                 => __( 'Bulk Discount', 'woocost' ),
		        'labels'                => $labels,
		        'supports'              => array( 'title'),
		        'hierarchical'          => false,
		        'public'                => false,
		        'show_ui'               => false,
		        'show_in_menu'          => false,
		        'has_archive'           => false, 
		        'exclude_from_search'   => true,
		        'publicly_queryable'    => false,
		        'capability_type'       => 'post',
		        'menu_icon'             => 'dashicons-book', // Icon for the custom post type in the dashboard
		    );
		    
		    register_post_type( 'bulk_discount', $args );

		    $labels = array(
		        'name'                  => _x( 'Bulk Discount Rules', 'woocost' ),
		        'singular_name'         => _x( 'Bulk Discount Rule', 'woocost' ),
		        'menu_name'             => __( 'Bulk Discount Rules', 'woocost' ),
		        'name_admin_bar'        => __( 'Bulk Discount Rules', 'woocost' ),
		        'all_items'             => __( 'Bulk Discount Rule', 'woocost' ),
		        'add_new_item'          => __( 'Add New Rule', 'woocost' ),
		        'add_new'               => __( 'Add New', 'woocost' ),
		        'new_item'              => __( 'New Rule', 'woocost' ),
		        'edit_item'             => __( 'Edit Rule', 'woocost' ),
		        'update_item'           => __( 'Update Rule', 'woocost' ),
		        'view_item'             => __( 'View Rule', 'woocost' ),
		        'view_items'            => __( 'View Rules', 'woocost' ),
		        'search_items'          => __( 'Search Rule', 'woocost' ),
		        'not_found'             => __( 'Not found', 'woocost' ),
		        'not_found_in_trash'    => __( 'Not found in Trash', 'woocost' ),
		    );
		    
		    $args = array(
		        'label'                 => __( 'Bulk Discount Rule', 'woocost' ),
		        'labels'                => $labels,
		        'supports'              => array( 'title'),
		        'hierarchical'          => false,
		        'public'                => false,
		        'show_ui'               => false,
		        'show_in_menu'          => false,
		        'has_archive'           => false, 
		        'exclude_from_search'   => true,
		        'publicly_queryable'    => false,
		        'capability_type'       => 'post',
		        'menu_icon'             => 'dashicons-book', // Icon for the custom post type in the dashboard
		    );
		    
		    register_post_type( 'bulk_discount_rules', $args );

		    $labels = array(
		        'name'                  => _x( 'Cost Operation', 'woocost' ),
		        'singular_name'         => _x( 'Cost Operation', 'woocost' ),
		        'menu_name'             => __( 'Cost Operation', 'woocost' ),
		        'name_admin_bar'        => __( 'Cost Operation', 'woocost' ),
		        'all_items'             => __( 'Cost Operation', 'woocost' ),
		        'add_new_item'          => __( 'Add New Rule', 'woocost' ),
		        'add_new'               => __( 'Add New', 'woocost' ),
		        'new_item'              => __( 'New Rule', 'woocost' ),
		        'edit_item'             => __( 'Edit Rule', 'woocost' ),
		        'update_item'           => __( 'Update Rule', 'woocost' ),
		        'view_item'             => __( 'View Rule', 'woocost' ),
		        'view_items'            => __( 'View Rules', 'woocost' ),
		        'search_items'          => __( 'Search Rule', 'woocost' ),
		        'not_found'             => __( 'Not found', 'woocost' ),
		        'not_found_in_trash'    => __( 'Not found in Trash', 'woocost' ),
		    );
		    
		    $args = array(
		        'label'                 => __( 'Cost of Operation', 'woocost' ),
		        'labels'                => $labels,
		        'supports'              => array( 'title'),
		        'hierarchical'          => false,
		        'public'                => false,
		        'show_ui'               => true,
		        'show_in_menu'          => false,
		        'menu_position'      	=> 3,
		        'has_archive'           => false, 
		        'exclude_from_search'   => true,
		        'publicly_queryable'    => false,
		        'capability_type'       => 'post',
		        'menu_icon'             => 'dashicons-book', // Icon for the custom post type in the dashboard
		    );
		    
		    register_post_type( 'cost_operation', $args );
		}

		public function plugin_init(): void {

			/**
			 * Settings filter
			 */
			add_filter( 'plugin_action_links_' . plugin_basename( WOOCOST_PLUGIN_FILE ), array( $this, 'settings_action_links' ) );

			if ( is_admin() ) {
				new wooCost\Admin();
				new wooCost\Notices\Notices();
			}
			$this->define_constants();
			$this->templates_include();
		}

		public function cost_operation_table(): void {
			global $wpdb;
			$table_name      = $wpdb->prefix . 'woocost';
			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE $table_name (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				cost_type_name varchar(255) NOT NULL,
				cost decimal(10,2) NOT NULL,
				account varchar(50) NOT NULL,
				notes text,
				memo varchar(255),
				date date NOT NULL,
				PRIMARY KEY (id)
			) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}


		public function bulk_discounts_table(): void {
			global $wpdb;

			// Table name with the WordPress prefix
			$table_name = $wpdb->prefix . 'woocost_bulk_discounts';

			// Get the character set from the current database
			$charset_collate = $wpdb->get_charset_collate();

			// Corrected SQL query to create the table
			$sql = "CREATE TABLE $table_name (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            active_rule TINYINT(1) NOT NULL DEFAULT 0,
            profit_rules TEXT NOT NULL,
            discount_rules TEXT NOT NULL,
            user_roles TEXT NOT NULL,
            exclude_products TINYINT(1) NOT NULL DEFAULT 0,
            discount_loop TINYINT(1) NOT NULL DEFAULT 0,
            PRIMARY KEY (id)
        ) $charset_collate;";

			// Include the WordPress file with the dbDelta function
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			// error_log("Table creation SQL: $sql");
			// Execute the query
			dbDelta( $sql );
		}


		public function templates_include(): void {

			require_once plugin_dir_path( __FILE__ ) . '/functions/cost-profit-metabox.php';
			require_once plugin_dir_path( __FILE__ ) . '/functions/custom-columns-sortable.php';
			require_once plugin_dir_path( __FILE__ ) . '/functions/order-by-date-range.php';
			require_once plugin_dir_path( __FILE__ ) . '/functions/custom-cost-field.php';
			require_once plugin_dir_path( __FILE__ ) . '/functions/compare-monthly-orders.php';
			require_once plugin_dir_path( __FILE__ ) . '/functions/order-page-custom-cost.php';
			require_once plugin_dir_path( __FILE__ ) . '/functions/bulk-discount.php';
			require_once plugin_dir_path( __FILE__ ) . '/functions/cost-operation.php';
			require_once plugin_dir_path( __FILE__ ) . '/functions/product-image-search.php';

		}

		/**
		 * Plugins setting setup
		 *
		 * @param $links
		 *
		 * @return mixed
		 */
		public function settings_action_links( $links ): mixed {
			$settings_link = '<a href="' . esc_url( admin_url( 'admin.php?page=bulk-discounts' ) ) . '">' . esc_html__( 'Settings', 'woocost' ) . '</a>';
			array_unshift( $links, $settings_link );

			return $links;
		}


		/**
		 * Total Cost Calculation
		 *
		 * @return int|null
		 */
		public function total_stock(): ?int {
			$total_stock = 0;
			// Ensure WooCommerce is active
			if ( class_exists( 'WooCommerce' ) ) {
				$args = array(
					'post_type'      => 'product',
					'posts_per_page' => - 1,
					'post_status'    => 'publish',
				);

				$products = new WP_Query( $args );

				if ( $products->have_posts() ) {
					while ( $products->have_posts() ) {
						$products->the_post();
						$product     = wc_get_product( get_the_ID() );
						$total_stock += $product->get_stock_quantity();
					}
				}
				wp_reset_postdata();
			}

			return $total_stock;
		}

		/**
		 * Total Price Calculation
		 *
		 * @return float|int
		 */
		public function total_price(): float|int {
			$total_price = 0;

			if ( class_exists( 'WooCommerce' ) ) {
				$args = array(
					'post_type'      => 'product',
					'posts_per_page' => - 1,
					'post_status'    => 'publish',
				);

				$products = new WP_Query( $args );

				if ( $products->have_posts() ) {
					while ( $products->have_posts() ) {
						$products->the_post();
						$product     = wc_get_product( get_the_ID() );
						$total_price += (float) $product->get_price();
					}
				}
				wp_reset_postdata();
			}

			return $total_price;
		}

		/**
		 * Total cost calculation
		 *
		 * @return float|int
		 */

		public function total_cost(): float|int {
			$total_cost = 0;

			if ( class_exists( 'WooCommerce' ) ) {
				// Check if the 'Cost of Goods for WooCommerce' plugin is activated
				$is_cog_activated = class_exists( 'Alg_WC_Cost_of_Goods_Core' );

				$meta_key = '_woo_product_cost';

				if ( $is_cog_activated ) {
					$meta_key = '_alg_wc_cog_cost';
				}

				$args = array(
					'post_type'      => 'product',
					'posts_per_page' => - 1,
					'post_status'    => 'publish',
					'meta_key'       => $meta_key,
					'meta_value'     => '',
					'meta_compare'   => '!='
				);

				$products = new WP_Query( $args );

				if ( $products->have_posts() ) {
					while ( $products->have_posts() ) {
						$products->the_post();
						$cost = 0;

						// Check if '_woo_product_cost' is set
						$woo_cost = (float) get_post_meta( get_the_ID(), '_woo_product_cost', true );

						if ( $is_cog_activated ) {
							// If 'Cost of Goods for WooCommerce' is activated, use '_alg_wc_cog_cost'
							$cost = (float) get_post_meta( get_the_ID(), '_alg_wc_cog_cost', true );
						} elseif ( $woo_cost ) {
							// Otherwise, use '_woo_product_cost' if it's set
							$cost = $woo_cost;
						}

						$total_cost += $cost;
					}
				}
				wp_reset_postdata();
			}

			return $total_cost;
		}

		/**
		 * Total profit Calculate
		 *
		 * @return float|int
		 */
		public function total_profit(): float|int {
			return $this->total_price() - $this->total_cost();
		}

	}

	Woocost::get_instance();

}



add_action('quick_edit_custom_box', 'my_quick_edit_custom_box', 10, 2);


function my_quick_edit_custom_box( $column_name, $post_type ) {

	switch( $column_name ) {
		case 'operation_cost': {
			?>
				<fieldset class="inline-edit-col-left">
					<div class="inline-edit-col">
						<label>
							<span class="title">Cost</span>
							<input type="number" name="cost_number" placeholder="Enter cost" class="cost-input" required> 
						</label>
					</div>
				<?php
			break;
		}
		case 'operation_account': {
			?>
				<div class="inline-edit-col">
				<label>
							<span class="title">Account</span>
							<select name="cost_account" class="account-selection" required>
								<option value=""  >Choose Account Type</option>
								<option value="cash" >Cash</option>
								<option value="bank-account" >Bank Accounts</option>
								<option value="card">Card</option>
							</select>
						</label>
				</div>
				<?php
			break;
		}

		case 'operation_notes': {
			?>
				<div class="inline-edit-col">
				<label>
							<span class="title">Notes</span>
							<textarea name="cost_notes"></textarea>

						</label>
				</div>
				<?php
			break;
		}

		

		case 'operation_date': {
			?>
					<div class="inline-edit-col">
						<label>
						<span class="title">Date</span>
						<input type="date" name="cost_date" >

						</label>
					</div>
				</fieldset>
			<?php
			break;
		}
		
		
	}
}



add_action( 'admin_head-edit.php', 'remove_extra_columns_quick_edit' );

function remove_extra_columns_quick_edit() 
{    
    

    global $current_screen;
    if( 'edit-cost_operation' != $current_screen->id )
        return;
    ?>
    <script type="text/javascript">         
        jQuery(document).ready( function($) {
            $('span:contains("Slug")').each(function (i) {
                $(this).parent().remove();
            });
            $('span:contains("Password")').each(function (i) {
                $(this).parent().parent().remove();
            });
			$('span:contains("Status")').each(function (i) {
                $(this).parent().parent().remove();
            });
            // $('span:contains("Date")').each(function (i) {
            //     $(this).parent().remove();
            // });
            $('.inline-edit-date').each(function (i) {
                $(this).remove();
            });
        });    
    </script>
    <?php
}
