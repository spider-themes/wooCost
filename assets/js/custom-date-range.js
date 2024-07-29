;(function ($) {
    'use strict';

    jQuery(document).ready(function ($) {
        $('select').niceSelect();

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
                    $('#average-order-value').html(data.average_order_value || 0);
                    $('#profit').html(data.profit).attr('class', data.profit_class || '');
                    $('#average-profit').html(data.average_profit || 0);
                    $('#average-order-profit').html(data.average_order_profit || 0);
                    $('#profit-percentage').html(`(<span id="profit-percentage">${data.profit_percentage || 0}</span>)`);

                    updateChart(data.chart_data || {});
                },
                error: function (error) {
                    console.log('Error:', error);
                }
            });
        }

        function updateChart(chartData) {
            chart.updateSeries([
                {
                    name: 'Order',
                    type: 'column',
                    data: chartData.orders || []
                },
                {
                    name: 'Profit',
                    type: 'area',
                    data: chartData.profits || []
                },
                {
                    name: 'Sales',
                    type: 'line',
                    data: chartData.sales || []
                }
            ]);
            chart.updateOptions({
                labels: chartData.labels || []
            });
        }

        $("#custom-date-range-form").submit(function (event) {
            event.preventDefault();
            loadData();
        });

        $('#start_date').change(loadData);
        $('#end_date').change(loadData);
        $('#filter-button').click(loadData);

        $('#date-range-select').val('today').change();

        $('select.nice-select').niceSelect();

        $('#prev_start_date, #prev_end_date').datepicker({
            dateFormat: 'yy-mm-dd'
        });

        function updateDateRangeFields() {
            var selectedOption = $('#previous-date-range-select option:selected');
            var startDate = selectedOption.attr('data-start-date');
            var endDate = selectedOption.attr('data-end-date');
            var selectedValue = selectedOption.val();

            if (selectedValue === 'custom') {
                $('#prev_start_date').val('');
                $('#prev_end_date').val('');
            } else if (startDate && endDate) {
                $('#prev_start_date').val(startDate);
                $('#prev_end_date').val(endDate);
            } else {
                $('#prev_start_date').val('');
                $('#prev_end_date').val('');
            }
        }

        $('#previous-date-range-select').on('change', function () {
            var selectedValue = $(this).val();
            if (selectedValue === 'custom') {
                $('#custom-date-range').show();
            } else {
                $('#custom-date-range').hide();
            }
            updateDateRangeFields();
            hideSmallTagInSelectedOption();
        });

        function hideSmallTagInSelectedOption() {
            $('.nice-select .current').each(function () {
                var text = $(this).text();
                text = text.replace(/\s*<small>.*<\/small>\s*/i, '');
                $(this).text(text);
            });
        }

        var initialSelectedValue = $('#previous-date-range-select').val();
        if (initialSelectedValue === 'custom') {
            $('#custom-date-range').show();
        } else {
            $('#custom-date-range').hide();
        }
        updateDateRangeFields();
        hideSmallTagInSelectedOption();

        $('#previous-date-range-select').on('change', function () {
            const selectedValue = $(this).val();

            if (selectedValue === 'no-comparison' || selectedValue === 'custom') {
                $('.badge').hide();
                $('[id^=pre-]').hide();
            } else {
                $('.badge').show();
                $('[id^=pre-]').show();
                compareData();
            }
        });


        function compareData() {
            const currentStartDate = $('#start_date').val();
            const currentEndDate = $('#end_date').val();
            const prevStartDate = $('#prev_start_date').val();
            const prevEndDate = $('#prev_end_date').val();

            $.ajax({
                url: ajax_params.ajaxurl,
                method: 'POST',
                data: {
                    action: 'wooprofit_compare_monthly_orders',
                    start_date: currentStartDate,
                    end_date: currentEndDate,
                    previous_start_date: prevStartDate,
                    previous_end_date: prevEndDate
                },
                success: function(response) {
                    console.log('Response:', response); // Log the response to see its content
                    if (typeof response === 'object') {
                        const data = response;
                        const current = data.current;
                        const previous = data.previous;

                        // Calculate percentage difference for total sales
                        const totalSalesCurrent = parseFloat(current.total_sales) || 0;
                        const totalSalesPrevious = parseFloat(previous.total_sales) || 0;
                        const totalSalesPercentageDifference = totalSalesPrevious !== 0 ? ((totalSalesCurrent - totalSalesPrevious) / totalSalesPrevious * 100).toFixed(2) : 'N/A';

                        $('#pre-total-sales').html(`${totalSalesPrevious}`);
                        $('#total-sales-badge').html(`(${totalSalesPercentageDifference}%)`);

                        // Update other data points
                        $('#pre-orders-list').html(`${previous.total_orders}`);
                        $('#pre-total-cost').html(`${previous.total_cost}`);
                        $('#pre-average-order-value').html(`${previous.average_order_value}`);
                        $('#pre-profit').html(`${previous.profit}`);
                        $('#pre-average-profit').html(`${previous.average_profit}`);
                        $('#pre-average-order-profit').html(`${previous.average_order_profit}`);
                    } else {
                        console.error('Invalid JSON response:', response);
                    }
                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        }

        // Event listener for date range selection change
        $('#previous-date-range-select').on('change', function() {
            compareData();
        });

        // Initial call to compare data
        compareData();

        
    });
})(jQuery);


/*
;(function ($) {
    'use strict'

    jQuery(document).ready(function ($) {
        $('select').niceSelect();

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

            // Load data based on the new date range
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

                    if (data.total_orders > 0) {
                        $('#orders-list').html(data.total_orders);
                    } else {
                        $('#orders-list').html(0);
                    }
                    $('#total-sales').html(data.total_sales);
                    $('#net-sales').html(data.net_sales);
                    $('#total-cost').html(data.total_cost);
                    $('#average-order-value').html(data.average_order_value);
                    $('#profit').html(data.profit).attr('class', data.profit_class);
                    $('#average-profit').html(data.average_profit);
                    $('#average-order-profit').html(data.average_order_profit);
                    $('#profit-percentage').html(`(<span id="profit-percentage">${data.profit_percentage}</span>)`);

                    // Update chart data
                    updateChart(data.chart_data);
                },
                error: function (error) {
                    console.log('Error:', error);
                }
            });
        }

        function updateChart(chartData) {
            chart.updateSeries([
                {
                    name: 'Order',
                    type: 'column',
                    data: chartData.orders
                }, {
                    name: 'Profit',
                    type: 'area',
                    data: chartData.profits
                }, {
                    name: 'Sales',
                    type: 'line',
                    data: chartData.sales
                }
            ]);
            chart.updateOptions({
                labels: chartData.labels
            });
        }

        $("#custom-date-range-form").submit(function (event) {
            event.preventDefault();
            loadData();
        });

        $('#start_date').change(loadData);
        $('#end_date').change(loadData);
        $('#filter-button').click(loadData);

        // Load data when the page first loads
        $('#date-range-select').val('today').change();

          // comparison js
            $('select.nice-select').niceSelect();

            // Initialize jQuery UI Datepicker
            $('#prev_start_date, #prev_end_date').datepicker({
                dateFormat: 'yy-mm-dd'
            });

            // Function to update custom date range fields
            function updateDateRangeFields() {
                var selectedOption = $('#previous-date-range-select option:selected');
                var startDate = selectedOption.attr('data-start-date');
                var endDate = selectedOption.attr('data-end-date');
                var selectedValue = selectedOption.val();

                if (selectedValue === 'custom') {
                    // Clear the date fields for custom selection
                    $('#prev_start_date').val('');
                    $('#prev_end_date').val('');
                } else if (startDate && endDate) {
                    $('#prev_start_date').val(startDate);
                    $('#prev_end_date').val(endDate);
                } else {
                    $('#prev_start_date').val('');
                    $('#prev_end_date').val('');
                }
            }

            // Event listener for dropdown change
            $('#previous-date-range-select').on('change', function () {
                var selectedValue = $(this).val();
                if (selectedValue === 'custom') {
                    $('#custom-date-range').show();
                } else {
                    $('#custom-date-range').hide();
                }
                updateDateRangeFields();
                hideSmallTagInSelectedOption();
            });

            // Hide <small> tag in selected option
            function hideSmallTagInSelectedOption() {
                $('.nice-select .current').each(function() {
                    var text = $(this).text();
                    // Remove text within <small> tag
                    text = text.replace(/\s*<small>.*<\/small>\s*!/i, '');
                    $(this).text(text);
                });
            }

            // Initial check
            var initialSelectedValue = $('#previous-date-range-select').val();
            if (initialSelectedValue === 'custom') {
                $('#custom-date-range').show();
            } else {
                $('#custom-date-range').hide();
            }
            updateDateRangeFields();
            hideSmallTagInSelectedOption();

            /!* Comparison data load by selected options *!/
                $('#previous-date-range-select').on('change', function() {
                    const selectedValue = $(this).val();

                    if (selectedValue === 'no-comparison' || selectedValue === 'custom') {
                        $('.badge').hide();
                        $('[id^=pre-]').hide();
                    } else {
                        $('.badge').show();
                        $('[id^=pre-]').show();
                    }
                });

                // Trigger change event on page load to set the initial state
                $('#previous-date-range-select').trigger('change');
            /!* Comparison data load by selected options *!/

    });
})(jQuery);


*/
