@extends('layouts.admin')

@section('page-title')
    {{__('Order Details')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Order Details')}}</li>
    <li class="breadcrumb-item">{{$order->order_id}}</li>
@endsection

@section('action-btn')
    <div class="float-end">
        <form id="updateStatus" action="{{route('erp_order.updateStatus', $order->id)}}" method="POST" class="d-flex align-items-center">
            @csrf
            <select onchange="document.getElementById('updateStatus').submit();" name="status" aria-label="Default select example" class="form-select">
                <option value="processing" @if($order->status == 'processing') selected @endif>Processing</option>
                <option value="shipping" @if($order->status == 'shipping') selected @endif>Shipping</option>
                <option value="delivered" @if($order->status == 'delivered') selected @endif>Delivered</option>
                <option value="cancelled" @if($order->status == 'cancelled') selected @endif>Cancelled</option>
            </select>
        </form>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <div class="card ">
                <div class="card-body employee-detail-body fulls-card">
                    <h5>Order Items</h5>
                    <hr>
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

            <div class="card">
                <div class="card-body employee-detail-body fulls-card">
                    <h5>Estimated Delivery Date</h5>
                    <hr>
                    <form class="row" action="{{route('erp_order.updateEstimatedDate', $order->id)}}" method="POST">
                        @csrf
                        <div class="col-md-10">
                            <input type="date" class="form-control" id="estimated_delivery_date" name="estimated_delivery_date"
                                value="{{ $order->estimated_delivery_date }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Save</button>
                        </div>
                        @if($order->estimated_delivery_date < now() && $order->status != 'delivered' && $order->status != 'cancelled')
                            <div class="col-12 pt-3">
                                <div class="alert alert-danger mt-2" role="alert">
                                    <strong><i class="fas fa-exclamation-circle"></i></strong> Estimated delivery date has expired 
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6">
            <div class="card ">
                <div class="card-body employee-detail-body fulls-card">
                    <h5>Addresses
                    </h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Billing Address</h4>

                            <div class="info text-sm font-style">
                                <strong class="font-bold">Name :</strong>
                                <span>{{ $order->billing_name }}</span>
                            </div>

                            <div class="info text-sm font-style">
                                <strong class="font-bold">Email :</strong>
                                <span>{{ $order->billing_email }}</span>
                            </div>

                            <div class="info text-sm">
                                <strong class="font-bold">Phone :</strong>
                                <span>{{ $order->billing_phone }}</span>
                            </div>

                            <div class="info text-sm">
                                <strong class="font-bold">Address :</strong>
                                <span>{{ $order->billing_address }}</span>
                            </div>

                            <div class="info text-sm">
                                <strong class="font-bold">City :</strong>
                                <span>{{ $order->billing_city }}</span>
                            </div>

                            <div class="info text-sm">
                                <strong class="font-bold">State :</strong>
                                <span>{{ $order->billing_state }}</span>
                            </div>

                            <div class="info text-sm">
                                <strong class="font-bold">Country :</strong>
                                <span>{{ $order->billing_country }}</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h4>Shipping Address</h4>

                            <div class="info text-sm font-style">
                                <strong class="font-bold">Name :</strong>
                                <span>{{ $order->shipping_name }}</span>
                            </div>

                            <div class="info text-sm font-style">
                                <strong class="font-bold">Email :</strong>
                                <span>{{ $order->shipping_email }}</span>
                            </div>

                            <div class="info text-sm">
                                <strong class="font-bold">Phone :</strong>
                                <span>{{ $order->shipping_phone }}</span>
                            </div>

                            <div class="info text-sm">
                                <strong class="font-bold">Address :</strong>
                                <span>{{ $order->shipping_address }}</span>
                            </div>

                            <div class="info text-sm">
                                <strong class="font-bold">City :</strong>
                                <span>{{ $order->shipping_city }}</span>
                            </div>

                            <div class="info text-sm">
                                <strong class="font-bold">State :</strong>
                                <span>{{ $order->shipping_state }}</span>
                            </div>

                            <div class="info text-sm">
                                <strong class="font-bold">Country :</strong>
                                <span>{{ $order->shipping_country }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card ">
                <div class="card-body employee-detail-body fulls-card">
                    <h5>Order Note</h5>
                    <hr>
                    <p>{{$order->order_note}}</p>
                </div>
            </div>
        </div>
    </div>
@endsection