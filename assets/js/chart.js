;(function ($) {
    'use strict'

    jQuery(document).ready(function($) {
        $('select').niceSelect();

        $('#date-range-select').on('change', function() {
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
        });

        $('#start_date, #end_date').datepicker({
            dateFormat: 'yy-mm-dd'
        });

        $("#custom-date-range-form").submit(function(event) {
            event.preventDefault();
            const startDate = $('#start_date').val();
            const endDate = $('#end_date').val();

            if (!startDate || !endDate) {
                alert('Please select a valid date range.');
                return;
            }

            function loadData() {
                var start_date = $('#start_date').val();
                var end_date = $('#end_date').val();
                var date_range = $('#date-range-select').val();

                $.ajax({
                    url: ajax_params.ajaxurl,
                    method: 'POST',
                    data: {
                        action: 'get_orders_by_date_range',
                        start_date: start_date,
                        end_date: end_date,
                        date_range: date_range
                    },
                    success: function (response) {
                        const data = JSON.parse(response);

                        $('#orders-list').html(data.orders_data.reduce((a, b) => a + b, 0));
                        $('#total-sales').html(data.sales_data.reduce((a, b) => a + b, 0));
                        $('#net-sales').html(data.sales_data.reduce((a, b) => a + b, 0));
                        $('#total-cost').html(data.cost_data.reduce((a, b) => a + b, 0));
                        $('#average-order-value').html(data.sales_data.reduce((a, b) => a + b, 0) / data.orders_data.length);
                        $('#profit').html(data.profit_data.reduce((a, b) => a + b, 0)).attr('class', data.profit_class);
                        $('#average-profit').html(data.profit_data.reduce((a, b) => a + b, 0) / data.orders_data.length);
                        $('#average-order-profit').html(data.profit_data.reduce((a, b) => a + b, 0) / data.orders_data.length);
                        $('#profit-percentage').html(`(<span id="profit-percentage">${data.profit_percentage}</span>)`);

                        // Render the chart
                        renderChart(data.labels, data.orders_data, data.sales_data, data.cost_data, data.profit_data);
                    },
                    error: function (error) {
                        console.log('Error:', error);
                    }
                });
            }

            function renderChart(labels, ordersData, salesData, costData, profitData) {
                var ctx = document.getElementById('profitChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Total Orders',
                                data: ordersData,
                                borderColor: 'blue',
                                fill: false
                            },
                            {
                                label: 'Total Sales',
                                data: salesData,
                                borderColor: 'green',
                                fill: false
                            },
                            {
                                label: 'Total Cost',
                                data: costData,
                                borderColor: 'red',
                                fill: false
                            },
                            {
                                label: 'Total Profit',
                                data: profitData,
                                borderColor: 'purple',
                                fill: false
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Date'
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Amount'
                                }
                            }
                        }
                    }
                });
            }

            $('#start_date').change(loadData);
            $('#end_date').change(loadData);
            // Trigger loadData on filter button click
            $('#filter-button').click(loadData);
            $('#date-range-select').change(loadData);
        });
        $('#date-range-select').val('today').change();
    });
})(jQuery);
