// Add Rule Input
const addRuleBtn = document.getElementById('addRuleBtn');
const ruleInput = document.getElementById('ruleInput');

var admin_url = bulk_discount.admin_url;
var nonce = bulk_discount.nonce;





// addRuleBtn.addEventListener('click', function () {
//     ruleInput.style.display = 'block'; // Show the input field
// });

// // Specific product rule field
// document.addEventListener('DOMContentLoaded', function () {
//     const allProductsRadio = document.getElementById('all-products');
//     const specificProductsRadio = document.getElementById('specific-products');

//     const specificProductsField = document.querySelector('.specific-products');

//     // Function to manage visibility based on the selected radio option
//     function updateProductFieldVisibility() {
//         if (allProductsRadio.checked) {
//             specificProductsField.style.display = 'none';
//         } else if (specificProductsRadio.checked) {
//             specificProductsField.style.display = 'flex';
//         }
//     }

//     // Add event listeners to radio buttons
//     allProductsRadio.addEventListener('change', updateProductFieldVisibility);
//     specificProductsRadio.addEventListener('change', updateProductFieldVisibility);

//     // Initialize the correct field visibility on page load
//     updateProductFieldVisibility();
// });

// // Discount Rules Field duplicate
// document.addEventListener('DOMContentLoaded', function () {
//     const addDiscountRuleBtn = document.getElementById('addDiscountRule');
//     const rulesContainer = document.getElementById('rulesContainer');

//     if (addDiscountRuleBtn && rulesContainer) {
//         addDiscountRuleBtn.addEventListener('click', function (e) {
//             e.preventDefault(); // Prevent default anchor behavior

//             // Clone the existing discount rule div
//             const originalDiv = document.querySelector('.discount-rules-inputs');
//             const clonedDiv = originalDiv.cloneNode(true);

//             // Optionally, clear the input fields in the cloned div
//             clonedDiv.querySelectorAll('input').forEach(input => input.value = '');

//             // Append the cloned div to the rules container
//             rulesContainer.appendChild(clonedDiv);
//         });
//     } else {
//         console.error("Element not found: Make sure #rulesContainer and #addDiscountRule exist in your HTML.");
//     }
// });


// // User role
// document.addEventListener('DOMContentLoaded', function () {
//     const allUsersRadio = document.getElementById('all-users');
//     const specificUserRadio = document.getElementById('specific-user');
//     const userRolesRadio = document.getElementById('user-roles');

//     const specificUserField = document.querySelector('.specific-user');
//     const userRolesField = document.querySelector('.user-roles');

//     // Function to manage visibility based on selected radio option
//     function updateFieldVisibility() {
//         if (allUsersRadio.checked) {
//             specificUserField.style.display = 'none';
//             userRolesField.style.display = 'none';
//         } else if (specificUserRadio.checked) {
//             specificUserField.style.display = 'flex';
//             userRolesField.style.display = 'none';
//         } else if (userRolesRadio.checked) {
//             specificUserField.style.display = 'none';
//             userRolesField.style.display = 'flex';
//         }
//     }

//     // Add event listeners to radio buttons
//     allUsersRadio.addEventListener('change', updateFieldVisibility);
//     specificUserRadio.addEventListener('change', updateFieldVisibility);
//     userRolesRadio.addEventListener('change', updateFieldVisibility);

//     // Initialize the correct field visibility on page load
//     updateFieldVisibility();


// // product exclude js
//     const toggleSwitch = document.getElementById('exclude-toggle-switch');
//     const excludeActiveDiv = document.querySelector('.exclude-active');

//     // Initially hide the exclude-active div
//     excludeActiveDiv.style.display = 'none';

//     // Add event listener to toggle switch
//     toggleSwitch.addEventListener('change', function () {
//         if (toggleSwitch.checked) {
//             excludeActiveDiv.style.display = 'block';
//         } else {
//             excludeActiveDiv.style.display = 'none';
//         }
//     });

//     // Exclude Products based on selection

//     const specificProductsRadio = document.getElementById('ex-specific-products');
//     const specificProductsCatRadio = document.getElementById('specific-products-cat');
//     const specificProductsTagRadio = document.getElementById('specific-products-tag');

//     // Corresponding divs for each option
//     const specificProductsField = document.querySelector('.ex-specific-products');
//     const specificProductsCatField = document.querySelector('.specific-products-cat');
//     const specificProductsTagField = document.querySelector('.specific-products-tag');

//     // Function to manage visibility based on the selected radio option
//     function updateExcludeFieldVisibility() {
//         // Hide all fields by default
//         specificProductsField.style.display = 'none';
//         specificProductsCatField.style.display = 'none';
//         specificProductsTagField.style.display = 'none';

//         // Display the correct field based on the selected option
//         if (specificProductsRadio.checked) {
//             specificProductsField.style.display = 'flex';
//         } else if (specificProductsCatRadio.checked) {
//             specificProductsCatField.style.display = 'flex';
//         } else if (specificProductsTagRadio.checked) {
//             specificProductsTagField.style.display = 'flex';
//         }
//     }

//     // Add event listeners to radio buttons
//     specificProductsRadio.addEventListener('change', updateExcludeFieldVisibility);
//     specificProductsCatRadio.addEventListener('change', updateExcludeFieldVisibility);
//     specificProductsTagRadio.addEventListener('change', updateExcludeFieldVisibility);

//     // Initialize the correct field visibility on page load
//     updateExcludeFieldVisibility();
// });





// document.addEventListener('DOMContentLoaded', function() {
//     const toggleSwitch = document.getElementById('toggle-switch');
//     const activeRuleInput = document.getElementById('active-rule');

//     // Function to update the hidden input field based on the checkbox state
//     function updateActiveRule() {
//         if (toggleSwitch.checked) {
//             activeRuleInput.value = '1';
//         } else {
//             activeRuleInput.value = '0';
//         }
//     }

//     // Update the value on page load based on the current state of the checkbox
//     updateActiveRule();

//     // Add an event listener to update the value whenever the checkbox state changes
//     toggleSwitch.addEventListener('change', updateActiveRule);
// });

// document.addEventListener('DOMContentLoaded', function() {
//     const shopToggleSwitch = document.getElementById('shop-toggle-switch');
//     const activeShopInput = document.getElementById('active-shop');

//     // Function to update the hidden input field based on the checkbox state
//     function updateActiveShop() {
//         if (shopToggleSwitch.checked) {
//             activeShopInput.value = '1';
//         } else {
//             activeShopInput.value = '0';
//         }
//     }

//     // Update the value on page load based on the current state of the checkbox
//     updateActiveShop();

//     // Add an event listener to update the value whenever the checkbox state changes
//     shopToggleSwitch.addEventListener('change', updateActiveShop);
// });

// document.addEventListener('DOMContentLoaded', function() {
//     const excludeToggleSwitch = document.getElementById('exclude-toggle-switch');
//     const activeExcludeInput = document.getElementById('active-exclude');

//     // Function to update the hidden input field based on the checkbox state
//     function updateActiveExclude() {
//         if (excludeToggleSwitch.checked) {
//             activeExcludeInput.value = '1';
//         } else {
//             activeExcludeInput.value = '0';
//         }
//     }

//     // Update the value on page load based on the current state of the checkbox
//     updateActiveExclude();

//     // Add an event listener to update the value whenever the checkbox state changes
//     excludeToggleSwitch.addEventListener('change', updateActiveExclude);
// });


// document.getElementById('toggle-switch').addEventListener('change', function() {
//     document.getElementById('active-rule').value = this.checked ? 1 : 0;
// });

jQuery(document).ready(function($){

    $('.specific_user_roles').select2();
    $('.specific_users').select2();


    // all_products();

    // $(document).on('click', '.all_products', all_products);

    // exclude_products();

    // $(document).on('click', '.exclude_products', exclude_products);

    // user_role();

    // $(document).on('click', '.user_role', user_role);

    // rule_options();

    // $(document).on('change', '.rule-options', rule_options);

    // $(document).on('click', '.exluclude_products_checkbox', exluclude_products_checkbox)

    jQuery('.live_search').each(function(){

        jQuery(this).select2({
            ajax: {
                url: admin_url, // AJAX URL is predefined in WordPress admin.
                dataType: 'json',
                type: 'POST',
                delay: 20, // Delay in ms while typing when to perform a AJAX search.
                data: function (params) {
                    return {
                        q: params.term, // search query
                        action: 'bulk_discount_live_search', // AJAX action for admin-ajax.php.//aftaxsearchUsers(is function name which isused in adminn file)
                        nonce: nonce, // AJAX nonce for admin-ajax.php.
                        search_type: $(this).data('search_type'),
                    };
                },
                processResults: function (data) {
                    var options = [];
                    if (data) {
                        // data is the array of arrays, and each of them contains ID and the Label of the option.
                        $.each(
                            data, function (index, text) {
                                // do not forget that "index" is just auto incremented value.
                                options.push({ id: text[0], text: text[1] });
                            }
                        );
                    }
                    return {
                        results: options
                    };
                },
                cache: true
            },
            minimumInputLength: 3 // the minimum of symbols to input before perform a search.
        });
    });

    $(document).on('click', '.add_discount_rule', function(e){

        var current_rule_id = $(this).data('current_rule_id');

        e.preventDefault();

        jQuery.ajax(
        {

            url: ajaxurl,

            type: 'POST',

            data: {

                current_rule_id   : current_rule_id,

                nonce             : nonce,

                action            : 'add_discount_rule',

            },

            success: function(data){

                $( '.discount_rules_main_div' ).append( data );
            }
        }
        );
    });

    $(document).on('click', '.delete_dicount_rule', function(e){

        var current_rule_id = $(this).data('current_rule_id');

        var button = $(this);

        e.preventDefault();

        jQuery.ajax(
        {

            url: ajaxurl,

            type: 'POST',

            data: {

                current_rule_id   : current_rule_id,

                nonce             : nonce,

                action            : 'remove_discount_rule',

            },

            success: function(data){

               button.closest('.remove_row').remove();
            }
        }
        );
    });

    $(document).on('click', '.delete_main_dicount_rule', function(e){

        var current_rule_id = $(this).data('current_rule_id');

        var button = $(this);

        e.preventDefault();

        jQuery.ajax(
        {

            url: ajaxurl,

            type: 'POST',

            data: {

                current_rule_id   : current_rule_id,

                nonce             : nonce,

                action            : 'remove_main_discount_rule',

            },

            success: function(data){

               button.closest('.all_discount_rules_div').remove();
            }
        }
        );
    });

    $(document).on('click', '.discount_main_add_rule', function(e){


        e.preventDefault();

        jQuery.ajax(
        {

            url: ajaxurl,

            type: 'POST',

            data: {

                nonce             : nonce,

                action            : 'add_main_discount_rule',

            },

            success: function(data){

                $('.discount_main_add_rule_div').prepend(data);

    
                $('.apply_to_products_div').each(function() {
                    if ($(this).find('input[name^="products"]:checked').val() === 'all_products') {
                        $(this).next('.specific_products_div').hide();
                    }
                });

                $('.user_based_restriction_div').each(function() {
                    var selectedValue = $(this).find('input[name^="user_role"]:checked').val();
            
                    if (selectedValue === 'all_users') {
                        $(this).next('.specific_users_div').hide();
                        $(this).next().next('.specific_user_roles_div').hide();
                    } else if (selectedValue === 'specific_user') {
                        $(this).next('.specific_users_div').show();
                        $(this).next().next('.specific_user_roles_div').hide();
                    } else if (selectedValue === 'specefic_user_role') {
                        $(this).next('.specific_users_div').hide();
                        $(this).next().next('.specific_user_roles_div').show();
                    }
                });

                $('.exclude_products_toggle_div').each(function() {
                    var isChecked = $(this).find('input[type="checkbox"]').is(':checked');
                    
                    if (isChecked) {
                        $(this).next('.exclude_producs_checkbox_div').show();
                    } else {
                        $(this).next('.exclude_producs_checkbox_div').hide();
                    }
                });

                $('.exclude_products_restriction_div').each(function() {
                    var selectedValue = $(this).find('input[name^="exclude_products"]:checked').val();
        
                    if (selectedValue === 'specific_products') {
                        $(this).next('.specific_exclude_products_div').show();
                        $(this).next().next('.specific_exclude_categories_div').hide();
                        $(this).next().next().next('.specific_exclude_tags_div').hide();
                    } else if (selectedValue === 'specific_categories') {
                        $(this).next('.specific_exclude_products_div').hide();
                        $(this).next().next('.specific_exclude_categories_div').show();
                        $(this).next().next().next('.specific_exclude_tags_div').hide();
                    } else if (selectedValue === 'specific_tags') {
                        $(this).next('.specific_exclude_products_div').hide();
                        $(this).next().next('.specific_exclude_categories_div').hide();
                        $(this).next().next().next('.specific_exclude_tags_div').show();
                    }
                });

                $('.specific_user_roles').select2();
                $('.specific_users').select2();

                jQuery('.live_search').each(function(){

                    jQuery(this).select2({
                        ajax: {
                            url: admin_url, // AJAX URL is predefined in WordPress admin.
                            dataType: 'json',
                            type: 'POST',
                            delay: 20, // Delay in ms while typing when to perform a AJAX search.
                            data: function (params) {
                                return {
                                    q: params.term, // search query
                                    action: 'bulk_discount_live_search', // AJAX action for admin-ajax.php.//aftaxsearchUsers(is function name which isused in adminn file)
                                    nonce: nonce, // AJAX nonce for admin-ajax.php.
                                    search_type: $(this).data('search_type'),
                                };
                            },
                            processResults: function (data) {
                                var options = [];
                                if (data) {
                                    // data is the array of arrays, and each of them contains ID and the Label of the option.
                                    $.each(
                                        data, function (index, text) {
                                            // do not forget that "index" is just auto incremented value.
                                            options.push({ id: text[0], text: text[1] });
                                        }
                                    );
                                }
                                return {
                                    results: options
                                };
                            },
                            cache: true
                        },
                        minimumInputLength: 3 // the minimum of symbols to input before perform a search.
                    });
                });
            }
        }
        );

        
    });

   
});

function exluclude_products_checkbox(){

    if (jQuery('.exluclude_products_checkbox').is(':checked')) {

        jQuery('.exclude_producs_checkbox_div').show();

        exclude_products();

    }else{

        jQuery('.exclude_producs_checkbox_div').hide();

        jQuery('.specific_exclude_products_div').hide();
        
        jQuery('.specific_exclude_categories_div').hide();
        
        jQuery('.specific_exclude_tags_div').hide();
    }
}

function exclude_products(){

    var exclude_products = jQuery('.exclude_products:checked').val();

    if ('specific_products' == exclude_products) {
    
         jQuery('.specific_exclude_products_div').show();
         jQuery('.specific_exclude_categories_div').hide();
         jQuery('.specific_exclude_tags_div').hide();
  
    }else if ('specific_categories' == exclude_products) {
    
         jQuery('.specific_exclude_products_div').hide();
         jQuery('.specific_exclude_categories_div').show();
         jQuery('.specific_exclude_tags_div').hide();
    
    }else{
        jQuery('.specific_exclude_products_div').hide();
        jQuery('.specific_exclude_categories_div').hide();
        jQuery('.specific_exclude_tags_div').show();
    }

}

function user_role(){

    var user_role = jQuery('.user_role:checked').val();

    if ('all_users' == user_role) {
    
         jQuery('.specific_users_div').hide();
         jQuery('.specific_user_roles_div').hide();
  
    }else if ('specific_user' == user_role) {
    
         jQuery('.specific_users_div').show();
         jQuery('.specific_user_roles_div').hide();
    
    }else{

        jQuery('.specific_users_div').hide();
        jQuery('.specific_user_roles_div').show();
    }

}

function all_products(){


    var all_products = jQuery('.all_products:checked').val();

    if ('all-products' == all_products) {
         jQuery('.specific_products_div').hide();
    }else{
        jQuery('.specific_products_div').show();
    }

}

function rule_options(){

    var rule_options_value = jQuery('.rule-options').children( "option:selected" ).val();

    if ('fixed' == rule_options_value) {
         jQuery('.percent_symbol').hide();
    }else{
        jQuery('.percent_symbol').show();
    }
}



jQuery(document).ready(function($) {
    $(document).on('change', 'input[name^="products"]', function() {
        var selectedValue = $(this).val();
        var parentDiv = $(this).closest('.apply_to_products_div');

        if (selectedValue === 'all_products') {
            parentDiv.next('.specific_products_div').hide();
        } else if (selectedValue === 'specific_products') {
            parentDiv.next('.specific_products_div').show();
        }
    });

    $('.apply_to_products_div').each(function() {
        if ($(this).find('input[name^="products"]:checked').val() === 'all_products') {
            $(this).next('.specific_products_div').hide();
        }
    });
});


jQuery(document).ready(function($) {

    $(document).on('change', 'input[name^="user_role"]', function() {
        var selectedValue = $(this).val();
        var parentDiv = $(this).closest('.user_based_restriction_div');

        if (selectedValue === 'all_users') {
            parentDiv.next('.specific_users_div').hide();
            parentDiv.next().next('.specific_user_roles_div').hide();
        } else if (selectedValue === 'specific_user') {
            parentDiv.next('.specific_users_div').show();
            parentDiv.next().next('.specific_user_roles_div').hide();
        } else if (selectedValue === 'specefic_user_role') {
            parentDiv.next('.specific_users_div').hide();
            parentDiv.next().next('.specific_user_roles_div').show();
        }
    });


    $('.user_based_restriction_div').each(function() {
        var selectedValue = $(this).find('input[name^="user_role"]:checked').val();

        if (selectedValue === 'all_users') {
            $(this).next('.specific_users_div').hide();
            $(this).next().next('.specific_user_roles_div').hide();
        } else if (selectedValue === 'specific_user') {
            $(this).next('.specific_users_div').show();
            $(this).next().next('.specific_user_roles_div').hide();
        } else if (selectedValue === 'specefic_user_role') {
            $(this).next('.specific_users_div').hide();
            $(this).next().next('.specific_user_roles_div').show();
        }
    });
});



jQuery(document).ready(function($) {
    function handleExcludeDivs() {
        $('.exclude_products_restriction_div').each(function() {
            var selectedValue = $(this).find('input[name^="exclude_products"]:checked').val();

            if (selectedValue === 'specific_products') {
                $(this).next('.specific_exclude_products_div').show();
                $(this).next().next('.specific_exclude_categories_div').hide();
                $(this).next().next().next('.specific_exclude_tags_div').hide();
            } else if (selectedValue === 'specific_categories') {
                $(this).next('.specific_exclude_products_div').hide();
                $(this).next().next('.specific_exclude_categories_div').show();
                $(this).next().next().next('.specific_exclude_tags_div').hide();
            } else if (selectedValue === 'specific_tags') {
                $(this).next('.specific_exclude_products_div').hide();
                $(this).next().next('.specific_exclude_categories_div').hide();
                $(this).next().next().next('.specific_exclude_tags_div').show();
            }
        });
    }

    handleExcludeDivs();

    $(document).on('change', 'input[name^="exclude_products"]', function() {
        handleExcludeDivs();
    })



    function handleExcludeProductsToggle() {
        $('.exclude_products_toggle_div').each(function() {
            var isChecked = $(this).find('input[type="checkbox"]').is(':checked');
            
            if (isChecked) {
                $(this).next('.exclude_producs_checkbox_div').show();
            } else {
                $(this).next('.exclude_producs_checkbox_div').hide();
            }
        });
    }

    handleExcludeProductsToggle();


    $(document).on('change', 'input[type="checkbox"].exluclude_products_checkbox', function() {
        handleExcludeProductsToggle();
        handleExcludeDivs();
    });



        function togglePercentSymbol($select) {
            var $percentSymbol = $select.closest('.bulk_discount_field_set').find('.percent_symbol');
            if ($select.val() === 'fixed') {
                $percentSymbol.html('&nbsp;&nbsp;&nbsp;&nbsp;');
            } else {
                $percentSymbol.html('%'); 
            }
        }
    
        $('.rule-options').each(function() {
            togglePercentSymbol($(this));
        });
    

    
        

        $(document).on('change', '.rule-options', function() {
            togglePercentSymbol($(this));
        });
    

    
});
