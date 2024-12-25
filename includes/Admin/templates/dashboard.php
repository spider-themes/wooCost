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

            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="includeOperationCostCheck">
                <label class="form-check-label" for="includeOperationCostCheck">
                    Include Operation Cost
                </label>
            </div>
        </div>
        <div class="flex gap-10">
            <div class="col-4 card">
                <div class="card-header flex">
                    <h4 class="text-white title">
                        Total Stock
                        <span class="tooltip" data-tooltip="Total Stock refers to the total number of all items currently available in the store.">
                        <i class="dashicons dashicons-info-outline"></i>
                    </span>
                    </h4>
                </div>
                <div class="card-body">
                    <p class="text-white data-card-value">
						<?php echo esc_html( $woocost->total_stock() ); ?>
                    </p>
                </div>
            </div>
            <div class="col-4 card">
                <div class="card-header flex">
                    <h4 class="text-white title">
                        Total Price
                        <span class="tooltip" data-tooltip="Total Price represents the combined retail price of all products currently in stock.">
                        <i class="dashicons dashicons-info-outline"></i>
                    </span>
                    </h4>
                </div>
                <div class="card-body">
                    <p class="text-white data-card-value">
						<?php echo get_woocommerce_currency_symbol() . esc_html( number_format( $woocost->total_price(), 2 ) ); ?>
                    </p>
                </div>
            </div>
            <div class="col-4 card">
                <div class="card-header flex">
                    <h4 class="text-white title">
                        Total Cost
                        <span class="tooltip" data-tooltip="Total Cost refers to the cumulative cost of all products currently in stock.">
                        <i class="dashicons dashicons-info-outline"></i>
                    </span>
                    </h4>
                </div>
                <div class="card-body operation-check-data-parent">
                    <p class="text-white data-card-value operation-check-false">
						<?php echo get_woocommerce_currency_symbol() . esc_html( number_format( $woocost->total_cost(), 2 ) ); ?>

                    </p>
                    <p class="text-white data-card-value  operation-check-true">
						<?php echo get_woocommerce_currency_symbol() . esc_html( number_format($woocost->total_cost() + $woocost->total_operation_cost(), 2 ) ); ?>
                    </p>
                </div>
            </div>
            <div class="col-4 card">
                <div class="card-header flex">
                    <h4 class="text-white title">
                        Total Potential Profit
                        <span class="tooltip"
                              data-tooltip="Total Potential Profit refers to the estimated profit that can be made from selling all items currently in stock at their listed prices.">
                        <i class="dashicons dashicons-info-outline"></i>
                    </span>
                    </h4>
                </div>
                <div class="card-body operation-check-data-parent">
                    <div class="operation-check-false">
						<?php

						$total_profit_amount = $woocost->total_profit();
						if ( $total_profit_amount > 0 ) { ?>
                            <p class="profit-positive data-card-value">
								<?php echo esc_html( get_woocommerce_currency_symbol() ) . esc_html( number_format( $total_profit_amount, 2 ) ); ?>
                            </p>
							<?php
						} else {
							?>
                            <p class="profit-negative data-card-value">
								<?php echo esc_html( get_woocommerce_currency_symbol() ) . esc_html( number_format( $total_profit_amount, 2 ) ); ?>
                            </p> <?php
						}
						?>
                    </div>
                    <div class="operation-check-true">
						<?php
						$profit_without_operational_cost = $woocost->total_profit() - $woocost->total_operation_cost();
						if ( $profit_without_operational_cost > 0 ) { ?>
                            <p class="profit-positive data-card-value">
								<?php echo esc_html( get_woocommerce_currency_symbol() ) . esc_html( number_format( $profit_without_operational_cost, 2 ) ); ?>
                            </p>
							<?php
						} else {
							?>
                            <p class="profit-negative data-card-value">
								<?php echo esc_html( get_woocommerce_currency_symbol() ) . esc_html( number_format( $profit_without_operational_cost, 2 ) ); ?>
                            </p> <?php
						}
						?>
                    </div>

                </div>
            </div>
        </div>
        <div class="date-range-picker">
			<?php include_once plugin_dir_path( __FILE__ ) . 'report-by-date.php'; ?>
        </div>
    </div>

	<?php
echo $page_content;