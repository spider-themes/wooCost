jQuery(document).ready(function ($) {
    const inputBox = $('#product-search-input');
    const suggestionBox = $('#suggestion-box');
    const selectedProductsContainer = $('#selected-products');

    // Display suggestion message when input is focused
    inputBox.on('focus', function () {
        if ($(this).val().length < 3) {
            suggestionBox.html('<small>Please enter 3 or more characters</small>').show();
        }
    });

    // Handle input event to trigger AJAX search
    inputBox.on('input', function () {
        const query = $(this).val();
        if (query.length >= 3) {
            // Fetch products via AJAX
            $.ajax({
                url: product_object.ajax_url, // Replace with your AJAX URL
                method: 'POST',
                data: {
                    action: 'woocost_product_names',
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

    // Handle click event on suggestion items to select a product
    $(document).on('click', '.suggestion-item', function () {
        const productId = $(this).data('id');
        const productName = $(this).data('name');

        // Check if the product is already selected
        if ($(`.product-tag[data-id="${productId}"]`).length === 0) {
            selectedProductsContainer.append(`
                <div class="product-tag" data-id="${productId}">
                    ${productName} <span class="close-btn">&times;</span>
                </div>
            `);
        }

        inputBox.val(''); // Clear input after selection
        suggestionBox.hide(); // Hide suggestion box
    });

    // Handle click event on close button to remove selected product
    $(document).on('click', '.close-btn', function () {
        $(this).parent('.product-tag').remove();
    });

    // Hide suggestion box when clicking outside of the input area
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.discount-application-options').length) {
            suggestionBox.hide();
        }
    });
});
