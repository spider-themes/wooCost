<div class="wrap">
    <form id="costForm" action="" method="post">

        <table id="costTable">
            <thead>
            <tr>
                <th>Cost Type Name</th>
                <th>Cost</th>
                <th>Account</th>
                <th>Notes</th>
                <th>Cost Memo</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <!-- Cost rows will be dynamically added here -->
            <td>
                <input type="text" name="cost-type-name" placeholder="Enter cost type name">
            </td>
            <td>
                <input type="number" name="cost" placeholder="Enter cost" class="cost-input">
                <p></p>
            </td>
            <td>
                <select name="account" id="account" class="account-selection">
                    <option value="" selected>Choose Account Type</option>
                    <option value="cash">Cash</option>
                    <option value="bank-account">Bank Accounts</option>
                    <option value="card">Card</option>
                </select>
            </td>
            <td>
                <textarea  id="notes" name="notes" class="notes-area"></textarea>
            </td>
            <td>
                <input type="file"  id="memo" name="memo" class="memo-input" accept=".pdf,.doc,.docx,.txt,.ppt,.jpeg,.jpg,.png">
            </td>
            <td>
                <input type="date" name="date" class="input-date">
            </td>
            <td>
                <input type="submit" value="Edit" class="button-link"/>
            </td>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="1"></td>
                <td><strong>Total Cost: $<span id="total-cost">0</span></strong></td>
                <td colspan="5"></td>

            </tr>
            </tfoot>
        </table>
        <div class="cost-btn-group">
            <button type="submit" id="save-btn" class="add-cost-btn">Save</button>
            <button type="button" id="add-cost-btn" class="add-cost-btn">Add Cost</button>
        </div>
    </form>
</div>

