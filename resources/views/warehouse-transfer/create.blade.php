{{ Form::open(array('route' => 'warehouse-transfer.store')) }}

<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('from_warehouse', __('From Warehouse'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            <select class="form-control select" name="from_warehouse" id="warehouse_id" placeholder="Select Warehouse">
                <option value="0" selected>Unlisted</option>
                @foreach($from_warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-6">
            {{Form::label('to_warehouse',__('To Warehouse'),array('class'=>'form-label')) }}<span class="text-danger">*</span>
            {{ Form::select('to_warehouse', $to_warehouses,null, array('class' => 'form-control select','required'=>'required')) }}
        </div>
    </div>

    <div class="row mb-3 justify-content-end">
        <button class="btn btn-primary" type="button" style="width: max-content;" id="add-product">Add Product</button>
    </div>

    <div id="product-container">
        <div class="row product-row">
            <div class="form-group col-md-4" id="product_div">
                {{Form::label('product',__('Product'),array('class'=>'form-label')) }}
                <select class="form-control select product-select" name="product_id[]" placeholder="Select Product">
                    <option value="">{{ __('Select Product') }}</option>
                </select>
            </div>

            <div class="form-group col-md-4" id="qty_div">
                {{ Form::label('quantity', __('Quantity'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                {{ Form::number('quantity[]',null, array('class' => 'form-control quantity-input', 'min' => 1)) }}
            </div>

            <div class="form-group col-lg-4">
                {{Form::label('date',__('Date'))}}
                {{Form::date('date[]',null,array('class'=>'form-control datepicker w-100 mt-2'))}}
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn btn-primary">
</div>
{{ Form::close() }}

<script>
    $(document).ready(function() {
        let rowCounter = 0;

        // Initialize Select2 for the first row
        initializeSelect2($('.product-row:first'));

        $('#add-product').click(function() {
            rowCounter++;
            
            // Clone the first row (template)
            var templateRow = $('.product-row:first').clone();
            
            // Clean up the cloned row
            cleanClonedRow(templateRow, rowCounter);
            
            // Append the new row
            $('#product-container').append(templateRow);
            
            // Initialize Select2 for the new row
            initializeSelect2(templateRow);
            
            // Load products if warehouse is already selected
            var warehouse_id = $('#warehouse_id').val();
            if(warehouse_id && warehouse_id !== '0') {
                loadProductsForRow(templateRow, warehouse_id);
            }
        });

        // Function to clean cloned row
        function cleanClonedRow(row, counter) {
            // Remove any Select2 elements
            row.find('.select2-container').remove();
            row.find('.select2').remove();
            
            // Reset the select element
            var productSelect = row.find('select[name="product_id[]"]');
            productSelect.removeClass('select2-hidden-accessible')
                         .removeAttr('data-select2-id')
                         .removeAttr('aria-hidden')
                         .removeAttr('tabindex')
                         .show();
            
            // Generate unique IDs
            row.find('#product_div').attr('id', 'product_div_' + counter);
            row.find('#qty_div').attr('id', 'qty_div_' + counter);
            productSelect.attr('id', 'product_id_' + counter);
            row.find('.quantity-input').attr('id', 'quantity_' + counter);
            
            // Clear values
            row.find('select').val('');
            row.find('input').val('');
            
            // Reset select options to default
            productSelect.html('<option value="">{{ __("Select Product") }}</option>');
        }

        // Function to initialize Select2 and bind events for a row
        function initializeSelect2(row) {
            var productSelect = row.find('select[name="product_id[]"]');
            
            // Destroy existing Select2 if it exists
            if (productSelect.hasClass("select2-hidden-accessible")) {
                productSelect.select2('destroy');
            }
            
            // Initialize Select2
            productSelect.select2({
                dropdownParent: $('#commonModal'),
                theme: 'bootstrap4',
                placeholder: "Select Product",
                allowClear: true,
                width: '100%'
            });

            // Bind change event for product selection
            productSelect.off('change.productSelect').on('change.productSelect', function() {
                var product_id = $(this).val();
                var warehouse_id = $('#warehouse_id').val();
                var quantityInput = row.find('.quantity-input');
                
                if(product_id && warehouse_id && warehouse_id !== '0') {
                    getQuantity(product_id, warehouse_id, quantityInput);
                }
            });
        }

        // Function to load products for a specific row
        function loadProductsForRow(row, warehouse_id) {
            $.ajax({
                url: '{{ route('warehouse-transfer.getproduct') }}',
                type: 'POST',
                data: {
                    "warehouse_id": warehouse_id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function (data) {
                    var productSelect = row.find('select[name="product_id[]"]');
                    var currentValue = productSelect.val();
                    
                    // Destroy existing Select2 instance
                    if(productSelect.hasClass("select2-hidden-accessible")) {
                        productSelect.select2('destroy');
                    }
                    
                    // Clear and rebuild options
                    productSelect.empty();
                    productSelect.append('<option value="">{{ __('Select Product') }}</option>');
                    
                    $.each(data.ware_products, function (key, value) {
                        productSelect.append('<option value="' + key + '">' + value + '</option>');
                    });
                    
                    // Restore previous value if it exists in new options
                    if(currentValue) {
                        productSelect.val(currentValue);
                    }
                    
                    // Reinitialize Select2
                    initializeSelect2(row);
                }
            });
        }

        // Modified getQuantity function
        function getQuantity(pid, wid, quantityInput) {
            $.ajax({
                url: '{{ route('warehouse-transfer.getquantity') }}',
                type: 'POST',
                data: {
                    "product_id": pid,
                    "warehouse_id": wid,
                    "_token": "{{ csrf_token() }}",
                },
                success: function (data) {
                    quantityInput.attr('max', data);
                    quantityInput.attr('placeholder', 'Available: ' + data);
                }
            });
        }

        // Handle warehouse change for all product rows
        $(document).on('change', 'select[name=from_warehouse]', function () {
            var warehouse_id = $(this).val();
            
            if(warehouse_id && warehouse_id !== '0') {
                // Update all existing product rows
                $('.product-row').each(function() {
                    loadProductsForRow($(this), warehouse_id);
                });
            } else {
                // Clear all product selects if no warehouse selected
                $('.product-row').each(function() {
                    var productSelect = $(this).find('select[name="product_id[]"]');
                    if(productSelect.hasClass("select2-hidden-accessible")) {
                        productSelect.select2('destroy');
                    }
                    productSelect.html('<option value="">{{ __("Select Product") }}</option>');
                    initializeSelect2($(this));
                });
            }
            
            // Update to_warehouse dropdown
            if(warehouse_id && warehouse_id !== '0') {
                $.ajax({
                    url: '{{ route('warehouse-transfer.getproduct') }}',
                    type: 'POST',
                    data: {
                        "warehouse_id": warehouse_id,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function (data) {
                        $('select[name=to_warehouse]').empty();
                        $.each(data.to_warehouses, function (key, value) {
                            $('select[name=to_warehouse]').append('<option value="' + key + '">' + value + '</option>');
                        });
                    }
                });
            }
        });
    });
</script>