jQuery(document).ready(function ($) {
    loadComparisonData();
    $(document).on('change', '#date-range-select , #previous-date-range-select', function (){
        updateDateRangeFields();
        loadComparisonData();
    });


    function loadComparisonData() {
        let date_detail = updateLoadingCredentials();
        $.ajax({
            url: ajax_params.ajaxurl, method: 'POST', data: {
                action: 'woocost_get_orders_by_date_range',
                start_date: date_detail.primaryStartDate,
                end_date: date_detail.primaryEndDate,
                date_range: $('#date-range-select').val(),
                prevStartDate: date_detail.prevStartDate,
                prevEndDate: date_detail.prevEndDate,
                comparison: $('#previous-date-range-select').val(),
            }, success: function (response) {
                if (response.data) {
                    $('#orders-list').text(response.data.total_orders);
                    $('#total-sales').html(response.data.total_sales);  // Use html() for price data
                    $('#total-cost').html(response.data.total_cost);    // Use html() for price data
                    $('#average-profit').html(response.data.average_daily_profit); // Use html() for price data
                    $('#average-order-profit').html(response.data.average_order_profit); // Use html() for price data
                    $('#profit').html(response.data.total_profit); // Use html() for price data

                    // Check if 'no-comparison' is selected
                    if ($('#previous-date-range-select').val() === 'no-comparison') {
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
                            if(response.data.order_percentage_change >= 0){
                                $('#order-percentage-change').removeClass('negative');
                            }else{
                                $('#order-percentage-change').addClass('negative');

                            }
                            $('#order-percentage-change').text(response.data.order_percentage_change + '%').show('slow');
                            $('#pre-orders-list').text(response.data.comp_total_orders);

                        }
                        if ($('#total-sales-percentage-change').hasClass('badge')) {
                            if(response.data.total_sales_percentage_change >= 0){
                                $('#total-sales-percentage-change').removeClass('negative');
                            }else{
                                $('#total-sales-percentage-change').addClass('negative');
                            }
                            $('#total-sales-percentage-change').text(response.data.total_sales_percentage_change + '%').show('slow');
                            $('#pre-total-sales').html(response.data.comp_total_sales);
                        }
                        if ($('#total-cost-percentage-change').hasClass('badge')) {
                            if(response.data.total_cost_percentage_change >= 0){
                                $('#total-cost-percentage-change').removeClass('negative');
                            }else{
                                $('#total-cost-percentage-change').addClass('negative');
                            }
                            $('#total-cost-percentage-change').text(response.data.total_cost_percentage_change + '%').show('slow');
                            $('#pre-total-cost').html(response.data.comp_total_cost);
                        }
                        if ($('#average-daily-profit-percentage-change').hasClass('badge')) {
                            if(response.data.average_daily_profit_percentage_change >= 0){
                                $('#average-daily-profit-percentage-change').removeClass('negative');
                            }else{
                                $('#average-daily-profit-percentage-change').addClass('negative');
                            }
                            $('#average-daily-profit-percentage-change').text(response.data.average_daily_profit_percentage_change + '%').show('slow');
                            $('#pre-average-daily-profit').html(response.data.comp_average_daily_profit);
                        }
                        if ($('#average-order-profit-percentage-change').hasClass('badge')) {
                            if(response.data.average_order_profit_percentage_change >= 0){
                                $('#average-order-profit-percentage-change').removeClass('negative');
                            }else{
                                $('#average-order-profit-percentage-change').addClass('negative');
                            }
                            $('#average-order-profit-percentage-change').text(response.data.average_order_profit_percentage_change + '%').show('slow');
                            $('#pre-average-order-profit').html(response.data.comp_average_order_profit);
                        }
                        if ($('#total-profit-percentage-change').hasClass('badge')) {
                            if(response.data.total_profit_percentage_change >= 0){
                                $('#total-profit-percentage-change').removeClass('negative');
                            }else{
                                $('#total-profit-percentage-change').addClass('negative');
                            }
                            $('#total-profit-percentage-change').text(response.data.total_profit_percentage_change + '%').show('slow');
                            $('#pre-profit').html(response.data.comp_total_profit);
                        }

                    }

                }
            }, error: function (error) {
                console.log('Error:', error);
            }
        });
    }


    function updateLoadingCredentials() {

        const today = new Date();
        let prevStartDate, prevEndDate, primaryStartDate, primaryEndDate;

        const dateRange = $('#date-range-select').val();
        const comparisonRange = $('#previous-date-range-select').val();

        if (comparisonRange === 'previous-month' || comparisonRange === 'previous-quarter' || comparisonRange === 'previous-period') {
            let targetMonthOffset = comparisonRange === 'previous-month' ? -1 : comparisonRange === 'previous-quarter' ? -3 : 0;
            switch (dateRange) {
                case 'today':
                    if (comparisonRange === 'previous-period') {
                        const previousDay = new Date(today);
                        previousDay.setDate(today.getDate() - 1);
                        prevStartDate = prevEndDate = formatDate1(previousDay);
                    } else {
                        prevStartDate = prevEndDate = formatDate1(new Date(today.getFullYear(), today.getMonth() + targetMonthOffset, today.getDate()));
                    }
                    break;

                case 'yesterday':
                    const yesterday = new Date(today);
                    yesterday.setDate(today.getDate() - 1);

                    if (comparisonRange === 'previous-period') {
                        prevStartDate = formatDate1(new Date(yesterday.getFullYear(), yesterday.getMonth(), yesterday.getDate() - 1));
                        prevEndDate = formatDate1(yesterday);
                    } else {
                        prevStartDate = formatDate1(new Date(yesterday.getFullYear(), yesterday.getMonth() + targetMonthOffset, yesterday.getDate()));
                        prevEndDate = formatDate1(new Date(today.getFullYear(), today.getMonth() + targetMonthOffset, today.getDate()));
                    }
                    break;

                case 'last-7-days':

                    if (comparisonRange === 'previous-period') {
                        prevStartDate = formatDate1(new Date(today.getFullYear(), today.getMonth(), today.getDate() - 14));
                        prevEndDate = formatDate1(new Date(today.getFullYear(), today.getMonth(), today.getDate() - 7));
                    } else {
                        prevStartDate = formatDate1(new Date(today.getFullYear(), today.getMonth() + targetMonthOffset, today.getDate() - 7));
                        prevEndDate = formatDate1(new Date(today.getFullYear(), today.getMonth() + targetMonthOffset, today.getDate()));
                    }
                    break;

                case 'last-14-days':

                    if (comparisonRange === 'previous-period') {
                        prevStartDate = formatDate1(new Date(today.getFullYear(), today.getMonth(), today.getDate() - 28));
                        prevEndDate = formatDate1(new Date(today.getFullYear(), today.getMonth(), today.getDate() - 14));
                    } else {
                        prevStartDate = formatDate1(new Date(today.getFullYear(), today.getMonth() + targetMonthOffset, today.getDate() - 14));
                        prevEndDate = formatDate1(new Date(today.getFullYear(), today.getMonth() + targetMonthOffset, today.getDate()));
                    }
                    break;

                case 'this-month':

                    if (comparisonRange === 'previous-period') {
                        const firstDayOfThisMonth = new Date(today.getFullYear(), today.getMonth(), 1);
                        const daysPassed = today.getDate() - 1;
                        prevStartDate = formatDate1(new Date(today.getFullYear(), today.getMonth() - 1, 1));
                        prevEndDate = formatDate1(new Date(today.getFullYear(), today.getMonth() - 1, daysPassed + 1));
                    } else {
                        prevStartDate = formatDate1(new Date(today.getFullYear(), today.getMonth() + targetMonthOffset, 1));
                        prevEndDate = formatDate1(new Date(today.getFullYear(), today.getMonth() + targetMonthOffset + 1, today.getDate()));
                    }
                    break;

                case 'last-month':

                    if (comparisonRange === 'previous-period') {
                        const firstDayOfLastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                        const lastDayOfLastMonth = new Date(today.getFullYear(), today.getMonth(), 0);
                        const daysInLastMonth = lastDayOfLastMonth.getDate();
                        prevStartDate = formatDate1(new Date(today.getFullYear(), today.getMonth() - 2, 1));
                        prevEndDate = formatDate1(new Date(today.getFullYear(), today.getMonth() - 2, daysInLastMonth));
                    } else {
                        prevStartDate = formatDate1(new Date(today.getFullYear(), today.getMonth() + targetMonthOffset - 1, 1));
                        prevEndDate = formatDate1(new Date(today.getFullYear(), today.getMonth() + targetMonthOffset, 0));
                    }
                    break;
            }
        }


        switch (dateRange) {

            case 'today':
                primaryStartDate = primaryEndDate = formatDate1(today);
                break;
            case 'yesterday':
                primaryStartDate = formatDate1(new Date(today.setDate(today.getDate() - 1)));
                primaryEndDate = formatDate1(new Date());
                break;
            case 'last-7-days':
                primaryStartDate = formatDate1(new Date(today.setDate(today.getDate() - 7)));
                primaryEndDate = formatDate1(new Date());
                break;
            case 'last-14-days':
                primaryStartDate = formatDate1(new Date(today.setDate(today.getDate() - 14)));
                primaryEndDate = formatDate1(new Date());
                break;
            case 'this-month':
                primaryStartDate = formatDate1(new Date(today.getFullYear(), today.getMonth(), 1));
                primaryEndDate = formatDate1(new Date());
                break;
            case 'last-month':
                const lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                primaryStartDate = formatDate1(lastMonth);
                primaryEndDate = formatDate1(new Date(lastMonth.getFullYear(), lastMonth.getMonth() + 1, 0));
                break;
        }

        return {
            "primaryStartDate": primaryStartDate,
            "primaryEndDate": primaryEndDate,
            "prevStartDate": prevStartDate,
            "prevEndDate": prevEndDate
        }
    }
    function formatDateMnthDayYr(date) {
        const options = {month: 'long', day: 'numeric', year: 'numeric'};
        return new Date(date).toLocaleDateString('en-US', options);
    }
    // Helper function to format date as yyyy-mm-dd
    function formatDate1(date) {
        return date.toLocaleDateString('en-CA');
    }

    // Function to update date range fields and display selected option
    function updateDateRangeFields() {
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
        if ($('#previous-date-range-select').length) {
            $('#previous-date-range-select').niceSelect();
        }
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

    }


    if ($('#date-range-select').length) {
        $('#date-range-select').niceSelect();
    }

    updateDateRangeFields();

});