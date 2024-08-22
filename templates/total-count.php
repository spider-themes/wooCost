<?php


/**
 * Total Cost Calculation
 *
 * @return int|null
 */
class TotalCount {

	public function __construct() {
		$this->total_cost();
		$this->total_stock()();
		$this->total_price()();
		$this->total_profit();

	}
	function total_stock(): ?int {
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
	function total_price(): float|int {
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

	function total_cost(): float|int {
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
	function total_profit(): float|int {
		return $this->total_price() - $this->total_cost();
	}
}

new TotalCount();

