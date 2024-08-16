document.getElementById('addCostNameBtn').addEventListener('click', function () {
    Swal.fire({
        title: 'Add Cost Details',
        html: `
                    <table >
                        <tr>
                            <th>Cost Name</th>
                            <th>Cost</th>
                        </tr>
                        <tr>
                            <td><input type="text" id="costName1" class="swal2-input" placeholder="Cost Name 1"></td>
                            <td><input type="number" id="cost1" class="swal2-input" placeholder="Cost 1"></td>
                        </tr>
                        <tr>
                            <td><input type="text" id="costName2" class="swal2-input" placeholder="Cost Name 2"></td>
                            <td><input type="number" id="cost2" class="swal2-input" placeholder="Cost 2"></td>
                        </tr>
                        <tr>
                            <td><input type="text" id="costName3" class="swal2-input" placeholder="Cost Name 3"></td>
                            <td><input type="number" id="cost3" class="swal2-input" placeholder="Cost 3"></td>
                        </tr>
                    </table>
                `,
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: 'Save',
        cancelButtonText: 'Cancel',
            preConfirm: () => {
            const costName1 = document.getElementById('costName1').value;
            const cost1 = document.getElementById('cost1').value;
            const costName2 = document.getElementById('costName2').value;
            const cost2 = document.getElementById('cost2').value;
            const costName3 = document.getElementById('costName3').value;
            const cost3 = document.getElementById('cost3').value;

            if (!costName1 || !cost1 || !costName2 || !cost2 || !costName3 || !cost3) {
                Swal.showValidationMessage('Please fill in all fields');
            }

            return { costName1, cost1, costName2, cost2, costName3, cost3 };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            console.log('Saved Data:', result.value);
            // Here you can handle the form submission, such as sending data via an AJAX request
        }
    });
});