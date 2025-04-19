@extends('ecommerce.layouts.master')
@section('content')
    <div class="breadcrumb-area mb-60">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-wrap">
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="checkout-wrapper pt-10 pb-70">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <main id="primary" class="site-main">
                        <div class="user-actions-area">
                            <div class="row">
                                <div class="col-12">

                                    <div class="user-actions user-coupon">
                                        <h3>Have A Coupon? <span id="show_coupon">Click Here To Enter Your Code.</span></h3>
                                        <div id="checkout_coupon" class="display-content">
                                            <div class="coupon-info">
                                                <form action="#">
                                                    <div class="row">
                                                        <div class="col-12 col-sm-12 col-md-6">
                                                            <div class="input-group">
                                                                <input type="text" name="coupon" value=""
                                                                    placeholder="Coupon Code" id="input-coupon"
                                                                    class="form-control me-3" required="">
                                                                <input type="submit" value="Apply Coupon" id="button-coupon"
                                                                    class="btn btn-secondary">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div> <!-- end of user-actions -->
                                </div>
                            </div> <!-- end of row -->
                        </div> <!-- end of user-actions -->

                        <div class="checkout-area">
                            <form action="{{ route('ecommerce.makeOrder') }}" method="post" class="row">
                                @csrf
                                <div class="col-12 col-md-6 col-lg-7">
                                    <div class="checkout-form">
                                        <div class="section-title left-aligned">
                                            <h3>Billing Details</h3>
                                        </div>

                                        <div>
                                            <div class="row g-2 mb-3">
                                                <div class="mb-3 col-12 col-sm-12 col-md-6">
                                                    <label for="first_name">First Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="first_name" name="billing_first_name" required="">
                                                </div>
                                                <div class="mb-3 col-12 col-sm-12 col-md-6">
                                                    <label for="last_name">Last Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="last_name" name="billing_last_name" required="">
                                                </div>
                                            </div>
                                            <div class="row g-2 mb-3">
                                                <div class="mb-3 col-12 col-sm-12 col-md-6">
                                                    <label for="email_address">Email Address <span
                                                            class="text-danger">*</span></label>
                                                    <input type="email" class="form-control" id="email_address" name="billing_email" required="">
                                                </div>

                                                
                                                <div class="mb-3 col-12 col-sm-12 col-md-6">
                                                    <label for="tel_number">telephone</label>
                                                    <input type="tel" class="form-control" id="tel_number" name="billing_tel_number">
                                                </div>
                                            </div>
                                            <div class="row g-2 mb-3">
                                                <div class="mb-3 col-12">
                                                    <label for="p_address">Address <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="billing_address" name="billing_address" required="">
                                                </div>
                                            </div>
                                            <div class="row g-2 mb-3">
                                                <div class="mb-3 col-12 col-sm-12 col-md-6">
                                                    <label for="city_name">City <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="billing_city_name" name="billing_city_name" required="">
                                                </div>
                                                <div class="mb-3 col-12 col-sm-12 col-md-6">
                                                    <label for="province_name">Province <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="billing_province_name" name="billing_province_name" required="">
                                                </div>
                                            </div>
                                            <div class="row g-2 mb-3">
                                                <div class="mb-3 col-12 col-sm-12 col-md-6">
                                                    <label for="zip_code">Zip Code <span
                                                            class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" id="billing_zip_code" name="billing_zip_code" required="">
                                                </div>
                                                <div class="mb-3 col-12 col-sm-12 col-md-6">
                                                    <label for="country_name" class="d-block">Country <span
                                                            class="text-danger">*</span></label>
                                                    <select name="billing_country_id" id="country_name"
                                                        class="form-control nice-select" required="" style="display: none;" name="billing_country_id">
                                                        <option value=""> --- Select --- </option>
                                                        <option value="bangladesh" selected>Bangladesh</option>
                                                    </select>
                                                    <div class="nice-select form-control" tabindex="0"><span
                                                            class="current"> Bangladesh </span>
                                                        <ul class="list">
                                                            <li data-value="" class="option"> --- Select --- </li>
                                                            <li data-value="bangladesh" class="option selected">Bangladesh</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-12">
                                                    <div class="form-check mb-3">
                                                        <div class="custom-checkbox">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="different_shipping">
                                                            <span class="checkmark"></span>
                                                            <label class="form-check-label" for="different_shipping"
                                                                id="different_shipping_address">Ship to a different
                                                                address?</label>
                                                        </div>
                                                    </div>
                                                    <div class="ship-box-info mt-4 mb-3">
                                                        <div class="row mb-3">
                                                            <div class="mb-3 col-12 col-sm-12 col-md-6">
                                                                <label for="f_name">First Name <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" id="f_name" name="shipping_first_name">
                                                            </div>
                                                            <div class="mb-3 col-12 col-sm-12 col-md-6">
                                                                <label for="l_name">Last Name <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" id="l_name" name="shipping_last_name">
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="mb-3 col-12 col-sm-12 col-md-6">
                                                                <label for="com_name">Company</label>
                                                                <input type="text" class="form-control" id="com_name" name="shipping_company_name">
                                                            </div>
                                                            <div class="mb-3 col-12 col-sm-12 col-md-6">
                                                                <label for="email_add">Email Address <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="email" class="form-control" id="email_add" name="shipping_email_address">
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="mb-3 col-12">
                                                                <label for="coun_name" class="d-block">Country <span
                                                                        class="text-danger">*</span></label>
                                                                <select name="country_id" id="coun_name"
                                                                    class="form-control nice-select"
                                                                    style="display: none;" name="shipping_country_id">
                                                                    <option value=""> --- Please Select --- </option>
                                                                    <option value="bangladesh">Bangladesh</option>
                                                                </select>
                                                                <div class="nice-select form-control" tabindex="0"><span
                                                                        class="current"> Bangladesh </span>
                                                                    <ul class="list">
                                                                        <li data-value="" class="option"> ---
                                                                            Please Select --- </li>
                                                                        <li data-value="bangladesh" class="option selected">Bangladesh</li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="mb-3 col-12">
                                                                <label for="street_add">Street Address</label>
                                                                <input type="text" class="form-control" id="street_add" name="shipping_address">
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="mb-3 col-12">
                                                                <label for="cit_name">City <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" id="cit_name"
                                                                 name="shipping_city_name">
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="mb-3 col-12">
                                                                <label for="prov_name">Province <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" id="prov_name"
                                                                     name="shipping_province_name">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="mb-3 col-12">
                                                                <label for="zp_code">Zip Code <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="number" class="form-control" id="zp_code"
                                                                     name="shipping_zip_code">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-12">
                                                    <label for="order_notes">Order Notes</label>
                                                    <textarea class="form-control" id="order_notes"
                                                        placeholder="Notes about your order, e.g. special notes for delivery." name="order_note"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- end of checkout-form -->
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-5">
                                    <div class="order-summary">
                                        <div class="section-title left-aligned">
                                            <h3>Your Order</h3>
                                        </div>
                                        <div class="product-container">
                                            @foreach ($carts as $cart)
                                                <div class="product-list">
                                                    <div class="product-inner d-flex align-items-center">
                                                        <div class="product-image me-4 me-sm-5 me-md-4 me-lg-5">
                                                            <a href="{{ route('ecommerce.singleProduct', $cart->product->slug) }}">
                                                                <img src={{ asset('/storage/uploads/pro_image/'.$cart->product->pro_image) }}
                                                                    alt="Compete Track Tote" title="Compete Track Tote">
                                                            </a>
                                                        </div>
                                                        <div class="media-body">
                                                            <h5>{{ $cart->product->name }}</h5>
                                                            <p class="product-quantity">Quantity: {{ $cart->quantity }}</p>
                                                            <p class="product-final-price">{{$cart->quantity * ($cart->product->discount_price ?? $cart->product->sale_price)}}৳</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div> <!-- end of product-container -->
                                        <div class="order-review">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <tbody>
                                                        <tr>
                                                            <th>Subtotal</th>
                                                            <td class="text-center">{{ $carts->sum(function($cart) {
                                                                $price = $cart->product->discount_price ?? $cart->product->sale_price;
                                                                return $cart->quantity * $price;
                                                            }) }}৳</td>
                                                        </tr>
                                                        <tr class="order-total">
                                                            <th>Total</th>
                                                            <td class="text-center"><strong>{{ $carts->sum(function($cart) {
                                                                $price = $cart->product->discount_price ?? $cart->product->sale_price;
                                                                return $cart->quantity * $price;
                                                            }) }}৳</strong></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="checkout-payment">
                                            <div>
                                                <div class="form-row">
                                                    {{-- <div class="custom-radio">
                                                        <input class="form-check-input" type="radio" name="payment"
                                                            id="check_payment" value="check">
                                                        <span class="checkmark"></span>
                                                        <label class="form-check-label" for="check_payment">Check
                                                            Payments</label>

                                                        <div class="payment-info" id="check_pay">
                                                            <p>Please send a check to Store Name, Store Street, Store Town,
                                                                Store State / County, Store Postcode.</p>
                                                        </div>
                                                    </div> --}}
                                                    <div class="custom-radio">
                                                        <input class="form-check-input" type="radio" name="payment"
                                                            id="cash_delivery_payment" value="cash" checked="">
                                                        <span class="checkmark"></span>
                                                        <label class="form-check-label" for="cash_delivery_payment">Cash on
                                                            Delivery</label>

                                                        <div class="payment-info" id="cash_pay">
                                                            <p>Pay with cash upon delivery.</p>
                                                        </div>
                                                    </div>
                                                    {{-- <div class="custom-radio">
                                                        <input class="form-check-input" type="radio" name="payment"
                                                            id="paypal_payment" value="paypal">
                                                        <span class="checkmark"></span>
                                                        <label class="form-check-label" for="paypal_payment">PayPal Express
                                                            Checkout</label>

                                                        <div class="payment-info" id="paypal_pay">
                                                            <p>Pay via PayPal. You can pay with your credit card if you
                                                                don’t have a PayPal account.</p>
                                                        </div>
                                                    </div> --}}
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-check">
                                                        <div class="custom-checkbox">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="terms_acceptance" required="">
                                                            <span class="checkmark"></span>
                                                            <label class="form-check-label" for="terms_acceptance">I agree
                                                                to the <a href="#">terms of service</a> and will adhere to
                                                                them unconditionally.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-row justify-content-end">
                                                    <input type="submit" class="btn btn-secondary dark"
                                                        value="Continue to Payment">
                                                </div>
                                            </div>
                                        </div> <!-- end of checkout-payment -->
                                    </div> <!-- end of order-summary -->
                                </div>
                            </form>
                        </div> <!-- end of checkout-area -->
                    </main> <!-- end of #primary -->
                </div>
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div>
@endsection