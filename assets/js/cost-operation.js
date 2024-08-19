/*
document.addEventListener('DOMContentLoaded', function () {
    let costRowCount = 1; // Start from 1 because the first row is already present

    document.getElementById('add-cost-btn').addEventListener('click', function () {
       /!* if (costRowCount >= 6) {
            alert('You cannot add more than 4 cost fields for the same product.');
            return;
        }*!/

        // Create a new table row
        const newRow = document.createElement('tr');

        // Create the Cost Type Name input field
        const costTypeCell = document.createElement('td');
        const costTypeInput = document.createElement('input');
        costTypeInput.setAttribute('type', 'text');
        costTypeInput.setAttribute('name', 'cost-type-name');
        costTypeInput.setAttribute('placeholder', 'Enter cost type name');
        costTypeCell.appendChild(costTypeInput);

        // Create the Cost input field
        const costCell = document.createElement('td');
        const costInput = document.createElement('input');
        costInput.setAttribute('type', 'number');
        costInput.setAttribute('name', 'cost');
        costInput.setAttribute('placeholder', 'Enter cost');
        costInput.classList.add('cost-input');
        costCell.appendChild(costInput);

        // Create the date field
        const dateCell = document.createElement('td');
        const dateInput = document.createElement('input');
        dateInput.setAttribute('type', 'date');
        dateInput.setAttribute('name', 'date');
        dateInput.classList.add('input-date');
        dateCell.appendChild(dateInput);

        // Append the new cells to the row
        newRow.appendChild(costTypeCell);
        newRow.appendChild(costCell);
        newRow.appendChild(dateCell);

        // Append the row to the table body
        document.querySelector('#costTable tbody').appendChild(newRow);
        // Increment the row count
        costRowCount++;
    });

    // Form submission logic
    document.getElementById('costForm').addEventListener('submit', function (e) {
        e.preventDefault();
        // Handle form submission logic here
        alert('Form submitted!');
    });

});
*/

document.addEventListener('DOMContentLoaded', function () {
    let costRowCount = 1; // Start from 1 because the first row is already present

    document.getElementById('add-cost-btn').addEventListener('click', function () {
        if (costRowCount >= 4) {
            alert('You cannot add more than 4 cost fields for the same product.');
            return;
        }
        // Create a new table row
        const newRow = document.createElement('tr');

        // Create the Cost Type Name input field
        const costTypeCell = document.createElement('td');
        const costTypeInput = document.createElement('input');
        costTypeInput.setAttribute('type', 'text');
        costTypeInput.setAttribute('name', 'cost-type-name');
        costTypeInput.setAttribute('placeholder', 'Enter cost type name');
        costTypeCell.appendChild(costTypeInput);

        // Create the Cost input field
        const costCell = document.createElement('td');
        const costInput = document.createElement('input');
        costInput.setAttribute('type', 'number');
        costInput.setAttribute('name', 'cost');
        costInput.setAttribute('placeholder', 'Enter cost');
        costInput.classList.add('cost-input');
        costCell.appendChild(costInput);

        // Create the date field
        const dateCell = document.createElement('td');
        const dateInput = document.createElement('input');
        dateInput.setAttribute('type', 'date');
        dateInput.setAttribute('name', 'date');
        dateInput.classList.add('input-date');
        dateCell.appendChild(dateInput);

        // Append the new cells to the row
        newRow.appendChild(costTypeCell);
        newRow.appendChild(costCell);
        newRow.appendChild(dateCell);

        // Append the row to the table body
        document.querySelector('#costTable tbody').appendChild(newRow);

        // Add an event listener to update the total cost whenever the cost input changes
        costInput.addEventListener('input', updateTotalCost);

        // Increment the row count
        costRowCount++;
    });

    // Update total cost function
    function updateTotalCost() {
        let totalCost = 0;

        // Sum all cost input values
        document.querySelectorAll('.cost-input').forEach(function (input) {
            const costValue = parseFloat(input.value) || 0; // Ensure we handle empty inputs
            totalCost += costValue;
        });

        // Display the total cost
        document.getElementById('total-cost').textContent = totalCost.toFixed(2);
    }

    // Add an event listener to update the total cost when the existing cost input changes
    document.querySelectorAll('.cost-input').forEach(function (input) {
        input.addEventListener('input', updateTotalCost);
    });

    // Form submission logic
    document.getElementById('costForm').addEventListener('submit', function (e) {
        e.preventDefault();
        // Handle form submission logic here
        alert('Form submitted!');
    });
});
