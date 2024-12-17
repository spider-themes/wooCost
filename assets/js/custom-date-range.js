

jQuery(document).ready(function ($) {

    
    const select = $('#date-range-select');


    function showOpenText() {
        select.find('option').each(function() {
            $(this).text($(this).data('open-text'));
        });
    }

    function showClosedText() {
        select.find('option').each(function() {
            $(this).text($(this).data('closed-text'));
        });
    }

    select.on('mousedown', function() {
        showOpenText();
    });

    select.on('change', function() {
        setTimeout(showClosedText, 100);
    });

    showClosedText();



    // Event listener for date range selection
    $(document).on('change', '#start_date , #end_date , #prev_start_date , #prev_end_date', loadData);


    loadData();
    function loadData() {
        const start_date = $('#start_date').val();
        const end_date   = $('#end_date').val();


        if (('custom-date' == $('#previous-date-range-select').val()) && (!$('#prev_start_date').val() || !$('#prev_end_date').val())) {

            return;
        }
        if ('custom-date' == $('#date-range-select').val() && (!start_date || !end_date)) {
            return;
        }
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
                        }
                        if ($('#total-sales-percentage-change').hasClass('badge')) {
                            $('#total-sales-percentage-change').text(response.data.total_sales_percentage_change + '%').show('slow');
                        }
                        if ($('#total-cost-percentage-change').hasClass('badge')) {
                            $('#total-cost-percentage-change').text(response.data.total_cost_percentage_change + '%').show('slow');
                        }
                        if ($('#average-daily-profit-percentage-change').hasClass('badge')) {
                            $('#average-daily-profit-percentage-change').text(response.data.average_daily_profit_percentage_change + '%').show('slow');
                        }
                        if ($('#average-order-profit-percentage-change').hasClass('badge')) {
                            $('#average-order-profit-percentage-change').text(response.data.average_order_profit_percentage_change + '%').show('slow');
                        }
                        if ($('#total-profit-percentage-change').hasClass('badge')) {
                            $('#total-profit-percentage-change').text(response.data.total_profit_percentage_change + '%').show('slow');
                        }

                    }

                }
            },
            error: function (error) {
                console.log('Error:', error);
            }
        });
    }


    function formatDate(date) {
        const d = new Date(date);
        const year = d.getFullYear();
        const month = ('0' + (d.getMonth() + 1)).slice(-2); // Ensure two digits for month
        const day = ('0' + d.getDate()).slice(-2); // Ensure two digits for day
        return `${year}-${month}-${day}`;
    }



    $(document).on('change', '#date-range-select', handle_date_change) ;

    handle_date_change();

    function handle_date_change(){
        const today = new Date();
        let start, end;
    
        switch ($('#date-range-select').val()) {
            case 'custom-date':
                break;
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
                $('#start_date').val(null);
                $('#end_date').val(null);
                loadData();
                return;
        }
    
        // Set the start and end dates if a range was selected
        if (start && end) {
            $('#start_date').val(start);
            $('#end_date').val(end);
        }
    
        loadData();
    }
    
    // Helper function to format date as yyyy-mm-dd
    function formatDate1(date) {
        return date.toLocaleDateString('en-CA'); 
    }
    

    $(document).on('change', '#previous-date-range-select', function () {

        // console.log($(this).val());
        switch ($(this).val()) {
            case 'no-comparison':
            case 'previous-month':
            case 'previous-period':
            case 'previous-quarter':


                // $('#prev_start_date').val(null);
                // $('#prev_end_date').val(null);
                // break;

            default:
                break;
        }

        loadData();
    });




    // Function to update date range fields and display selected option
    function updateDateRangeFields() {

        const selectedValue = $('#previous-date-range-select').val();

        // Date input fields.
        const prevStartDateInput = $('#prev_start_date').val();
        const prevEndDateInput = $('#prev_end_date').val();

        let startDate = null, endDate = null;

        // Get today's date.
        const today = new Date();

        const startDate1 = $('#start_date').val();
        const endDate1 = $('#end_date').val();
        const today1 = formatDate1(new Date());
        const yesterday = formatDate1(new Date(new Date().setDate(new Date().getDate() - 1)));
        const last7Days = formatDate1(new Date(new Date().setDate(new Date().getDate() - 7)));
        const last14Days = formatDate1(new Date(new Date().setDate(new Date().getDate() - 14)));
        const firstOfThisMonth = formatDate1(new Date(new Date().getFullYear(), new Date().getMonth(), 1));
        const lastMonthStart = formatDate1(new Date(new Date().getFullYear(), new Date().getMonth() - 1, 1));
        const lastMonthEnd = formatDate1(new Date(new Date().getFullYear(), new Date().getMonth(), 0));

        if (
            (startDate1 === today1 && endDate1 === today1) ||
            (startDate1 === yesterday && endDate1 === today1) ||
            (startDate1 === last7Days && endDate1 === today1) ||
            (startDate1 === last14Days && endDate1 === today1) ||
            (startDate1 === firstOfThisMonth && endDate1 === today1) ||
            (startDate1 === lastMonthStart && endDate1 === lastMonthEnd)
        ) {
            $('#date-range-select option[value="custom-date"]').remove();

        } else {
           if ($('#start_date').val() && $('#end_date').val() && 'custom-date' != $('#date-range-select').val()) {
                if ($('#date-range-select option[value="custom-date"]').length === 0) {
                    $('#date-range-select').append(
                        '<option value="custom-date" data-closed-text="Custom Date" data-open-text="Custom Date">Custom Date</option>'
                    );
                }
                $('#date-range-select').val('custom-date').change(); // Trigger change event if needed.
            }
        }
        


        if (prevStartDateInput && prevEndDateInput && 'custom-date' != selectedValue) {

            $('#previous-date-range-select').val('custom-date').change(); // Trigger change event if needed.
        }
        
        switch ($('#previous-date-range-select').val()) {
            case 'custom-date':
                startDate = prevStartDateInput;
                endDate = prevEndDateInput;
                break;
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

        // Update input fields if dates are set.

        if (startDate && endDate) {
            console.log(startDate);
            const newStartDate =formatDate(startDate)
            const newEndDate =formatDate(endDate)

            // console.log(newStartDate);

            // $('#prev_start_date').val(newStartDate);
            // $('#prev_end_date').val(newEndDate);
            // Update the text after the select element.
            // $('.nice-select .current').text(`${$('#previous-date-range-select').find("option:selected").text()} (From ${formatDate(startDate)} to ${formatDate(endDate)})`);

        } else {
            // Reset input fields and text for "No comparison" option.
            // $('.nice-select .current').text('No comparison');
        }

        return {
            'startDate': startDate,
            'endDate': endDate,
        };
    }


    $(document).ready(function() {
        $('#date-range-select, #previous-date-range-select, #start_date, #end_date').change(function() {

            toggleCustomDateRange();

            const today = new Date();
            let startDate, endDate;
    
            const dateRange = $('#date-range-select').val();
            const comparisonRange = $('#previous-date-range-select').val();
    
            if (comparisonRange === 'previous-month' || comparisonRange === 'previous-quarter' || comparisonRange === 'previous-period') {
                 $('#prev_start_date').val(null);
                    $('#prev_end_date').val(null);
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
                            endDate = new Date(today.getFullYear(), today.getMonth() + targetMonthOffset + 1,today.getDate() );
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
    
                    case 'custom-date':
                        const customStartDate = new Date($('#start_date').val());
                        const customEndDate = new Date($('#end_date').val());
                        
                        if (!isNaN(customStartDate) && !isNaN(customEndDate)) {
                            if (comparisonRange === 'previous-period') {
                                const diffDays = (customEndDate - customStartDate) / (1000 * 60 * 60 * 24) + 1;
                                startDate = new Date(customStartDate);
                                startDate.setDate(startDate.getDate() - diffDays);
                                endDate = new Date(customEndDate);
                                endDate.setDate(endDate.getDate() - diffDays);
                            } else {
                                startDate = new Date(customStartDate.getFullYear(), customStartDate.getMonth() + targetMonthOffset, customStartDate.getDate());
                                endDate = new Date(customEndDate.getFullYear(), customEndDate.getMonth() + targetMonthOffset, customEndDate.getDate());
                            }
                        }
                        break;
                }
    
                if (startDate && endDate) {
                    const formattedStartDate = formatDate(startDate);
                    const formattedEndDate = formatDate(endDate);

                    $('.nice-select1 .current').text(`(From ${formattedStartDate} to ${formattedEndDate})`);

                    // $('#prev_start_date').val(formattedStartDate);
                    // $('#prev_end_date').val(formattedEndDate);
                }

            }
            });
        });
        
        toggleCustomDateRange();
        function toggleCustomDateRange() {
            const selectedValue = $('#previous-date-range-select').val();
            if (selectedValue === 'custom-date') {
                $('#custom-date-range').show(); 
                $('.nice-select1').hide();
            } else {
                $('#custom-date-range').hide();
                $('.nice-select1').show();

            }


            if(selectedValue==='no-comparison'){
                $('.nice-select1').hide();
                $('#custom-date-range').hide(); 


            }
        }
    

});