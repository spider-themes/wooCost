<?php
namespace wooCost\Admin\templates;

use Woocost;

$woocost = Woocost::get_instance(); // Get the global instance

ob_start();
$page_content = ob_get_clean(); // Get buffered content
?>
    <div class="wrap bg-dark mw-1400">
        <div class="page-header">
            <h2 class="text-white title">WooCost Details</h2>
        </div>
        <div class="flex gap-10">
            <div class="col-4 card">
                <div class="card-header flex">
                    <h2 class="text-white title">
                        Total Stock
                        <span class="tooltip" data-tooltip="Total Stock refers to the total number of all items currently available in the store.">
                        <i class="dashicons dashicons-info-outline"></i>
                    </span>
                    </h2>
                </div>
                <div class="card-body">
                    <p class="text-white">
						<?php echo esc_html( $woocost->total_stock() ); ?>
                    </p>
                </div>
            </div>
            <div class="col-4 card">
                <div class="card-header flex">
                    <h2 class="text-white title">
                        Total Price
                        <span class="tooltip" data-tooltip="Total Price represents the combined retail price of all products currently in stock.">
                        <i class="dashicons dashicons-info-outline"></i>
                    </span>
                    </h2>
                </div>
                <div class="card-body">
                    <p class="text-white">
						<?php echo get_woocommerce_currency_symbol() . esc_html( number_format( $woocost->total_price(), 2 ) ); ?>
                    </p>
                </div>
            </div>
            <div class="col-4 card">
                <div class="card-header flex">
                    <h2 class="text-white title">
                        Total Cost
                        <span class="tooltip" data-tooltip="Total Cost refers to the cumulative cost of all products currently in stock.">
                        <i class="dashicons dashicons-info-outline"></i>
                    </span>
                    </h2>
                </div>
                <div class="card-body">
                    <p class="text-white">
						<?php echo get_woocommerce_currency_symbol(). esc_html( number_format( $woocost->total_cost(), 2 ) ); ?>
                    </p>
                </div>
            </div>
            <div class="col-4 card">
                <div class="card-header flex">
                    <h2 class="text-white title">
                        Total Potential Profit
                        <span class="tooltip" data-tooltip="Total Potential Profit refers to the estimated profit that can be made from selling all items currently in stock at their listed prices.">
                        <i class="dashicons dashicons-info-outline"></i>
                    </span>
                    </h2>
                </div>
                <div class="card-body">
					<?php
					if ( $woocost->total_profit() > 0 ) { ?>
                        <p class="profit-positive">
							<?php echo esc_html( get_woocommerce_currency_symbol() ) . esc_html( number_format( $woocost->total_profit(), 2 ) ); ?>
                        </p>
						<?php
					} else {
						?>
                        <p class="profit-negative">
							<?php echo esc_html( get_woocommerce_currency_symbol() ) . esc_html( number_format( $woocost->total_profit(), 2 ) ); ?>
                        </p> <?php
					}
					?>
                </div>
            </div>
        </div>
        <div class="date-range-picker">
			<?php include_once plugin_dir_path( __FILE__ ) . 'report-by-date.php'; ?>
        </div>
    </div>

	<?php
echo $page_content;