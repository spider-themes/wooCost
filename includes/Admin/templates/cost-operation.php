<?php
global $wpdb;
$table_name = $wpdb->prefix . 'woocost';

// Check if we are editing
$editing = false;
$cost_entry = null;
if ( isset($_GET['edit_id']) ) {
	$edit_id = intval( $_GET['edit_id'] );
	$cost_entry = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $edit_id ) );
	$editing = true;
}
?>


<div class="wrap">
    <h2><?php echo $editing ? 'Edit Cost Entry' : 'Add New Cost'; ?></h2>
    <form id="costForm" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="save_cost">
        <input type="hidden" name="woocost_nonce" value="<?php echo wp_create_nonce( 'save_cost' ); ?>">
		<?php if ( $editing ) : ?>
            <input type="hidden" name="entry_id" value="<?php echo esc_attr( $cost_entry->id ); ?>">
		<?php endif; ?>

        <table id="costTable">
            <thead>
            <tr>
                <th>Cost Type Name *</th>
                <th>Cost *</th>
                <th>Account *</th>
                <th>Notes</th>
                <th>Cost Memo</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><input type="text" name="cost-type-name" placeholder="Enter cost type name" value="<?php echo $editing ? esc_attr( $cost_entry->cost_type_name ) : ''; ?>" required></td>
                <td><input type="number" name="cost" placeholder="Enter cost" class="cost-input" value="<?php echo $editing ? esc_attr( $cost_entry->cost ) : ''; ?>" required></td>
                <td>
                    <select name="account" class="account-selection" required>
                        <option value="" <?php selected( $editing && $cost_entry->account == '', true ); ?>>Choose Account Type</option>
                        <option value="cash" <?php selected( $editing && $cost_entry->account == 'cash', true ); ?>>Cash</option>
                        <option value="bank-account" <?php selected( $editing && $cost_entry->account == 'bank-account', true ); ?>>Bank Accounts</option>
                        <option value="card" <?php selected( $editing && $cost_entry->account == 'card', true ); ?>>Card</option>
                    </select>
                </td>
                <td><textarea id="notes" name="notes" class="notes-area"><?php echo $editing ? esc_textarea( $cost_entry->notes ) : ''; ?></textarea></td>
                <td>
                    <input type="file" id="memo" name="memo" class="memo-input" accept=".pdf,.doc,.docx,.txt,.ppt,.png,.jpg,.jpeg">
					<?php if ( $editing && !empty( $cost_entry->memo ) ) : ?>
                        <p>Current file: <a href="<?php echo esc_url( $cost_entry->memo ); ?>" target="_blank"><?php echo basename( $cost_entry->memo ); ?></a></p>
					<?php endif; ?>
                </td>
                <td><input type="date" name="date" class="input-date" value="<?php echo $editing ? esc_attr( $cost_entry->date ) : ''; ?>" ></td>

            </tr>

            </tbody>

        </table>
        <div class="cost-btn-group">
            <button type="submit" class="button-primary"><?php echo $editing ? 'Update' : 'Save'; ?></button>
        </div>
    </form>


        <h2>Cost Details</h2>
        <table class="widefat fixed">
            <thead>
            <tr>
                <th>Cost Type Name</th>
                <th>Cost</th>
                <th>Account</th>
                <th>Notes</th>
                <th>Cost Memo</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
		    <?php
		    $cost_entries = $wpdb->get_results( "SELECT * FROM $table_name" );
		    if ( $cost_entries ) {
			    foreach ( $cost_entries as $entry ) {
				    $delete_url = esc_url( admin_url( 'admin-post.php?action=delete_cost&cost_id=' . esc_attr( $entry->id ) . '&_wpnonce=' . wp_create_nonce( 'delete_cost_' . $entry->id ) ) );
				    echo '<tr>';
				    echo '<td>' . esc_html( $entry->cost_type_name ) . '</td>';
				    echo '<td>' . esc_html( $entry->cost ) . '</td>';
				    echo '<td>' . esc_html( $entry->account ) . '</td>';
				    echo '<td>' . esc_html( $entry->notes ) . '</td>';
				    echo '<td>';
				    if (!empty( $entry->memo )) {
					    echo '<a href="' . esc_url( $entry->memo ) . '" target="_blank">' . esc_html( basename( $entry->memo ) ) . '</a>';
				    } else {
					    echo 'No file';
				    }
				    echo '</td>';
				    echo '<td>' . esc_html( $entry->date ) . '</td>';
				    echo '<td>';
				    echo '<a href="?page=operation-cost&edit_id=' . esc_attr( $entry->id ) . '">Edit</a> | ';
				    echo '<a href="' . $delete_url . '" onclick="return confirm(\'Are you sure you want to delete this entry?\');">Delete</a>';
				    echo '</td>';
				    echo '</tr>';
			    }
		    } else {
			    echo '<tr><td colspan="6">No cost entries found.</td></tr>';
		    }
		    ?>
            </tbody>
        </table>


</div>

