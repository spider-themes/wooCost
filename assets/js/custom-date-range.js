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




        // Function to get date range based on type and offset
        function getDateRange(type, offset) {
            var today = new Date();
            var startDate, endDate;

            switch (type) {
                case 'month':
                    var firstDayOfMonth = new Date(today.getFullYear(), today.getMonth() - offset, 1);
                    var lastDayOfMonth = new Date(today.getFullYear(), today.getMonth() - offset + 1, 0);
                    startDate = firstDayOfMonth.toISOString().split('T')[0];
                    endDate = lastDayOfMonth.toISOString().split('T')[0];
                    break;
                case 'period':
                    var firstDayOfPreviousPeriod = new Date(today.getFullYear(), today.getMonth() - offset - 1, 1);
                    var lastDayOfPreviousPeriod = new Date(today.getFullYear(), today.getMonth() - offset, 0);
                    startDate = firstDayOfPreviousPeriod.toISOString().split('T')[0];
                    endDate = lastDayOfPreviousPeriod.toISOString().split('T')[0];
                    break;
                case 'quarter':
                    var currentQuarter = Math.floor((today.getMonth() + 3) / 3);
                    var firstMonthOfPreviousQuarter = (currentQuarter - 2) * 3;
                    var firstDayOfQuarter = new Date(today.getFullYear(), firstMonthOfPreviousQuarter, 1);
                    var lastDayOfQuarter = new Date(today.getFullYear(), firstMonthOfPreviousQuarter + 3, 0);
                    startDate = firstDayOfQuarter.toISOString().split('T')[0];
                    endDate = lastDayOfQuarter.toISOString().split('T')[0];
                    break;
                default:
                    startDate = '';
                    endDate = '';
            }

            return {
                startDate: startDate,
                endDate: endDate
            };
        }


        function updateDateRangeFields() {
            var selectedOption = $('#previous-date-range-select option:selected');
            var optionText = selectedOption.text().trim();

            // Default start and end date
            var startDate = '';
            var endDate = '';

            // Extract the start and end dates from the option text
            if (optionText.includes('Previous Month')) {
                startDate = getDateRange('month', -1).startDate;
                endDate = getDateRange('month', -1).endDate;
            } else if (optionText.includes('Previous Period')) {
                startDate = getDateRange('period', -1).startDate;
                endDate = getDateRange('period', -1).endDate;
            } else if (optionText.includes('Previous Quarter')) {
                startDate = getDateRange('quarter', -1).startDate;
                endDate = getDateRange('quarter', -1).endDate;
            }

            // Update the input fields with the extracted dates
            $('#prev_start_date').val(startDate);
            $('#prev_end_date').val(endDate);
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

        $('#custom-date-range').hide();

        // onload badge class will be hide
        $('.badge').hide();
        $('[id^=pre-]').hide();

        $('#previous-date-range-select').on('change', function () {
            updateDateRangeFields();
            updateDateRangeOptions();

            const selectedValue = $(this).val();
            const selectedOption = $(this).find('option:selected');

            if (selectedValue === 'no-comparison') {
                $('.badge').hide();
                $('[id^=pre-]').hide();
                $('#custom-date-range').hide();
                $('#prev_start_date').val('');
                $('#prev_end_date').val('');
            } else if (selectedValue === 'previous-month' || selectedValue === 'previous-period' || selectedValue === 'previous-quarter') {
                $('#custom-date-range').hide();
                const smallElement = selectedOption.find('.small');

                if (smallElement.length > 0) {
                    const dateText = smallElement.text().split(' – ');
                    $('#prev_start_date').val(dateText[0].trim());
                    $('#prev_end_date').val(dateText[1].trim());
                }

                $('.badge').show();
                $('[id^=pre-]').show();
                compareData();
            } else {
                $('#custom-date-range').show();
            }
        });



        $('#prev_start_date, #prev_end_date').datepicker({
            dateFormat: 'yy-mm-dd',
            onSelect: function () {
                const startDate = $('#prev_start_date').val();
                const endDate = $('#prev_end_date').val();
                if (startDate && endDate) {
                    $('.badge').show();
                    $('[id^=pre-]').show();
                    compareData();
                }
            }
        });

        function updateData(previousMonth, currencySymbol) {
            if (typeof previousMonth !== 'object' || previousMonth === null) {
                return;
            }

            const validateNumber = (num) => typeof num === 'number' && !isNaN(num);

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

                if (typeof value !== 'number' || isNaN(value)) {
                    element.html('N/A');
                    return;
                }

                let formattedValue = value > 0 ? `+${value.toFixed(1)}%` : `${value.toFixed(1)}%`;

                if (value < 0) {
                    element.css('background-color', '#E68E00');
                } else if (value === 0) {
                    element.css('background-color', '#50809f');
                    formattedValue = '0.0%';
                } else {
                    element.css('background-color', '');
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


        function formatDate(date) {
            const day = date.getDate().toString().padStart(2, '0');
            const month = (date.getMonth() + 1).toString().padStart(2, '0');
            const year = date.getFullYear();
            return `${month}/${day}/${year}`;
        }
        function updateDateRangeOptions() {
            const today = new Date();
            const currentMonthStart = new Date(today.getFullYear(), today.getMonth(), 1);
            const lastMonthStart = new Date(today.getFullYear(), today.getMonth() - 1, 1);
            const lastMonthEnd = new Date(today.getFullYear(), today.getMonth(), 0);
            const lastQuarterStart = new Date(today.getFullYear(), Math.floor((today.getMonth() - 1) / 3) * 3, 1);
            const lastQuarterEnd = new Date(today.getFullYear(), Math.floor((today.getMonth() - 1) / 3) * 3 + 3, 0);
            const previousPeriodStart = new Date(currentMonthStart.getTime() - (30 * 24 * 60 * 60 * 1000)); // Approximately 30 days ago
            const previousPeriodEnd = new Date(currentMonthStart.getTime() - (1 * 24 * 60 * 60 * 1000)); // Day before current month

            // Update date ranges in the <small> tags
            $('#previous-date-range-select option[value="previous-month"] small').text(
                ` (${formatDate(lastMonthStart)} – ${formatDate(lastMonthEnd)})`
            );

            $('#previous-date-range-select option[value="previous-period"] small').text(
                ` (${formatDate(previousPeriodStart)} – ${formatDate(previousPeriodEnd)})`
            );

            $('#previous-date-range-select option[value="previous-quarter"] small').text(
                ` (${formatDate(lastQuarterStart)} – ${formatDate(lastQuarterEnd)})`
            );
        }


        // Call the function to set the initial values
        updateDateRangeOptions();

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
                success: function (response) {
                    try {
                        if (response && response.success) {
                            const previousMonth = response.data.previous_month;
                            const currencySymbol = response.data.currency_symbol || '$';
                            updateData(previousMonth, currencySymbol);
                            updatePercentageChanges(response.data);
                        } else {
                            console.error('Failed to fetch comparison data:', response);
                            handleEmptyResponse('$');
                        }
                    } catch (error) {
                        console.error('Error parsing response:', error);
                        handleEmptyResponse('$');
                    }
                },
                error: function (error) {
                    console.error('Error:', error);
                    handleEmptyResponse('$');
                }
            });
        }


        function handleEmptyResponse(currencySymbol) {
            jQuery("#pre-orders-list").html(0);
            jQuery("#pre-total-sales").html(`${currencySymbol}0.00`);
            jQuery("#pre-total-cost").html(`${currencySymbol}0.00`);
            jQuery("#pre-average-order-profit").html(`${currencySymbol}0.00`);
            jQuery("#pre-profit").html(`${currencySymbol}0.00`);
            jQuery("#pre-average-daily-profit").html(`${currencySymbol}0.00`);
            updatePercentageChanges({
                order_percentage_change: 0,
                total_sales_percentage_change: 0,
                total_cost_percentage_change: 0,
                average_daily_profit_percentage_change: 0,
                average_order_profit_percentage_change: 0,
                total_profit_percentage_change: 0
            });
        }

        compareData();
        $('#date-range-select, #previous-date-range-select').on('change', compareData);
    });
})(jQuery);
