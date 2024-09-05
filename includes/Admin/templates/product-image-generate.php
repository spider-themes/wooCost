<div class="mw-1200">
    <h2 class="wp-heading-inline">Generate Product Image</h2>

    <!-- SmartWizard html -->
    <div id="smartwizard">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="#step-1">
                    <div class="num">1</div>
                    Choose Design
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#step-2">
                    <span class="num">2</span>
                    Customize Design
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#step-3">
                    <span class="num">3</span>
                    Select Products
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="#step-4">
                    <span class="num">4</span>
                    Finish
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div id="step-1" class="tab-pane" role="tabpanel" aria-labelledby="step-1">
                <div class="choose-design">
                    <ul>
                        <li><input type="radio" name="test" id="cb1" />
                            <label for="cb1"><img src="https://images.pexels.com/photos/5650026/pexels-photo-5650026.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" /></label>
                        </li>
                        <li><input type="radio" name="test" id="cb2" />
                            <label for="cb2"><img src="https://images.pexels.com/photos/5624987/pexels-photo-5624987.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" /></label>
                        </li>
                        <li><input type="radio" name="test" id="cb3" />
                            <label for="cb3"><img src="https://images.pexels.com/photos/5625003/pexels-photo-5625003.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" /></label>
                        </li>
                    </ul>
                </div>
            </div>

            <div id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-2">

                <h1>Edit Banner Image</h1>

                <canvas id="canvas" width="1200" height="400"></canvas>

                <div class="controls">
                    <div class="row">
                        <label for="valentineText">Banner Heading:</label>
                        <input type="text" id="valentineText" placeholder="Banner Heading" />

                        <label for="discountText">Discount :</label>
                        <input type="text" id="discountText" placeholder="up to 45% OFF" />


                        <label for="buttonText">Button:</label>
                        <input type="text" id="buttonText" placeholder="Shop Now" />

                        <label for="vendorName">Vendor Name:</label>
                        <input type="text" id="vendorName" placeholder="example.com" />
                    </div>

                    <div class="row">
                        <label for="backgroundImageUpload">Background Image:</label>
                        <input type="file" id="backgroundImageUpload" accept="image/*" onchange="uploadBackgroundImage()" />

                        <label for="imageUpload">Upload Logo:</label>
                        <input type="file" id="imageUpload" accept="image/*" onchange="uploadImage()" />
                    </div>
                    <div class="row">
                        <button onclick="updateImage()" class="btn update-btn">Update</button>
                        <button onclick="downloadImage()" class="btn download-btn">Download Edited Image</button>
                    </div>
                </div>

            </div>

            <div id="step-3" class="tab-pane" role="tabpanel" aria-labelledby="step-3">
                <div class="select-product">
                        <div class="radio-group">
                            <input type="radio" id="all-products" name="products" value="all-products" checked>
                            <label for="all-products">All products</label>
                        </div>
                        <div class="radio-group">
                            <input type="radio" id="specific-products" name="products" value="specific-products">
                            <label for="specific-products">Specific products</label>
                            <small class="application-info">Choose to apply the rule to the specific product</small>
                        </div>
                        <div class="product-search w-50">
                            <div class="select-product-options">
                                <input type="text" name="specific-products" placeholder="Type the Product name">
                            </div>
                            <span class="application-info"><small>Search the product name</small></span>
                        </div>
                </div>

            </div>

            <div id="step-4" class="tab-pane" role="tabpanel" aria-labelledby="step-4">
                <div class="finish">
                    <form action="#" method="post">
                        <button type="submit" class="finish-btn button">Finish</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Include optional progressbar HTML -->
        <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>
</div>