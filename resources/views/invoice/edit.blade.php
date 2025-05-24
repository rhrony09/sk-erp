@extends('layouts.admin')
@section('page-title')
    {{ __('Invoice Edit') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('invoice.index') }}">{{ __('Invoice') }}</a></li>
    <li class="breadcrumb-item">{{ __('Invoice Edit') }}</li>
@endsection
@push('script-page')
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/jquery.repeater.min.js') }}"></script>
    <script>
        var selector = "body";
        if ($(selector + " .repeater").length) {
            var $dragAndDrop = $("body .repeater tbody").sortable({
                handle: '.sort-handler'
            });
            var $repeater = $(selector + ' .repeater').repeater({
                initEmpty: false,
                defaultValues: {
                    'status': 1
                },
                show: function() {
                    $(this).slideDown();
                    var file_uploads = $(this).find('input.multi');
                    if (file_uploads.length) {
                        $(this).find('input.multi').MultiFile({
                            max: 3,
                            accept: 'png|jpg|jpeg',
                            max_size: 2048
                        });
                    }
                    if ($('.select2').length) {
                        $('.select2').select2();
                    }
                },
                hide: function(deleteElement) {
                    $(this).slideUp(deleteElement);
                    $(this).remove();
                    var inputs = $(".amount");
                    var subTotal = 0;
                    for (var i = 0; i < inputs.length; i++) {
                        subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
                    }
                    $('.subTotal').html(subTotal.toFixed(2));
                    $('.totalAmount').html(subTotal.toFixed(2));
                },
                ready: function(setIndexes) {
                    $dragAndDrop.on('drop', setIndexes);
                },
                isFirstItemUndeletable: true
            });

            var value = $(selector + " .repeater").attr('data-value');
            if (typeof value != 'undefined' && value.length != 0) {
                value = JSON.parse(value);
                $repeater.setList(value);
                
                for (var i = 0; i < value.length; i++) {
                    var tr = $('#sortable-table .id[value="' + value[i].id + '"]').parent().parent();
                    tr.find('.item').val(value[i].product_id);
                    
                    setTimeout(function(element) {
                        changeItem(element);
                    }, 200, tr.find('.item'));
                }
            } else {
                var tr = $('#sortable-table .id').first().parent().parent();
                tr.find('.item').trigger('change');
            }
        }

        $(document).on('change', '#customer', function() {
            $('#customer_detail').removeClass('d-none');
            $('#customer_detail').addClass('d-block');
            $('#customer-box').removeClass('d-block');
            $('#customer-box').addClass('d-none');
            var id = $(this).val();
            var url = $(this).data('url');
            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('#token').val()
                },
                data: {
                    'id': id
                },
                cache: false,
                success: function(data) {
                    if (data != '') {
                        $('#customer_detail').html(data);
                    } else {
                        $('#customer-box').removeClass('d-none');
                        $('#customer-box').addClass('d-block');
                        $('#customer_detail').removeClass('d-block');
                        $('#customer_detail').addClass('d-none');
                    }
                },

            });
        });

        $(document).on('click', '#remove', function() {
            $('#customer-box').removeClass('d-none');
            $('#customer-box').addClass('d-block');
            $('#customer_detail').removeClass('d-block');
            $('#customer_detail').addClass('d-none');
        })

        $(document).on('change', '.item', function() {
            changeItem($(this));
        });

        var invoice_id = '{{ $invoice->id }}';

        function changeItem(element) {
            var iteams_id = element.val();
            var url = element.data('url');
            var el = element;
            if (iteams_id === '') {
                // If no item selected, just clear the fields
                $(el.parent().parent().find('.quantity')).val(1);
                $(el.parent().parent().find('.price')).val(0);
                $(el.parent().parent().find('.discount')).val(0);
                $(el.parent().parent().parent().find('.pro_description')).val('');
                $(el.parent().parent().find('.taxes')).html('');
                $(el.parent().parent().find('.tax')).val('');
                $(el.parent().parent().find('.unit')).html('');
                $(el.parent().parent().find('.amount')).html('0.00');
                calculateTotals();
                return;
            }
            
            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('#token').val()
                },
                data: {
                    'product_id': iteams_id
                },
                cache: false,
                success: function(data) {
                    var item = JSON.parse(data);

                    $.ajax({
                        url: '{{ route('invoice.items') }}',
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': jQuery('#token').val()
                        },
                        data: {
                            'invoice_id': invoice_id,
                            'product_id': iteams_id,
                        },
                        cache: false,
                        success: function(data) {
                            var invoiceItems = JSON.parse(data);
                            if (invoiceItems != null) {
                                var amount = (invoiceItems.price * invoiceItems.quantity);

                                $(el.parent().parent().find('.quantity')).val(invoiceItems.quantity);
                                $(el.parent().parent().find('.price')).val(invoiceItems.price);
                                $(el.parent().parent().find('.discount')).val(invoiceItems.discount);
                                $(el.parent().parent().parent().find('.pro_description')).val(invoiceItems.description);
                            } else {
                                $(el.parent().parent().find('.quantity')).val(1);
                                $(el.parent().parent().find('.price')).val(item.product.sale_price);
                                $(el.parent().parent().find('.discount')).val(0);
                                $(el.parent().parent().parent().find('.pro_description')).val(item.product.description);
                            }

                            var taxes = '';
                            var tax = [];

                            var totalItemTaxRate = 0;
                            for (var i = 0; i < item.taxes.length; i++) {
                                taxes +=
                                    '<span class="badge bg-primary p-2 px-3 rounded mt-1 mr-1">' +
                                    item.taxes[i].name + ' ' + '(' + item.taxes[i].rate + '%)' +
                                    '</span>';
                                tax.push(item.taxes[i].id);
                                totalItemTaxRate += parseFloat(item.taxes[i].rate);
                            }

                            var discount = $(el.parent().parent().find('.discount')).val();
                            if (discount.length <= 0) {
                                discount = 0;
                            }

                            if (invoiceItems != null) {
                                var itemTaxPrice = parseFloat((totalItemTaxRate / 100)) * parseFloat((invoiceItems.price * invoiceItems.quantity) - discount);
                            } else {
                                var itemTaxPrice = parseFloat((totalItemTaxRate / 100)) * parseFloat((item.product.sale_price * 1) - discount);
                            }

                            $(el.parent().parent().find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));
                            $(el.parent().parent().find('.itemTaxRate')).val(totalItemTaxRate.toFixed(2));
                            $(el.parent().parent().find('.taxes')).html(taxes);
                            $(el.parent().parent().find('.tax')).val(tax);
                            $(el.parent().parent().find('.unit')).html(item.unit);

                            // Calculate amount including tax and discount
                            var quantity = parseFloat($(el.parent().parent().find('.quantity')).val());
                            var price = parseFloat($(el.parent().parent().find('.price')).val());
                            var discountVal = parseFloat($(el.parent().parent().find('.discount')).val()) || 0;
                            
                            var totalItemPrice = (quantity * price) - discountVal;
                            var amount = totalItemPrice + itemTaxPrice;
                            
                            $(el.parent().parent().find('.amount')).html(amount.toFixed(2));

                            // Recalculate all totals
                            calculateTotals();
                        }
                    });
                },
            });
        }
        
        // Function to calculate all totals
        function calculateTotals() {
            var totalItemPrice = 0;
            var totalItemTaxPrice = 0;
            var totalItemDiscountPrice = 0;
            var subTotal = 0;
            
            // Calculate subtotal from each line
            $('.amount').each(function() {
                subTotal += parseFloat($(this).html()) || 0;
            });
            
            // Get totals from quantity and price
            $('.quantity').each(function(index) {
                var quantity = parseFloat($(this).val()) || 0;
                var price = parseFloat($('.price').eq(index).val()) || 0;
                totalItemPrice += quantity * price;
            });
            
            // Get all tax amounts
            $('.itemTaxPrice').each(function() {
                totalItemTaxPrice += parseFloat($(this).val()) || 0;
            });
            
            // Get all discount amounts
            $('.discount').each(function() {
                totalItemDiscountPrice += parseFloat($(this).val()) || 0;
            });
            
            // Update summary displays
            $('.subTotal').html(totalItemPrice.toFixed(2));
            $('.totalTax').html(totalItemTaxPrice.toFixed(2));
            $('.totalDiscount').html(totalItemDiscountPrice.toFixed(2));
            $('.totalAmount').html((parseFloat(totalItemPrice) - parseFloat(totalItemDiscountPrice) + parseFloat(totalItemTaxPrice)).toFixed(2));
        }

        $(document).on('keyup', '.quantity', function() {
            var el = $(this).parent().parent().parent().parent();

            var quantity = $(this).val();
            var price = $(el.find('.price')).val();
            var discount = $(el.find('.discount')).val();
            if (discount.length <= 0) {
                discount = 0;
            }

            var totalItemPrice = (quantity * price) - discount;
            var amount = (totalItemPrice);

            var totalItemTaxRate = $(el.find('.itemTaxRate')).val();
            var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (totalItemPrice));
            $(el.find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));

            $(el.find('.amount')).html((parseFloat(itemTaxPrice) + parseFloat(amount)).toFixed(2));

            // Use the common calculation function
            calculateTotals();
        });

        $(document).on('keyup change', '.price', function() {
            var el = $(this).parent().parent().parent().parent();
            var price = $(this).val();
            var quantity = $(el.find('.quantity')).val();
            var discount = $(el.find('.discount')).val();
            if (discount.length <= 0) {
                discount = 0;
            }

            var totalItemPrice = (quantity * price) - discount;
            var amount = (totalItemPrice);

            var totalItemTaxRate = $(el.find('.itemTaxRate')).val();
            var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (totalItemPrice));
            $(el.find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));

            $(el.find('.amount')).html((parseFloat(itemTaxPrice) + parseFloat(amount)).toFixed(2));

            // Use the common calculation function
            calculateTotals();
        });

        $(document).on('keyup change', '.discount', function() {
            var el = $(this).parent().parent().parent();
            var discount = $(this).val();
            if (discount.length <= 0) {
                discount = 0;
            }
            var price = $(el.find('.price')).val();
            var quantity = $(el.find('.quantity')).val();
            
            var totalItemPrice = (quantity * price) - discount;
            var amount = (totalItemPrice);

            var totalItemTaxRate = $(el.find('.itemTaxRate')).val();
            var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (totalItemPrice));
            $(el.find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));

            $(el.find('.amount')).html((parseFloat(itemTaxPrice) + parseFloat(amount)).toFixed(2));

            // Use the common calculation function
            calculateTotals();
        });

        // Trigger calculations when document is ready
        $(document).ready(function() {
            // Initialize select2 for all select elements
            if (typeof $.fn.select2 !== 'undefined') {
                $('.select2').select2();
            }

            // Initialize customer details
            if ($('#customer').length > 0 && $('#customer').val() !== '') {
                $('#customer').trigger('change');
            }

            // First, set the product_id in items
            const invoiceItems = {!! json_encode($invoice->items) !!};
            if (invoiceItems.length > 0) {
                // Make sure all products are properly selected in their dropdowns
                setTimeout(function() {
                    invoiceItems.forEach(function(item, index) {
                        const tr = $('#sortable-table tbody tr').eq(index);
                        if (tr.length) {
                            $(tr).find('.item').val(item.product_id).trigger('change');
                        }
                    });
                }, 300);
            }

            // Recalculate all values after a brief delay to ensure DOM is fully loaded
            setTimeout(function() {
                // Update summary calculations on page load
                var totalItemPrice = 0;
                var totalItemTaxPrice = 0;
                var totalItemDiscountPrice = 0;
                var subTotal = 0;

                // Calculate subtotal from each line
                $('.amount').each(function() {
                    subTotal += parseFloat($(this).html()) || 0;
                });

                // Get totals from quantity and price
                $('.quantity').each(function(index) {
                    var quantity = parseFloat($(this).val()) || 0;
                    var price = parseFloat($('.price').eq(index).val()) || 0;
                    totalItemPrice += quantity * price;
                });

                // Get all tax amounts
                $('.itemTaxPrice').each(function() {
                    totalItemTaxPrice += parseFloat($(this).val()) || 0;
                });

                // Get all discount amounts
                $('.discount').each(function() {
                    totalItemDiscountPrice += parseFloat($(this).val()) || 0;
                });

                // Update summary displays
                $('.subTotal').html(totalItemPrice.toFixed(2));
                $('.totalTax').html(totalItemTaxPrice.toFixed(2));
                $('.totalDiscount').html(totalItemDiscountPrice.toFixed(2));
                $('.totalAmount').html((parseFloat(totalItemPrice) - parseFloat(totalItemDiscountPrice) + parseFloat(totalItemTaxPrice)).toFixed(2));
            }, 500);
        });

        $(document).on('click', '[data-repeater-create]', function() {
            $('.item :selected').each(function() {
                var id = $(this).val();
                $(".item option[value=" + id + "]").prop("disabled", true);
            });
        })

        $(document).on('click', '[data-repeater-delete]', function() {
            // $('.delete_item').click(function () {
            if (confirm('Are you sure you want to delete this element?')) {
                var el = $(this).parent().parent();
                var id = $(el.find('.id')).val();
                var amount = $(el.find('.amount')).html();

                $.ajax({
                    url: '{{ route('invoice.product.destroy') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': jQuery('#token').val()
                    },
                    data: {
                        'id': id,
                        'amount': amount,
                    },
                    cache: false,
                    success: function(data) {

                    },
                });

            }
        });
    </script>
    <script>
        $(document).on('click', '[data-repeater-delete]', function() {
            $(".price").change();
            $(".discount").change();
        });
    </script>
@endpush

@section('content')
    {{--    @dd($invoice) --}}
    <div class="row">
        {{ Form::model($invoice, ['route' => ['invoice.update', $invoice->id], 'method' => 'PUT', 'class' => 'w-100']) }}
        <div class="col-12">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                                                <div class="col-md-6">                            <div class="form-group" id="customer-box">                                {{ Form::label('customer_id', __('Customer'), ['class' => 'form-label']) }}                                {{ Form::select('customer_id', $customers, null, ['class' => 'form-control select2', 'id' => 'customer', 'data-url' => route('invoice.customer'), 'required' => 'required']) }}                            </div>                            <div id="customer_detail" class="d-none">                            </div>                                                        <div class="row mt-3">                                <div class="col-md-6">                                    <div>                                        <label for="billing_address_line_1">Billing Address Line 1</label>                                        <input type="text" class="form-control mb-2" id="billing_address_line_1" name="billing_address_line_1" value="{{ $invoiceAddress->billing_address_line_1 ?? '' }}">                                    </div>                                    <div>                                        <label for="billing_address_line_2">Billing Address Line 2</label>                                        <input type="text" class="form-control mb-2" id="billing_address_line_2" name="billing_address_line_2" value="{{ $invoiceAddress->billing_address_line_2 ?? '' }}">                                    </div>                                    <div>                                        <label for="billing_city">Billing City</label>                                        <input type="text" class="form-control mb-2" id="billing_city" name="billing_city" value="{{ $invoiceAddress->billing_city ?? '' }}">                                    </div>                                    <div>                                        <label for="billing_state">Billing State</label>                                        <input type="text" class="form-control mb-2" id="billing_state" name="billing_state" value="{{ $invoiceAddress->billing_state ?? '' }}">                                    </div>                                    <div>                                        <label for="billing_zip_code">Billing Zip Code</label>                                        <input type="text" class="form-control" id="billing_zip_code" name="billing_zip_code" value="{{ $invoiceAddress->billing_zip_code ?? '' }}">                                    </div>                                </div>                                <div class="col-md-6">                                    <div>                                        <label for="shipping_address_line_1">Shipping Address Line 1</label>                                        <input type="text" class="form-control mb-2" id="shipping_address_line_1" name="shipping_address_line_1" value="{{ $invoiceAddress->shipping_address_line_1 ?? '' }}">                                    </div>                                    <div>                                        <label for="shipping_address_line_2">Shipping Address Line 2</label>                                        <input type="text" class="form-control mb-2" id="shipping_address_line_2" name="shipping_address_line_2" value="{{ $invoiceAddress->shipping_address_line_2 ?? '' }}">                                    </div>                                    <div>                                        <label for="shipping_city">Shipping City</label>                                        <input type="text" class="form-control mb-2" id="shipping_city" name="shipping_city" value="{{ $invoiceAddress->shipping_city ?? '' }}">                                    </div>                                    <div>                                        <label for="shipping_state">Shipping State</label>                                        <input type="text" class="form-control mb-2" id="shipping_state" name="shipping_state" value="{{ $invoiceAddress->shipping_state ?? '' }}">                                    </div>                                    <div>                                        <label for="shipping_zip_code">Shipping Zip Code</label>                                        <input type="text" class="form-control" id="shipping_zip_code" name="shipping_zip_code" value="{{ $invoiceAddress->shipping_zip_code ?? '' }}">                                    </div>                                </div>                            </div>                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('issue_date', __('Issue Date'), ['class' => 'form-label']) }}
                                        <div class="form-icon-user">
                                            {{ Form::date('issue_date', null, ['class' => 'form-control', 'required' => 'required']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('due_date', __('Due Date'), ['class' => 'form-label']) }}
                                        <div class="form-icon-user">
                                            {{ Form::date('due_date', null, ['class' => 'form-control', 'required' => 'required']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('invoice_number', __('Invoice Number'), ['class' => 'form-label']) }}
                                        <div class="form-icon-user">
                                            <input type="text" class="form-control" value="{{ $invoice_number }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {{ Form::label('category_id', __('Category'), ['class' => 'form-label']) }}
                                    {{ Form::select('category_id', $category, null, ['class' => 'form-control select', 'required' => 'required']) }}
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('ref_number', __('Ref Number'), ['class' => 'form-label']) }}
                                        <div class="form-icon-user">
                                            <span><i class="ti ti-joint"></i></span>
                                            {{ Form::text('ref_number', null, ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('note', __('Add Note'), ['class' => 'form-label']) }}
                                        <div class="form-icon-user">
                                            <span><i class="ti ti-joint"></i></span>
                                            {{ Form::textarea('note', null, ['class' => 'form-control summernote-simple']) }}
                                        </div>
                                    </div>
                                </div>
                                {{--                                <div class="col-md-6"> --}}
                                {{--                                    <div class="form-check custom-checkbox mt-4"> --}}
                                {{--                                        <input class="form-check-input" type="checkbox" name="discount_apply" id="discount_apply" {{$invoice->discount_apply==1?'checked':''}}> --}}
                                {{--                                        <label class="form-check-label" for="discount_apply">{{__('Discount Apply')}}</label> --}}
                                {{--                                    </div> --}}
                                {{--                                </div> --}}

                                {{--                                <div class="col-md-6"> --}}
                                {{--                                    <div class="form-group"> --}}
                                {{--                                        {{Form::label('sku',__('SKU')) }} --}}
                                {{--                                        {!!Form::text('sku', null,array('class' => 'form-control','required'=>'required')) !!} --}}
                                {{--                                    </div> --}}
                                {{--                                </div> --}}
                                @if (!$customFields->isEmpty())
                                    <div class="col-md-6">
                                        <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                                            @include('customFields.formBuilder')
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <h5 class=" d-inline-block mb-4">{{ __('Product & Services') }}</h5>
            <div class="card repeater" data-value='{!! json_encode($invoice->items) !!}'>
                <div class="item-section py-2">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">
                            <div class="all-button-box me-2">
                                <a href="#" data-repeater-create="" class="btn btn-primary" data-bs-toggle="modal"
                                    data-target="#add-bank">
                                    <i class="ti ti-plus"></i> {{ __('Add item') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 table-custom-style" data-repeater-list="items" id="sortable-table">
                            <thead>
                                <tr>
                                    <th>{{ __('Items') }}</th>
                                    <th>{{ __('Quantity') }}</th>
                                    <th>{{ __('Price') }} </th>
                                    <th>{{ __('Discount') }}</th>
                                    <th>{{ __('Tax') }}</th>
                                    <th class="text-end">{{ __('Amount') }} </th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="ui-sortable" data-repeater-item>
                                <tr>
                                    {{ Form::hidden('id', null, ['class' => 'form-control id']) }}
                                    <td width="25%" class="form-group pt-0">
                                        {{ Form::select('item', $product_services, null, ['class' => 'form-control item select2', 'data-url' => route('invoice.product')]) }}
                                    </td>
                                    <td>
                                        <div class="form-group price-input input-group search-form">
                                            {{ Form::text('quantity', null, ['class' => 'form-control quantity', 'required' => 'required', 'placeholder' => __('Qty')]) }}
                                            <span class="unit input-group-text bg-transparent"></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group price-input input-group search-form">
                                            {{ Form::text('price', null, ['class' => 'form-control price', 'required' => 'required', 'placeholder' => __('Price')]) }}
                                            <span class="input-group-text bg-transparent">{{ \Auth::user()->currencySymbol() }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group price-input input-group search-form">
                                            {{ Form::text('discount', null, ['class' => 'form-control discount', 'placeholder' => __('Discount')]) }}
                                            <span class="input-group-text bg-transparent">{{ \Auth::user()->currencySymbol() }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <div class="input-group colorpickerinput">
                                                <div class="taxes"></div>
                                                {{ Form::hidden('tax', '', ['class' => 'form-control tax']) }}
                                                {{ Form::hidden('itemTaxPrice', '', ['class' => 'form-control itemTaxPrice']) }}
                                                {{ Form::hidden('itemTaxRate', '', ['class' => 'form-control itemTaxRate']) }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end amount">0.00</td>
                                    <td>
                                        <a href="#"
                                            class="ti ti-trash text-white repeater-action-btn bg-danger ms-2 delete_item"
                                            data-repeater-delete></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="form-group">
                                            {{ Form::textarea('description', null, ['class' => 'form-control pro_description', 'rows' => '2', 'placeholder' => __('Description')]) }}
                                        </div>
                                    </td>
                                    <td colspan="5"></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td></td>
                                    <td><strong>{{ __('Sub Total') }} ({{ \Auth::user()->currencySymbol() }})</strong>
                                    </td>
                                    <td class="text-end subTotal">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td></td>
                                    <td><strong>{{ __('Discount') }} ({{ \Auth::user()->currencySymbol() }})</strong></td>
                                    <td class="text-end totalDiscount">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td></td>
                                    <td><strong>{{ __('Tax') }} ({{ \Auth::user()->currencySymbol() }})</strong></td>
                                    <td class="text-end totalTax">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td class="blue-text"><strong>{{ __('Total Amount') }}
                                            ({{ \Auth::user()->currencySymbol() }})</strong></td>
                                    <td class="text-end totalAmount blue-text">0.00</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card p-3">
            <div class="row">
                <div class="col-md-12">
                    <h5>Footer Note</h5>
                    <textarea id="editor" name="footer_note">
                        {{ $invoice->footer_text }}
                    </textarea>
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <input type="button" value="{{ __('Cancel') }}" onclick="location.href = '{{ route('invoice.index') }}';"
                class="btn btn-light me-3">
            <input type="submit" value="{{ __('Update') }}" class="btn  btn-primary">
        </div>
        {{ Form::close() }}
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jodit/3.24.5/jodit.min.js"></script>
    
    <script>
        // Initialize Jodit editor
        const editor = Jodit.make('#editor', {
            height: 400,
            theme: 'default',
            language: 'en',
            
            // Toolbar configuration
            buttons: [
                'source', '|',
                'bold', 'italic', 'underline', 'strikethrough', '|',
                'superscript', 'subscript', '|',
                'ul', 'ol', '|',
                'outdent', 'indent', '|',
                'font', 'fontsize', 'brush', 'paragraph', '|',
                'image', 'video', 'table', 'link', '|',
                'align', 'undo', 'redo', '|',
                'hr', 'eraser', 'copyformat', '|',
                'fullsize', 'selectall', 'print'
            ],
            
            // Editor configuration
            uploader: {
                insertImageAsBase64URI: true // For demo purposes
            },
            
            // Disable some features for demo
            filebrowser: {
                ajax: {
                    url: '#' // Disabled for demo
                }
            },
            
            // Image resize
            image: {
                openOnDblClick: true,
                editSrc: false,
                useImageEditor: true,
                editTitle: true,
                editAlt: true,
                editLink: true,
                editSize: true,
                editMargins: true,
                editClass: true,
                editStyle: true,
                editId: true,
                editAlign: true,
                showPreview: true,
                selectImageAfterClose: true
            },
            
            // Events
            events: {
                change: function(value) {
                    console.log('Content changed:', value);
                }
            }
        });
        
        // Helper functions
        function getContent() {
            const content = editor.getEditorValue();
            document.getElementById('output-content').innerHTML = 
                '<strong>HTML Content:</strong><pre>' + escapeHtml(content) + '</pre>';
        }
        
        function getPlainText() {
            const plainText = editor.getEditorText();
            document.getElementById('output-content').innerHTML = 
                '<strong>Plain Text:</strong><pre>' + escapeHtml(plainText) + '</pre>';
        }
        
        function setContent() {
            const sampleContent = `
                <h2>Sample Content</h2>
                <p>This is <strong>sample content</strong> with various formatting:</p>
                <blockquote>
                    <p>"This is a blockquote example."</p>
                </blockquote>
                <table border="1" style="border-collapse: collapse; width: 100%;">
                    <tr>
                        <th>Column 1</th>
                        <th>Column 2</th>
                    </tr>
                    <tr>
                        <td>Data 1</td>
                        <td>Data 2</td>
                    </tr>
                </table>
                <p><a href="#" target="_blank">This is a link</a></p>
            `;
            editor.setEditorValue(sampleContent);
        }
        
        function clearEditor() {
            editor.setEditorValue('');
            document.getElementById('output-content').innerHTML = 'Editor cleared!';
        }
        
        let isReadOnly = false;
        function toggleReadOnly() {
            isReadOnly = !isReadOnly;
            editor.setReadOnly(isReadOnly);
            document.getElementById('output-content').innerHTML = 
                'Editor is now: ' + (isReadOnly ? 'Read-Only' : 'Editable');
        }
        
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        // Auto-update output on editor change (optional)
        editor.events.on('change', function() {
            // Uncomment the line below for real-time HTML output
            // getContent();
        });
    </script>
@endsection
