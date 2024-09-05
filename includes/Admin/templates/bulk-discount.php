<div class="wrap">
    <!--   Start Tabs -->
    <div class="bulk-tabs">
        <div class="tabs">
            <div class="tab active" data-tab="settings">Settings</div>
        </div>
        <!-- Tab Content -->
        <div class="tab-content active" id="settings">
            <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                <input type="hidden" name="action" value="save_bulk_discounts">
                <input type="hidden" name="woocost_nonce" value="<?php echo wp_create_nonce('bulk_discounts'); ?>">
                <div class="flex items-center gap-30">
                    <h2 class="heading">Discounts & Percentage Rules</h2>
                    <a class="add-rule button button-link add-rule-btn" id="addRuleBtn"> + Add rule</a>
                </div>

                <div class="bulk-container">
                    <div class="rules-section">
                        <div class="rule-input" id="ruleInput">
                            <input type="text" placeholder="Add title">
                        </div>
                    </div>
                </div>

                <div class="bulk-container bulk-border">
                    <!--    Switcher -->
                    <div class="switch-container mb-30 mt-30">
                        <label class="switch-label">Active Rule</label>
                        <div class="switch-wrapper">
                            <div class="switch">
                                <input type="checkbox" id="toggle-switch">
                                <label for="toggle-switch" class="slider"></label>
                                <input type="hidden" id="active-rule" name="active-rule" value="0"  >
                            </div>
                            <span class="switch-info"><small>Select to enable or disable this discount rule</small></span>
                        </div>
                    </div>

                    <!--Percentage role -->
                    <div class="w-100 mb-30">
                        <label class="discount-application-label">Create a profit percentage rule to:</label>
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
                            <span class="application-info"><small>Choose to apply the rule to the specific product</small></span>
                        </div>
                    </div>

                    <!-- Apply percentage to specific-products-->
                    <div class="w-100 mb-30 specific-products">
                        <label class="discount-application-label">Apply rule to:</label>
                        <div class="p-relative w-70">
                            <div class="discount-application-options">
                                <input type="text" name="specific-products" placeholder="Type the Product name">
                            </div>
                            <span class="application-info"><small>Search the product(s) to include in the rule</small></span>
                        </div>
                    </div>

                    <!--Discount role -->
                    <div class="discount-rules-container mb-30">
                        <div class="discount-rule">
                            <label class="discount-rules-label">Discount Rules</label>
                        </div>
                        <div class="discount-rules-wrapper">
                            <div id="rulesContainer">
                                <div class="discount-rules-inputs mb-20">
                                    <div class="input-group">
                                        <label for="from-field">From</label>
                                        <input type="text" id="from-field" name="from-field" placeholder="1" class="from-field">
                                    </div>
                                    <div class="input-group">
                                        <label for="to-field">To</label>
                                        <input type="text" id="to-field" name="to-field" placeholder="" class="to-field">
                                    </div>
                                    <div class="input-group">
                                        <label for="apply-field">Apply</label>
                                        <select id="apply-field" class="rule-options" name="apply-field">
                                            <option value="percentage">% Percentage</option>
                                            <option value="fixed">Fixed Amount</option>
                                        </select>
                                    </div>
                                    <div class="input-group">
                                        <input type="text" id="value-field" name="value-field" placeholder="20" class="value-field"> %
                                    </div>
                                </div>
                            </div>

                            <div class="rule-button m-block-start-20">
                                <a href="#" class="button button-link" id="addDiscountRule"> + Add Rule </a>
                            </div>
                        </div>
                    </div>

                    <!--  Apply discount to -->
                    <div class="w-100 mb-30">
                        <label class="discount-application-label">Apply discount to:</label>
                        <div class="p-relative">
                            <div class="discount-application-options">
                                <div class="radio-group">
                                    <input type="radio" id="all-users" name="user-role" value="all-users" checked>
                                    <label for="all-users">All users</label>
                                </div>
                                <div class="radio-group">
                                    <input type="radio" id="specific-user" name="user-role" value="specific-user">
                                    <label for="specific-user">Only to a specific user</label>
                                </div>
                                <div class="radio-group">
                                    <input type="radio" id="user-roles" name="user-role" value="only-specefic-user">
                                    <label for="user-roles">Only to specific user roles</label>
                                </div>
                            </div>
                            <span class="application-info"><small>Choose to apply the rule to all users or only specific user roles</small></span>
                        </div>
                    </div>
                    <!-- Only to a specific user-->
                    <div class="w-100 mb-30 specific-user">
                        <label class="discount-application-label">Users included</label>
                        <div class="p-relative w-70">
                            <div class="discount-application-options">
                                <input type="text" placeholder="Select User">
                            </div>
                            <span class="application-info"><small>Choose to apply the rule to all users or only specific user roles</small></span>
                        </div>
                    </div>
                    <!-- Only to specific user roles-->
                    <div class="w-100 mb-30 user-roles">
                        <label class="discount-application-label">User roles included</label>
                        <div class="p-relative w-70">
                            <div class="discount-application-options">
                                <input type="text" placeholder="Search the user roles">
                            </div>
                            <span class="application-info"><small>Search the user roles you want to include in this rule</small></span>
                        </div>
                    </div>

                    <!--    Switcher for product exclude-->
                    <div class="switch-container mb-30">
                        <label class="switch-label">Exclude Products from this discount</label>
                        <div class="excluded-products">
                            <div class="switch">
                                <input type="checkbox" id="exclude-toggle-switch">
                                <label for="exclude-toggle-switch" class="slider"></label>
                                <input type="hidden" id="active-exclude" name="active-exclude" value="0" >
                            </div>
                            <span class="switch-description"><small>Enable if you want to exclude specific products from this rule</small></span>
                        </div>
                    </div>

                    <!-- display exclude products if active-->
                    <div class="exclude-active">
                        <!--Exclude Products-->
                        <div class="w-100 mb-30">
                            <label class="discount-application-label">Exclude Products</label>
                            <div class="p-relative">
                                <div class="discount-application-options">
                                    <div class="radio-group">
                                        <input type="radio" id="exspecific-products" name="exclude-products" value="s-product" checked>
                                        <label for="exspecific-products">Specific products</label>
                                    </div>
                                    <div class="radio-group">
                                        <input type="radio" id="specific-products-cat" name="exclude-products" value="s-product-cat">
                                        <label for="specific-products-cat">Specific product categories</label>
                                    </div>
                                    <div class="radio-group">
                                        <input type="radio" id="specific-products-tag" name="exclude-products" value="s-product-tag">
                                        <label for="specific-products-tag">Specific product tags</label>
                                    </div>
                                </div>
                                <span class="application-info"><small>Choose if you want to exclude some specific products or categories/tags from this rule</small></span>
                            </div>
                        </div>

                        <!-- Exclude products list-->
                        <div class="w-100 mb-30 exspecific-products">
                            <label class="discount-application-label">Choose which product(s) to exclude </label>
                            <div class="p-relative w-70">
                                <div class="discount-application-options">
                                    <input type="text" name="exclude-specific-product" placeholder="Type the Product name">
                                </div>
                                <span class="application-info"><small>Search the product(s) to exclude</small></span>
                            </div>
                        </div>
                        <div class="w-100 mb-30 specific-products-cat">
                            <label class="discount-application-label">Choose the product categories to exclude </label>
                            <div class="p-relative w-70">
                                <div class="discount-application-options">
                                    <input type="text" name="exclude-specific-categories" placeholder="Search for a category">
                                </div>
                                <span class="application-info"><small>Search the product categories to exclude</small></span>
                            </div>
                        </div>
                        <div class="w-100 mb-30 specific-products-tag">
                            <label class="discount-application-label">Choose which product tags to exclude</label>
                            <div class="p-relative w-70">
                                <div class="discount-application-options">
                                    <input type="text" name="exclude-specific-tag" placeholder="Search for a tag">
                                </div>
                                <span class="application-info"><small>Search the product tags to exclude</small></span>
                            </div>
                        </div>
                    </div>
                    <!-- end display exclude products if active-->

                    <!--    Show discount in loop-->
                    <div class="switch-container mb-30">
                        <label class="switch-label">Show discount in loop</label>
                        <div class="excluded-products">
                            <div class="switch">
                                <input type="checkbox" id="shop-toggle-switch">
                                <label for="shop-toggle-switch" class="slider"></label>
                                <input type="hidden" id="active-shop" name="active-shop" value="0">
                            </div>
                            <span class="switch-description"><small>Enable if you want to show the discounted price in the shop page</small></span>
                        </div>
                    </div>

                    <div class="save-button">
                        <button type="submit" class="button button-primary">Save Rules</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--   End Tabs -->

</div>


