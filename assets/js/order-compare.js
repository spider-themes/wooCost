(function ($) {
    'use strict';

    $(document).ready(function () {
        // Initialize Nice Select
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
    });
})(jQuery);