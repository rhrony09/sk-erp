@extends('ecommerce.layouts.master')
@section('content')
<div class="breadcrumb-area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-wrap">
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Cart</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="shopping-cart-wrapper pb-70">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <main id="primary" class="site-main">
                    <div class="shopping-cart">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="section-title">
                                    <h3>Shopping Cart</h3>
                                </div>
                                <div id="cart-container"></div>
                                
                                <div class="cart-amount-wrapper">
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-4 offset-md-8">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <td><strong>Sub-Total:</strong></td>
                                                        <td>
                                                            {{ $carts->sum(function($cart) {
                                                                $price = $cart->product->discount_price ?? $cart->product->sale_price;
                                                                return $cart->quantity * $price;
                                                            }) }}৳
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Total:</strong></td>
                                                        <td><span class="color-primary">{{ $carts->sum(function($cart) {
                                                            $price = $cart->product->discount_price ?? $cart->product->sale_price;
                                                            return $cart->quantity * $price;
                                                        }) }}৳</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="cart-accordion-wrapper mt-full mt-40">
                                    <h3>What would you like to do next?</h3>
                                    <p>Choose if you have a discount code or reward points you want to use or would like to estimate your delivery cost.</p>
                                    <div id="cart_accordion" class="accordion mt-4">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingCoupon">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCoupon" aria-expanded="true" aria-controls="collapseCoupon">
                                                    Use Coupon Code
                                                </button>
                                            </h2>
                                            <div id="collapseCoupon" class="accordion-collapse collapse show" aria-labelledby="headingCoupon" data-bs-parent="#cart_accordion">
                                                <div class="accordion-body">
                                                    <div class="input-group row">
                                                        <label class="col-12 col-sm-12 col-md-3" for="input-coupon">Enter your coupon here</label>
                                                        <div class="col-12 col-sm-12 col-md-9">
                                                            <div class="input-group">
                                                            <input type="text" name="coupon" value="" placeholder="Enter your coupon here" id="input-coupon" class="form-control">
                                                            <input type="button" value="Apply Coupon" id="button-coupon" class="btn btn-secondary cart-pg">
                                                        </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="cart-button-wrapper d-flex justify-content-between mt-4">
                                    <a href="shop-grid-left-sidebar.html" class="btn btn-secondary">Continue Shopping</a>
                                    <a href="{{ route('ecommerce.checkoutPage') }}" class="btn btn-secondary dark align-self-end">Checkout</a>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end of shopping-cart -->
                </main> <!-- end of #primary -->
            </div>
        </div> <!-- end of row -->
    </div> <!-- end of container -->
</div>
@endsection