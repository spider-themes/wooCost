
document.addEventListener('DOMContentLoaded', function () {
    let costRowCount = 1; // Start from 1 because the first row is already present

    document.getElementById('add-cost-btn').addEventListener('click', function () {
        if (costRowCount >= 10) {
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


        // Create the account selection field
        const accountCell = document.createElement('td');
        const accountSelect = document.createElement('select');

        // Set attributes for the select element
        accountSelect.setAttribute('name', 'account');
        accountSelect.setAttribute('id', 'account');
        accountSelect.classList.add('account-selection');

        // Create and append the options
        const option1 = document.createElement('option');
        option1.value = '';
        option1.text = 'Choose Account Type';
        option1.selected = true;

        const option2 = document.createElement('option');
        option2.value = 'cash';
        option2.text = 'Cash';

        const option3 = document.createElement('option');
        option3.value = 'bank-account';
        option3.text = 'Bank Accounts';

        const option4 = document.createElement('option');
        option4.value = 'card';
        option4.text = 'Card';

        // Append options to the select element
        accountSelect.appendChild(option1);
        accountSelect.appendChild(option2);
        accountSelect.appendChild(option3);
        accountSelect.appendChild(option4);
        accountCell.appendChild(accountSelect);

        // Create the notes field
        const notesCell = document.createElement('td');
        const notesTextarea = document.createElement('textarea');

        // Set attributes for the textarea element
        notesTextarea.setAttribute('id', 'notes');
        notesTextarea.setAttribute('name', 'notes');
        notesTextarea.classList.add('notes-area');
        notesCell.appendChild(notesTextarea);



        // Create the memo file input field
        const memoCell = document.createElement('td');
        const memoInput = document.createElement('input');

        // Set attributes for the file input element
        memoInput.setAttribute('type', 'file');
        memoInput.setAttribute('id', 'memo');
        memoInput.setAttribute('name', 'memo');
        memoInput.classList.add('memo-input');
        memoCell.appendChild(memoInput);



        // Create the category input field
        /*const catCell = document.createElement('td');
        const catInput = document.createElement('input');

        // Set attributes for the file input element
        catInput.setAttribute('type', 'submit');
        catInput.setAttribute('id', 'category');
        catInput.setAttribute('value', 'Add Category');
        catInput.setAttribute('name', 'category');
        catInput.classList.add('category','button-primary');
        catCell.appendChild(catInput);*/


        // Create the date field
        const dateCell = document.createElement('td');
        const dateInput = document.createElement('input');
        dateInput.setAttribute('type', 'date');
        dateInput.setAttribute('name', 'date');
        dateInput.classList.add('input-date');
        dateCell.appendChild(dateInput);



        // Create the submit button
        const submitCell = document.createElement('td');
        const submitButton = document.createElement('input');

        // Set attributes for the submit button
        submitButton.setAttribute('type', 'submit');
        submitButton.setAttribute('value', 'Edit');
        submitButton.classList.add('button-link');
        submitCell.appendChild(submitButton);


        // Append the new cells to the row
        newRow.appendChild(costTypeCell);
        newRow.appendChild(costCell);
        newRow.appendChild(accountCell);
        newRow.appendChild(notesCell);
        newRow.appendChild(memoCell);
        // newRow.appendChild(catCell);
        newRow.appendChild(dateCell);
        newRow.appendChild(submitCell);

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
        console.log('Form submitted!');
    });
});

//  restriction for file types selection
document.addEventListener('DOMContentLoaded', () => {
    const fileInput = document.getElementById('memo');
    const warningMessage = document.getElementById('file-warning');

    fileInput.addEventListener('change', function () {
        // Get the selected file's extension
        const filePath = fileInput.value;
        const allowedExtensions = /(\.pdf|\.doc|\.docx|\.txt|\.ppt)$/i;

        if (!allowedExtensions.exec(filePath)) {
            // If the file extension is not valid, clear the file input and show the warning
            fileInput.value = '';
            warningMessage.style.display = 'block';
        } else {
            // Hide the warning message if the file is valid
            warningMessage.style.display = 'none';
        }
    });
});


// Added the leaving confirmation message without save after edit the page
document.addEventListener('DOMContentLoaded', () => {
    // Track if any changes have been made
    let isFormEdited = false;

    // Get references to the form and its elements
    const form = document.getElementById('costForm');
    const inputs = form.querySelectorAll('input, textarea, select');
    const addCostButton = document.getElementById('add-cost-btn');

    // Add event listeners to detect changes in form inputs
    inputs.forEach((element) => {
        element.addEventListener('input', () => {
            isFormEdited = true;
        });
    });

    // Add event listener to detect when the "Add Cost" button is clicked
    addCostButton.addEventListener('click', () => {
        isFormEdited = true;
    });

    // Listen for the beforeunload event
    window.addEventListener('beforeunload', (event) => {
        // Only show the warning if changes were made
        if (isFormEdited) {
            event.preventDefault(); // Required for some browsers
            event.returnValue = ''; // Standard way to trigger a warning dialog
        }
    });

    // Optionally reset the isFormEdited flag when the form is submitted
    form.addEventListener('submit', () => {
        isFormEdited = false;
    });
});

/*
document.getElementById("category").addEventListener("click", function(event) {
    event.preventDefault(); // Prevents form submission if it's within a form
    var inputContainer = document.getElementById("inputContainer");
    inputContainer.style.display = "block"; // Show the input field container
});
*/
