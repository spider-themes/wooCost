jQuery(document).ready(function ($) {
    // Function to handle visibility of exclusion options based on selected radio button
    function handleVisibility() {
        const selectedValue = $('input[name="exclude-products"]:checked').val();
        // Hide all exclusion options first
        $('.ex-specific-products, .specific-products-tag, .specific-products-cat').hide();
        // Show the selected exclusion option
        $(`.${selectedValue}`).show();

        // Clear all input fields and suggestion boxes
        $('.discount-application-options input[type="text"]').val('');
        $('.suggestion-box').hide().empty();
    }

    // Handle radio button change to show the relevant exclusion options
    $('input[name="exclude-products"]').on('change', function () {
              handleVisibility(); // Call the function on change
    });

    // Trigger change on document ready to set initial visibility correctly
    handleVisibility();

    // Function to handle dynamic searches
    function handleDynamicSearch(inputSelector, actionType, suggestionBoxId, selectedItemsContainerClass) {
        const inputBox = $(inputSelector);
        const suggestionBox = $(`#${suggestionBoxId}`);

        inputBox.on('focus', function () {
            if ($(this).val().length < 3) {
                suggestionBox.html('<small>Please enter 3 or more characters</small>').show();

            }
        });

        inputBox.on('input', function () {
            const query = $(this).val();
            if (query.length >= 3) {
                // Fetch items via AJAX
                $.ajax({
                    url: exclude_object.ajax_url, // Ensure this URL is correct
                    method: 'POST',
                    data: {
                        action: actionType, // Dynamically set action type
                        query: query
                    },
                    success: function (response) {
                        suggestionBox.empty().show();
                        if (response.data && response.data.length === 0) {
                            // Show warning if no matches are found
                            suggestionBox.html('<small>No matches found</small>').show();
                        } else {
                            response.data.forEach(function (item) {
                                suggestionBox.append(`<div class="suggestion-item" data-id="${item.id}" data-name="${item.name}">${item.name}</div>`);
                            });
                        }
                    },
                    error: function () {
                        suggestionBox.html('<small>An error occurred. Please try again.</small>').show();
                    }
                });
            } else {
                suggestionBox.hide();
            }
        });

        // Handle selection from suggestion items
        $(document).on('click', `#${suggestionBoxId} .suggestion-item`, function () {
            const itemId = $(this).data('id');
            const itemName = $(this).data('name');
            const selectedItemsContainer = $(inputSelector).siblings(`.${selectedItemsContainerClass}`); // Reference the specific container

            // Check if item is already selected
            if (selectedItemsContainer.find(`.item-tag[data-id="${itemId}"]`).length === 0) {
                selectedItemsContainer.append(`
                    <div class="item-tag" data-id="${itemId}">
                        ${itemName} <span class="close-btn">&times;</span>
                    </div>
                `);
            }

            inputBox.val(''); // Clear input after selection
            suggestionBox.hide(); // Hide suggestion box
        });

        // Handle removing selected item
        $(document).on('click', '.close-btn', function () {
            $(this).parent('.item-tag').remove();
        });

        // Hide suggestion box when clicking outside
        $(document).on('click', function (e) {
            if (!$(e.target).closest('.discount-application-options').length) {
                suggestionBox.hide();
            }
        });
    }

    // Initialize dynamic search for products, categories, and tags
    handleDynamicSearch('input[name="exclude-specific-product"]', 'woocost_search_product_names', 'suggestion-box-product', 'selected-products');
    handleDynamicSearch('input[name="exclude-specific-categories"]', 'woocost_get_category_names', 'suggestion-box-category', 'selected-categories');
    handleDynamicSearch('input[name="exclude-specific-tag"]', 'woocost_get_tag_names', 'suggestion-box-tag', 'selected-tags');
});

