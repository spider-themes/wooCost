;(function ($) {
    $('#smartwizard').smartWizard();

})(jQuery);

// Wait until the DOM is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Get the radio buttons and the product search div
    const specificProductsRadio = document.getElementById('specific-products');
    const allProductsRadio = document.getElementById('all-products');
    const productSearchDiv = document.querySelector('.product-search');

    // Function to toggle the visibility of the product search div
    function toggleProductSearch() {
        if (specificProductsRadio.checked) {
            productSearchDiv.style.display = 'block';
        } else {
            productSearchDiv.style.display = 'none';
        }
    }
    // Attach event listeners to the radio buttons
    specificProductsRadio.addEventListener('change', toggleProductSearch);
    allProductsRadio.addEventListener('change', toggleProductSearch);

    // Call the function on page load to set the correct initial state
    toggleProductSearch();
});


