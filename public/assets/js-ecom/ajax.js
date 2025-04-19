// ajax.js - Cart operations with jQuery

$(document).ready(function() {
    
    function updateCartCount() {
        $.ajax({
            url: '/cart-count',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    // Update cart count in UI elements with the class 'cart-count'
                    $('#nav-cart-count').text(data.cart_count);
                    if(data.cart_count > 0) {
                        $('#nav-cart-count').addClass('show-count');
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching cart count:', error);
            }
        });
    }

    updateCartCount();

    // Replace your current click handler
    $(document).on('click', '.add-to-cart-btn', function() {
        const productId = $(this).data('product-id');
        let quantity = $(this).data('quantity') || 1;
        
        // If this button references an input for quantity, get the value from there
        const inputId = $(this).data('input-id');
        if (inputId) {
            const qtyValue = $('#' + inputId).val();
            if (qtyValue && parseInt(qtyValue) > 0) {
                quantity = parseInt(qtyValue);
            }
        }
        
        addToCart(productId, quantity);
    });
    
    // Function to add product to cart using jQuery AJAX
    function addToCart(productId, quantity) {
        $.ajax({
            url: '/add-to-cart',
            type: 'POST',
            data: {
                product_id: productId,
                quantity: quantity,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    // Show success message
                    showNotification(data.message || 'Product added to cart', 'success');
                    
                    // Update cart count in header if it exists
                    updateCartCount();

                    updateNavCart();
                } else {
                    showNotification(data.message || 'Failed to add product to cart', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error adding to cart:', error);
                showNotification('An error occurred. Please try again.', 'error');
            }
        });
    }

    fetchCartData();
    function fetchCartData() {
        // Show loading indicator
        $('#cart-container').html('<div class="text-center py-5"><i class="fa fa-spinner fa-spin fa-3x"></i><p>Loading cart...</p></div>');
        
        // Make AJAX request
        $.ajax({
            url: '/cart-page-data',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // Check if cart is empty
                if (response.carts.length === 0) {
                    $('#cart-container').html('<div class="alert alert-info">Your cart is empty.</div>');
                    $('.cart-amount-wrapper').hide();
                    return;
                }
                
                // Generate HTML for cart items
                let cartHtml = `
                    <form id="cart-form">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <td>Image</td>
                                        <td>Product Name</td>
                                        <td>Quantity</td>
                                        <td>Unit Price</td>
                                        <td>Total</td>
                                    </tr>
                                </thead>
                                <tbody>`;
                
                // Calculate total
                let cartTotal = 0;
                
                // Loop through cart items
                $.each(response.carts, function(index, cart) {
                    const product = cart.product;
                    const unitPrice = product.discount_price || product.sale_price;
                    const itemTotal = unitPrice * cart.quantity;
                    cartTotal += itemTotal;
                    
                    cartHtml += `
                        <tr data-cart-id="${cart.id}">
                            <td>
                                <a href="${window.location.origin}/product-details/${product.slug}">
                                    <img src="${window.location.origin}/storage/uploads/pro_image/${product.pro_image}" alt="Cart Product Image" class="img-thumbnail">
                                </a>
                            </td>
                            <td>
                                <a href="/product-details/${product.slug}">${product.name}</a>
                                <span>Color: Brown</span>
                            </td>
                            <td>
                                <div class="input-group btn-block" style='max-width: unset'>
                                    <div class="product-qty me-3" data-cart-id="${cart.id}">
                                        <input type="text" value="${cart.quantity}" min="1" readonly>
                                        <span class="dec qtybtn"><i class="fa fa-minus"></i></span>
                                        <span class="inc qtybtn"><i class="fa fa-plus"></i></span>
                                    </div>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary update-cart" data-cart-id="${cart.id}">
                                            <i class="fa fa-refresh"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger remove-cart" data-cart-id="${cart.id}">
                                            <i class="fa fa-times-circle"></i>
                                        </button>
                                    </span>
                                </div>
                            </td>
                            <td>${unitPrice.toLocaleString()}৳</td>
                            <td class="item-total">${itemTotal.toLocaleString()}৳</td>
                        </tr>`;
                });
                
                cartHtml += `
                                </tbody>
                            </table>
                        </div>
                    </form>`;
                    
                // Generate totals HTML
                let totalsHtml = `
                    <div class="cart-amount-wrapper">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-4 offset-md-8">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td><strong>Sub-Total:</strong></td>
                                            <td class="cart-subtotal">${cartTotal.toLocaleString()}৳</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total:</strong></td>
                                            <td><span class="color-primary cart-total">${cartTotal.toLocaleString()}৳</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>`;
                
                // Update the cart container with new HTML
                $('#cart-container').html(cartHtml);
                
                // Update or append the totals
                if ($('.cart-amount-wrapper').length) {
                    $('.cart-amount-wrapper').replaceWith(totalsHtml);
                } else {
                    $('#cart-container').after(totalsHtml);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching cart data:', error);
                $('#cart-container').html('<div class="alert alert-danger">Error loading cart. Please try again later.</div>');
            }
        });
    }

    $(document).on('click', '.inc.qtybtn', function() {
        const cartId = $(this).closest('.product-qty').data('cart-id');
        increaseCartQuantity(cartId);
    });
    
    // Event handler for decrement button click
    $(document).on('click', '.dec.qtybtn', function() {
        const cartId = $(this).closest('.product-qty').data('cart-id');
        decreaseCartQuantity(cartId);
    });
    
    // Function to increase cart quantity
    function increaseCartQuantity(cartId) {
        $.ajax({
            url: `/increate-cart-qty/${cartId}`,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Update the input value
                    const inputField = $(`.product-qty[data-cart-id="${cartId}"]`).find('input');
                    const newQuantity = parseInt(inputField.val()) + 1;
                    inputField.val(newQuantity);
                    
                    fetchCartData();

                    // Update item total
                    updateItemTotal(cartId);
                    
                    // Update cart totals
                    updateCartTotals();

                    updateNavCart();
                    
                    // Show success notification
                    showNotification(response.message, 'success');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error increasing quantity:', error);
                showNotification('Error updating quantity.', 'error');
            }
        });
    }

    function decreaseCartQuantity(cartId) {
        const inputField = $(`.product-qty[data-cart-id="${cartId}"]`).find('input');
        const currentQty = parseInt(inputField.val());
        
        // Only proceed if quantity is greater than 1
        if (currentQty > 1) {
            $.ajax({
                url: `/decreate-cart-qty/${cartId}`,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Update the input value
                        const newQuantity = currentQty - 1;
                        inputField.val(newQuantity);
                        
                        fetchCartData();
                        // Update item total
                        updateItemTotal(cartId);
                        
                        // Update cart totals
                        updateCartTotals();

                        updateNavCart();
                        
                        // Show success notification
                        showNotification(response.message, 'success');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error decreasing quantity:', error);
                    showNotification('Error updating quantity.', 'error');
                }
            });
        } else {
            showNotification('Quantity cannot be less than 1.', 'error');
        }
    }

    function updateCartTotals() {
        let total = 0;
        
        // Calculate total from all visible items
        $('#cart-container tbody tr').each(function() {
            const itemTotalText = $(this).find('.item-total').text();
            const itemTotal = parseFloat(itemTotalText.replace('৳', '').replace(',', ''));
            total += itemTotal;
        });
        
        // Update the total displays
        $('.cart-subtotal').text(total.toLocaleString() + '৳');
        $('.cart-total').text(total.toLocaleString() + '৳');
    }
    updateCartTotals();

    function updateNavCart() {
        $.ajax({
            url: '/cart-nav-data',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // Check if cart is empty
                if (!response.carts || response.carts.length === 0) {
                    $('.mini-cart-drop-down').html('<li class="text-center py-3">Your cart is empty</li>');
                    return;
                }
                
                let cartHtml = '';
                let subtotal = 0;
                
                // Loop through cart items (limited to 3 from controller)
                $.each(response.carts, function(index, cart) {
                    const product = cart.product;
                    const unitPrice = product.discount_price || product.sale_price;
                    const itemTotal = unitPrice * cart.quantity;
                    subtotal += itemTotal;
                    
                    cartHtml += `
                        <li class="mb-30">
                            <div class="cart-img">
                                <a href="/product-details/${product.slug}">
                                    <img alt="${product.name}" src="${window.origin}/storage/uploads/pro_image/${product.pro_image}">
                                </a>
                            </div>
                            <div class="cart-info">
                                <h4><a href="/product-details/${product.slug}">${product.name}</a></h4>
                                <span><span>${cart.quantity} x </span>${unitPrice.toLocaleString()}৳</span>
                            </div>
                            <div class="del-icon">
                                <button type='button' class='remove-cart' data-cart-id="${cart.id}">
                                    <i class="fa fa-times-circle delete-nav-cart"></i>
                                </button>
                            </div>
                        </li>`;
                });
                
                // Calculate additional fees and total
                // const vat = subtotal * vatRate;
                const total = subtotal;
                
                // Add summary items
                cartHtml += `
                    <li>
                        <div class="subtotal-text">Sub-total: </div>
                        <div class="subtotal-price">${subtotal.toLocaleString()}৳</div>
                    </li>`;
                
                // Add total and action buttons
                cartHtml += `
                    <li>
                        <div class="subtotal-text">Total: </div>
                        <div class="subtotal-price"><span>${total.toLocaleString()}৳</span></div>
                    </li>
                    <li class="mt-30">
                        <a class="cart-button" href="${window.origin}/carts">view cart</a>
                    </li>
                    <li>
                        <a class="cart-button" href="/checkout">checkout</a>
                    </li>`;
                    
                // Update the dropdown
                $('.mini-cart-drop-down').html(cartHtml);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching cart nav data:', error);
            }
        });
    }

    updateNavCart();

    $(document).on('click', '.remove-cart', function() {
        const cartId = $(this).closest('tr').data('cart-id');
        deleteCartItem(cartId);
        console.log('Delete button clicked');
    });

    $(".mini-cart-drop-down").on('click', '.remove-cart', function() {
        const cartId = $(this).data('cart-id');
        deleteCartItem(cartId);
        console.log('Delete button clicked');
    });

    function deleteCartItem(cartId) {
        if (confirm('Are you sure you want to remove this item from your cart?')) {
            $.ajax({
                url: `/delete-cart/${cartId}`,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Remove the row from the table with animation
                        $(`tr[data-cart-id="${cartId}"]`).fadeOut(300, function() {
                            $(this).remove();
                            
                            // If no more items in cart
                            if ($('#cart-container tbody tr').length === 0) {
                                $('#cart-container').html('<div class="alert alert-info">Your cart is empty.</div>');
                                $('.cart-amount-wrapper').hide();
                            } else {
                                // Recalculate totals
                                updateCartTotals();
                            }
                        });
                        
                        // Show success message
                        showNotification(response.message, 'success');
                        
                        // Update cart count in header if it exists
                        updateCartCount();

                        updateNavCart();
                    } else {
                        showNotification('Failed to remove item from cart', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error removing cart item:', error);
                    showNotification('An error occurred while removing the item.', 'error');
                }
            });
        }
    }






    // Wishlist

    function toggleWishlist(productId) {
        $.ajax({
            url: `/add-wishlist/${productId}`,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Toggle the wishlist icon appearance
                const wishlistButton = $(`.wishlist-btn[data-product-id="${productId}"]`);
                updateWishlistCount();
                
                if (wishlistButton.hasClass('active-action')) {
                    wishlistButton.removeClass('active-action');
                    showNotification('Product removed from wishlist', 'success');
                } else {
                    wishlistButton.addClass('active-action');
                    showNotification('Product added to wishlist', 'success');
                }
            },
            error: function(xhr, status, error) {
                if (xhr.status === 401) {
                    // Unauthorized - user not logged in
                    showNotification('Please login to add items to wishlist', 'error');
                } else {
                    showNotification('An error occurred. Please try again.', 'error');
                    console.error('Error toggling wishlist:', error);
                }
            }
        });
    }
    
    // Add click event handler
    $(document).on('click', '.wishlist-btn', function(e) {
        e.preventDefault();
        const productId = $(this).data('product-id');
        toggleWishlist(productId);
    });

    function updateWishlistCount() {
        $.ajax({
            url: '/wishlist-count',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    // Update cart count in UI elements with the class 'cart-count'
                    $('#nav-wish-count').text(data.wishlistCount);
                    if(data.wishlistCount > 0) {
                        $('#nav-wish-count').addClass('show-count');
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching cart count:', error);
            }
        });
    }

    updateWishlistCount();


    function fetchWishlistData() {
        // Show loading indicator
        $('#wishlist-container').html('<div class="text-center py-5"><i class="fa fa-spinner fa-spin fa-3x"></i><p>Loading wishlist...</p></div>');
        
        $.ajax({
            url: '/wishlist-data',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (!response.success || !response.wishlists || response.wishlists.length === 0) {
                    $('#wishlist-container').html('<div class="alert alert-info">Your wishlist is empty.</div>');
                    return;
                }
                
                let wishlistHtml = `
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td>Image</td>
                                <td>Product Name</td>
                                <td>Stock</td>
                                <td>Unit Price</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tbody>`;
                
                $.each(response.wishlists, function(index, wishlist) {
                    const product = wishlist.product;
                    const unitPrice = product.discount_price || product.sale_price;
                    
                    wishlistHtml += `
                        <tr data-wishlist-id="${wishlist.id}" data-product-id="${product.id}">
                            <td>
                                <a href="/product-details/${product.slug}">
                                    <img src="${window.origin}/storage/uploads/pro_image/${product.pro_image}" alt="${product.name}" class="img-thumbnail" style="max-width: 100px;">
                                </a>
                            </td>
                            <td>
                                <a href="/product-details/${product.slug}">${product.name}</a>
                            </td>
                            <td>In Stock</td>
                            <td>
                                <div class="price">
                                    ${product.discount_price ? 
                                        `<small><del>${product.sale_price}৳</del></small> <strong>${product.discount_price}৳</strong>` : 
                                        `<strong>${product.sale_price}৳</strong>`
                                    }
                                </div>
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary add-to-cart-btn" data-product-id="${product.id}">
                                    <i class="fa fa-shopping-cart"></i>
                                </button>
                                <button type="button" class="btn btn-danger remove-wishlist-btn" data-wishlist-id="${wishlist.id}" data-product-id="${product.id}">
                                    <i class="fa fa-times"></i>
                                </button>
                            </td>
                        </tr>`;
                });
                
                wishlistHtml += `
                        </tbody>
                    </table>`;
                    
                $('#wishlist-container').html(wishlistHtml);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching wishlist data:', error);
                $('#wishlist-container').html('<div class="alert alert-danger">Error loading wishlist. Please try again later.</div>');
            }
        });
    }

    fetchWishlistData();

    function removeFromWishlist(wishlistId, productId) {
        $.ajax({
            url: `/add-wishlist/${productId}`,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Remove the item from the wishlist table
                $(`tr[data-wishlist-id="${wishlistId}"]`).fadeOut(300, function() {
                    $(this).remove();
                    
                    // Update wishlist count
                    updateWishlistCount();
                    
                    // If no more items in wishlist
                    if ($('#wishlist-container tbody tr').length === 0) {
                        $('#wishlist-container').html('<div class="alert alert-info">Your wishlist is empty.</div>');
                    }
                    
                    // Remove active class from all wishlist buttons for this product
                    $(`.wishlist-btn[data-product-id="${productId}"]`).removeClass('active active-action');
                    
                    // Show success message
                    showNotification('Product removed from wishlist', 'success');
                });
            },
            error: function(xhr, status, error) {
                console.error('Error removing from wishlist:', error);
                showNotification('An error occurred. Please try again.', 'error');
            }
        });
    }
    
    // Event handler for wishlist page remove button
    $(document).on('click', '.remove-wishlist-btn', function() {
        const wishlistId = $(this).data('wishlist-id');
        const productId = $(this).data('product-id');
        removeFromWishlist(wishlistId, productId);
    });


    // search result


    // Compare

    function toggleCompare(productId) {
        $.ajax({
            url: `/add-compare/${productId}`,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Toggle the wishlist icon appearance
                const compareButton = $(`.compare-btn[data-product-id="${productId}"]`);
                updateCompareCount();
                
                if (compareButton.hasClass('active-action')) {
                    compareButton.removeClass('active-action');
                    showNotification('Product removed from compare', 'success');
                } else {
                    compareButton.addClass('active-action');
                    showNotification('Product added to compare', 'success');
                }
            },
            error: function(xhr, status, error) {
                if (xhr.status === 401) {
                    // Unauthorized - user not logged in
                    showNotification('Please login to add items to compare', 'error');
                } else {
                    showNotification('An error occurred. Please try again.', 'error');
                    console.error('Error toggling compare:', error);
                }
            }
        });
    }

    $(document).on('click', '.compare-btn', function(e) {
        e.preventDefault();
        const productId = $(this).data('product-id');
        toggleCompare(productId);
    });

    function updateCompareCount() {
        $.ajax({
            url: '/compare-count',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    // Update cart count in UI elements with the class 'cart-count'
                    $('#nav-compare-count').text(data.compareCount);
                    if(data.compareCount > 0) {
                        $('#nav-compare-count').addClass('show-count');
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching cart count:', error);
            }
        });
    }

    updateCompareCount();
    

    
    // Function to show notification
    function showNotification(message, type) {
        const notification = $('<div>', {
            class: 'notification ' + type,
            text: message
        });
        
        $('body').append(notification);
        
        // Add active class after a brief delay to trigger animation
        setTimeout(function() {
            notification.addClass('active');
        }, 10);
        
        // Remove notification after 3 seconds
        setTimeout(function() {
            notification.removeClass('active');
            setTimeout(function() {
                notification.remove();
            }, 300);
        }, 3000);
    }
});