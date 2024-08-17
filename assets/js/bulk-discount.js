// Add Rule Input
const addRuleBtn = document.getElementById('addRuleBtn');
const ruleInput = document.getElementById('ruleInput');

addRuleBtn.addEventListener('click', function () {
    ruleInput.style.display = 'block'; // Show the input field
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