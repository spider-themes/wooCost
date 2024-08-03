;(function ($) {
    'use strict';

    jQuery(document).ready(function ($) {

        $('select').niceSelect();

        // Event listener for date range selection
        $('#date-range-select').on('change', function () {
            const selectedRange = $(this).val();
            const today = new Date();
            let startDate, endDate;

            switch (selectedRange) {
                case 'today':
                    startDate = today.toISOString().split('T')[0];
                    endDate = startDate;
                    break;
                case 'yesterday':
                    const yesterday = new Date(today);
                    yesterday.setDate(today.getDate() - 1);
                    startDate = yesterday.toISOString().split('T')[0];
                    endDate = startDate;
                    break;
                case 'last-7-days':
                    const last7Days = new Date(today);
                    last7Days.setDate(today.getDate() - 7);
                    startDate = last7Days.toISOString().split('T')[0];
                    endDate = today.toISOString().split('T')[0];
                    break;
                case 'last-14-days':
                    const last14Days = new Date(today);
                    last14Days.setDate(today.getDate() - 14);
                    startDate = last14Days.toISOString().split('T')[0];
                    endDate = today.toISOString().split('T')[0];
                    break;
                case 'this-month':
                    startDate = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
                    endDate = today.toISOString().split('T')[0];
                    break;
                case 'last-month':
                    const firstDayLastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                    const lastDayLastMonth = new Date(today.getFullYear(), today.getMonth(), 0);
                    startDate = firstDayLastMonth.toISOString().split('T')[0];
                    endDate = lastDayLastMonth.toISOString().split('T')[0];
                    break;
                default:
                    startDate = today.toISOString().split('T')[0];
                    endDate = startDate;
            }

            $('#start_date').val(startDate);
            $('#end_date').val(endDate);

            loadData();
        });

        $('#start_date, #end_date').datepicker({
            dateFormat: 'yy-mm-dd'
        });

        function loadData() {
            const start_date = $('#start_date').val();
            const end_date = $('#end_date').val();
            const date_range = $('#date-range-select').val();

            $.ajax({
                url: ajax_params.ajaxurl,
                method: 'POST',
                data: {
                    action: 'wooprofit_get_orders_by_date_range',
                    start_date: start_date,
                    end_date: end_date,
                    date_range: date_range
                },
                success: function (response) {
                    const data = JSON.parse(response);

                    $('#orders-list').html(data.total_orders || 0);
                    $('#total-sales').html(data.total_sales || 0);
                    $('#net-sales').html(data.net_sales || 0);
                    $('#total-cost').html(data.total_cost || 0);
                    // $('#average-order-value').html(data.average_order_value || 0);
                    $('#profit').html(data.profit).attr('class', data.profit_class || '');
                    $('#average-profit').html(data.average_profit || 0);
                    $('#average-order-profit').html(data.average_order_profit || 0);
                    $('#profit-percentage').html(`(<span id="profit-percentage">${data.profit_percentage || 0}</span>)`);

                },
                error: function (error) {
                    console.log('Error:', error);
                }
            });
        }

        $("#custom-date-range-form").submit(function (event) {
            event.preventDefault();
            loadData();
        });

        $('#start_date').change(loadData);
        $('#end_date').change(loadData);

        $('#date-range-select').val('today').change();

        $('select.nice-select').niceSelect();

        $('#prev_start_date, #prev_end_date').datepicker({
            dateFormat: 'yy-mm-dd'
        });

        function updateDateRangeFields() {
            var selectedOption = $('#previous-date-range-select option:selected');
            var startDate = selectedOption.attr('data-start-date');
            var endDate = selectedOption.attr('data-end-date');
            // var selectedValue = selectedOption.val();
            if (startDate && endDate) {
                $('#prev_start_date').val(startDate);
                $('#prev_end_date').val(endDate);
            } else {
                $('#prev_start_date').val('');
                $('#prev_end_date').val('');
            }
        }

        $('#custom-date-range').hide();

        // onload badge class will be hide
        $('.badge').hide();
        $('[id^=pre-]').hide();
        $('#previous-date-range-select').on('change', function () {

            updateDateRangeFields();
            const selectedValue = $(this).val();

            if (selectedValue === 'no-comparison' ) {
                $('.badge').hide();
                $('[id^=pre-]').hide();
            } else {
                $('#custom-date-range').hide();
                $('.badge').show();
                $('[id^=pre-]').show();
                compareData();
            }
        });

        $('#prev_start_date, #prev_end_date').datepicker({
            dateFormat: 'yy-mm-dd',
            onSelect: function() {
                const startDate = $('#prev_start_date').val();
                const endDate = $('#prev_end_date').val();
                if (startDate && endDate) {
                    $('.badge').show();
                    $('[id^=pre-]').show();
                    compareData();
                }
            }
        })

        function updateData(previousMonth, currencySymbol) {
            // Ensure previousMonth is an object
            if (typeof previousMonth !== 'object' || previousMonth === null) {
                // console.error('Invalid data provided to updateData function.');
                return; // Exit function if previousMonth is invalid
            }

            // Helper function to validate number
            const validateNumber = (num) => typeof num === 'number' && !isNaN(num);

            // Update DOM elements with validated data
            jQuery("#pre-orders-list").html(validateNumber(previousMonth.total_orders) ? previousMonth.total_orders : 0);
            jQuery("#pre-total-sales").html(validateNumber(previousMonth.total_sales) ? `${currencySymbol}${previousMonth.total_sales.toFixed(2)}` : `${currencySymbol}0.00`);
            jQuery("#pre-total-cost").html(validateNumber(previousMonth.total_cost) ? `${currencySymbol}${previousMonth.total_cost.toFixed(2)}` : `${currencySymbol}0.00`);
            jQuery("#pre-average-order-profit").html(validateNumber(previousMonth.average_order_profit) ? `${currencySymbol}${previousMonth.average_order_profit.toFixed(2)}` : `${currencySymbol}0.00`);
            jQuery("#pre-profit").html(validateNumber(previousMonth.total_profit) ? `${currencySymbol}${previousMonth.total_profit.toFixed(2)}` : `${currencySymbol}0.00`);
            jQuery("#pre-average-daily-profit").html(validateNumber(previousMonth.average_daily_profit) ? `${currencySymbol}${previousMonth.average_daily_profit.toFixed(2)}` : `${currencySymbol}0.00`);
        }

        function updatePercentageChanges(response) {
            const setPercentageChange = (selector, value) => {
                const element = jQuery(selector);

                // Check if value is a number
                if (typeof value !== 'number' || isNaN(value)) {
                    element.html('N/A'); // Show 'N/A' or handle invalid value as needed
                    return;
                }

                // Format percentage value
                let formattedValue = value > 0 ? `+${value.toFixed(1)}%` : `${value.toFixed(1)}%`;

                // Apply background color for different conditions
                if (value < 0) {
                    element.css('background-color', '#E68E00'); // Negative percentage
                } else if (value === 0) {
                    element.css('background-color', '#50809f'); // Zero percentage
                    formattedValue = '0.0%';
                } else {
                    element.css('background-color', ''); // Reset background color for positive percentage
                }

                element.html(formattedValue);
            };

            setPercentageChange("#order-percentage-change", response.order_percentage_change);
            setPercentageChange("#total-sales-percentage-change", response.total_sales_percentage_change);
            setPercentageChange("#total-cost-percentage-change", response.total_cost_percentage_change);
            setPercentageChange("#average-daily-profit-percentage-change", response.average_daily_profit_percentage_change);
            setPercentageChange("#average-order-profit-percentage-change", response.average_order_profit_percentage_change);
            setPercentageChange("#total-profit-percentage-change", response.total_profit_percentage_change);
        }


        function compareData() {
            const currentStartDate = $('#start_date').val();
            const currentEndDate = $('#end_date').val();
            const prevStartDate = $('#prev_start_date').val();
            const prevEndDate = $('#prev_end_date').val();

            if (!currentStartDate || !currentEndDate || !prevStartDate || !prevEndDate) {
                console.error('Please select valid date ranges.');
                return;
            }

            $.ajax({
                url: ajax_params.ajaxurl,
                method: "POST",
                data: {
                    action: "wooprofit_compare_monthly_orders",
                    nonce: ajax_params.nonce,
                    current_start_date: currentStartDate,
                    current_end_date: currentEndDate,
                    prev_start_date: prevStartDate,
                    prev_end_date: prevEndDate
                },
                success: function(response) {
                    if (response) {
                        updateData(response.previous_month, response.currency_symbol);
                        updatePercentageChanges(response);
                    } else {
                        handleEmptyResponse(response.currency_symbol);
                    }
                },
                error: function(error) {
                    console.error("AJAX Error:", error);
                }
            });
        }

        function handleEmptyResponse(currencySymbol) {
            console.log('Empty response received.');
            jQuery("#pre-orders-list").html(0);
            jQuery("#pre-total-sales").html(`${currencySymbol}0.00`);
            // jQuery("#pre-average-order-value").html(`${currencySymbol}0.00`);
            jQuery("#pre-total-cost").html(`${currencySymbol}0.00`);
            jQuery("#pre-average-order-profit").html(`${currencySymbol}0.00`);
            jQuery("#pre-profit").html(`${currencySymbol}0.00`);
            jQuery("#pre-average-daily-profit").html(`${currencySymbol}0.00`);
            updatePercentageChanges({
                order_percentage_change: 0,
                total_sales_percentage_change: 0,
                // average_order_value_percentage_change: 0,
                total_cost_percentage_change: 0,
                average_daily_profit_percentage_change: 0,
                average_order_profit_percentage_change: 0,
                total_profit_percentage_change: 0
            });
        }

        // Fetch comparison data on page load
        compareData();

        // Fetch comparison data on date change
        $('#date-range-select, #previous-date-range-select').on('change', compareData);
    });
})(jQuery);