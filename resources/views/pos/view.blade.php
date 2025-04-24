@extends('layouts.admin')
@section('page-title')
    {{__('POS Detail')}}
@endsection
@push('script-page')
    <script>
        $(document).on('click', '#shipping', function () {
            var url = $(this).data('url');
            var is_display = $("#shipping").is(":checked");
            $.ajax({
                url: url,
                type: 'get',
                data: {
                    'is_display': is_display,
                },
                success: function (data) {
                    // console.log(data);
                }
            });
        })
    </script>
@endpush

@php
    $settings = Utility::settings();
@endphp
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('pos.report')}}">{{__('POS Summary')}}</a></li>
    <li class="breadcrumb-item">{{ AUth::user()->posNumberFormat($pos->pos_id) }}</li>
@endsection

@section('action-btn')
    <div class="float-end d-flex">

        <form id="delivery_status" action="{{ route('pos.delivery_status', $pos->id) }}" class="me-2" method="post">
            @csrf
            <select onchange="document.getElementById('delivery_status').submit();" name="delivery_status" class="form-select" id="">
                <option value="pending" @if($pos->delivery_status == 'pending') selected @endif>Pending</option>
                <option value="shipping" @if($pos->delivery_status == 'shipping') selected @endif>Shipping</option>
                <option value="delivered" @if($pos->delivery_status == 'delivered') selected @endif>Delivered</option>
                <option value="cancelled" @if($pos->delivery_status == 'cancelled') selected @endif>Cancelled</option>
            </select>
        </form>

        <a href="{{ route('pos.pdf', Crypt::encrypt($pos->id))}}" class="btn btn-primary" target="_blank">{{__('Download')}}</a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mt-2">
                        <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12">
                            <h4>{{__('POS')}}</h4>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12 text-end">
                            <h4 class="invoice-number">{{ Auth::user()->posNumberFormat($pos->pos_id) }}</h4>
                        </div>
                        <div class="col-12">
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5">
                            @if(!empty($customer->billing_name))
                                <small class="font-style">
                                    <strong>{{__('Billed To')}} :</strong><br>
                                    {{!empty($customer->billing_name)?$customer->billing_name:''}}<br>
                                    {{!empty($customer->billing_address)?$customer->billing_address:''}}<br>
                                    {{!empty($customer->billing_city)?$customer->billing_city:'' .', '}}<br>
                                    {{!empty($customer->billing_state)?$customer->billing_state:''.', '}},
                                    {{!empty($customer->billing_zip)?$customer->billing_zip:''}}<br>
                                    {{!empty($customer->billing_country)?$customer->billing_country:''}}<br>
                                    {{!empty($customer->billing_phone)?$customer->billing_phone:''}}<br>


                                @if($settings['vat_gst_number_switch'] == 'on')
                                    <strong>{{__('Tax Number ')}} : </strong>{{!empty($customer->tax_number)?$customer->tax_number:''}}
                                    @endif
                                </small>
                            @endif
                        </div>
                        <div class="col-4">
                            @if(App\Models\Utility::getValByName('shipping_display')=='on')
                                @if(!empty($customer->shipping_name))
                                    <small>
                                        <strong>{{__('Shipped To')}} :</strong><br>
                                        {{!empty($customer->shipping_name)?$customer->shipping_name:''}}<br>
                                        {{!empty($customer->shipping_address)?$customer->shipping_address:''}}<br>
                                        {{!empty($customer->shipping_city)?$customer->shipping_city:'' . ', '}}<br>
                                        {{!empty($customer->shipping_state)?$customer->shipping_state:'' .', '}},
                                        {{!empty($customer->shipping_zip)?$customer->shipping_zip:''}}<br>
                                        {{!empty($customer->shipping_country)?$customer->shipping_country:''}}<br>
                                        {{!empty($customer->shipping_phone)?$customer->shipping_phone:''}}<br>
                                    </small>
                                @endif
                            @endif
                        </div>
                        <div class="col-3">
                            <div class="d-flex align-items-center justify-content-end">
                                <div class="me-4">
                                    <small>
                                        <strong>{{__('Issue Date')}} :</strong>
                                        {{\Auth::user()->dateFormat($pos->purchase_date)}}<br><br>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="table-responsive mt-3">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th class="text-dark" >#</th>
                                        <th class="text-dark">{{__('Items')}}</th>
                                        <th class="text-dark">{{__('Quantity')}}</th>
                                        <th class="text-dark">{{__('Price')}}</th>
                                        <th class="text-dark">{{__('Tax')}}</th>
                                        <th class="text-dark">{{__('Tax Amount')}}</th>
                                        <th class="text-dark">{{__('Total')}}</th>
                                        @if($pos->delivery_status == 'delivered')
                                        <th>Action</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    @php
                                        $totalQuantity=0;
                                        $totalRate=0;
                                        $totalTaxPrice=0;
                                        $totalDiscount=0;
                                        $taxesData=[];
                                    @endphp
                                    @foreach($iteams as $key =>$iteam)
                                        @if(!empty($iteam->tax))
                                            @php
                                                $taxes=App\Models\Utility::tax($iteam->tax);
                                                $totalQuantity+=$iteam->quantity;
                                                $totalRate+=$iteam->price;
                                                $totalDiscount+=$iteam->discount;
                                                foreach($taxes as $taxe){

                                                    $taxDataPrice=App\Models\Utility::taxRate($taxe->rate,$iteam->price,$iteam->quantity);
                                                    if (array_key_exists($taxe->name,$taxesData))
                                                    {
                                                        $taxesData[$taxe->name] = $taxesData[$taxe->name]+$taxDataPrice;
                                                    }
                                                    else
                                                    {
                                                        $taxesData[$taxe->name] = $taxDataPrice;
                                                    }
                                                }
                                            @endphp
                                        @endif
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{!empty($iteam->product)?$iteam->product->name:''}}</td>
                                            @php
                                                $returnedQuantity = App\Models\SaleReturn::where('pos_id', $pos->id)
                                                    ->where('product_id', $iteam->product_id)
                                                    ->sum('quantity');
                                            @endphp
                                            <td>{{$iteam->quantity}} ({{ $returnedQuantity }} returned)</td>
                                            <td>{{\Auth::user()->priceFormat($iteam->price)}}</td>
                                            <td>
                                                @if(!empty($iteam->tax))
                                                    <table>
                                                        @php
                                                            $totalTaxRate = 0;
                                                            $totalTaxPrice = 0;
                                                        @endphp
                                                        @foreach($taxes as $tax)
                                                            @php
                                                                $taxPrice=App\Models\Utility::taxRate($tax->rate,$iteam->price,$iteam->quantity);
                                                                $totalTaxPrice+=$taxPrice;
                                                            @endphp
                                                            <tr>
                                                                <span class="badge bg-primary">{{$tax->name .' ('.$tax->rate .'%)'}}</span> <br>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{\Auth::user()->priceFormat($totalTaxPrice)}}</td>
                                            <td >{{\Auth::user()->priceFormat(($iteam->price*$iteam->quantity) + $totalTaxPrice)}}</td>
                                            @if($pos->delivery_status == 'delivered')
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#return-{{ $iteam->id }}">Make Return</button>
                                                <div class="modal fade" id="return-{{ $iteam->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <form action="{{ route('sale.return.store',[$iteam->product->id, $pos->id]) }}" method="POST"  class="modal-content">
                                                            @csrf
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">{{ !empty($iteam->product) ? $iteam->product->name : '' }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                
                                                                <!-- Product Preview -->
                                                                <div class="mb-3 text-center">
                                                                    <img src="{{ !empty($iteam->product->pro_image) ? asset('storage/uploads/pro_image/' . $iteam->product->pro_image) : 'https://via.placeholder.com/150' }}" 
                                                                         alt="Product Image" 
                                                                         class="img-fluid rounded" 
                                                                         style="max-height: 150px; object-fit: cover;">
                                                                </div>
                                                                <div>
                                                                    <div class="mb-3">
                                                                        <label for="returnQuantity" class="form-label">Quantity to Return</label>
                                                                        <input type="number" class="form-control" id="returnQuantity" name="return_quantity" min="1" max="{{ $iteam->quantity - $returnedQuantity}}" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="returnDetails" class="form-label">Reason</label>
                                                                        <textarea class="form-control" id="returnDetails" name="return_details" rows="4" placeholder="Please provide more information about the return"></textarea>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="returnCondition" class="form-label">Product Condition</label>
                                                                        <select class="form-select" id="returnCondition" name="product_condition" required>
                                                                            <option value="" disabled selected>Select condition</option>
                                                                            <option value="unopened">Unopened</option>
                                                                            <option value="opened">Opened but Unused</option>
                                                                            <option value="used">Used</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-primary">Submit Return</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                            @endif
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <td><b>{{__(' Sub Total')}}</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{\Auth::user()->priceFormat($posPayment['amount'])}}</td>
                                        @if($pos->delivery_status == 'delivered')
                                        <td></td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td><b>{{__('Discount')}}</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{\Auth::user()->priceFormat($posPayment['discount'])}}</td>
                                        @if($pos->delivery_status == 'delivered')
                                        <td></td>
                                        @endif
                                    </tr>
                                    <tr class="pos-header">
                                        <td><b>{{__('Total')}}</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{\Auth::user()->priceFormat($posPayment['discount_amount'])}}</td>
                                        @if($pos->delivery_status == 'delivered')
                                        <td></td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td><b>{{__('Paid')}}</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{\Auth::user()->priceFormat($posPayment['paid'])}}</td>
                                        @if($pos->delivery_status == 'delivered')
                                        <td></td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td><b>{{__('Due')}}</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{\Auth::user()->priceFormat($posPayment['due'])}}</td>
                                        @if($pos->delivery_status == 'delivered')
                                        <td></td>
                                        @endif
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
