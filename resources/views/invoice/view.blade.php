@extends('layouts.admin')
@section('page-title')
    {{__('Invoice Detail')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('invoice.index')}}">{{__('Invoice')}}</a></li>
    <li class="breadcrumb-item">{{ AUth::user()->invoiceNumberFormat($invoice->invoice_id) }}</li>
@endsection
@php
    $settings = Utility::settings();
@endphp
@push('css-page')
    <style>
        #card-element {
            border: 1px solid #a3afbb !important;
            border-radius: 10px !important;
            padding: 10px !important;
        }
    </style>
@endpush
@push('script-page')
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script type="text/javascript">
        @if($invoice->getDue() > 0  && !empty($company_payment_setting) &&  $company_payment_setting['is_stripe_enabled'] == 'on' && !empty($company_payment_setting['stripe_key']) && !empty($company_payment_setting['stripe_secret']))

        var stripe = Stripe('{{ $company_payment_setting['stripe_key'] }}');
        var elements = stripe.elements();

        // Custom styling can be passed to options when creating an Element.
        var style = {
            base: {
                // Add your base input styles here. For example:
                fontSize: '14px',
                color: '#32325d',
            },
        };

        // Create an instance of the card Element.
        var card = elements.create('card', {style: style});

        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');

        // Create a token or display an error when the form is submitted.
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            stripe.createToken(card).then(function (result) {
                if (result.error) {
                    $("#card-errors").html(result.error.message);
                    show_toastr('error', result.error.message, 'error');
                } else {
                    // Send the token to your server.
                    stripeTokenHandler(result.token);
                }
            });
        });

        function stripeTokenHandler(token) {
            // Insert the token ID into the form so it gets submitted to the server
            var form = document.getElementById('payment-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);

            // Submit the form
            form.submit();
        }

        @endif

        @if(isset($company_payment_setting['paystack_public_key']))
        $(document).on("click", "#pay_with_paystack", function () {

            $('#paystack-payment-form').ajaxForm(function (res) {
                var amount = res.total_price;
                if (res.flag == 1) {
                    var paystack_callback = "{{ url('/invoice/paystack') }}";

                    var handler = PaystackPop.setup({
                        key: '{{ $company_payment_setting['paystack_public_key']  }}',
                        email: res.email,
                        amount: res.total_price * 100,
                        currency: res.currency,
                        ref: 'pay_ref_id' + Math.floor((Math.random() * 1000000000) +
                            1
                        ), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
                        metadata: {
                            custom_fields: [{
                                display_name: "Email",
                                variable_name: "email",
                                value: res.email,
                            }]
                        },

                        callback: function (response) {

                            window.location.href = paystack_callback + '/' + response.reference + '/' + '{{encrypt($invoice->id)}}' + '?amount=' + amount;
                        },
                        onClose: function () {
                            alert('window closed');
                        }
                    });
                    handler.openIframe();
                } else if (res.flag == 2) {
                    toastrs('Error', res.msg, 'msg');
                } else {
                    toastrs('Error', res.message, 'msg');
                }

            }).submit();
        });
        @endif

        @if(isset($company_payment_setting['flutterwave_public_key']))
        //    Flaterwave Payment
        $(document).on("click", "#pay_with_flaterwave", function () {
            $('#flaterwave-payment-form').ajaxForm(function (res) {

                if (res.flag == 1) {
                    var amount = res.total_price;
                    var API_publicKey = '{{ $company_payment_setting['flutterwave_public_key']  }}';
                    var nowTim = "{{ date('d-m-Y-h-i-a') }}";
                    var flutter_callback = "{{ url('/invoice/flaterwave') }}";
                    var x = getpaidSetup({
                        PBFPubKey: API_publicKey,
                        customer_email: '{{Auth::user()->email}}',
                        amount: res.total_price,
                        currency: '{{App\Models\Utility::getValByName('site_currency')}}',
                        txref: nowTim + '__' + Math.floor((Math.random() * 1000000000)) + 'fluttpay_online-' + '{{ date('Y-m-d') }}' + '?amount=' + amount,
                        meta: [{
                            metaname: "payment_id",
                            metavalue: "id"
                        }],
                        onclose: function () {
                        },
                        callback: function (response) {
                            var txref = response.tx.txRef;
                            if (
                                response.tx.chargeResponseCode == "00" ||
                                response.tx.chargeResponseCode == "0"
                            ) {
                                window.location.href = flutter_callback + '/' + txref + '/' + '{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}}';
                            } else {
                                // redirect to a failure page.
                            }
                            x.close(); // use this to close the modal immediately after payment.
                        }
                    });
                } else if (res.flag == 2) {
                    toastrs('Error', res.msg, 'msg');
                } else {
                    toastrs('Error', data.message, 'msg');
                }

            }).submit();
        });
        @endif

        @if(isset($company_payment_setting['razorpay_public_key']))
        // Razorpay Payment
        $(document).on("click", "#pay_with_razorpay", function () {
            $('#razorpay-payment-form').ajaxForm(function (res) {
                if (res.flag == 1) {
                    var amount = res.total_price;
                    var razorPay_callback = '{{url('/invoice/razorpay')}}';
                    var totalAmount = res.total_price * 100;
                    var coupon_id = res.coupon;
                    var options = {
                        "key": "{{ $company_payment_setting['razorpay_public_key']  }}", // your Razorpay Key Id
                        "amount": totalAmount,
                        "name": 'Plan',
                        "currency": '{{App\Models\Utility::getValByName('site_currency')}}',
                        "description": "",
                        "handler": function (response) {
                            window.location.href = razorPay_callback + '/' + response.razorpay_payment_id + '/' + '{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}}' + '?amount=' + amount;
                        },
                        "theme": {
                            "color": "#528FF0"
                        }
                    };
                    var rzp1 = new Razorpay(options);
                    rzp1.open();
                } else if (res.flag == 2) {
                    toastrs('Error', res.msg, 'msg');
                } else {
                    toastrs('Error', data.message, 'msg');
                }

            }).submit();
        });
        @endif


        $('.cp_link').on('click', function () {
            var value = $(this).attr('data-link');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(value).select();
            document.execCommand("copy");
            $temp.remove();
            show_toastr('success', '{{__('Link Copy on Clipboard')}}', 'success')
        });
    </script>
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


@section('content')

    @can('send invoice')
        @if($invoice->status!=4)
            <div class="row">
                <div class="col-12">
                    <div class="card ">
                    <div class="card-body">
                        <div class="row timeline-wrapper">
                            <div class="col-md-6 create_invoice">
                                <div class="timeline-icons"><span class="timeline-dots"></span>
                                    <i class="ti ti-plus text-primary"></i>
                                </div>
                                <h6 class="text-primary my-3">{{__('Create Invoice')}}</h6>
                                <p class="text-muted text-sm mb-3"><i class="ti ti-clock mr-2"></i>{{__('Created on ')}}{{\Auth::user()->dateFormat($invoice->issue_date)}}</p>
                                @can('edit invoice')
                                    <a href="{{ route('invoice.edit',\Crypt::encrypt($invoice->id)) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-original-title="{{__('Edit')}}"><i class="ti ti-pencil mr-2"></i>{{__('Edit')}}</a>
                                @endcan
                            </div>
                            <!-- <div class="col-md-6 col-lg-4 col-xl-4 send_invoice">
                                <div class="timeline-icons"><span class="timeline-dots"></span>
                                    <i class="ti ti-mail text-warning"></i>
                                </div>
                                <h6 class="text-warning my-3">{{__('Send Invoice')}}</h6>
                                <p class="text-muted text-sm mb-3">
                                    @if($invoice->status!=0)
                                        <i class="ti ti-clock mr-2"></i>{{__('Sent on')}} {{\Auth::user()->dateFormat($invoice->send_date)}}
                                    @else
                                        @can('send invoice')
                                            <small>{{__('Status')}} : {{__('Not Sent')}}</small>
                                        @endcan
                                    @endif
                                </p>

                                @if($invoice->status==0 && $invoice->customer_id != 0)
                                    @can('send bill')
                                        <a href="{{ route('invoice.sent',$invoice->id) }}" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" data-original-title="{{__('Mark Sent')}}"><i class="ti ti-send mr-2"></i>{{__('Send')}}</a>
                                    @endcan
                                @endif
                            </div> -->
                            <div class="col-md-6 create_invoice">
                                <div class="timeline-icons"><span class="timeline-dots"></span>
                                    <i class="ti ti-report-money text-info"></i>
                                </div>
                                <h6 class="text-info my-3">{{__('Get Paid')}}</h6>
                                <p class="text-muted text-sm mb-3">{{__('Status')}} : {{__('Awaiting payment')}}</p>
                                @can('create payment invoice')
                                        <a href="#" data-url="{{ route('invoice.payment',$invoice->id) }}" data-ajax-popup="true" data-title="{{__('Add Payment')}}" class="btn btn-sm btn-info" data-original-title="{{__('Add Payment')}}"><i class="ti ti-report-money mr-2"></i>{{__('Recieve')}}</a> <br>
                                    @endcan

                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        @endif
    @endcan

    @if(\Auth::user()->type=='company')
        @if($invoice->status!=0)
            <div class="row justify-content-between align-items-center mb-3">
                <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">
                    @if(!empty($invoicePayment))
                        <div class="all-button-box mx-2 mr-2">
                            <a href="#" class="btn btn-sm btn-primary" data-url="{{ route('invoice.credit.note',$invoice->id) }}" data-ajax-popup="true" data-title="{{__('Add Credit Note')}}">
                                {{__('Add Credit Note')}}
                            </a>
                        </div>
                    @endif
                    @if($invoice->status!=4)
                        <div class="all-button-box mr-2">
                            <a href="{{ route('invoice.payment.reminder',$invoice->id)}}" class="btn btn-sm btn-primary me-2">{{__('Receipt Reminder')}}</a>
                        </div>
                    @endif
                    <div class="all-button-box mr-2">
                        <a href="{{ route('invoice.resent',$invoice->id)}}" class="btn btn-sm btn-primary me-2">{{__('Resend Invoice')}}</a>
                    </div>
                    <div class="all-button-box mr-2">
                        <a href="{{ route('invoice.pdf', ['requestType'=>'download', 'id' => Crypt::encrypt($invoice->id)]) }}" target="_blank" class="btn btn-sm btn-primary me-2">{{__('Download')}}</a>
                    </div>
                    <div class="all-button-box">
                        <a href="{{ route('invoice.pdf', ['requestType'=>'print', 'id' => Crypt::encrypt($invoice->id)]) }}" target="_blank" class="btn btn-sm btn-primary">{{__('Print')}}</a>
                    </div>
                </div>
            </div>
        @endif
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="invoice">
                        <div class="invoice-print">
                            <div class="row invoice-title mt-2">
                                <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12">
                                    <h4>{{__('Invoice')}}</h4>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12 text-end">
                                    <h4 class="invoice-number">{{ AUth::user()->invoiceNumberFormat($invoice->invoice_id) }}</h4>
                                </div>
                                <div class="col-12">
                                    <hr>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col text-end">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <div class="me-4">
                                            <small>
                                                <strong>{{__('Issue Date')}} :</strong><br>
                                                {{\Auth::user()->dateFormat($invoice->issue_date)}}<br><br>
                                            </small>
                                        </div>
                                        <div>
                                            <small>
                                                <strong>{{__('Due Date')}} :</strong><br>
                                                {{\Auth::user()->dateFormat($invoice->due_date)}}<br><br>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                @if(!empty($customer->billing_name))
                                    <div class="col">
                                        <small class="font-style">
                                            <strong>{{__('Billed To')}} :</strong><br>
                                            @if(!empty($invoiceAddress->billing_address_line_1))
                                                {{!empty($invoiceAddress->billing_address_line_1)?$invoiceAddress->billing_address_line_1:''}}<br>
                                                {{!empty($invoiceAddress->billing_address_line_2)?$invoiceAddress->billing_address_line_2:''}}<br>
                                                {{!empty($invoiceAddress->billing_city)?$invoiceAddress->billing_city:'' .', '}}<br>
                                                {{!empty($invoiceAddress->billing_state)?$invoiceAddress->billing_state:'',', '}},
                                                {{!empty($invoiceAddress->billing_zip_code)?$invoiceAddress->billing_zip_code:''}}<br>
                                                @if($settings['vat_gst_number_switch'] == 'on')
                                                    <strong>{{__('Tax Number ')}} : </strong>{{!empty($customer->tax_number)?$customer->tax_number:''}}
                                                @endif
                                            @else
                                                -
                                            @endif

                                        </small>
                                    </div>
                                @endif
                                @if(App\Models\Utility::getValByName('shipping_display')=='on')
                                    <div class="col ">
                                        <small>
                                            <strong>{{__('Shipped To')}} :</strong><br>
                                            @if(!empty($invoiceAddress->shipping_address_line_1))
                                                {{!empty($invoiceAddress->shipping_address_line_1)?$invoiceAddress->shipping_address_line_1:''}}<br>
                                                {{!empty($invoiceAddress->shipping_address_line_2)?$invoiceAddress->shipping_address_line_2:''}}<br>
                                                {{!empty($invoiceAddress->shipping_state)?$invoiceAddress->shipping_state:'' .', '}},
                                                {{!empty($invoiceAddress->shipping_zip_code)?$invoiceAddress->shipping_zip_code:''}}<br>
                                                {{!empty($invoiceAddress->shipping_phone)?$invoiceAddress->shipping_phone:''}}<br>
                                            @else
                                                -
                                            @endif
                                        </small>
                                    </div>
                                @endif
                                <div class="col">
                                    <div class="float-end mt-3">
                                        {!! DNS2D::getBarcodeHTML(route('invoice.link.copy',\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)), "QRCODE",2,2) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <small>
                                        <strong>{{__('Status')}} :</strong><br>
                                        @if($invoice->status == 0)
                                            <span class="badge bg-primary">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 1)
                                            <span class="badge bg-warning">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 2)
                                            <span class="badge bg-danger">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 3)
                                            <span class="badge bg-info">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 4)
                                            <span class="badge bg-success">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @endif
                                    </small>
                                </div>
                                <div class="col">
                                    <small>
                                        <strong>{{__('Note')}} :</strong><br>
                                        <p>{{!empty($invoice->note) ? $invoice->note : __('No additional notes')}}</p>
                                    </small>
                                </div>
                                <div class="col">
                                    <small>
                                        <strong>{{__('Sales Person')}} :</strong><br>
                                        <p>{{!empty($invoice->salesman) ? $invoice->salesman->name : __('No sales person assigned')}}</p>
                                    </small>
                                </div>

                                @if(!empty($customFields) && count($invoice->customField)>0)
                                    @foreach($customFields as $field)
                                        <div class="col text-md-right">
                                            <small>
                                                <strong>{{$field->name}} :</strong><br>
                                                {{!empty($invoice->customField)?$invoice->customField[$field->id]:'-'}}
                                                <br><br>
                                            </small>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="font-weight-bold">{{__('Product Summary')}}</div>
                                    <small>{{__('All items here cannot be deleted.')}}</small>
                                    <div class="table-responsive mt-2">
                                        <table class="table mb-0 table-striped">
                                            @if($iteams->count() > 0)
                                            <tr>
                                                <th data-width="40" class="text-dark">#</th>
                                                <th class="text-dark">{{__('Product')}}</th>
                                                <th class="text-dark">{{__('Quantity')}}</th>
                                                <th class="text-dark">{{__('Rate')}}</th>
                                                <th class="text-dark">{{__('Discount')}}</th>
                                                <th class="text-dark">{{__('Tax')}}</th>
                                                <th class="text-dark">{{__('Description')}}</th>
                                                <th class="text-end text-dark" width="12%">{{__('Price')}}<br>
                                                    <small class="text-danger font-weight-bold">{{__('after tax & discount')}}</small>
                                                </th>
                                            </tr>
                                            @endif
                                            @php
                                                $totalQuantity=0;
                                                $totalRate=0;
                                                $totalTaxPrice=0;
                                                $totalDiscount=0;
                                                $taxesData=[];
                                            @endphp
                                            @foreach($iteams as $key =>$iteam)
                                                <tr>
                                                    <td>{{$key+1}}</td>
                                                    @php
                                                        $productName = $iteam->product;
                                                        $totalRate += $iteam->price;
                                                        $totalQuantity += $iteam->quantity;
                                                        $totalDiscount += $iteam->discount;
                                                    @endphp
                                                    <td>{{ !empty($productName) ? $productName->name : '' }}</td>
                                                    <td>{{ $iteam->quantity . ' (' . $productName->unit->name . ')' }}</td>
                                                    <td>{{\Auth::user()->priceFormat($iteam->price)}}</td>
                                                    <td>{{\Auth::user()->priceFormat($iteam->discount)}}</td>
                                                    <td>
                                                        @if (!empty($iteam->tax))
                                                            <table>
                                                                @php
                                                                    $itemTaxes = [];
                                                                    $getTaxData = Utility::getTaxData();

                                                                    if (!empty($iteam->tax)) {
                                                                        foreach (explode(',', $iteam->tax) as $tax) {
                                                                            $taxPrice = \Utility::taxRate($getTaxData[$tax]['rate'], $iteam->price, $iteam->quantity);
                                                                            $totalTaxPrice += $taxPrice;
                                                                            $itemTax['name'] = $getTaxData[$tax]['name'];
                                                                            $itemTax['rate'] = $getTaxData[$tax]['rate'] . '%';
                                                                            $itemTax['price'] = \Auth::user()->priceFormat($taxPrice);

                                                                            $itemTaxes[] = $itemTax;
                                                                            if (array_key_exists($getTaxData[$tax]['name'], $taxesData)) {
                                                                                $taxesData[$getTaxData[$tax]['name']] = $taxesData[$getTaxData[$tax]['name']] + $taxPrice;
                                                                            } else {
                                                                                $taxesData[$getTaxData[$tax]['name']] = $taxPrice;
                                                                            }
                                                                        }
                                                                        $iteam->itemTax = $itemTaxes;
                                                                    } else {
                                                                        $iteam->itemTax = [];
                                                                    }
                                                                @endphp
                                                                @foreach ($iteam->itemTax as $tax)

                                                                        <tr>
                                                                            <td>{{$tax['name'] .' ('.$tax['rate'] .'%)'}}</td>
                                                                            <td>{{ $tax['price']}}</td>
                                                                        </tr>
                                                                @endforeach
                                                            </table>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>

                                                    <td>{{!empty($iteam->description)?$iteam->description:'-'}}</td>
                                                    <td class="text-end">{{\Auth::user()->priceFormat(($iteam->price * $iteam->quantity - $iteam->discount) + $totalTaxPrice)}}</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <th class="text-dark" colspan="2">{{__('Service Charge')}}</th>
                                                <th class="text-dark" colspan="4">{{__('Description')}}</th>
                                                <th class="text-dark" colspan="2">{{__('Employee')}}</th>
                                            </tr>
                                            <tr>
                                                <td colspan="2">{{@$invoice->customerService->service_charge}}৳</td>
                                                <td colspan="4">{{@$invoice->customerService->description}}</td>
                                                <td colspan="2">{{@$invoice->customerService->employee->name}}</td>
                                            </tr>
                                            <tfoot>
                                            <tr>
                                                <td></td>
                                                <td><b>{{__('Total')}}</b></td>
                                                <td><b>{{$totalQuantity}}</b></td>
                                                <td><b>{{\Auth::user()->priceFormat($totalRate)}}</b></td>
                                                <td><b>{{\Auth::user()->priceFormat($totalDiscount)}}</b></td>
                                                <td><b>{{\Auth::user()->priceFormat($totalTaxPrice)}}</b></td>

                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="6"></td>
                                                <td class="text-end"><b>{{__('Sub Total')}}</b></td>
                                                <td class="text-end">{{\Auth::user()->priceFormat($invoice->getSubTotal())}}</td>
                                            </tr>

                                                <tr>
                                                    <td colspan="6"></td>
                                                    <td class="text-end"><b>{{__('Discount')}}</b></td>
                                                    <td class="text-end">
                                                        {{\Auth::user()->priceFormat($invoice->getTotalDiscount())}}
                                                        @if(\Auth::user()->can('edit invoice'))
                                                        <a href="#" class="ms-2" data-bs-toggle="modal" data-bs-target="#edit-discount-modal" title="{{__('Edit Discount')}}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                        @endif
                                                    </td>
                                                </tr>

                                            @if(!empty($taxesData))
                                                @foreach($taxesData as $taxName => $taxPrice)
                                                    <tr>
                                                        <td colspan="6"></td>
                                                        <td class="text-end"><b>{{$taxName}}</b></td>
                                                        <td class="text-end">{{ \Auth::user()->priceFormat($taxPrice) }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            <tr>
                                                <td colspan="6"></td>
                                                <td class="blue-text text-end"><b>{{__('Total')}}</b></td>
                                                <td class="blue-text text-end">{{\Auth::user()->priceFormat($invoice->getTotal())}}</td>
                                            </tr>
                                            @php
                                                $creditNote = $invoice->creditNote->sum('amount');
                                                $getDue = $invoice->getDue();
                                            @endphp
                                            <tr>
                                                <td colspan="6"></td>
                                                <td class="text-end"><b>{{__('Paid')}}</b></td>
                                                <td class="text-end">{{\Auth::user()->priceFormat(($invoice->getTotal()-$getDue)-($creditNote))}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="6"></td>
                                                <td class="text-end"><b>{{__('Credit Note')}}</b></td>
                                                <td class="text-end">{{\Auth::user()->priceFormat(($creditNote))}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="6"></td>
                                                <td class="text-end"><b>{{__('Due')}}</b></td>
                                                <td class="text-end">{{\Auth::user()->priceFormat($getDue)}}</td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <h5 class=" d-inline-block  mb-5">{{__('Receipt Summary')}}</h5>
                    <div class="table-responsive">
                        <table class="table ">
                            <thead>
                            <tr>
                                <th class="text-dark">{{__('Payment Receipt')}}</th>
                                <th class="text-dark">{{__('Date')}}</th>
                                <th class="text-dark">{{__('Amount')}}</th>
                                <th class="text-dark">{{__('Payment Type')}}</th>
                                <th class="text-dark">{{__('Account')}}</th>
                                <th class="text-dark">{{__('Reference')}}</th>
                                <th class="text-dark">{{__('Description')}}</th>
                                <th class="text-dark">{{__('Receipt')}}</th>
                                <th class="text-dark">{{__('OrderId')}}</th>
                                @can('delete payment invoice')
                                    <th class="text-dark">{{__('Action')}}</th>
                                @endcan
                            </tr>
                            </thead>
                            @if(!empty($invoice->payments) && $invoice->bankPayments)
                                @php
                                    $path =\App\Models\Utility::get_file('uploads/order');
                                @endphp

                                @foreach($invoice->payments as $key =>$payment)
                                    <tr>
                                        <td>
                                            @if(!empty($payment->add_receipt))
                                                <a href="{{asset(Storage::url('uploads/payment')).'/'.$payment->add_receipt}}" download="" class="btn btn-sm btn-secondary btn-icon rounded-pill" target="_blank"><span class="btn-inner--icon"><i class="ti ti-download"></i></span></a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{\Auth::user()->dateFormat($payment->date)}}</td>
                                        <td>{{\Auth::user()->priceFormat($payment->amount)}}</td>
                                        <td>{{$payment->payment_type}}</td>
                                        <td>{{!empty($payment->bankAccount)?$payment->bankAccount->bank_name.' '.$payment->bankAccount->holder_name:'--'}}</td>
                                        <td>{{!empty($payment->reference)?$payment->reference:'--'}}</td>
                                        <td>{{!empty($payment->description)?$payment->description:'--'}}</td>
                                        <td>
                                            @if(!empty($payment->receipt))
                                                <a href="{{ $path . '/' . $payment->receipt }}" target="_blank">
                                                    <i class="ti ti-file"></i>{{__('Receipt')}}</a>
                                            @elseif(!empty($payment->add_receipt))
                                                <a href="{{asset(Storage::url('uploads/payment')).'/'.$payment->add_receipt}}" target="_blank">
                                                    <i class="ti ti-file"></i>{{__('Receipt')}}</a>
                                            @else --
                                            @endif
                                        </td>
                                        <td>{{!empty($payment->order_id)?$payment->order_id:'--'}}</td>
                                        @can('delete invoice product')
                                            <td>
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'post', 'route' => ['invoice.payment.destroy',$invoice->id,$payment->id],'id'=>'delete-form-'.$payment->id, 'style' => 'margin: 0']) !!}

                                                    <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="Delete" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$payment->id}}').submit();">
                                                        <i class="ti ti-trash text-white"></i>
                                                    </a>
                                                {!! Form::close() !!}
                                            </td>
                                        @endcan
                                    </tr>
                                @endforeach

                                {{--  start for bank transfer--}}

                                @foreach($invoice->bankPayments as $key =>$bankPayment)

                                    <tr>
                                        <td>-</td>
                                        <td>{{\Auth::user()->dateFormat($bankPayment->date)}}</td>
                                        <td>{{\Auth::user()->priceFormat($bankPayment->amount)}}</td>
                                        <td>{{__('Bank Transfer')}}<br>
                                            {{--                                            @if($bankPayment->status == 'Pending')--}}
                                            {{--                                                <span class="badge bg-warning p-2 px-3 rounded">{{$bankPayment->status}}</span>--}}
                                            {{--                                            @elseif($bankPayment->status == 'Approved')--}}
                                            {{--                                                <span class="badge bg-primary p-2 px-3 rounded">{{$bankPayment->status}}</span>--}}
                                            {{--                                            @else--}}
                                            {{--                                                <span class="badge bg-danger p-2 px-3 rounded">{{$bankPayment->status}}</span>--}}
                                            {{--                                            @endif--}}

                                        </td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>
                                            @if(!empty($bankPayment->receipt))
                                                <a href="{{ $path . '/' . $bankPayment->receipt }}" target="_blank">
                                                    <i class="ti ti-file"></i> {{__('Receipt')}}
                                                </a>
                                            @endif

                                        </td>
                                        <td>{{!empty($bankPayment->order_id)?$bankPayment->order_id:'--'}}</td>
                                        @can('delete invoice product')

                                            <td>
                                                @if($bankPayment->status == 'Pending')
                                                    <div class="action-btn bg-warning">
                                                        <a href="#" data-url="{{ URL::to('invoice/'.$bankPayment->id.'/action') }}" data-size="lg"
                                                           data-ajax-popup="true" data-title="{{__('Payment Status')}}" class="mx-3 btn btn-sm align-items-center"
                                                           data-bs-toggle="tooltip" title="{{__('Payment Status')}}" data-original-title="{{__('Payment Status')}}">
                                                            <i class="ti ti-caret-right text-white"></i>
                                                        </a>
                                                    </div>

                                                @endif
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'post', 'route' => ['invoice.payment.destroy',$invoice->id,$bankPayment->id],'id'=>'delete-form-'.$bankPayment->id, 'style' => 'margin: 0']) !!}

                                                    <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="Delete" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$bankPayment->id}}').submit();">
                                                        <i class="ti ti-trash text-white"></i>
                                                    </a>
                                                {!! Form::close() !!}
                                            </td>
                                        @endcan
                                    </tr>
                                @endforeach

                                {{--  end for bank transfer--}}

                            @else
                                <tr>
                                    <td colspan="{{ (Gate::check('delete invoice product') ? '10' : '9') }}" class="text-center text-dark"><p>{{__('No Data Found')}}</p></td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <h5 class="d-inline-block mb-5">{{__('Credit Note Summary')}}</h5>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="text-dark">{{__('Date')}}</th>
                                <th class="text-dark" class="">{{__('Amount')}}</th>
                                <th class="text-dark" class="">{{__('Description')}}</th>
                                @if(Gate::check('edit credit note') || Gate::check('delete credit note'))
                                    <th class="text-dark">{{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            @forelse($invoice->creditNote as $key =>$creditNote)
                                <tr>
                                    <td>{{\Auth::user()->dateFormat($creditNote->date)}}</td>
                                    <td class="">{{\Auth::user()->priceFormat($creditNote->amount)}}</td>
                                    <td class="">{{$creditNote->description}}</td>
                                    <td>
                                        @can('edit credit note')
                                            <div class="action-btn bg-primary ms-2">
                                                <a data-url="{{ route('invoice.edit.credit.note',[$creditNote->invoice,$creditNote->id]) }}" data-ajax-popup="true" title="{{__('Edit')}}" data-original-title="{{__('Credit Note')}}" href="#" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            </div>
                                        @endcan
                                        @can('delete credit note')
                                            <div class="action-btn bg-danger ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => array('invoice.delete.credit.note', $creditNote->invoice,$creditNote->id),'id'=>'delete-form-'.$creditNote->id]) !!}
                                                <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para " data-bs-toggle="tooltip" title="Delete" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$creditNote->id}}').submit();">
                                                    <i class="ti ti-trash text-white"></i>
                                                </a>
                                                {!! Form::close() !!}
                                            </div>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <p class="text-dark">{{__('No Data Found')}}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@if(\Auth::user()->can('edit invoice'))
<!-- Modal for editing discount -->
<div class="modal fade" id="edit-discount-modal" tabindex="-1" role="dialog" aria-labelledby="edit-discount-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-discount-modal-label">{{ __('Edit Discount') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="discount-form" action="{{ route('invoice.update.discount', $invoice->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="discount_apply" class="form-label">{{ __('Discount Amount') }}</label>
                        <input type="number" name="discount_apply" id="discount_apply" class="form-control" value="{{ $invoice->discount_apply ?? 0 }}" step="0.01" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
