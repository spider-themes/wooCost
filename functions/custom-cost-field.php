<?php

/**
 * add cost field for product page
 *
 * @return void
 */
function woocost_add_cost_field(): void {
	woocommerce_wp_text_input(
		array(
			'id'          => '_woo_product_cost',
			'label'       => esc_html__( 'Cost', 'woocost' ),
			'placeholder' => 'Enter the cost price',
			'desc_tip'    => 'true',
			'description' => esc_html__( 'Enter the cost price of the product.', 'woocost' )
		)
	);
	echo '<p id="product_profit_display" class="form-field description">'
	     . esc_html__( 'Profit: 0.00 (' . esc_html( get_woocommerce_currency_symbol() ) . ' 0.00%)', 'woocost' ) . '</p>';

}
add_action( 'woocommerce_product_options_general_product_data',  'woocost_add_cost_field' ) ;


/**
 * Save cost field for product page
 *
 * @param $post_id
 *
 * @return void
 */
function woocost_save_cost_field( $post_id ): void {
	$product_cost = isset( $_POST['_woo_product_cost'] ) ? sanitize_text_field( $_POST['_woo_product_cost'] ) : '';
	update_post_meta( $post_id, '_woo_product_cost', $product_cost );
}

add_action( 'woocommerce_process_product_meta','woocost_save_cost_field' ) ;


/**
 * Add custom columns to the products admin page
 *
 * @param $columns
 *
 * @return array
 */
function woocost_add_cost_and_profit_column_header( $columns ): array {
	// Remove the existing 'product_cost' and 'product_profit' columns if they already exist
	unset( $columns['product_cost'] );
	unset( $columns['product_profit'] );

	/**
	 * Insert 'product_cost' and 'product_profit' after 'price' column
	 */
	$new_columns = array();
	foreach ( $columns as $key => $column ) {
		$new_columns[ $key ] = $column;
		if ( 'price' === $key ) {
			$new_columns['product_cost']   = esc_html__( 'Cost', 'woocost' );
			$new_columns['product_profit'] = esc_html__( 'Profit', 'woocost' );
		}
	}

	return $new_columns;
}
add_filter( 'manage_product_posts_columns',  'woocost_add_cost_and_profit_column_header', 20 );

/**
 * Populate custom columns with data
 *
 * @param $column
 * @param $post_id
 *
 * @return void
 */
function woocost_populate_cost_and_profit_column_content( $column, $post_id ): void {
	if ( 'product_cost' === $column ) {
		$product_cost = get_post_meta( $post_id, '_woo_product_cost', true );
		if ( $product_cost !== '' ) {
			echo esc_html( number_format( (float) $product_cost, 2 ) . esc_html( get_woocommerce_currency_symbol() ) );
		} else {
			echo '-';
		}
	}

	if ( 'product_profit' === $column ) {
		$product_price = get_post_meta( $post_id, '_price', true );
		$product_cost  = get_post_meta( $post_id, '_woo_product_cost', true );

		if ( $product_price && $product_cost !== '' ) {
			$profit            = $product_price - $product_cost;
			$profit_percentage = ( $product_cost > 0 ) ? ( $profit / $product_cost ) * 100 : 0;
			echo esc_html( number_format( (float) $profit, 2 ) . esc_html( get_woocommerce_currency_symbol() ) . ' ('
			               . number_format( $profit_percentage,
					2 ) . '%)' );
		} else {
			echo '-';
		}
	}
}

add_action( 'manage_product_posts_custom_column', 'woocost_populate_cost_and_profit_column_content', 20, 2 );