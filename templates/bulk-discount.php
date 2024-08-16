<div class="wrap">
    <!--   Start Tabs -->
    <div class="bulk-tabs">
        <div class="tabs">
            <div class="tab active" data-tab="settings">Settings</div>
            <div class="tab" data-tab="discounts-rules">Discounts & Percentage Rules</div>
        </div>
        <!-- Tab Content -->
        <div class="tab-content active" id="settings">

            <div class="flex items-center gap-30">
                <h2 class="heading">Discounts & Percentage Rules</h2>
                <a class="add-rule button button-link add-rule-btn"  id="addRuleBtn"> + Add rule</a>
            </div>

            <div class="bulk-container">
                <div class="rules-section">
                    <div class="rule-input" id="ruleInput">
                        <input type="text"  placeholder="Add title">
                    </div>
                </div>
            </div>


            <div class="bulk-container bulk-border">
                <form action="#" method="post">
                    <!--    Switcher -->
                    <div class="switch-container mb-30 mt-30">
                        <label class="switch-label">Active Rule</label>
                        <div class="switch-wrapper">
                            <div class="switch">
                                <input type="checkbox" id="toggle-switch">
                                <label for="toggle-switch" class="slider"></label>
                            </div>
                            <span class="switch-info"><small>Select to enable or disable this discount rule</small></span>
                        </div>
                    </div>

                    <!--Percentage role -->
                    <div class="discount-application-container mb-30">
                        <label class="discount-application-label">Create a percentage rule for the purchase of:</label>
                        <div class="p-relative">
                            <div class="discount-application-options">
                                <div class="radio-group">
                                    <input type="radio" id="all-products" name="products" value="all-products" checked>
                                    <label for="all-products">All products</label>
                                </div>
                                <div class="radio-group">
                                    <input type="radio" id="specific-products" name="products" value="specific-products">
                                    <label for="specific-products">Specific products</label>
                                </div>
                            </div>
                            <span class="application-info"><small>Choose to apply the rule to all users or only specific user roles</small></span>
                        </div>
                    </div>

                    <!--Discount role -->
                    <div class="discount-rules-container mb-30">
                        <div class="discount-rule">
                            <label class="discount-rules-label">Discount Rules</label>
                        </div>
                        <div class="discount-rules-wrapper">
                            <div class="discount-rules-inputs">
                                <div class="input-group">
                                    <label for="from-field">From</label>
                                    <input type="text" id="from-field" placeholder="1" class="from-field">
                                </div>
                                <div class="input-group">
                                    <label for="to-field">To</label>
                                    <input type="text" id="to-field" placeholder="" class="to-field">
                                </div>
                                <div class="input-group">
                                    <label for="apply-field">Apply</label>
                                    <select id="apply-field" class="rule-options">
                                        <option value="percentage">% Percentage</option>
                                        <option value="fixed">Fixed Amount</option>
                                    </select>
                                </div>
                                <div class="input-group">
                                    <input type="text" id="value-field" placeholder="20" class="value-field"> %
                                </div>
                            </div>

                            <div class="rule-button m-block-start-20">
                                <a href="#" class="button button-link" id="addDiscountRule"> + Add Rule </a>
                            </div>
                        </div>

                    </div>

                    <!--  Apply discount to -->
                    <div class="discount-application-container mb-30">
                        <label class="discount-application-label">Apply discount to:</label>
                        <div class="p-relative">
                            <div class="discount-application-options">
                                <div class="radio-group">
                                    <input type="radio" id="all-users" name="discount-application" value="all" checked>
                                    <label for="all-users">All users</label>
                                </div>
                                <div class="radio-group">
                                    <input type="radio" id="specific-user" name="discount-application" value="specific">
                                    <label for="specific-user">Only to a specific user</label>
                                </div>
                                <div class="radio-group">
                                    <input type="radio" id="user-roles" name="discount-application" value="roles">
                                    <label for="user-roles">Only to specific user roles</label>
                                </div>
                            </div>
                            <span class="application-info"><small>Choose to apply the rule to all users or only specific user roles</small></span>
                        </div>
                    </div>

                    <!--    Switcher for product exclude-->
                    <div class="switch-container mb-30">
                        <label class="switch-label">Exclude Products</label>
                        <div class="excluded-products">
                            <div class="switch">
                                <input type="checkbox" id="exclude-toggle-switch">
                                <label for="exclude-toggle-switch" class="slider"></label>
                            </div>
                            <span class="switch-description"><small>Enable if you want to exclude specific products from this rule</small></span>
                        </div>
                    </div>

                    <!--    Show discount in loop-->
                    <div class="switch-container mb-30">
                        <label class="switch-label">Show discount in loop</label>
                        <div class="excluded-products">
                            <div class="switch">
                                <input type="checkbox" id="shop-toggle-switch">
                                <label for="shop-toggle-switch" class="slider"></label>
                            </div>
                            <span class="switch-description"><small>Enable if you want to show the discounted price in the shop page</small></span>
                        </div>
                    </div>

                    <div class="save-button">
                        <button type="submit" class="button button-primary">Save Rules</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="tab-content" id="discounts-rules">
            <div class="flex items-center mb-30 gap-30">
                <h2 class="heading">Discounts & Percentage Rules</h2>
                <a class="add-rule button button-link add-rule-btn"  id="addRuleBtn"> + Add rule</a>
            </div>

            <div class="alignleft actions bulkactions mb-20">
                <label for="bulk-action-selector-top" class="screen-reader-text">Select bulk action</label>
                <select name="action" id="bulk-action-selector-top">
                    <option value="-1">Bulk actions</option>
                    <option value="edit" class="hide-if-no-js">Edit</option>
                    <option value="activate">Activate</option>
                    <option value="deactivate">Deactivate</option>
                    <option value="delete">Delete</option>
                </select>
                <input type="submit" id="doaction" class="button action" value="Apply">
            </div>

            <table class="wp-list-table widefat fixed striped table-view-list posts ui-sortable">
                <thead>
                    <tr>
                        <td id="cb" class="manage-column column-cb check-column">
                            <input id="cb-select" type="checkbox">
                            <label for="cb-select"><span class="screen-reader-text">Select All</span></label>
                        </td>
                        <th scope="col" id="name" class="">Rule Name</th>
                        <th scope="col" id="discount_mode" class="">Discount Mode</th>
                        <th scope="col" id="status" class="">Status</th>
                        <th scope="col" id="priority" class="">Priority</th>
                    </tr>
                </thead>
                <tbody>
                        <tr>
                            <th scope="row" class="check-column">
                                <input id="" type="checkbox" name="" value="">
                                <label for=""><span class="screen-reader-text">Select Ramadan Discount</span></label>
                                <div class="locked-indicator">
                                    <span class="locked-indicator-icon" aria-hidden="true"></span>
                                    <span class="screen-reader-text">“Ramadan Discount” is locked</span>
                                </div>
                            </th>
                            <td>ONe</td>
                            <td>ONe</td>
                            <td>ONe</td>
                            <td>two</td>
                        </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!--   End Tabs -->

</div>


