@extends('layouts.admin')
@section('page-title')
    {{ __('Invoice Edit') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('invoice.index') }}">{{ __('Invoice') }}</a></li>
    <li class="breadcrumb-item">{{ __('Invoice Edit') }}</li>
@endsection

@section('content')
<style>
    .select2 {
        width: 100% !important;
    }

    .select2-container--default .select2-selection--single {
        height: 38px !important;
        border: 2px solid #3E3F4A !important;
        border-radius: 6px !important;
        background-color: #22242c !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #808191 !important;
        line-height: 38px !important;
        padding-left: 1rem !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
        right: 8px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #808191 transparent transparent transparent !important;
    }

    .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
        border-color: transparent transparent #808191 transparent !important;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #3E3F4A !important;
        color: #fff !important;
    }

    .select2-dropdown {
        border: 2px solid #3E3F4A !important;
        border-radius: 6px !important;
        background-color: #22242c !important;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 2px solid #3E3F4A !important;
        border-radius: 6px !important;
        padding: 0.375rem 0.75rem !important;
        background-color: #22242c !important;
        color: #808191 !important;
    }

    .select2-container--default .select2-results__option {
        padding: 0.375rem 0.75rem !important;
        color: #808191 !important;
    }

    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: #3E3F4A !important;
        color: #fff !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__clear {
        color: #808191 !important;
        font-size: 1.2em !important;
        margin-right: 20px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #808191 !important;
    }
</style>

<form action="{{ route('invoice.update', $invoice->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-6">
            <div class="card p-3">
                <div class="form-group">
                    <label for="customer_id">Customer</label>
                    <div class="row d-flex">
                        <div class="col-md-4 pe-0">
                            <select name="type" class="form-control">
                                <option value="sl-no">#SL No.</option>
                                <option value="name" {{ old('type', 'name') == 'name' ? 'selected' : '' }}>Name</option>
                                <option value="email">Email</option>
                                <option value="contact">Phone</option>
                            </select>
                        </div>
                        
                        <div class="col-md-8">
                            <select class="form-control js-customer-select" id="customer_id" name="customer_id" required>
                                <option value="">Select a customer</option>
                                @foreach($customers as $id => $name)
                                    <option value="{{ $id }}" {{ $invoice->customer_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-0">
                    <div class="row">
                        <div class="col-md-6">
                            <div>
                                <label for="billing_address_line_1">Billing Address Line 1</label>
                                <input type="text" class="form-control mb-2" id="billing_address_line_1" name="billing_address_line_1" value="{{ old('billing_address_line_1', $invoiceAddress->billing_address ?? '') }}">
                            </div>
                            <div>
                                <label for="billing_address_line_2">Billing Address Line 2</label>
                                <input type="text" class="form-control mb-2" id="billing_address_line_2" name="billing_address_line_2" value="{{ old('billing_address_line_2', $invoiceAddress->billing_address_line_2 ?? '') }}">
                            </div>
                            <div>
                                <label for="billing_city">Billing City</label>
                                <input type="text" class="form-control mb-2" id="billing_city" name="billing_city" value="{{ old('billing_city', $invoiceAddress->billing_city ?? '') }}">
                            </div>
                            <div>
                                <label for="billing_state">Billing State</label>
                                <input type="text" class="form-control mb-2" id="billing_state" name="billing_state" value="{{ old('billing_state', $invoiceAddress->billing_state ?? '') }}">
                            </div>
                            <div>
                                <label for="billing_zip_code">Billing Zip Code</label>
                                <input type="text" class="form-control" id="billing_zip_code" name="billing_zip_code" value="{{ old('billing_zip_code', $invoiceAddress->billing_zip ?? '') }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div>
                                <label for="shipping_address_line_1">Shipping Address Line 1</label>
                                <input type="text" class="form-control mb-2" id="shipping_address_line_1" name="shipping_address_line_1" value="{{ old('shipping_address_line_1', $invoiceAddress->shipping_address ?? '') }}">
                            </div>
                            <div>
                                <label for="shipping_address_line_2">Shipping Address Line 2</label>
                                <input type="text" class="form-control mb-2" id="shipping_address_line_2" name="shipping_address_line_2" value="{{ old('shipping_address_line_2', $invoiceAddress->shipping_address_line_2 ?? '') }}">
                            </div>
                            <div>
                                <label for="shipping_city">Shipping City</label>
                                <input type="text" class="form-control mb-2" id="shipping_city" name="shipping_city" value="{{ old('shipping_city', $invoiceAddress->shipping_city ?? '') }}">
                            </div>
                            <div>
                                <label for="shipping_state">Shipping State</label>
                                <input type="text" class="form-control mb-2" id="shipping_state" name="shipping_state" value="{{ old('shipping_state', $invoiceAddress->shipping_state ?? '') }}">
                            </div>
                            <div>
                                <label for="shipping_zip_code">Shipping Zip Code</label>
                                <input type="text" class="form-control" id="shipping_zip_code" name="shipping_zip_code" value="{{ old('shipping_zip_code', $invoiceAddress->shipping_zip ?? '') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-3">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="issueDate" class="form-label">Issue Date</label>
                        <input type="date" class="form-control" id="issueDate" name="issue_date" value="{{ old('issue_date', $invoice->issue_date) }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="dueDate" class="form-label">Due Date</label>
                        <input type="date" class="form-control" id="dueDate" name="due_date" value="{{ old('due_date', $invoice->due_date) }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="invoiceNumber" class="form-label">Invoice Number</label>
                        <input type="text" class="form-control" id="invoiceNumber" value="{{ $invoice_number }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" id="category" name="category_id">
                            
                            @foreach($category as $id => $name)
                                <option value="{{ $id }}" @if($invoice->category_id == $id) selected @endif>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="invoice_category" class="form-label">Invoice Category</label>
                        <select class="form-select" id="invoice_category" name="invoice_category_id">
                            <option value="">Select Category</option>
                            @foreach($invoiceCategories as $category)
                                <option value="{{ $category->id }}" data-footer-note="{{ $category->footer_note }}" {{ $invoice->invoice_category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="refNumber" class="form-label">Ref Number</label>
                        <input type="text" class="form-control" id="refNumber" name="ref_number" value="{{ old('ref_number', $invoice->ref_number) }}" placeholder="Enter REF">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="note" class="form-label">Add Note</label>
                    <textarea class="form-control" id="note" rows="3" name="note">{{ old('note', $invoice->note) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Product & Services -->
    <div class="card p-3">
        <div class="d-flex justify-content-between mb-3">
            <button type="button" class="btn btn-primary" id="addItemBtn">ADD ITEM</button>
        </div>
        <div class="table-responsive m-0 w-100">
            <table class="table">
                <thead>
                    <tr>
                        <th>ITEMS</th>
                        <th>QUANTITY</th>
                        <th>PRICE</th>
                        <th>DISCOUNT</th>
                        <th>TAX (%)</th>
                        <th>AMOUNT AFTER DISCOUNT</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody id="productTableBody">
                    @foreach($invoice->items as $index => $item)
                    <tr>
                        <td>
                            <div class="form-group">
                                <select class="form-control js-product-select" id="product_id_{{ $index }}" name="items[{{ $index }}][item]" required>
                                    <option value="">Select a product</option>
                                    @foreach($product_services as $id => $name)
                                        <option value="{{ $id }}" {{ $item->item == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <textarea class="form-control mt-2" name="items[{{ $index }}][description]" placeholder="Description" rows="2">{{ $item->description }}</textarea>
                        </td>
                        <td><input name="items[{{ $index }}][quantity]" type="number" class="form-control" value="{{ $item->quantity }}"></td>
                        <td><input name="items[{{ $index }}][price]" type="number" class="form-control" value="{{ $item->price }}"></td>
                        <td><input name="items[{{ $index }}][discount]" type="number" class="form-control" value="{{ $item->discount }}"></td>
                        <td><input name="items[{{ $index }}][tax]" type="number" class="form-control" value="{{ $item->tax }}"></td>
                        <td>{{ number_format(($item->quantity * $item->price) - $item->discount + ($item->quantity * $item->price * ($item->tax / 100)), 2) }}</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-row" {{ $index == 0 ? 'disabled' : '' }}>
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="table">
                <thead>
                    <tr>
                        <th>Service Charge</th>
                        <th>Employee</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input type="number" name="service_charge" id="service_charge" class="form-control" min="0" value="{{ old('service_charge', $invoice->service_charge ?? 0) }}">
                        </td>
                        <td>
                            <select name="employee_id" id="employee_id" class="form-control select2">
                                <option value="">Select Employee</option>
                                @foreach($employees as $id => $name)
                                    <option value="{{ $id }}" {{ $invoice->employee_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <textarea name="service_charge_description" id="service_charge_description" class="form-control" placeholder="Description">{{ old('service_charge_description', $invoice->service_charge_description ?? '') }}</textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Subtotal Calculation Table and Discount Option -->
        <div class="row mt-4">
            <div class="col-md-6">
                <!-- Space for any additional information -->
            </div>
            <div class="col-md-6">
                <div class="card shadow-none border">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <tbody>
                                    <tr>
                                        <td class="fw-bold">
                                            <div class="d-flex align-items-center">
                                                <label class="form-label me-2 mb-0">Paid Amount</label>
                                                <select name="payment_method" id="payment_method" class="form-control">
                                                    <option value="">Select Payment Method</option>
                                                    @foreach($bank_accounts as $id => $name)
                                                        <option value="{{ $id }}" {{ $invoice->payment_method == $id ? 'selected' : '' }}>{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Subtotal</td>
                                        <td class="text-end" id="subtotal-amount">{{ number_format($invoice->getSubTotal(), 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">
                                            <div class="d-flex align-items-center">
                                                <label class="form-label me-2 mb-0">Paid Amount</label>
                                                <input type="number" name="paid_amount" id="paid_amount" class="form-control" value="{{ old('paid_amount', $invoice->paid_amount ?? 0) }}" min="0" step="0.01">
                                            </div>
                                        </td>
                                        <td class="text-end" id="paid-amount-display">{{ number_format($invoice->paid_amount ?? 0, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">
                                            <div class="d-flex align-items-center">
                                                <label class="form-label me-2 mb-0">Payment Reference</label>
                                                <input type="text" name="payment_reference" id="payment_reference" class="form-control" placeholder="Transaction ID" value="{{ old('payment_reference', $invoice->payment_reference ?? '') }}">
                                            </div>
                                        </td>
                                        <td class="text-end">-</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">
                                            <div class="d-flex align-items-center">
                                                <label class="form-label me-2 mb-0">Discount</label>
                                                <input type="number" name="discount_apply" id="discount_apply" class="form-control" value="{{ old('discount_apply', $invoice->discount_apply ?? 0) }}" min="0" step="0.01">
                                            </div>
                                        </td>
                                        <td class="text-end" id="discount-amount">{{ number_format($invoice->discount_apply ?? 0, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Tax</td>
                                        <td class="text-end" id="tax-amount">{{ number_format($invoice->getTotalTax(), 2) }}</td>
                                    </tr>
                                    <tr class="border-top">
                                        <td class="fw-bold fs-5">Total Amount</td>
                                        <td class="text-end fw-bold fs-5" id="total-amount">{{ number_format($invoice->getTotal(), 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Due Amount</td>
                                        <td class="text-end fw-bold text-danger" id="due-amount">{{ number_format($invoice->getDue(), 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card p-3">
        <div class="row">
            <div class="col-md-12">
                <h5>Footer Note</h5>
                <textarea id="editor" name="footer_note" readonly>{!! $invoice->footer_text !!}</textarea>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-3">
            <div>
                <button type="button" class="btn btn-outline-secondary me-2">Cancel</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </div>

    <!-- Custom Fields -->
    @if($customFields)
        <div class="card p-3 mt-3">
            <h5>Custom Fields</h5>
            @foreach($customFields as $field)
                <div class="form-group">
                    <label for="custom_field_{{ $field->id }}">{{ $field->name }}</label>
                    <input type="text" name="custom_field[{{ $field->id }}]" id="custom_field_{{ $field->id }}" class="form-control" value="{{ $invoice->customField[$field->id] ?? '' }}">
                </div>
            @endforeach
        </div>
    @endif
</form>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Jodit Editor -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jodit/3.24.5/jodit.min.js"></script>

<script>
    $(document).ready(function() {
        // Counter for dynamic product rows
        let productRowCounter = {{ count($invoice->items) }};

        // Function to calculate amount after discount for a row
        function calculateAmount(row) {
            const quantity = parseFloat(row.find('input[name*="[quantity]"]').val()) || 0;
            const price = parseFloat(row.find('input[name*="[price]"]').val()) || 0;
            const discount = parseFloat(row.find('input[name*="[discount]"]').val()) || 0;
            const tax = parseFloat(row.find('input[name*="[tax]"]').val()) || 0;
            
            const subtotal = price * quantity;
            const taxAmount = subtotal * (tax / 100);
            const total = subtotal - discount + taxAmount;
            row.find('td:last').prev().text(total.toFixed(2));
            
            updateSummaryTable();
        }
        
        // Function to calculate invoice summary totals
        function updateSummaryTable() {
            let subtotal = 0;
            let totalTax = 0;
            let totalItemDiscount = 0;
            
            $('#productTableBody tr').each(function() {
                const quantity = parseFloat($(this).find('input[name*="[quantity]"]').val()) || 0;
                const price = parseFloat($(this).find('input[name*="[price]"]').val()) || 0;
                const discount = parseFloat($(this).find('input[name*="[discount]"]').val()) || 0;
                const tax = parseFloat($(this).find('input[name*="[tax]"]').val()) || 0;
                
                const rowSubtotal = price * quantity;
                subtotal += rowSubtotal;
                totalItemDiscount += discount;
                totalTax += rowSubtotal * (tax / 100);
            });
            
            const serviceCharge = parseFloat($('#service_charge').val()) || 0;
            subtotal += serviceCharge;
            
            const additionalDiscount = parseFloat($('#discount_apply').val()) || 0;
            const paidAmount = parseFloat($('#paid_amount').val()) || 0;
            const totalDiscount = additionalDiscount;
            
            const totalAmount = subtotal - totalDiscount + totalTax;
            const dueAmount = totalAmount - paidAmount;
            
            $('#subtotal-amount').text(subtotal.toFixed(2));
            $('#discount-amount').text(totalDiscount.toFixed(2));
            $('#tax-amount').text(totalTax.toFixed(2));
            $('#total-amount').text(totalAmount.toFixed(2));
            $('#paid-amount-display').text(paidAmount.toFixed(2));
            $('#due-amount').text(dueAmount.toFixed(2));
        }

        // Add event listener for input changes
        $('#discount_apply, #paid_amount, #service_charge').on('input', function() {
            updateSummaryTable();
        });

        // Initialize Select2 for customer search
        $('.js-customer-select').select2({
            ajax: {
                url: '{{ route("invoice.customers.search") }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                        type: $('select[name="type"]').val()
                    };
                },
                processResults: function(data) {
                    const searchType = $('select[name="type"]').val();
                    return {
                        results: $.map(data, function(item) {
                            let displayText = '';
                            switch(searchType) {
                                case 'sl-no':
                                    displayText = item.name + ' (#' + item.customer_id + ')';
                                    break;
                                case 'email':
                                    displayText = item.name + ' (' + item.email + ')';
                                    break;
                                case 'contact':
                                    displayText = item.name + ' (' + item.contact + ')';
                                    break;
                                default:
                                    displayText = item.name;
                                    break;
                            }
                            return {
                                id: item.id,
                                text: displayText,
                                customerData: item
                            };
                        })
                    };
                },
                cache: true
            },
            placeholder: "Select a customer",
            minimumInputLength: 2,
            allowClear: true,
            width: '100%',
            theme: 'default'
        }).on('select2:select', function(e) {
            const customerData = e.params.data.customerData;
            $('#billing_address_line_1').val(customerData.billing_address || '');
            $('#billing_city').val(customerData.billing_city || '');
            $('#billing_state').val(customerData.billing_state || '');
            $('#billing_zip_code').val(customerData.billing_zip || '');
            $('#shipping_address_line_1').val(customerData.billing_address || '');
            $('#shipping_city').val(customerData.billing_city || '');
            $('#shipping_state').val(customerData.billing_state || '');
            $('#shipping_zip_code').val(customerData.billing_zip || '');
        }).on('select2:clear', function() {
            $('#billing_address_line_1, #billing_city, #billing_state, #billing_zip_code').val('');
            $('#shipping_address_line_1, #shipping_city, #shipping_state, #shipping_zip_code').val('');
        });

        // Function to initialize Select2 for product dropdowns
        function initializeProductSelect(element, index) {
            $(element).select2({
                ajax: {
                    url: '{{ route("invoice.products.search") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    id: item.id,
                                    text: item.name + ' (' + item.sku + ') - ' + item.sale_price + '৳'
                                };
                            })
                        };
                    },
                    cache: true
                },
                placeholder: "Select a product",
                minimumInputLength: 2,
                allowClear: true,
                width: '100%',
                theme: 'default'
            }).on('select2:select', function(e) {
                const selectedData = e.params.data;
                const row = $(this).closest('tr');
                const priceField = row.find('input[name="items[' + index + '][price]"]');
                const price = selectedData.text.match(/(\d+\.\d+|\d+)৳/);
                if (price) {
                    priceField.val(parseFloat(price[0].replace('৳', '')));
                    calculateAmount(row);
                }
            });
        }

        // Initialize Select2 for existing product dropdowns
        @foreach($invoice->items as $index => $item)
            initializeProductSelect('#product_id_{{ $index }}', {{ $index }});
        @endforeach

        // Add event listeners for quantity, price, and discount changes
        $(document).on('input', 'input[name*="[quantity]"], input[name*="[price]"], input[name*="[discount]"]', function() {
            calculateAmount($(this).closest('tr'));
        });

        // Add new product row
        $('#addItemBtn').on('click', function() {
            const newRow = `
                <tr>
                    <td>
                        <div class="form-group">
                            <select class="form-control js-product-select" id="product_id_${productRowCounter}" name="items[${productRowCounter}][item]" required>
                                <option value="">Select a product</option>
                            </select>
                        </div>
                        <textarea class="form-control mt-2" name="items[${productRowCounter}][description]" placeholder="Description" rows="2"></textarea>
                    </td>
                    <td><input name="items[${productRowCounter}][quantity]" type="number" class="form-control" value="1"></td>
                    <td><input name="items[${productRowCounter}][price]" type="number" class="form-control" value="0.00"></td>
                    <td><input name="items[${productRowCounter}][discount]" type="number" class="form-control" value="0"></td>
                    <td><input name="items[${productRowCounter}][tax]" type="number" class="form-control" value="0"></td>
                    <td>0.00</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-row">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#productTableBody').append(newRow);
            initializeProductSelect(`#product_id_${productRowCounter}`, productRowCounter);
            productRowCounter++;
            updateSummaryTable();
        });

        // Handle row removal
        $(document).on('click', '.remove-row', function() {
            const rowCount = $('#productTableBody tr').length;
            if (rowCount > 1) {
                $(this).closest('tr').remove();
                productRowCounter--;
                updateSummaryTable();
            }
        });

        // Calculate initial amounts
        $('#productTableBody tr').each(function() {
            calculateAmount($(this));
        });
        
        // Initialize summary table
        updateSummaryTable();

        // Handle invoice category selection change
        $('#invoice_category').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const footerNote = selectedOption.data('footer-note');
            editor.value = footerNote || '';
        });

        // Initialize Jodit editor
        const editor = new Jodit('#editor', {
            height: 300,
            toolbar: true,
            buttons: [
                'source', '|',
                'bold', 'italic', 'underline', 'strikethrough', '|',
                'font', 'fontsize', 'brush', 'paragraph', '|',
                'align', '|',
                'ul', 'ol', '|',
                'table', 'link', '|',
                'undo', 'redo', '|',
                'hr', 'eraser', 'copyformat', '|',
                'symbol', 'fullsize', 'print', 'about'
            ],
            uploader: {
                insertImageAsBase64URI: true
            },
            removeButtons: ['image'],
            showCharsCounter: true,
            showWordsCounter: true,
            showXPathInStatusbar: false
        });

        // Set initial footer note
        editor.value = '{{ $invoice->footer_note ?? '' }}';
    });
</script>
@endsection