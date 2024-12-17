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
    <div class="card w-100 neg-margin p-0">
        <form name="custom-date-range-form" id="custom-date-range-form" method="post">
            <div class="flex gap-10">
                <select id="date-range-select" class="nice-select">
                    <option value="today" data-closed-text="Today" data-open-text="Today  <?php echo str_repeat('&nbsp;',17).formatDate($today); ?> - <?php echo formatDate($today); ?>">
                        Today
                    </option>
                    <option value="yesterday" data-closed-text="Yesterday" data-open-text="Yesterday  <?php echo str_repeat('&nbsp;',11).formatDate($yesterday); ?> - <?php echo formatDate($today); ?>">
                        Yesterday
                    </option>
                    <option value="last-7-days" data-closed-text="Last 7 Days" data-open-text="Last 7 Days  <?php echo str_repeat('&nbsp;',8).formatDate($sevenDaysBack); ?> - <?php echo formatDate($today); ?>">
                        Last 7 Days
                    </option>
                    <option value="last-14-days" data-closed-text="Last 14 Days" data-open-text="Last 14 Days  <?php echo str_repeat('&nbsp;',6).formatDate($fourteenDaysBack); ?> - <?php echo formatDate($today); ?>">
                        Last 14 Days
                    </option>
                    <option value="this-month" data-closed-text="Month to Date" data-open-text="Month to Date  <?php echo str_repeat('&nbsp;',4).formatDate($firstOfCurrentMonth); ?> - <?php echo formatDate($today); ?>">
                        Month to Date
                    </option>
                    <option value="last-month" data-closed-text="Last Month" data-open-text="Last Month  <?php echo str_repeat('&nbsp;',9).formatDate($firstOfPreviousMonth); ?> - <?php echo formatDate($lastOfPreviousMonth); ?>">
                        Last Month
                    </option>
                    <option value="all-time" data-closed-text="All Time" data-open-text="All Time">
                        All time
                    </option>
                    <option value="custom-date" data-closed-text="Custom Date" data-open-text="Custom Date">
                        Custom Date
                    </option>
                </select>
                <div class="custom-date-filter-div">
                    <input type="date" id="start_date" name="start_date" autocomplete="off" placeholder="Start Date"
                        style="background: #234058; color: white; font: icon;">
                    <input type="date" id="end_date" name="end_date" autocomplete="off" placeholder="End Date"
                        style="background: #234058; color: white; font: icon;">
                </div>
            </div>
        </form>
        <!--comparison tab-->
        <div class="flex">
            <form action="" method="post" id="previous-date-range-form" class="flex">
                <h4 class="compare-text title">Compare to</h4>
                <select id="previous-date-range-select" class="nice-select">
                    <option value="no-comparison" selected><?php esc_html_e('No comparison', 'woocost'); ?></option>
                    <option value="previous-month"><?php esc_html_e('Previous Month', 'woocost'); ?><small
                            class="small"> </small></option>
                    <option value="previous-period"><?php esc_html_e('Previous Period', 'woocost'); ?><small
                            class="small"> </small></option>
                    <option value="previous-quarter"><?php esc_html_e('Previous Quarter', 'woocost'); ?><small
                            class="small"> </small></option>
                    <option value="custom-date"><?php esc_html_e('Custom', 'woocost'); ?></option>

                </select>

                <div style="width:375px" class="nice-select1"><span style="padding-left:20px" class="current"></span></div>

                <div id="custom-date-range" style="padding-left:30px">
                    <input type="date" id="prev_start_date" name="prev_start_date" autocomplete="off"
                        placeholder="Start Date">
                    <input type="date" id="prev_end_date" name="prev_end_date" autocomplete="off"
                        placeholder="End Date">
                </div>
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
