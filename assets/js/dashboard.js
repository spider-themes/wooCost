jQuery(document).ready(function ($) {

    $('#includeOperationCostCheck').change(function() {
        if ($(this).is(':checked')) {
            $('.operation-check-data-parent .operation-check-false').hide();
            $('.operation-check-data-parent .operation-check-true').show();
        } else {
            $('.operation-check-data-parent .operation-check-false').show();
            $('.operation-check-data-parent .operation-check-true').hide();
        }
    });

    if ($('#includeOperationCostCheck').is(':checked')) {
        $('.operation-check-data-parent .operation-check-false').hide();
        $('.operation-check-data-parent .operation-check-true').show();
    } else {
        $('.operation-check-data-parent .operation-check-false').show();
        $('.operation-check-data-parent .operation-check-true').hide();
    }

});