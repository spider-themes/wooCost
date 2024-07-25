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


        // comparison jquery
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
        });

        // Initial check
        var initialSelectedValue = $('#previous-date-range-select').val();
        if (initialSelectedValue === 'custom') {
            $('#custom-date-range').show();
        } else {
            $('#custom-date-range').hide();
        }
        updateDateRangeFields();
        // end comparison js

    });
})(jQuery);


