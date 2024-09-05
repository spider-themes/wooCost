// Add Rule Input
const addRuleBtn = document.getElementById('addRuleBtn');
const ruleInput = document.getElementById('ruleInput');

addRuleBtn.addEventListener('click', function () {
    ruleInput.style.display = 'block'; // Show the input field
});

// Specific product rule field
document.addEventListener('DOMContentLoaded', function () {
    const allProductsRadio = document.getElementById('all-products');
    const specificProductsRadio = document.getElementById('specific-products');

    const specificProductsField = document.querySelector('.specific-products');

    // Function to manage visibility based on the selected radio option
    function updateProductFieldVisibility() {
        if (allProductsRadio.checked) {
            specificProductsField.style.display = 'none';
        } else if (specificProductsRadio.checked) {
            specificProductsField.style.display = 'flex';
        }
    }

    // Add event listeners to radio buttons
    allProductsRadio.addEventListener('change', updateProductFieldVisibility);
    specificProductsRadio.addEventListener('change', updateProductFieldVisibility);

    // Initialize the correct field visibility on page load
    updateProductFieldVisibility();
});

// Discount Rules Field duplicate
document.addEventListener('DOMContentLoaded', function () {
    const addDiscountRuleBtn = document.getElementById('addDiscountRule');
    const rulesContainer = document.getElementById('rulesContainer');

    if (addDiscountRuleBtn && rulesContainer) {
        addDiscountRuleBtn.addEventListener('click', function (e) {
            e.preventDefault(); // Prevent default anchor behavior

            // Clone the existing discount rule div
            const originalDiv = document.querySelector('.discount-rules-inputs');
            const clonedDiv = originalDiv.cloneNode(true);

            // Optionally, clear the input fields in the cloned div
            clonedDiv.querySelectorAll('input').forEach(input => input.value = '');

            // Append the cloned div to the rules container
            rulesContainer.appendChild(clonedDiv);
        });
    } else {
        console.error("Element not found: Make sure #rulesContainer and #addDiscountRule exist in your HTML.");
    }
});


// User role
document.addEventListener('DOMContentLoaded', function () {
    const allUsersRadio = document.getElementById('all-users');
    const specificUserRadio = document.getElementById('specific-user');
    const userRolesRadio = document.getElementById('user-roles');

    const specificUserField = document.querySelector('.specific-user');
    const userRolesField = document.querySelector('.user-roles');

    // Function to manage visibility based on selected radio option
    function updateFieldVisibility() {
        if (allUsersRadio.checked) {
            specificUserField.style.display = 'none';
            userRolesField.style.display = 'none';
        } else if (specificUserRadio.checked) {
            specificUserField.style.display = 'flex';
            userRolesField.style.display = 'none';
        } else if (userRolesRadio.checked) {
            specificUserField.style.display = 'none';
            userRolesField.style.display = 'flex';
        }
    }

    // Add event listeners to radio buttons
    allUsersRadio.addEventListener('change', updateFieldVisibility);
    specificUserRadio.addEventListener('change', updateFieldVisibility);
    userRolesRadio.addEventListener('change', updateFieldVisibility);

    // Initialize the correct field visibility on page load
    updateFieldVisibility();


// product exclude js

    const toggleSwitch = document.getElementById('exclude-toggle-switch');
    const excludeActiveDiv = document.querySelector('.exclude-active');

    // Initially hide the exclude-active div
    excludeActiveDiv.style.display = 'none';

    // Add event listener to toggle switch
    toggleSwitch.addEventListener('change', function () {
        if (toggleSwitch.checked) {
            excludeActiveDiv.style.display = 'block';
        } else {
            excludeActiveDiv.style.display = 'none';
        }
    });

    // Exclude Products based on selection

    const specificProductsRadio = document.getElementById('exspecific-products');
    const specificProductsCatRadio = document.getElementById('specific-products-cat');
    const specificProductsTagRadio = document.getElementById('specific-products-tag');

    // Corresponding divs for each option
    const specificProductsField = document.querySelector('.exspecific-products');
    const specificProductsCatField = document.querySelector('.specific-products-cat');
    const specificProductsTagField = document.querySelector('.specific-products-tag');

    // Function to manage visibility based on the selected radio option
    function updateExcludeFieldVisibility() {
        // Hide all fields by default
        specificProductsField.style.display = 'none';
        specificProductsCatField.style.display = 'none';
        specificProductsTagField.style.display = 'none';

        // Display the correct field based on the selected option
        if (specificProductsRadio.checked) {
            specificProductsField.style.display = 'flex';
        } else if (specificProductsCatRadio.checked) {
            specificProductsCatField.style.display = 'flex';
        } else if (specificProductsTagRadio.checked) {
            specificProductsTagField.style.display = 'flex';
        }
    }

    // Add event listeners to radio buttons
    specificProductsRadio.addEventListener('change', updateExcludeFieldVisibility);
    specificProductsCatRadio.addEventListener('change', updateExcludeFieldVisibility);
    specificProductsTagRadio.addEventListener('change', updateExcludeFieldVisibility);

    // Initialize the correct field visibility on page load
    updateExcludeFieldVisibility();
});





document.addEventListener('DOMContentLoaded', function() {
    const toggleSwitch = document.getElementById('toggle-switch');
    const activeRuleInput = document.getElementById('active-rule');

    // Function to update the hidden input field based on the checkbox state
    function updateActiveRule() {
        if (toggleSwitch.checked) {
            activeRuleInput.value = '1';
        } else {
            activeRuleInput.value = '0';
        }
    }

    // Update the value on page load based on the current state of the checkbox
    updateActiveRule();

    // Add an event listener to update the value whenever the checkbox state changes
    toggleSwitch.addEventListener('change', updateActiveRule);
});

document.addEventListener('DOMContentLoaded', function() {
    const shopToggleSwitch = document.getElementById('shop-toggle-switch');
    const activeShopInput = document.getElementById('active-shop');

    // Function to update the hidden input field based on the checkbox state
    function updateActiveShop() {
        if (shopToggleSwitch.checked) {
            activeShopInput.value = '1';
        } else {
            activeShopInput.value = '0';
        }
    }

    // Update the value on page load based on the current state of the checkbox
    updateActiveShop();

    // Add an event listener to update the value whenever the checkbox state changes
    shopToggleSwitch.addEventListener('change', updateActiveShop);
});

document.addEventListener('DOMContentLoaded', function() {
    const excludeToggleSwitch = document.getElementById('exclude-toggle-switch');
    const activeExcludeInput = document.getElementById('active-exclude');

    // Function to update the hidden input field based on the checkbox state
    function updateActiveExclude() {
        if (excludeToggleSwitch.checked) {
            activeExcludeInput.value = '1';
        } else {
            activeExcludeInput.value = '0';
        }
    }

    // Update the value on page load based on the current state of the checkbox
    updateActiveExclude();

    // Add an event listener to update the value whenever the checkbox state changes
    excludeToggleSwitch.addEventListener('change', updateActiveExclude);
});


document.getElementById('toggle-switch').addEventListener('change', function() {
    document.getElementById('active-rule').value = this.checked ? 1 : 0;
});