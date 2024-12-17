<?php
$bulk_discount_rules = get_posts(
    array(
        'post_type' => 'bulk_discount',
        'post_status' => 'publish',
        'numberposts' => -1,
        'orderby' => 'date',  
        'order' => 'DESC',    
        'fields' => 'ids'
    )
);
?>

<div class="wrap">

    <div class="bulk-tabs">
        <div class="tabs">
            <div class="tab active" data-tab="settings">Settings</div>
        </div>

        <div class="tab-content active" id="settings">
            <div class="flex items-center gap-30">
                <h2 class="heading">Discounts & Percentage Rules</h2>
                <a class="button discount_main_add_rule" id="addRuleBtn" class="discount_main_add_rule"> + Add rule</a>
            </div>

            <div class="discount_main_add_rule_div">

                <?php

                foreach ($bulk_discount_rules as $rule_id ) {

                    
                    $bulk_discount_ativate_rule = get_post_meta( $rule_id, 'bulk_discount_ativate_rule', true );

                    $bulk_discount_rule_title = get_the_title( $rule_id );

                    $products = get_post_meta( $rule_id, 'products', true );

                    $specific_products = (array) get_post_meta( $rule_id, 'specific_products', true );

                    $exclude_products = get_post_meta( $rule_id, 'exclude_products', true );

                    $exclude_specific_products = (array) get_post_meta( $rule_id, 'exclude_specific_products', true );

                    $exclude_specific_categories = (array) get_post_meta( $rule_id, 'exclude_specific_categories', true );
                    
                    $exclude_specific_tags = (array) get_post_meta( $rule_id, 'exclude_specific_tags', true );

                    $user_role = get_post_meta( $rule_id, 'user_role', true );

                    $exluclude_products_checkbox = get_post_meta( $rule_id, 'exluclude_products_checkbox', true );

                    $show_discount_in_loop = get_post_meta( $rule_id, 'show_discount_in_loop', true );

                    $specific_users = (array) get_post_meta( $rule_id, 'specific_users', true );

                    $specific_user_roles = (array) get_post_meta( $rule_id, 'specific_user_roles', true );

                    ?>

                    <div class="all_discount_rules_div">

                        <input type="text" placeholder="Add title" name="bulk_discount_rule_title[<?php echo $rule_id ?>]" style="font-size: 25px; width: 99%; height: 50px; margin-bottom: 10px;" value="<?php echo $bulk_discount_rule_title ?>">

                        <div class="bulk-container bulk-border">
                            
                            <div style="float: right; width: 100%; margin: 10px;">

                                <a href="" class="button-primary delete_main_dicount_rule" style="float: right;" data-current_rule_id="<?php echo $rule_id ?>">Remove Rule</a>

                            </div>

                            <div class="switch-container mb-30 mt-30">
                                <label class="switch-label">Active Rule</label>
                                <div class="switch-wrapper">
                                    <div class="switch">
                                        <input type="checkbox" id="toggle-switch<?php echo esc_attr($rule_id);?>" name="bulk_discount_ativate_rule[<?php echo $rule_id ?>]" value="yes" <?php echo checked($bulk_discount_ativate_rule, 'yes') ?>>
                                        <label for="toggle-switch<?php echo esc_attr($rule_id);?>" class="slider"></label>
                                        <input type="hidden" id="active-rule<?php echo esc_attr($rule_id);?>" name="active-rule" value="0">
                                    </div>
                                    <span class="switch-info"><small>Select to enable or disable this discount rule</small></span>
                                </div>
                            </div>


                            <!--Percentage role -->
                            <div class="w-100 mb-30 apply_to_products_div">
                                <label class="discount-application-label">Create a profit percentage rule to:</label>
                                <div class="p-relative">
                                    <div class="discount-application-options">
                                        <div class="radio-group">
                                            <input type="radio" name="products[<?php echo $rule_id ?>]" value="all_products" class="all_products" <?php echo checked($products, 'all_products') ?>>
                                            <label for="all-products">All products</label>
                                        </div>
                                        <div class="radio-group">
                                            <input type="radio" name="products[<?php echo $rule_id ?>]" value="specific_products" class="all_products" <?php echo checked($products, 'specific_products') ?>>
                                            <label for="specific-products">Specific products</label>
                                        </div>
                                    </div>
                                    <span class="application-info"><small>Choose to apply the rule to the specific product</small></span>
                                </div>
                            </div>

                            <div class="w-100 mb-30 specific_products_div">
                                <label class="discount-application-label">Apply rule to:</label>
                                <div class="p-relative w-70">
                                    <div class="discount-application-options">
                                        <div id="selected-products" class="selected-products"></div>
                                        <select name="specific_products[<?php echo $rule_id ?>][]" class="live_search" data-search_type="products" multiple style="width: 80%;">
                                
                                            <?php foreach ( $specific_products as $prod_id ){
                                            
                                                if ( ! $prod_id) {
                                                    continue;
                                                }
                                            
                                                ?>
                                            
                                                <option value="<?php echo $prod_id ?>" selected >
                                            
                                                    <?php echo wc_get_product($prod_id)->get_name(); ?>
                                            
                                                </option>
                                            
                                            <?php } ?>
                                        
                                        </select>
                                    </div>
                                    <span class="application-info">
                                    <small>Search the product(s) to include in the rule</small>
                                </span>
                                </div>
                            </div>

                            <?php

                            $bulk_discount_rules = get_posts(
                                array(

                                    'post_type' => 'bulk_discount_rules',
                                    'post_status' => 'publish',
                                    'numberposts' => -1,
                                    'orderby' => 'menu_order',
                                    'order' => 'ASC',
                                    'post_parent' => $rule_id,
                                    'fields' => 'ids'

                                )
                            );

                            ?>

                            <div class="discount-rules-container mb-30">
                                <div class="discount-rule">
                                    <label class="discount-rules-label">Discount Rules</label>
                                </div>
                                <div class="discount-rules-wrapper">
                                    <div class="discount_rules_main_div">

                                        <?php 

                                        foreach ( $bulk_discount_rules as $discount_rule_id ){ 

                                            if ( ! $discount_rule_id) {
                                                continue;
                                            }

                                            $bulk_discount_from = get_post_meta( $discount_rule_id, 'bulk_discount_from', true );
                                    
                                            $bulk_discount_to = get_post_meta( $discount_rule_id, 'bulk_discount_to', true );
                                            
                                            $discount_type = get_post_meta( $discount_rule_id, 'discount_type', true );
                                            
                                            $discount_amount = get_post_meta( $discount_rule_id, 'discount_amount', true );
                                            
                                            ?>

                                            <div style="display: inline-flex;" class="remove_row">
                                            
                                                <div style="width: 46%; ">

                                                    <fieldset style="margin-right: 10px;" class="bulk_discount_field_set">

                                                        <legend style="padding: 5px 10px; font-weight: bold;">Profit</legend>

                                                        <label >From</label>
                                                        <input type="number" name="bulk_discount_from[<?php echo $rule_id ?>][<?php echo $discount_rule_id ?>]" placeholder="1" min="1" value="<?php echo $bulk_discount_from ?>" style="width: 31%;">
                                                        <label>%</label>

                                                        <label >To</label>
                                                        <input type="number" name="bulk_discount_to[<?php echo $rule_id ?>][<?php echo $discount_rule_id ?>]" min="1" value="<?php echo $bulk_discount_to ?>" style="width: 31%;">

                                                        <label>%</label>

                                                    </fieldset>

                                                </div>

                                                <div style="width: 46%; ">

                                                    <fieldset class="bulk_discount_field_set">

                                                        <legend style="padding: 5px 10px; font-weight: bold;">Apply</legend>

                                                        <select class="rule-options" name="discount_type[<?php echo $rule_id ?>][<?php echo $discount_rule_id ?>]" style="width: 45%">
                                                            
                                                            <option value="percentage" <?php echo selected($discount_type, 'percentage') ?>>
                                                                % Percentage
                                                            </option>

                                                            <option value="fixed" <?php echo selected($discount_type, 'fixed') ?>>
                                                                Fixed Amount 
                                                            </option>

                                                        </select>

                                                        <input type="number" name="discount_amount[<?php echo $rule_id ?>][<?php echo $discount_rule_id ?>]" placeholder="20" value="<?php echo $discount_amount ?>" style="width: 45%;" min="1">
                                                        <span class='percent_symbol'>%</span>

                                                    </fieldset>

                                                </div>

                                                <div style="margin-top: 14px; width: 6%; margin-left: 10px;">
                                                    
                                                    <a href="" class="button delete_dicount_rule" data-current_rule_id="<?php echo $discount_rule_id ?>">X</a>
                                                
                                                </div>

                                            </div>

                                            <?php

                                        } 

                                        ?>

                                    </div>

                                    <div class="rule-button m-block-start-20">
                                        <a href="" class="button add_discount_rule" style="margin-top: 15px;" data-current_rule_id="<?php echo $rule_id ?>" >+ Add Rule</a>
                                    </div>
                                </div>
                            </div>

                            <div class="w-100 mb-30 user_based_restriction_div">
                                <label class="discount-application-label">Apply discount to:</label>
                                <div class="p-relative">
                                    <div class="discount-application-options">
                                        <div class="radio-group">
                                            <input type="radio" name="user_role[<?php echo $rule_id ?>]" class="user_role" value="all_users" <?php echo checked($user_role, 'all_users') ?>>
                                            <label for="all-users">All users</label>
                                        </div>
                                        <div class="radio-group">
                                            <input type="radio" name="user_role[<?php echo $rule_id ?>]" class="user_role" value="specific_user" <?php echo checked($user_role, 'specific_user') ?>>
                                            <label for="specific-user">Only to a specific user</label>
                                        </div>
                                        <div class="radio-group">
                                            <input type="radio" name="user_role[<?php echo $rule_id ?>]" class="user_role" value="specefic_user_role" <?php echo checked($user_role, 'specefic_user_role') ?>>
                                            <label for="user-roles">Only to specific user roles</label>
                                        </div>
                                    </div>
                                    <span class="application-info"><small>Choose to apply the rule to all users or only specific user roles</small></span>
                                </div>
                            </div>

                            <div class="w-100 mb-30 specific_users_div">
                                <label class="discount-application-label">Users included</label>
                                <div class="p-relative w-70">
                                    <div class="discount-application-options">
                                         <select name="specific_users[<?php echo $rule_id ?>][]" class="specific_users" multiple style="width: 80%;">

                                         <?php 
										    $users = get_users( array( 'fields' => array( 'ID', 'display_name', 'user_email' ) ) );
                                            foreach ($users as $cust_key => $cust_value) {
                                                $status = '';
                                                if ( in_array($cust_value->ID,$specific_users) ) {
                                                    $status="selected";
                                                }
                                                ?>

                                                <option <?php echo $status;?> value="<?php echo esc_attr($cust_value->ID); ?>" ><?php echo esc_html__( $cust_value->display_name . '(' . $cust_value->user_email . ')' , 'woo-af-drpc' ); ?></option>
                                                <?php
                                                
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <span class="application-info"><small>Choose to apply the rule to all users or only specific user roles</small></span>
                                </div>
                            </div>

                            <div class="w-100 mb-30 specific_user_roles_div">
                                <label class="discount-application-label">User roles included</label>
                                <div class="p-relative w-70">
                                    <div class="discount-application-options">
                                        <select name="specific_user_roles[<?php echo $rule_id ?>][]" class="specific_user_roles" multiple style="width: 80%;">
                                            <?php 
                                            global $wp_roles;

                                            $roles = $wp_roles->get_names();
            
                                            foreach ($roles as $key => $value) {
                                                ?>
            
                                                 <option  value="<?php echo esc_html($key); ?>" 
                                                    <?php 
                                                    if ( ! empty( $specific_user_roles ) && in_array( $key, $specific_user_roles ) ) {
                                                        echo 'selected';
                                                    }
                                                    ?>
                                                         >
                                                    <?php echo esc_html($value); ?>    
                                                </option>
            
                                            <?php } ?>
            
                                            ?>
                                        </select>
                                    </div>
                                    <span class="application-info"><small>Search the user roles you want to include in this rule</small></span>
                                </div>
                            </div>

                            <div class="switch-container mb-30 exclude_products_toggle_div">
                                <label class="switch-label">Exclude Products from this discount</label>
                                <div class="excluded-products">
                                    <div class="switch">
                                        <input type="checkbox" name="exluclude_products_checkbox[<?php echo $rule_id ?>]" id="exclude-toggle-switch<?php echo $rule_id ?>" class="exluclude_products_checkbox" value='yes' <?php echo checked($exluclude_products_checkbox, 'yes') ?>>
                                        <label for="exclude-toggle-switch<?php echo $rule_id ?>" class="slider"></label>
                                    </div>
                                    <span class="switch-description"><small>Enable if you want to exclude specific products from this rule</small></span>
                                </div>
                            </div>

                            <div class="exclude-active exclude_producs_checkbox_div">

                                <div class="w-100 mb-30 exclude_products_restriction_div">
                                    <label class="discount-application-label">Exclude Products</label>
                                    <div class="p-relative">
                                        <div class="discount-application-options">
                                            <div class="radio-group">
                                                 <input type="radio" name="exclude_products[<?php echo $rule_id ?>]" class="exclude_products" value="specific_products" <?php echo checked($exclude_products, 'specific_products') ?>>
                                                <label for="ex-specific-products">Specific products</label>
                                            </div>
                                            <div class="radio-group">
                                                <input type="radio" name="exclude_products[<?php echo $rule_id ?>]" class="exclude_products" value="specific_categories" <?php echo checked($exclude_products, 'specific_categories') ?>>
                                                <label for="specific-products-cat">Specific product categories</label>
                                            </div>
                                            <div class="radio-group">
                                                <input type="radio" name="exclude_products[<?php echo $rule_id ?>]" class="exclude_products" value="specific_tags" <?php echo checked($exclude_products, 'specific_tags') ?>>
                                                <label for="specific-products-tag">Specific product tags</label>
                                            </div>
                                        </div>
                                        <span class="application-info">
                                            <small>Choose if you want to exclude some specific products or categories/tags from this rule</small>
                                        </span>
                                    </div>
                                </div>

                                <div class="w-100 mb-30 specific_exclude_products_div">
                                    <label class="discount-application-label">Choose which product(s) to exclude</label>
                                    <div class="p-relative w-70">
                                        <div class="discount-application-options">
                                            <div class="selected-items selected-products"></div> <!-- Unique container for products above the input -->
                                            <select name="exclude_specific_products[<?php echo $rule_id ?>][]" class="live_search" data-search_type="products" multiple style="width: 80%;">
                                                <?php
                                                foreach ($exclude_specific_products as $prod_id) {
                                                    if (!$prod_id) {
                                                        continue;
                                                    }

                                                    ?>
                                                    <option value="<?php echo $prod_id ?>" selected>
                                                        <?php echo wc_get_product($prod_id)->get_name(); ?>
                                                    </option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <span class="application-info">
                                            <small>Search the product(s) to exclude</small>
                                        </span>
                                    </div>
                                </div>

                                <div class="w-100 mb-30 specific_exclude_categories_div">
                                    <label class="discount-application-label">Choose the product categories to exclude</label>
                                    <div class="p-relative w-70">
                                        <div class="discount-application-options">
                                            <div class="selected-items selected-categories"></div> <!-- Unique container for categories above the input -->
                                            <select name="exclude_specific_categories[<?php echo $rule_id ?>][]" class="live_search" data-search_type="categories" multiple style="width: 80%;">
                                                <?php
                                                foreach ($exclude_specific_categories as $cat_id) {
                                                    if (!$cat_id) {
                                                        continue;
                                                    }
                                                    
                                                    $cat_obj = get_term($cat_id);

                                                    ?>
                                                    <option selected value="<?php echo $cat_id ?>">
                                                        <?php echo $cat_obj->name ?>
                                                    </option>
                                                    <?php
                                                }
                                                ?>
                                             </select>
                                        </div>
                                        <span class="application-info">
                                            <small>Search the product categories to exclude</small>
                                        </span>
                                    </div>
                                </div>

                                <div class="w-100 mb-30 specific_exclude_tags_div">
                                    <label class="discount-application-label">Choose which product tags to exclude</label>
                                    <div class="p-relative w-70">
                                        <div class="discount-application-options">
                                            <div class="selected-items selected-tags"></div> <!-- Unique container for tags above the input -->
                                            <select name="exclude_specific_tags[<?php echo $rule_id ?>][]" class="live_search" data-search_type="tags" multiple style="width: 80%;">
                                                <?php
                                                foreach ($exclude_specific_tags as $tag_id) {
                                                    if (!$tag_id) {
                                                        continue;
                                                    }
                                                    
                                                    $tag_obj = get_term($tag_id);

                                                    ?>
                                                    <option selected value="<?php echo $tag_id ?>">
                                                        <?php echo $tag_obj->name ?>
                                                    </option>
                                                    <?php
                                                }
                                                ?>
                                             </select>
                                        </div>
                                        <span class="application-info">
                                            <small>Search the product tags to exclude</small>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="switch-container mb-30">
                                <label class="switch-label">Show discount in loop</label>
                                <div class="excluded-products">
                                    <div class="switch">
                                        <input type="checkbox" name="show_discount_in_loop[<?php echo $rule_id ?>]" id="shop-toggle-switch<?php echo $rule_id ?>" value="yes" <?php echo checked($show_discount_in_loop, 'yes') ?>>
                                        <label for="shop-toggle-switch<?php echo $rule_id ?>" class="slider"></label>
                                        <input type="hidden" id="active-shop" name="active-shop" value="0">
                                    </div>
                                    <span class="switch-description"><small>Enable if you want to show the discounted price in the shop page</small></span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <?php 
                }
                ?>

            </div>

            <div class="save-button">
                <input type="submit" class="button-primary" name="save_bulk_discount_rules" value="Save Rules">
            </div>
        </div>
    </div>

</div>