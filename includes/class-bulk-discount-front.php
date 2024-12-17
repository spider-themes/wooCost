<?php

class Bulk_Discount_Front
{
	
	public function __construct(){
		
		add_filter( 'woocommerce_get_price_html', array( $this, 'custom_override_product_price' ), 10, 2 );

		add_filter( 'woocommerce_add_cart_item_data', array( $this, 'add_data_in_cart_menu' ), 10, 3 );

		add_filter( 'woocommerce_add_cart_item', array( $this, 'add_cart_item' ), 10, 1 );

		add_action( 'woocommerce_after_cart_item_quantity_update', array( $this, 'update_price_on_quantity_update' ), 20, 4 );

		add_filter( 'woocommerce_get_item_data', array( $this, 'get_item_data_filter' ), 10, 2 );

		add_filter( 'woocommerce_get_cart_item_from_session', array( $this, 'get_cart_item_from_session' ), 10, 2 );

		add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'checkout_create_order_line_item' ), 10, 4 );

		add_action( 'woocommerce_order_item_meta_start', array( $this, 'show_option_name_thankyou_page' ), 10, 3 );
	}

	public function add_data_in_cart_menu( $cart_item_data, $product_id, $variation_id ){

		$prod_id = $variation_id > 0 ? $variation_id : $product_id;

		$prod_price = wc_get_product($prod_id)->get_price();

		$bulk_discount_rules = get_posts(
			array(

				'post_type' => 'bulk_discount',
				'post_status' => 'publish',
				'numberposts' => -1,
				'orderby' => 'date',
				'order' => 'DESC',
				'fields' => 'ids'

			)
		);

		if (empty($bulk_discount_rules)) {
			return $cart_item_data;
		}

		
		foreach ( $bulk_discount_rules as $rule_id ){ 
			$is_rule_applied = true;

			$new_price = 0;

    		if ( ! $rule_id) {
				$is_rule_applied = false;
    			continue;
    		}

			if('yes'  != get_post_meta($rule_id,'bulk_discount_ativate_rule',true)){
				$is_rule_applied =false;
				continue;
			}

    		if ( ! $this->product_included($rule_id, $product_id) ) {
				$is_rule_applied = false;
    			continue;
    		}

    		if ( $this->product_excluded($rule_id, $product_id) ) {
				$is_rule_applied = false;
    			continue;
    		}

    		if ( ! $this->check_user($rule_id) ) {
				$is_rule_applied = false;
    			continue;
    		}


		   	$new_price = ($this->product_new_price($rule_id, $prod_id,$prod_price))[0];
			break;

		   	// if ( $new_price > 0 ) {

		   	// 	break;
		   	// }

    	}

		if($is_rule_applied){
			$selected_data_add = array(
				'rule_id'   => $rule_id,
				'prod_final_price' => $new_price,
			);
	
			$cart_item_data['selected_item_post_id'] = $selected_data_add;
		}


		return $cart_item_data;
	}

	public function add_cart_item( $cart_item_data ){

		$prod_price  = $cart_item_data['data']->get_price();

		$prod_id = $cart_item_data['variation_id'] > 0 ? $cart_item_data['variation_id'] : $cart_item_data['product_id'] ;

		if ( in_array( 'selected_item_post_id', array_keys( $cart_item_data ) ) ) {

			$get_added_data = $cart_item_data['selected_item_post_id'];

			$cart_item_data['data']->set_price( $get_added_data['prod_final_price'] );
		}

    	return $cart_item_data;
	}

	public function update_price_on_quantity_update( $cart_item_key, $quantity, $old_quantity, $cart ) {

		$cart_item_data = $cart->get_cart_item( $cart_item_key );

		$product_price  = wc_get_product($cart_item_data['product_id'])->get_price();

		if ( in_array( 'selected_item_post_id', array_keys( $cart_item_data ) ) ) {

			$get_added_data = $cart_item_data['selected_item_post_id'];

			$cart_item_data['data']->set_price( $get_added_data['prod_final_price'] );

		}

		return $cart_item_data;
	}

	public function get_item_data_filter( $item_data, $cart_item_data ){

		$product_price  = wc_get_product($cart_item_data['product_id'])->get_price();

		if ( in_array( 'selected_item_post_id', array_keys( $cart_item_data ) ) ) {

			$get_added_data = $cart_item_data['selected_item_post_id'];

			$item_data[] = array(
				'key'   => 'New Price',
				'value' => wc_price($get_added_data['prod_final_price']),
			);

		}

		return $item_data;	
	}

	public function get_cart_item_from_session( $cart_item, $values ) {

		if ( isset( $cart_item['selected_item_post_id'] ) ) {

			$new_price = $cart_item['selected_item_post_id']['prod_final_price'];

			$cart_item['data']->set_price( $new_price );
		}

		return $cart_item;
	}

	public function checkout_create_order_line_item( $item, $cart_item_key, $values, $order ) {

		$order_id = $order->get_id();

		foreach ( WC()->cart->get_cart() as $item_key => $value_check ) {

			$total_files_in_array = 0;

			if ( ! empty( $value_check ) && $item_key === $cart_item_key && array_key_exists( 'selected_item_post_id', $value_check ) ) {

				$get_data_of_files = $value_check['selected_item_post_id'];

				$item->add_meta_data( 'selected_item_post_id', $get_data_of_files, true );
			}
		}
	}

	public function show_option_name_thankyou_page( $item_id, $item, $order ) {

		foreach ( $item->get_meta_data() as $item_data ) {

			$item_data_array = $item_data->get_data();

			if ( in_array( 'selected_item_post_id', $item_data_array ) ) {
				
				$get_added_data = $item_data_array['value'];

				?><b> New price: </b><br><?php

				?><b><?php echo wc_price($get_added_data['prod_final_price']) ?></b><?php
			}
		}
	}

	public function custom_override_product_price( $price_html, $product ) {

		$old_price = $product->get_price();

	 	// if ( is_admin()) {
	 	// 	return;
	 	// }

		$product_id = get_the_ID();

		$bulk_discount_rules = get_posts(
			array(

				'post_type' => 'bulk_discount',
				'post_status' => 'publish',
				'numberposts' => -1,
				'orderby' => 'date',
				'order' => 'DESC',
				'fields' => 'ids'

			)
		);

		foreach ( $bulk_discount_rules as $rule_id ){ 

    		if ( ! $rule_id) {
    			continue;
    		}

			if('yes'  != get_post_meta($rule_id,'bulk_discount_ativate_rule',true)){
				// $is_rule_applied =false;
				continue;
			}


    		if ( ! $this->product_included($rule_id, $product_id) ) {
    			continue;
    		}

    		if ( $this->product_excluded($rule_id, $product_id) ) {
    			continue;
    		}

    		if ( ! $this->check_user($rule_id) ) {
    			continue;
    		}


		   	$new_price = ($this->product_new_price($rule_id, $product_id,$old_price))[0];
			$is_rule_applied = ($this->product_new_price($rule_id, $product_id,$old_price))[1];
			

			
			if(!$is_rule_applied){
				return $price_html;
			}

			if('yes' != get_post_meta( $rule_id, 'show_discount_in_loop', true)){
				$price_html =  '<ins>' . wc_price( $old_price ) . '</ins>';
				break;

			}

			

		   	// if ( $new_price > 0 ) {
			
		   		
			$price_html =  '<del>'.wc_price( $old_price ).'</del> <ins>' . wc_price( $new_price ) . '</ins>';

			break;
		   	// }

    	} 

	    return $price_html;
	}

	public function product_included($rule_id,$product_id){

		if ('all_products' == get_post_meta( $rule_id, 'products', true ) ) {
		
			return true;
		
		}else if ( 'specific_products' == get_post_meta( $rule_id, 'products', true ) ) {

			$specific_products = (array) get_post_meta( $rule_id, 'specific_products', true );
			
			if ( empty( $specific_products ) ) {
				return true;
			}else{

				if (in_array($product_id, $specific_products)) {
					return true;
				}
			}
		}

		return false;
	}

	public function product_excluded( $rule_id, $product_id ){

		if ('yes' != get_post_meta( $rule_id, 'exluclude_products_checkbox', true )) {
			return false;
		}

		$exclude_products = get_post_meta( $rule_id, 'exclude_products', true );

		$exclude_specific_products = (array) get_post_meta( $rule_id, 'exclude_specific_products', true );

	
		$exclude_specific_categories = (array) get_post_meta( $rule_id, 'exclude_specific_categories', true );
		
		$exclude_specific_tags = (array) get_post_meta( $rule_id, 'exclude_specific_tags', true );

		if ('specific_products' == $exclude_products) {

			if (empty($exclude_specific_products)) {
				return false;
			}
			
			if ( in_array( $product_id, $exclude_specific_products ) ) {
				return true;
			}

		}else if ('specific_categories' == $exclude_products) {

			if (empty($exclude_specific_categories)) {
				return false;
			}

			if ( has_term( $exclude_specific_categories, 'product_cat', $product_id ) ) {
				return true;
			}

		}else{

			if (empty($exclude_specific_tags)) {
				return false;
			}

			if ( has_term( $exclude_specific_tags, 'product_tag', $product_id ) ) {
				return true;
			}
		}

		return false;
	}

	public function check_user($rule_id){

		$user_role = get_post_meta( $rule_id, 'user_role', true );

		$current_user = wp_get_current_user();

		if ( ! empty( $current_user->roles ) && is_array( $current_user->roles ) ) {

	       $current_user_role = $current_user->roles[0];
	    }

	    $current_user_id = $current_user->ID;

		if ( 'all_users' == $user_role ) {

			return true;

		}else if ( 'specific_user' == $user_role ) {

			$specific_users = (array) get_post_meta($rule_id, 'specific_users', true );

			if ( in_array($current_user_id, $specific_users ) ) {
				return true;
			}

		}else{

			$specific_user_roles = (array) get_post_meta($rule_id, 'specific_user_roles', true );

			if ( in_array($current_user_role, $specific_user_roles) ) {
				return true;
			}
		}

		return false;
	}

	public function product_new_price($rule_id, $product_id,$product_price){

		$new_price = $product_price;
		$rule_applied = false;

		$all_rules = get_posts(
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

		if (is_bool( wc_get_product($product_id) )) {
			return [$new_price,$rule_applied];

		}

		$product_price = (int)wc_get_product($product_id)->get_price();

		$_woo_product_cost = get_post_meta( $product_id, '_woo_product_cost', true );

		if(!$_woo_product_cost){
			return [$new_price,$rule_applied];
		}

		if ($_woo_product_cost < 0 ) {
			return [0,$rule_applied];

		}

		
		$profit_percentage = (($product_price - $_woo_product_cost) / $_woo_product_cost) * 100;
		$profit_value      =  $product_price - $_woo_product_cost;
		
	

		$profit_percentage = round( $profit_percentage, 2 );

		foreach ($all_rules as $discount_rule_id) {
			
			$bulk_discount_from = get_post_meta( $discount_rule_id, 'bulk_discount_from', true );

			$bulk_discount_to = get_post_meta( $discount_rule_id, 'bulk_discount_to', true );

			$discount_amount = (int) get_post_meta( $discount_rule_id, 'discount_amount', true );

			$profit_type = get_post_meta( $discount_rule_id, 'discount_type', true);

			if('percentage' == $profit_type){
				if ( $profit_percentage >= $bulk_discount_from && $profit_percentage <= $bulk_discount_to ) {
					$new_price = $product_price - ( $product_price * $discount_amount ) / 100;
					$rule_applied =true;
					break;
				}
			}
			else if('fixed' == $profit_type){
				if ( $profit_percentage >= $bulk_discount_from && $profit_percentage <= $bulk_discount_to  ) {
					$new_price = $product_price - $discount_amount;
					$rule_applied =true;
					break;
				}
			}
			

			
		}

		if($new_price<0){
			$new_price = 0;
		}
		return [$new_price,$rule_applied];
	}
}

new Bulk_Discount_Front();