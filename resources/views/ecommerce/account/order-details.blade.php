@extends('ecommerce.layouts.master')
@section('content')
    <div class="breadcrumb-area mb-70">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-wrap">
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Order</li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $order->order_id }}</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="my-account-wrapper pb-20">
        <div class="container-fluid">
            <div class="row">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="mb-2">Order Id - {{$order->order_id}}
                            @if($order->status == 'delivered')
                                <button type="button" class="btn btn-warning ms-5" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">Return
                            Product</button> @endif</h2>

                        <!-- Enhanced Return Product Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold" id="exampleModalLabel">
                    <i class="fas fa-undo-alt me-2"></i>Return Product
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @foreach ($order->items as $item)
                    <div class="d-flex w-100 justify-content-between align-items-center py-2 border-bottom mb-3">
                        <div>
                            <h5 class="mb-1">{{@$item->product->name}}</h5>
                            <p class="text-muted mb-0 small">
                                <span class="fw-semibold">Price:</span> ৳{{@$item->price}}
                            </p>
                        </div>
                        <div class="d-flex align-items-center">
                            <label for="item{{@$item->id}}" class="me-2 text-nowrap">Qty:</label>
                            <input type="number" class="form-control form-control-sm" id="item{{@$item->id}}"
                                name="quantity" placeholder="Qty" min="1" max="{{@$item->quantity}}" 
                                value="0">
                        </div>
                    </div>
                @endforeach
                
                <div class="mb-3">
                    <label for="returnReason" class="form-label fw-semibold">
                        Reason <span class="text-danger">*</span>
                    </label>
                    <textarea id="returnReason" name="reason" class="form-control" rows="4" 
                        placeholder="Please explain why you want to return this product" required></textarea>
                    <div class="form-text small">Your feedback helps us improve our products and services.</div>
                </div>
                
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                    <label class="form-check-label small" for="agreeTerms">
                        I have read and agree to the return policy
                    </label>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Close
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-1"></i>Return
                </button>
            </div>
        </form>
    </div>
</div>

                        <p>{{$order->created_at->format('d/m/Y')}} at
                            {{$order->created_at->setTimezone('Asia/Dhaka')->format('h:i A')}}
                            @if($order->status == 'processing')
                                <span class="badge bg-info ms-3">Processing</span>
                            @elseif($order->status == 'shipping')
                                <span class="badge bg-warning ms-3">Shipping</span>
                            @elseif($order->status == 'delivered')
                                <span class="badge bg-success ms-3">Delivered</span>
                            @elseif($order->status == 'cancelled')
                                <span class="badge bg-danger ms-3">Cancelled</span>
                            @endif
                        </p>
                    </div>

                    <div class="col-md-6 d-flex flex-column align-items-end">
                        <h2 class="mb-2">Estimated Delivery Date</h2>

                        <p>
                            {{ $order->estimated_delivery_date }}
                        </p>
                    </div>
                </div>


                <div class="col-8 mt-3 pe-5">
                    <div class="user-info py-4">
                        <h5>Order Items</h5>

                        @foreach ($order->items as $item)
                            <div class="d-flex w-100 justify-content-between align-items-center py-2">
                                <img src="{{ asset('storage/uploads/pro_image/' . @$item->product->pro_image) }}" alt="">
                                <div>
                                    <h4>{{@$item->product->name}}</h4>
                                    <p>{{@$item->unit_price}}৳</p>
                                </div>
                                <div>
                                    <p>{{@$item->quantity}}</p>
                                </div>
                                <div>
                                    <p>{{@$item->price}}৳</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-bottom py-4">
                        <h5 class="mt-3">Order Summary</h5>

                        <div class="d-flex w-100 justify-content-between align-items-center border-bottom py-2">
                            <h4>Subtotal</h4>
                            <p>{{number_format($order->subtotal, 2, '.', '')}}৳</p>
                        </div>
                        <div class="d-flex w-100 justify-content-between align-items-center border-bottom py-2">
                            <h4>Discount</h4>
                            <p>{{number_format($order->discount, 2, '.', '')}}৳</p>
                        </div>
                        <div class="d-flex w-100 justify-content-between align-items-center py-2">
                            <h4>Total</h4>
                            <p>{{number_format($order->total, 2, '.', '')}}৳</p>
                        </div>
                    </div>
                </div>

                <div class="col-4 mt-3 ps-5">
                    <div class="user-info py-4">
                        <h5>Billing Address</h5>

                        <div class="d-flex align-items-center">
                            <h3 class="pe-3">Name:</h3>
                            <p>{{$order->billing_name}}</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <h3 class="pe-3">Email:</h3>
                            <p>{{$order->billing_email}}</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <h3 class="pe-3">Phone:</h3>
                            <p>{{$order->billing_phone}}</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <h3 class="pe-3">Address:</h3>
                            <p>{{$order->billing_address}}</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <h3 class="pe-3">Zip Code:</h3>
                            <p>{{$order->billing_zip}}</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <h3 class="pe-3">City:</h3>
                            <p>{{$order->billing_city}}</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <h3 class="pe-3">State:</h3>
                            <p>{{$order->billing_state}}</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <h3 class="pe-3">Country:</h3>
                            <p>{{$order->billing_country}}</p>
                        </div>
                    </div>

                    <div class="py-4 border-bottom">
                        <h5>Shipping Address</h5>

                        <div class="d-flex align-items-center">
                            <h3 class="pe-3">Name:</h3>
                            <p>{{$order->shipping_name}}</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <h3 class="pe-3">Email:</h3>
                            <p>{{$order->shipping_email}}</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <h3 class="pe-3">Phone:</h3>
                            <p>{{$order->shipping_phone}}</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <h3 class="pe-3">Address:</h3>
                            <p>{{$order->shipping_address}}</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <h3 class="pe-3">Zip Code:</h3>
                            <p>{{$order->shipping_zip}}</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <h3 class="pe-3">City:</h3>
                            <p>{{$order->shipping_city}}</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <h3 class="pe-3">State:</h3>
                            <p>{{$order->shipping_state}}</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <h3 class="pe-3">Country:</h3>
                            <p>{{$order->shipping_country}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection