<?php

/**
 * create cost and profit columns
 *
 * @param $columns
 *
 * @return mixed
 */
function woocost_make_cost_and_profit_column_sortable( $columns ): mixed {
	$columns['product_cost']   = 'product_cost';
	$columns['product_profit'] = 'product_profit';

	return $columns;
}
/**
 * Make the custom columns sortable
 */
add_filter( 'manage_edit-product_sortable_columns', 'woocost_make_cost_and_profit_column_sortable');

/**
 * cost and profit columns sorting
 *
 * @param $query
 *
 * @return void
 */
function woocost_sort_cost_and_profit_columns( $query ): void {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}
	$orderby = $query->get( 'orderby' );

	if ( 'product_cost' === $orderby ) {
		$query->set( 'meta_key', '_woo_product_cost' );
		$query->set( 'orderby', 'meta_value_num' );
	}

	if ( 'product_profit' === $orderby ) {
		$query->set( 'meta_key', '_product_profit' );
		$query->set( 'orderby', 'meta_value_num' );
	}
}
/**
 * Handle sorting by custom columns
 */
add_action( 'pre_get_posts', 'woocost_sort_cost_and_profit_columns');
