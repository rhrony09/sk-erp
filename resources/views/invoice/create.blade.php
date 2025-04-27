@extends('layouts.admin')
@section('page-title')
    {{ __('Invoice Create') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('invoice.index') }}">{{ __('Invoice') }}</a></li>
    <li class="breadcrumb-item">{{ __('Invoice Create') }}</li>
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

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #3E3F4A !important;
        color: #fff !important;
    }
</style>
<form action="{{ route('invoice.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="card p-3">
                <div class="form-group">
                    <label for="customer_id">Customer</label>
                    <select class="form-control js-customer-select" id="customer_id" name="customer_id" required>
                        <option value="">Select a customer</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-3">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="issueDate" class="form-label">Issue Date</label>
                        <input type="date" class="form-control" id="issueDate" name="issue_date">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="dueDate" class="form-label">Due Date</label>
                        <input type="date" class="form-control" id="dueDate" name="due_date">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="invoiceNumber" class="form-label">Invoice Number</label>
                        <input type="text" class="form-control" id="invoiceNumber" value="{{ $invoice_number }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" id="category" name="category_id">
                            <option value="">Select Category</option>
                            @foreach($category as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="refNumber" class="form-label">Ref Number</label>
                        <input type="text" class="form-control" id="refNumber" name="ref_number" placeholder="Enter REF">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="note" class="form-label">Add Note</label>
                    <textarea class="form-control" id="note" rows="3" name="note"></textarea>
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
                    <tr>
                        <td>
                            <div class="form-group">
                                <label for="product_id_0">Product</label>
                                <select class="form-control js-product-select" id="product_id_0" name="items[0][item]" required>
                                    <option value="">Select a product</option>
                                </select>
                            </div>
                            <textarea class="form-control mt-2" name="items[0][description]" placeholder="Description" rows="2"></textarea>
                        </td>
                        <td><input name="items[0][quantity]" type="number" class="form-control" value="1"></td>
                        <td><input name="items[0][price]" type="number" class="form-control" value="0.00"></td>
                        <td><input name="items[0][discount]" type="number" class="form-control" value="0"></td>
                        <td><input name="items[0][tax]" type="number" class="form-control" value="0"></td>
                        <td>0.00</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-row" disabled>
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between mt-3">
            <div>
                <button type="button" class="btn btn-outline-secondary me-2">Cancel</button>
                <button type="submit" class="btn btn-primary">Create</button>
            </div>
        </div>
    </div>
</form>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Counter for dynamic product rows
        let productRowCounter = 1;

        // Function to calculate amount after discount
        function calculateAmount(row) {
            const quantity = parseFloat(row.find('input[name*="[quantity]"]').val()) || 0;
            const price = parseFloat(row.find('input[name*="[price]"]').val()) || 0;
            const discount = parseFloat(row.find('input[name*="[discount]"]').val()) || 0;
            
            const total = (price * quantity) - discount;
            row.find('td:last').prev().text(total.toFixed(2));
        }

        // Initialize Select2 for customer search
        $('.js-customer-select').select2({
            ajax: {
                url: '{{ route("invoice.customers.search") }}',
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
                                text: item.name + ' (' + item.email + ')'
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
                // Update the price field when a product is selected
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

        // Initialize Select2 for the initial product dropdown
        initializeProductSelect('#product_id_0', 0);

        // Add event listeners for quantity, price, and discount changes
        $(document).on('input', 'input[name*="[quantity]"], input[name*="[price]"], input[name*="[discount]"]', function() {
            calculateAmount($(this).closest('tr'));
        });

        // Add new product row on "Add Item" click
        $('#addItemBtn').on('click', function() {
            const newRow = `
                <tr>
                    <td>
                        <div class="form-group">
                            <label for="product_id_${productRowCounter}">Product</label>
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

            // Initialize Select2 for the newly added product dropdown
            initializeProductSelect(`#product_id_${productRowCounter}`, productRowCounter);

            productRowCounter++;
        });

        // Handle row removal
        $(document).on('click', '.remove-row', function() {
            const rowCount = $('#productTableBody tr').length;
            if (rowCount > 1) {
                $(this).closest('tr').remove();
                productRowCounter--;
            }
        });

        // Calculate initial amounts
        $('#productTableBody tr').each(function() {
            calculateAmount($(this));
        });
    });
</script>
@endsection