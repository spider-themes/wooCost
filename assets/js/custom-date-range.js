jQuery(document).ready(function ($) {


    const select = $('#date-range-select');

    //
    //
    // function showOpenText() {
    //     console.log( 'hello');
    //     select.find('option').each(function() {
    //     console.log( 'hello');
    //         $(this).text($(this).data('open-text'));
    //     });
    // }
    //
    // function showClosedText() {
    //     select.find('option').each(function() {
    //         $(this).text($(this).data('closed-text'));
    //     });
    // }
    //
    // select.on('mousedown', function() {
    //     showOpenText();
    // });
    //
    // select.on('change', function() {
    //     setTimeout(showClosedText, 100);
    // });
    //
    // showClosedText();




    loadData();

    function loadData() {
        const start_date = null;
        const end_date =null;


        let date_detail = updateDateRangeFields();


        $.ajax({
            url: ajax_params.ajaxurl,
            method: 'POST',
            data: {
                action: 'woocost_get_orders_by_date_range',
                start_date: start_date,
                end_date: end_date,
                date_range: $('#date-range-select').val(),
                prevStartDate: date_detail.startDate,
                prevEndDate: date_detail.endDate,
                comparison: $('#previous-date-range-select').val(),
            },
            success: function (response) {
                // const data = JSON.parse(response);


                if (response.data) {

                    console.log(response.data)
                    $('#orders-list').text(response.data.total_orders);
                    $('#total-sales').html(response.data.total_sales);  // Use html() for price data
                    $('#total-cost').html(response.data.total_cost);    // Use html() for price data
                    $('#average-profit').html(response.data.average_daily_profit); // Use html() for price data
                    $('#average-order-profit').html(response.data.average_order_profit); // Use html() for price data
                    $('#profit').html(response.data.total_profit); // Use html() for price data

                    // Check if 'no-comparison' is selected
                    if ($('#previous-date-range-select').val() == 'no-comparison') {
                        // Clear comparison fields
                        $('#pre-orders-list').text('');
                        $('#pre-total-sales').text('');
                        $('#pre-total-cost').text('');
                        $('#pre-average-daily-profit').text('');
                        $('#pre-average-order-profit').text('');
                        $('#pre-profit').text('');

                        // Clear percentage change badges
                        $('#order-percentage-change ,#total-sales-percentage-change,#total-cost-percentage-change,#average-daily-profit-percentage-change,#average-order-profit-percentage-change,#total-profit-percentage-change').text('').hide('slow');
                    } else {
                        // Set comparison values if the fields have the 'badge' class
                        if ($('#order-percentage-change').hasClass('badge')) {
                            $('#order-percentage-change').text(response.data.order_percentage_change + '%').show('slow');
                            $('#pre-orders-list').text(response.data.comp_total_orders);
                        }
                        if ($('#total-sales-percentage-change').hasClass('badge')) {
                            $('#total-sales-percentage-change').text(response.data.total_sales_percentage_change + '%').show('slow');
                            $('#pre-total-sales').html(response.data.comp_total_sales);
                        }
                        if ($('#total-cost-percentage-change').hasClass('badge')) {
                            $('#total-cost-percentage-change').text(response.data.total_cost_percentage_change + '%').show('slow');
                            $('#pre-total-cost').html(response.data.comp_total_cost);
                        }
                        if ($('#average-daily-profit-percentage-change').hasClass('badge')) {
                            $('#average-daily-profit-percentage-change').text(response.data.average_daily_profit_percentage_change + '%').show('slow');
                            $('#pre-average-daily-profit').html(response.data.comp_average_daily_profit);
                        }
                        if ($('#average-order-profit-percentage-change').hasClass('badge')) {
                            $('#average-order-profit-percentage-change').text(response.data.average_order_profit_percentage_change + '%').show('slow');
                            $('#pre-average-order-profit').html(response.data.comp_average_order_profit);
                        }
                        if ($('#total-profit-percentage-change').hasClass('badge')) {
                            $('#total-profit-percentage-change').text(response.data.total_profit_percentage_change + '%').show('slow');
                            $('#pre-profit').html(response.data.comp_total_profit);
                        }

                    }

                }
            },
            error: function (error) {
                console.log('Error:', error);
            }
        });
    }



    function formatDateMnthDayYr(date) {
        const options = {month: 'long', day: 'numeric', year: 'numeric'};
        return new Date(date).toLocaleDateString('en-US', options);
    }


    $(document).on('change', '#date-range-select', handle_date_change);

    handle_date_change();

    function handle_date_change() {
        const today = new Date();
        let start, end;


        switch ($('#date-range-select').val()) {

            case 'today':
                start = end = formatDate1(today);
                break;
            case 'yesterday':
                start = formatDate1(new Date(today.setDate(today.getDate() - 1)));
                end = formatDate1(new Date());
                break;
            case 'last-7-days':
                start = formatDate1(new Date(today.setDate(today.getDate() - 7)));
                end = formatDate1(new Date());
                break;
            case 'last-14-days':
                start = formatDate1(new Date(today.setDate(today.getDate() - 14)));
                end = formatDate1(new Date());
                break;
            case 'this-month':
                start = formatDate1(new Date(today.getFullYear(), today.getMonth(), 1));
                end = formatDate1(new Date());
                break;
            case 'last-month':
                const lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                start = formatDate1(lastMonth);
                end = formatDate1(new Date(lastMonth.getFullYear(), lastMonth.getMonth() + 1, 0));
                break;
            default:
                // Reset both dates for all other cases
                loadData();
                return;
        }


        loadData();
    }

    // Helper function to format date as yyyy-mm-dd
    function formatDate1(date) {
        return date.toLocaleDateString('en-CA');
    }


    $(document).on('change', '#previous-date-range-select', function () {
        loadData();
    });


    // Function to update date range fields and display selected option
    function updateDateRangeFields() {

        const selectedValue = $('#previous-date-range-select').val();


        let startDate = null, endDate = null;

        // Get today's date.
        const today = new Date();

        const dateRange = $('#date-range-select').val();

        var compPrevMonth, compPrevQuarter, compPrevPeriod;


        switch (dateRange) {
            case 'today':
                compPrevMonth = {
                    startDate: formatDateMnthDayYr(new Date(today.getFullYear(), today.getMonth() + -1, today.getDate())),
                    endDate: formatDateMnthDayYr(new Date(today.getFullYear(), today.getMonth() + -1, today.getDate()))
                }

                compPrevQuarter = {
                    startDate: formatDateMnthDayYr(new Date(today.getFullYear(), today.getMonth() + -3, today.getDate())),
                    endDate: formatDateMnthDayYr(new Date(today.getFullYear(), today.getMonth() + -3, today.getDate()))
                }
                compPrevPeriod = {
                    startDate: formatDateMnthDayYr(new Date(new Date(today).setDate(today.getDate() - 1))),
                    endDate: formatDateMnthDayYr(new Date(new Date(today).setDate(today.getDate() - 1)))
                }
                break;

            case 'yesterday':
                const yesterday = new Date(today);
                yesterday.setDate(today.getDate() - 1);

                compPrevMonth = {
                    startDate: formatDateMnthDayYr(new Date(yesterday.getFullYear(), yesterday.getMonth() + -1, yesterday.getDate())),
                    endDate: formatDateMnthDayYr(new Date(today.getFullYear(), today.getMonth() + -1, today.getDate()))
                }
                compPrevQuarter = {
                    startDate: formatDateMnthDayYr(new Date(yesterday.getFullYear(), yesterday.getMonth() + -3, yesterday.getDate())),
                    endDate: formatDateMnthDayYr(new Date(today.getFullYear(), today.getMonth() + -3, today.getDate()))
                }
                compPrevPeriod = {
                    startDate: formatDateMnthDayYr(new Date(yesterday.getFullYear(), yesterday.getMonth(), yesterday.getDate() - 1)),
                    endDate: formatDateMnthDayYr(yesterday)
                }
                break;

            case 'last-7-days':
                compPrevMonth = {

                    startDate: formatDateMnthDayYr(new Date(today.getFullYear(), today.getMonth() + -1, today.getDate() - 7)),
                    endDate: formatDateMnthDayYr(new Date(today.getFullYear(), today.getMonth() + -1, today.getDate()))
                }
                compPrevQuarter = {
                    startDate: formatDateMnthDayYr(new Date(today.getFullYear(), today.getMonth() + -3, today.getDate() - 7)),
                    endDate: formatDateMnthDayYr(new Date(today.getFullYear(), today.getMonth() + -3, today.getDate()))
                }
                compPrevPeriod = {
                    startDate: formatDateMnthDayYr(new Date(today.getFullYear(), today.getMonth(), today.getDate() - 14)),
                    endDate: formatDateMnthDayYr(new Date(today.getFullYear(), today.getMonth(), today.getDate() - 7))
                }
                break;

            case 'last-14-days':
                compPrevMonth = {
                    startDate: formatDateMnthDayYr(new Date(today.getFullYear(), today.getMonth() + -1, today.getDate() - 14)),
                    endDate: formatDateMnthDayYr(new Date(today.getFullYear(), today.getMonth() + -1, today.getDate()))
                }
                compPrevQuarter = {
                    startDate: formatDateMnthDayYr(new Date(today.getFullYear(), today.getMonth() + -3, today.getDate() - 14)),
                    endDate: formatDateMnthDayYr(new Date(today.getFullYear(), today.getMonth() + -3, today.getDate()))
                }
                compPrevPeriod = {
                    startDate: formatDateMnthDayYr(new Date(today.getFullYear(), today.getMonth(), today.getDate() - 28)),
                    endDate: formatDateMnthDayYr(new Date(today.getFullYear(), today.getMonth(), today.getDate() - 14))
                }
                break;

            case 'this-month':
                compPrevMonth = {
                    startDate: formatDateMnthDayYr(new Date(today.getFullYear(), today.getMonth() + -1, 1)),
                    endDate: formatDateMnthDayYr(new Date(today.getFullYear(), today.getMonth() + -1 + 1, today.getDate()))
                }
                compPrevQuarter = {
                    startDate: formatDateMnthDayYr(new Date(today.getFullYear(), today.getMonth() + -3, 1)),
                    endDate: formatDateMnthDayYr(new Date(today.getFullYear(), today.getMonth() + -3 + 1, today.getDate()))
                }
                compPrevPeriod = {
                    startDate: formatDateMnthDayYr(new Date(today.getFullYear(), today.getMonth() - 1, 1)),
                    endDate: formatDateMnthDayYr(new Date(today.getFullYear(), today.getMonth() - 1, today.getDate()))
                }
                break;

            case 'last-month':
                compPrevMonth = {
                    startDate: formatDateMnthDayYr(new Date(today.getFullYear(), today.getMonth() + -1 - 1, 1)),
                    endDate: formatDateMnthDayYr(new Date(today.getFullYear(), today.getMonth() + -1, 0))
                }
                compPrevQuarter = {
                    startDate: formatDateMnthDayYr(new Date(today.getFullYear(), today.getMonth() + -3 - 1, 1)),
                    endDate: formatDateMnthDayYr(new Date(today.getFullYear(), today.getMonth() + -3, 0))
                }
                compPrevPeriod = {
                    startDate: formatDateMnthDayYr(new Date(today.getFullYear(), today.getMonth() - 2, 1)),
                    endDate: formatDateMnthDayYr(new Date(today.getFullYear(), today.getMonth() - 2, new Date(today.getFullYear(), today.getMonth(), 0).getDate()))
                }
                break;

        }

        $('#previous-date-range-select').children().map(function (e) {
            var attrValue = $(this).attr("value");
            if (attrValue === 'previous-month' && compPrevMonth) {
                $(this).attr('data-open-text', (compPrevMonth.startDate + ' - ' + compPrevMonth.endDate))
            } else if (attrValue === 'previous-period' && compPrevPeriod) {
                $(this).attr('data-open-text', (compPrevPeriod.startDate + ' - ' + compPrevPeriod.endDate))
            } else if (attrValue === 'previous-quarter' && compPrevQuarter) {
                $(this).attr('data-open-text', (compPrevQuarter.startDate + ' - ' + compPrevQuarter.endDate))
            }
        });

        $('.previous-date-range-select .list').children().map(function (e) {
            var attrValue = $(this).attr("data-value");
            if (attrValue === 'previous-month' && compPrevMonth) {
                $(this).find('.main-label').text((compPrevMonth.startDate + ' - ' + compPrevMonth.endDate))
            } else if (attrValue === 'previous-period' && compPrevPeriod) {
                $(this).find('.main-label').text((compPrevPeriod.startDate + ' - ' + compPrevPeriod.endDate))
            } else if (attrValue === 'previous-quarter' && compPrevQuarter) {
                $(this).find('.main-label').text((compPrevQuarter.startDate + ' - ' + compPrevQuarter.endDate))
            }

        })

        if ($('#previous-date-range-select').length) {
            $('#previous-date-range-select').niceSelect();
        }

        switch ($('#previous-date-range-select').val()) {

            case 'previous-month':
                startDate = new Date(today.getFullYear(), today.getMonth() - 1, 1); // First day of previous month.
                endDate = new Date(today.getFullYear(), today.getMonth(), 0); // Last day of previous month.
                break;

            case 'previous-period':
                const daysInCurrentMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0).getDate();
                startDate = new Date(today.getFullYear(), today.getMonth(), today.getDate() - daysInCurrentMonth); // Start of previous period.
                endDate = today; // Current date for end of the period.
                break;

            case 'previous-quarter':
                const currentQuarter = Math.floor((today.getMonth() + 3) / 3);
                const startMonth = (currentQuarter - 2) * 3; // Start month of the previous quarter.
                startDate = new Date(today.getFullYear(), startMonth - 3, 1); // First day of previous quarter.
                endDate = new Date(today.getFullYear(), startMonth, 0); // Last day of previous quarter.
                break;

            default:
                // Reset dates if "No comparison" or invalid option is selected.
                startDate = null;
                endDate = null;
                break;
        }

        return {
            'startDate': startDate,
            'endDate': endDate,
        };
    }


    $(document).ready(function () {
        $('#date-range-select, #previous-date-range-select').change(function () {


            const today = new Date();
            let startDate, endDate;

            const dateRange = $('#date-range-select').val();
            const comparisonRange = $('#previous-date-range-select').val();

            if (comparisonRange === 'previous-month' || comparisonRange === 'previous-quarter' || comparisonRange === 'previous-period') {


                let targetMonthOffset = comparisonRange === 'previous-month' ? -1 : comparisonRange === 'previous-quarter' ? -3 : 0;

                switch (dateRange) {
                    case 'today':

                        if (comparisonRange === 'previous-period') {
                            const previousDay = new Date(today);
                            previousDay.setDate(today.getDate() - 1);
                            startDate = endDate = previousDay;
                        } else {
                            startDate = endDate = new Date(today.getFullYear(), today.getMonth() + targetMonthOffset, today.getDate());
                        }
                        break;

                    case 'yesterday':
                        const yesterday = new Date(today);
                        yesterday.setDate(today.getDate() - 1);

                        if (comparisonRange === 'previous-period') {
                            startDate = new Date(yesterday.getFullYear(), yesterday.getMonth(), yesterday.getDate() - 1);
                            endDate = yesterday;
                        } else {
                            startDate = new Date(yesterday.getFullYear(), yesterday.getMonth() + targetMonthOffset, yesterday.getDate());
                            endDate = new Date(today.getFullYear(), today.getMonth() + targetMonthOffset, today.getDate());
                        }
                        break;

                    case 'last-7-days':

                        if (comparisonRange === 'previous-period') {
                            startDate = new Date(today.getFullYear(), today.getMonth(), today.getDate() - 14);
                            endDate = new Date(today.getFullYear(), today.getMonth(), today.getDate() - 7);
                        } else {
                            startDate = new Date(today.getFullYear(), today.getMonth() + targetMonthOffset, today.getDate() - 7);
                            endDate = new Date(today.getFullYear(), today.getMonth() + targetMonthOffset, today.getDate());
                        }
                        break;

                    case 'last-14-days':

                        if (comparisonRange === 'previous-period') {
                            startDate = new Date(today.getFullYear(), today.getMonth(), today.getDate() - 28);
                            endDate = new Date(today.getFullYear(), today.getMonth(), today.getDate() - 14);
                        } else {
                            startDate = new Date(today.getFullYear(), today.getMonth() + targetMonthOffset, today.getDate() - 14);
                            endDate = new Date(today.getFullYear(), today.getMonth() + targetMonthOffset, today.getDate());
                        }
                        break;

                    case 'this-month':

                        if (comparisonRange === 'previous-period') {
                            const firstDayOfThisMonth = new Date(today.getFullYear(), today.getMonth(), 1);
                            const daysPassed = today.getDate() - 1;
                            startDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                            endDate = new Date(today.getFullYear(), today.getMonth() - 1, daysPassed + 1);
                        } else {
                            startDate = new Date(today.getFullYear(), today.getMonth() + targetMonthOffset, 1);
                            endDate = new Date(today.getFullYear(), today.getMonth() + targetMonthOffset + 1, today.getDate());
                        }
                        break;

                    case 'last-month':

                        if (comparisonRange === 'previous-period') {
                            const firstDayOfLastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                            const lastDayOfLastMonth = new Date(today.getFullYear(), today.getMonth(), 0);
                            const daysInLastMonth = lastDayOfLastMonth.getDate();
                            startDate = new Date(today.getFullYear(), today.getMonth() - 2, 1);
                            endDate = new Date(today.getFullYear(), today.getMonth() - 2, daysInLastMonth);
                        } else {
                            startDate = new Date(today.getFullYear(), today.getMonth() + targetMonthOffset - 1, 1);
                            endDate = new Date(today.getFullYear(), today.getMonth() + targetMonthOffset, 0);
                        }
                        break;
                }

            }
        });
    });
    if ($('#date-range-select').length) {
        $('#date-range-select').niceSelect();
    }

    //
    // if ($('.nice-select').length) {
    //     $('.nice-select').niceSelect();
    // }

});