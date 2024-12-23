<?php

function formatDate($date) {
    return $date->format('F j, Y');
}

$today = new DateTime();

$yesterday = new DateTime('-1 day');

$sevenDaysBack = new DateTime('-7 days');

$fourteenDaysBack = new DateTime('-14 days');


$oneMonthBack = new DateTime('-1 month');

$firstOfCurrentMonth = new DateTime('first day of this month');

$firstOfPreviousMonth = new DateTime('first day of last month');

$lastOfPreviousMonth = new DateTime('last day of last month');

?>

<div class="">
    <div class="page-header">
        <h2 class="text-white title">Date Range</h2>
    </div>
    <div class="card w-100 neg-margin align-items-center">
        <form name="custom-date-range-form" class="custom-date-range-form" id="custom-date-range-form" method="post">
            <div class="flex gap-10">
                <select id="date-range-select" class="nice-select">
                    <option value="today" data-display="Today" data-open-text="<?php echo formatDate($today); ?> - <?php echo formatDate($today); ?>">
                        Today
                    </option>
                    <option value="yesterday" data-display="Yesterday" data-open-text="<?php echo formatDate($yesterday); ?> - <?php echo formatDate($today); ?>">
                        Yesterday
                    </option>
                    <option value="last-7-days" data-display="Last 7 Days" data-open-text="<?php echo formatDate($sevenDaysBack); ?> - <?php echo formatDate($today); ?>">
                        Last 7 Days
                    </option>
                    <option value="last-14-days" data-display="Last 14 Days" data-open-text="<?php echo formatDate($fourteenDaysBack); ?> - <?php echo formatDate($today); ?>">
                        Last 14 Days
                    </option>
                    <option value="this-month" data-display="Month to Date" data-open-text="<?php echo formatDate($firstOfCurrentMonth); ?> - <?php echo formatDate($today); ?>">
                        Month to Date
                    </option>
                    <option value="last-month" data-display="Last Month" data-open-text="<?php echo formatDate($firstOfPreviousMonth); ?> - <?php echo formatDate($lastOfPreviousMonth); ?>">
                        Last Month
                    </option>
                    <option value="all-time" data-display="All Time">
                        All time
                    </option>
                </select>
            </div>
        </form>
        <!--comparison tab-->
        <div class="flex">
            <form action="" method="post"  class="custom-date-range-form flex" id="previous-date-range-form">
                <h4 class="compare-text title">Compare to</h4>
                <select id="previous-date-range-select" class="previous-date-range-select nice-select">
                    <option value="no-comparison" data-display="No comparison" selected><?php esc_html_e('No comparison', 'woocost'); ?></option>
                    <option value="previous-month" data-display="Previous Month"><?php esc_html_e('Previous Month', 'woocost'); ?><small
                            class="small"> </small></option>
                    <option value="previous-period" data-display="Previous Period"><?php esc_html_e('Previous Period', 'woocost'); ?><small
                            class="small"> </small></option>
                    <option value="previous-quarter" data-display="Previous Quarter"><?php esc_html_e('Previous Quarter', 'woocost'); ?><small
                            class="small"> </small></option>
                </select>

            </form>

        </div>
        <!--end comparison tab-->
    </div>
</div>

<div class="flex gap-10 w-100 justify-center">
    <div class="col-3">
        <div class="card">
            <div class="card-header flex space-between">
                <h2 class="text-white title">Order</h2>
                <span class="badge" id="order-percentage-change"></span>
            </div>
            <div class="card-body flex space-between">
                <div id="orders-list"></div>
                <div id="pre-orders-list" class="text-off-white"></div>
            </div>
        </div>
    </div>
    <div class="col-3">
        <div class="card">
            <div class="card-header flex space-between">
                <h2 class="text-white title">Total Sales</h2>
                <span class="badge" id="total-sales-percentage-change"></span>
            </div>
            <div class="card-body flex space-between">
                <div id="total-sales"></div>
                <div id="pre-total-sales" class="text-off-white"></div>
            </div>
        </div>
    </div>

    <div class="col-3">
        <div class="card">
            <div class="card-header flex space-between">
                <h2 class="text-white title">Cost</h2>
                <span class="badge" id="total-cost-percentage-change"></span>
            </div>
            <div class="card-body flex space-between">
                <div id="total-cost"></div>
                <div id="pre-total-cost" class="text-off-white"></div>
            </div>
        </div>
    </div>
</div>
<div class="flex gap-10">
    <div class="col-3">
        <div class="card">
            <div class="card-header flex space-between">
                <h2 class="text-white title">Average Daily Profit </h2>
                <span class="badge" id="average-daily-profit-percentage-change"></span>
            </div>
            <div class="card-body flex space-between">
                <div id="average-profit"></div>
                <div id="pre-average-daily-profit" class="text-off-white"></div>
            </div>
        </div>
    </div>
    <div class="col-3">
        <div class="card">
            <div class="card-header flex space-between">
                <h2 class="text-white title">Average Order profit</h2>
                <span class="badge" id="average-order-profit-percentage-change"></span>
            </div>
            <div class="card-body flex space-between">
                <div id="average-order-profit"></div>
                <div id="pre-average-order-profit" class="text-off-white"></div>
            </div>
        </div>
    </div>
    <div class="col-3">
        <div class="card">
            <div class="card-header flex space-between">
                <h2 class="text-white title">Total Profit</h2>
                <span class="badge" id="total-profit-percentage-change"></span>
            </div>
            <div class="card-body flex space-between">
                <div class="flex aligncenter gap-5">
                    <div id="profit"></div>
                    <div id="profit-percentage"></div>
                </div>
                <div id="pre-profit" class="text-off-white"></div>
            </div>
        </div>
    </div>
</div>
<?php
