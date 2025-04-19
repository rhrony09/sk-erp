@extends('ecommerce.layouts.master')
@section('content')
    <div class="breadcrumb-area mb-30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-wrap">
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{$category->name}}</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-wrapper pt-35">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3">
                    <div class="shop-sidebar-inner mb-30">
                        <!-- filter-price-content start -->
                        <div class="single-sidebar mb-45">
                            <div class="sidebar-inner-title mb-25">
                                <h3>Fillter by price</h3>
                            </div>
                            <div class="sidebar-content-box">
                                <div class="filter-price-content">
                                    <form action="#" method="post">
                                        <div id="price-slider"
                                            class="price-slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all">
                                            <div class="ui-slider-range ui-widget-header ui-corner-all"
                                                style="left: 16.6667%; width: 79.1667%;"></div><span
                                                class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0"
                                                style="left: 0%;"></span><span
                                                class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0"
                                                style="left: 100%;"></span>
                                            <div class="ui-slider-range ui-widget-header ui-corner-all"
                                                style="left: 0%; width: 100%;"></div>
                                        </div>
                                        <div class="filter-price-wapper">
                                            <div class="filter-price-cont">
                                                <div class="input-type">
                                                    <input id="min-price" readonly="" type="text">
                                                </div>
                                                <div class="input-type">
                                                    <input id="max-price" readonly="" type="text">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- filte price end -->
                        <!-- categories filter start -->
                        <div class="single-sidebar mb-45">
                            <div class="sidebar-inner-title mb-25">
                                <h3>Categories</h3>
                            </div>
                            <div class="sidebar-content-box">
                                <div class="filter-attribute-container">
                                    <ul>
                                        <li><a class="active" href="shop-grid-left-sidebar.html">Categories 1 (05)</a></li>
                                        <li><a href="shop-grid-left-sidebar.html">Categories 2 (03)</a></li>
                                        <li><a href="shop-grid-left-sidebar.html">Categories 3 (10)</a></li>
                                        <li><a href="shop-grid-left-sidebar.html">Categories 4 (02)</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- categories filter end -->
                        <!-- categories filter start -->
                        <div class="single-sidebar mb-45">
                            <div class="sidebar-inner-title mb-25">
                                <h3>Manufacturer</h3>
                            </div>
                            <div class="sidebar-content-box">
                                <div class="filter-attribute-container">
                                    <ul>
                                        <li><a class="active" href="shop-grid-left-sidebar.html">Christian Dior (2)</a></li>
                                        <li><a href="shop-grid-left-sidebar.html">ferragamo (7)</a></li>
                                        <li><a href="shop-grid-left-sidebar.html">hermes (7)</a></li>
                                        <li><a href="shop-grid-left-sidebar.html">louis vuitton (6)</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- categories filter end -->
                        <!-- categories filter start -->
                        <div class="single-sidebar mb-45">
                            <div class="sidebar-inner-title mb-25">
                                <h3>Select by color</h3>
                            </div>
                            <div class="sidebar-content-box">
                                <div class="filter-attribute-container">
                                    <ul>
                                        <li><a class="active" href="shop-grid-left-sidebar.html">Black (2)</a></li>
                                        <li><a href="shop-grid-left-sidebar.html">blue (7)</a></li>
                                        <li><a href="shop-grid-left-sidebar.html">brown (7)</a></li>
                                        <li><a href="shop-grid-left-sidebar.html">white (6)</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 order-first order-lg-last">
                    <div class="product-shop-main-wrapper mb-50">
                        <div class="shop-top-bar mb-30">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="top-bar-left">
                                        <div class="product-view-mode">
                                            <a class="active" href="#" data-target="column_3"><i
                                                    class="fas fa-th-large"></i></a>
                                            <a href="#" data-target="grid"><i class="fas fa-th"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="top-bar-right">
                                        <div class="per-page">
                                            <p>Show : </p>
                                            <select class="nice-select" name="sortbyPage" style="display: none;">
                                                <option value="10">10</option>
                                                <option value="20">20</option>
                                                <option value="30">30</option>
                                                <option value="40">40</option>
                                                <option value="50">50</option>
                                                <option value="60">60</option>
                                                <option value="70">70</option>
                                                <option value="100">100</option>
                                            </select>
                                            <div class="nice-select" tabindex="0"><span class="current">10</span>
                                                <ul class="list">
                                                    <li data-value="10" class="option selected">10</li>
                                                    <li data-value="20" class="option">20</li>
                                                    <li data-value="30" class="option">30</li>
                                                    <li data-value="40" class="option">40</li>
                                                    <li data-value="50" class="option">50</li>
                                                    <li data-value="60" class="option">60</li>
                                                    <li data-value="70" class="option">70</li>
                                                    <li data-value="100" class="option">100</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product-short">
                                            <p>Sort By : </p>
                                            <select class="nice-select" name="sortby" style="display: none;">
                                                <option value="trending">Relevance</option>
                                                <option value="aToZ">Name (A - Z)</option>
                                                <option value="zToA">Name (Z - A)</option>
                                                <option value="lowToHigh">Price (Low &gt; High)</option>
                                                <option value="highToLow">Price (High &gt; Low)</option>
                                            </select>
                                            <div class="nice-select" tabindex="0"><span class="current">Relevance</span>
                                                <ul class="list">
                                                    <li data-value="trending" class="option selected">Relevance</li>
                                                    <li data-value="aToZ" class="option">Name (A - Z)</li>
                                                    <li data-value="zToA" class="option">Name (Z - A)</li>
                                                    <li data-value="lowToHigh" class="option">Price (Low &gt; High)</li>
                                                    <li data-value="highToLow" class="option">Price (High &gt; Low)</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div id="product-container" class="shop-product-wrap grid column_3 row">
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function () {
            // Get current category ID from URL or set a default
            const urlParams = new URLSearchParams(window.location.search);
            const categoryId = {{$category->id}};

            // Fetch products immediately when page loads
            fetchCategoryProducts(categoryId);

            $('select[name="sortbyPage"]').on('change', function () {
                const perPage = parseInt($(this).val()) || defaultPerPage;
                const sortby = $('select[name="sortby"]').val() || defaultSortBy;
                fetchCategoryProducts(categoryId, 1, perPage, sortby);
            });

            // Handle change in sort order dropdown
            $('select[name="sortby"]').on('change', function () {
                const sortby = $(this).val() || defaultSortBy;
                const perPage = parseInt($('select[name="sortbyPage"]').val()) || defaultPerPage;
                fetchCategoryProducts(categoryId, 1, perPage, sortby);
            });

            /**
             * Fetch products by category ID and render them in the product grid
             * @param {number} categoryId - The ID of the category to fetch products for
             * @param {number} page - Optional page number for pagination (defaults to 1)
             */
            function fetchCategoryProducts(categoryId, page = 1, perPage = 10, sortby = 'trending') {
                // Show loading indicator
                $('#product-container').html('<div class="text-center py-5"><i class="fa fa-spinner fa-spin fa-3x"></i><p>Loading products...</p></div>');

                // Make AJAX request to the Laravel endpoint
                $.ajax({
                    url: `/get-products/${categoryId}`,
                    type: 'GET',
                    data: {
                        page: page,
                        perPage: perPage,
                        sortby: sortby
                    },
                    dataType: 'json',
                    success: function (response) {

                        console.log('ok');


                        $('#product-container').empty();

                        // If no products found
                        if (response.data.length === 0) {
                            $('#product-container').html('<div class="col-12 text-center"><p>No products found in this category.</p></div>');
                            return;
                        }

                        // Create product grid container
                        let productGrid = $('<div class="shop-product-wrap grid column_3 row"></div>');

                        // Loop through each product and create HTML
                        $.each(response.data, function (index, product) {
                            // Calculate discount percentage if discount price exists
                            let discountPercentage = '';
                            if (product.discount_price) {
                                discountPercentage = Math.round(100 - (100 * product.discount_price / product.sale_price));
                            }

                            // Check if product is new (created within last 10 days)
                            const createdDate = new Date(product.created_at);
                            const tenDaysAgo = new Date();
                            tenDaysAgo.setDate(tenDaysAgo.getDate() - 10);
                            const isNew = createdDate > tenDaysAgo;

                            // Create product HTML
                            let productHtml = `
                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                            <div class="product-item mb-30">
                                                <div class="product-thumb">
                                                    <a href="${window.location.origin}/sk-erp-test/product-details/${product.slug}">
                                                        <img src="${window.location.origin}/sk-erp-test/storage/uploads/pro_image/${product.pro_image}"
                                                            class="pri-img" alt="${product.name}">
                                                        <img src="${window.location.origin}/sk-erp-test/assets/img/product/product-2.jpg" class="sec-img" alt="${product.name}">
                                                    </a>
                                                    <div class="box-label">
                                                        ${isNew ?
                                    '<div class="label-product label_new"><span>new</span></div>' : ''
                                }
                                                        ${product.discount_price ?
                                    `<div class="label-product label_sale"><span>-${discountPercentage}%</span></div>` : ''
                                }
                                                    </div>
                                                    <div class="action-links">
                                                        <a href="#" title="Wishlist" class="wishlist-btn" data-product-id="${ product.id }"><i class="lnr lnr-heart"></i></a>
                                                        <a href="#" title="Compare" class="compare-btn" data-product-id="${ product.id }"><i class="lnr lnr-sync"></i></a>
                                                        <a href="#" title="Quick view" data-bs-target="#quickk_view-${product.id}"
                                                            data-bs-toggle="modal"><i class="lnr lnr-magnifier"></i></a>
                                                    </div>
                                                </div>
                                                <div class="product-caption">
                                                    <div class="manufacture-product">
                                                        <p><a href="${window.location.origin}/sk-erp-test/product-details/${product.slug}">Category</a></p>
                                                    </div>
                                                    <div class="product-name">
                                                        <h4><a href="${window.location.origin}/sk-erp-test/product-details/${product.slug}">${product.name}</a></h4>
                                                    </div>
                                                    <div class="ratings">
                                                        <span class="yellow"><i class="lnr lnr-star"></i></span>
                                                        <span class="yellow"><i class="lnr lnr-star"></i></span>
                                                        <span class="yellow"><i class="lnr lnr-star"></i></span>
                                                        <span class="yellow"><i class="lnr lnr-star"></i></span>
                                                        <span><i class="lnr lnr-star"></i></span>
                                                    </div>
                                                    <div class="price-box">
                                                        ${product.discount_price ?
                                    `<span class="regular-price"><span class="special-price">${product.discount_price}৳</span></span>
                                                            <span class="old-price"><del>${product.sale_price}৳</del></span>` :
                                    `<span class="regular-price"><span class="special-price">${product.sale_price}৳</span></span>`
                                }
                                                    </div>
                                                    <button class="btn-cart add-to-cart-btn" type="button" data-product-id="${ product.id }" data-quantity="1">add to cart</button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="quickk_view-${product.id}">
                                            <div class="container">
                                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-lg-5">
                                                                    <div class="product-large-slider mb-20">
                                                                        <div class="pro-large-img">
                                                                            <img src="${window.location.origin}/sk-erp-test/storage/uploads/pro_image/${product.pro_image}" alt="${product.name}"/>
                                                                        </div>
                                                                        <div class="pro-large-img">
                                                                            <img src="${window.location.origin}/sk-erp-test/assets/img-ecom/product/product-5.jpg" alt=""/>
                                                                        </div>
                                                                        <div class="pro-large-img">
                                                                            <img src="${window.location.origin}/sk-erp-test/assets/img-ecom/product/product-6.jpg" alt=""/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="pro-nav">
                                                                        <div class="pro-nav-thumb"><img src="${window.location.origin}/sk-erp-test/storage/uploads/pro_image/${product.pro_image}" alt="${product.name}" /></div>
                                                                        <div class="pro-nav-thumb"><img src="${window.location.origin}/assets/img-ecom/product/product-5.jpg" alt="" /></div>
                                                                        <div class="pro-nav-thumb"><img src="${window.location.origin}/assets/img-ecom/product/product-6.jpg" alt="" /></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-7">
                                                                    <div class="product-details-inner">
                                                                        <div class="product-details-contentt">
                                                                            <div class="pro-details-name mb-10">
                                                                                <h3>${product.name}</h3>
                                                                            </div>
                                                                            <div class="pro-details-review mb-20">
                                                                                <ul>
                                                                                    <li>
                                                                                        <span><i class="fa fa-star"></i></span>
                                                                                        <span><i class="fa fa-star"></i></span>
                                                                                        <span><i class="fa fa-star"></i></span>
                                                                                        <span><i class="fa fa-star"></i></span>
                                                                                        <span><i class="fa fa-star"></i></span>
                                                                                    </li>
                                                                                    <li><a href="#">1 Reviews</a></li>
                                                                                </ul>
                                                                            </div>
                                                                            <div class="price-box mb-15">
                                                                                ${product.discount_price ?
                                    `<span class="regular-price"><span class="special-price">${product.discount_price}৳</span></span>
                                                                                    <span class="old-price"><del>${product.sale_price}৳</del></span>` :
                                    `<span class="regular-price"><span class="special-price">${product.sale_price}৳</span></span>`
                                }
                                                                            </div>
                                                                            <div class="product-detail-sort-des pb-20">
                                                                                <p>${product.description}</p>
                                                                            </div>
                                                                            <div class="pro-details-list pt-20">
                                                                                <ul>
                                                                                    <li><span>Availability :</span>In Stock</li>
                                                                                </ul>
                                                                            </div>
                                                                            <div class="product-availabily-option mt-15 mb-15">
                                                                                <h3>Available Options</h3>
                                                                                <div class="color-optionn">
                                                                                    <h4><sup>*</sup>color</h4>
                                                                                    <ul>
                                                                                        <li>
                                                                                            <a class="c-black" href="#" title="Black"></a>
                                                                                        </li>
                                                                                        <li>
                                                                                            <a class="c-blue" href="#" title="Blue"></a>
                                                                                        </li>
                                                                                        <li>
                                                                                            <a class="c-brown" href="#" title="Brown"></a>
                                                                                        </li>
                                                                                    </ul> 
                                                                                </div>
                                                                            </div>
                                                                            <div class="pro-quantity-box mb-30">
                                                                                <div class="qty-boxx">
                                                                                    <label>qty :</label>
                                                                                    <input type="text" id="qty-${product.id }" placeholder="0" value="1">
                                                                    <button class="btn-cart add-to-cart-btn lg-btn" type="button" 
                                                                            data-product-id="${product.id }" 
                                                                            data-input-id="qty-${product.id }">add to cart</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    `;

                            // Append product HTML to the grid
                            productGrid.append(productHtml);
                        });

                        // Append the product grid to the container
                        $('#product-container').append(productGrid);

                        // Create pagination if needed
                        if (response.last_page > 1) {
                            createPagination(response, categoryId, perPage);
                        }

                        // Initialize slick slider for product images if needed
                        if (typeof $.fn.slick !== 'undefined') {
                            setTimeout(function () {
                                $('.product-large-slider').slick({
                                    slidesToShow: 1,
                                    slidesToScroll: 1,
                                    arrows: false,
                                    asNavFor: '.pro-nav'
                                });

                                $('.pro-nav').slick({
                                    slidesToShow: 3,
                                    slidesToScroll: 1,
                                    asNavFor: '.product-large-slider',
                                    arrows: true,
                                    focusOnSelect: true
                                });
                            }, 200);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching products:', error);
                        $('#product-container').html('<div class="alert alert-danger">Error loading products. Please try again later.</div>');
                    }
                });
            }

            /**
             * Create pagination links based on API response
             * @param {Object} response - API response containing pagination data
             * @param {number} categoryId - Current category ID for page links
             */
            // In your document.ready function, update the createPagination function:
            function createPagination(response, categoryId, perPage, sortby = 'trending') {
                // Remove existing pagination if any
                $('.paginatoin-area').remove();
                
                // Calculate showing from-to counts
                const from = response.from || 0;
                const to = response.to || 0;
                const total = response.total || 0;
                const lastPage = response.last_page || 1;
                const currentPage = response.current_page || 1;
                
                // Create pagination markup
                let paginationHTML = `
                    <div class="paginatoin-area style-2 pt-35 pb-20">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="pagination-area">
                                    <p>Showing ${from} to ${to} of ${total} (${lastPage} ${lastPage > 1 ? 'Pages' : 'Page'})</p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <ul class="pagination-box pagination-style-2">
                `;
                
                // Previous button
                paginationHTML += `
                    <li>
                        <a class="Previous ${currentPage === 1 ? 'disabled' : ''}" 
                        href="javascript:void(0);" 
                        data-page="${currentPage - 1}"
                        ${currentPage === 1 ? 'disabled="disabled"' : ''}>
                        Previous
                        </a>
                    </li>
                `;
                
                // Page numbers
                if (lastPage <= 5) {
                    for (let i = 1; i <= lastPage; i++) {
                        paginationHTML += `
                            <li class="${i === currentPage ? 'active' : ''}">
                                <a href="javascript:void(0);" 
                                data-page="${i}">
                                ${i}
                                </a>
                            </li>
                        `;
                    }
                } else {
                    // Always show first page
                    paginationHTML += `
                        <li class="${1 === currentPage ? 'active' : ''}">
                            <a href="javascript:void(0);" 
                            data-page="1">
                            1
                            </a>
                        </li>
                    `;
                    
                    // Show ellipsis if current page is far from first page
                    if (currentPage > 3) {
                        paginationHTML += `<li><span>...</span></li>`;
                    }
                    
                    // Show one page before current if not first or second page
                    if (currentPage > 2) {
                        paginationHTML += `
                            <li>
                                <a href="javascript:void(0);" 
                                data-page="${currentPage - 1}">
                                ${currentPage - 1}
                                </a>
                            </li>
                        `;
                    }
                    
                    // Show current page if not first
                    if (currentPage !== 1 && currentPage !== lastPage) {
                        paginationHTML += `
                            <li class="active">
                                <a href="javascript:void(0);" 
                                data-page="${currentPage}">
                                ${currentPage}
                                </a>
                            </li>
                        `;
                    }
                    
                    // Show one page after current if not last or second-to-last page
                    if (currentPage < lastPage - 1) {
                        paginationHTML += `
                            <li>
                                <a href="javascript:void(0);" 
                                data-page="${currentPage + 1}">
                                ${currentPage + 1}
                                </a>
                            </li>
                        `;
                    }
                    
                    // Show ellipsis if current page is far from last page
                    if (currentPage < lastPage - 2) {
                        paginationHTML += `<li><span>...</span></li>`;
                    }
                    
                    // Always show last page if not first page
                    if (lastPage > 1) {
                        paginationHTML += `
                            <li class="${lastPage === currentPage ? 'active' : ''}">
                                <a href="javascript:void(0);" 
                                data-page="${lastPage}">
                                ${lastPage}
                                </a>
                            </li>
                        `;
                    }
                }
                
                // Next button
                paginationHTML += `
                    <li>
                        <a class="Next ${currentPage === lastPage ? 'disabled' : ''}" 
                        href="javascript:void(0);" 
                        data-page="${currentPage + 1}"
                        ${currentPage === lastPage ? 'disabled="disabled"' : ''}>
                        Next
                        </a>
                    </li>
                `;
                
                // Close the container tags
                paginationHTML += `
                                </ul>
                            </div>
                        </div>
                    </div>
                `;
                
                // Append pagination to the container
                $('#product-container').after(paginationHTML);
                
                // Add disabled class styling
                $('.pagination-box a.disabled').css({
                    'pointer-events': 'none',
                    'opacity': '0.6',
                    'cursor': 'default'
                });
                
                // IMPORTANT: Attach click event handlers for pagination links
                $('.pagination-box a[data-page]').on('click', function(e) {
                    e.preventDefault();
                    if ($(this).hasClass('disabled')) return;
                    
                    const page = parseInt($(this).attr('data-page'));
                    fetchCategoryProducts(categoryId, page, perPage, sortby);
                });
            }
        });

        // Make fetchCategoryProducts globally accessible so it can be called from pagination
        function fetchCategoryProducts(categoryId, page = 1) {
            // Call the function defined inside document.ready
            $(document).ready(function () {
                fetchCategoryProducts(categoryId, page);
            });
        }
    </script>
@endsection