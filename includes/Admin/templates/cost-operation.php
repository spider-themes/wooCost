<div class="wrap">
    <form id="costForm" action="" method="post">

        <table id="costTable">
            <thead>
            <tr>
                <th>Cost Type Name</th>
                <th>Cost</th>
                <th>Date</th>
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
                <input type="date" name="date" class="input-date">
            </td>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="1"></td>
                <td><strong>Total Cost: $<span id="total-cost">0</span></strong></td>
                <td></td>
            </tr>
            </tfoot>
        </table>

        <button type="button" id="add-cost-btn">Add Cost</button>
        <button type="submit" id="save-btn" class="button button-primary">Save</button>
    </form>
</div>

