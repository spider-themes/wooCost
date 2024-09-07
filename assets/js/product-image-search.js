jQuery(document).ready(function ($) {
    const inputBox = $('#product-search-input');
    const suggestionBox = $('#suggestion-box');
    const selectedProductsContainer = $('#selected-products');

    inputBox.on('focus', function () {
        if ($(this).val().length < 3) {
            suggestionBox.html('<small>Please enter 3 or more characters</small>').show();
        }
    });

    inputBox.on('input', function () {
        const query = $(this).val();
        if (query.length >= 3) {
            // Fetch products via AJAX
            $.ajax({
                url: product_object.ajax_url,
                method: 'POST',
                data: {
                    action: 'woocost_get_product_names',
                    query: query
                },
                success: function (response) {
                    suggestionBox.empty().show();
                    response.data.forEach(function (product) {
                        suggestionBox.append(`<div class="suggestion-item" data-id="${product.id}" data-name="${product.name}">${product.name}</div>`);
                    });
                }
            });
        } else {
            suggestionBox.hide();
        }
    });

    $(document).on('click', '.suggestion-item', function () {
        const productId = $(this).data('id');
        const productName = $(this).data('name');

        // Check if product is already selected
        if ($(`.product-tag[data-id="${productId}"]`).length === 0) {
            selectedProductsContainer.append(`
                <div class="product-tag" data-id="${productId}">
                    ${productName} <span class="close-btn">&times;</span>
                </div>
            `);
        }

        inputBox.val('');
        suggestionBox.hide();
    });

    // Handle removing selected product
    $(document).on('click', '.close-btn', function () {
        $(this).parent('.product-tag').remove();
    });

    // Hide suggestions when clicking outside
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.select-product-options').length) {
            suggestionBox.hide();
        }
    });
});


