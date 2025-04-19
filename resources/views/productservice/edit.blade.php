{{ Form::model($productService, array('route' => array('productservice.update', $productService->id), 'method' => 'PUT','enctype' => "multipart/form-data")) }}
<div class="modal-body">
    {{-- start for ai module--}}
    @php
        $settings = \App\Models\Utility::settings();
    @endphp
    @if($settings['ai_chatgpt_enable'] == 'on')
        <div class="text-end">
            <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="{{ route('generate',['productservice']) }}"
               data-bs-placement="top" data-title="{{ __('Generate content with AI') }}">
                <i class="fas fa-robot"></i> <span>{{__('Generate with AI')}}</span>
            </a>
        </div>
    @endif
    {{-- end for ai module--}}
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('name', __('Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                {{ Form::text('name',null, array('class' => 'form-control','required'=>'required', 'id' => 'product_name')) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('sku', __('SKU'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                {{ Form::text('sku', null, array('class' => 'form-control','required'=>'required')) }}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('sale_price', __('Sale Price'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                {{ Form::number('sale_price', null, array('class' => 'form-control','required'=>'required','step'=>'0.01')) }}
            </div>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('sale_chartaccount_id', __('Income Account'),['class'=>'form-label']) }}
            <select name="sale_chartaccount_id" class="form-control" required="required">
                @foreach ($incomeChartAccounts as $key => $chartAccount)
                    <option value="{{ $key }}" class="subAccount" {{ ($productService->sale_chartaccount_id == $key) ? 'selected' : ''}}>{{ $chartAccount }}</option>
                    @foreach ($incomeSubAccounts as $subAccount)
                        @if ($key == $subAccount['account'])
                            <option value="{{ $subAccount['id'] }}" class="ms-5" {{ ($productService->sale_chartaccount_id == $subAccount['id']) ? 'selected' : ''}}> &nbsp; &nbsp;&nbsp; {{ $subAccount['code_name'] }}</option>
                        @endif
                    @endforeach
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('purchase_price', __('Purchase Price'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                {{ Form::number('purchase_price', null, array('class' => 'form-control','required'=>'required','step'=>'0.01')) }}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('discount_price', __('Discount Price'),['class'=>'form-label']) }}
                {{ Form::number('discount_price', null, array('class' => 'form-control','step'=>'0.01')) }}
            </div>
        </div>

        <div class="form-group col-md-6">
            {{ Form::label('expense_chartaccount_id', __('Expense Account'),['class'=>'form-label']) }}
            <select name="expense_chartaccount_id" class="form-control" required="required">
                @foreach ($expenseChartAccounts as $key => $chartAccount)
                    <option value="{{ $key }}" class="subAccount" {{ ($productService->expense_chartaccount_id == $key) ? 'selected' : ''}}>{{ $chartAccount }}</option>
                    @foreach ($expenseSubAccounts as $subAccount)
                        @if ($key == $subAccount['account'])
                            <option value="{{ $subAccount['id'] }}" class="ms-5" {{ ($productService->expense_chartaccount_id == $subAccount['id']) ? 'selected' : ''}}> &nbsp; &nbsp;&nbsp; {{ $subAccount['code_name'] }}</option>
                        @endif
                    @endforeach
                @endforeach
            </select>
        </div>

        <div class="form-group  col-md-6">
            {{ Form::label('tax_id', __('Tax'),['class'=>'form-label']) }}
            {{ Form::select('tax_id[]', $tax,null, array('class' => 'form-control select2','id'=>'choices-multiple1','multiple'=>'')) }}
        </div>

        <div class="form-group  col-md-6">
            {{ Form::label('category_id', __('Category'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::select('category_id', $category, $productService->category_id ?? null, array('class' => 'form-control select','required'=>'required')) }}
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('unit_id', __('Unit'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::select('unit_id', $unit,null, array('class' => 'form-control select','required'=>'required')) }}
        </div>

        <div class="col-md-6 form-group">
            {{Form::label('pro_image',__('Product Image'),['class'=>'form-label'])}}
            <div class="choose-file ">
                <label for="pro_image" class="form-label">
                    <input type="file" class="form-control" name="pro_image" id="pro_image" data-filename="pro_image_create">
                    <img id="image"  class="mt-3" width="100" src="@if($productService->pro_image){{asset(Storage::url('uploads/pro_image/'.$productService->pro_image))}}@else{{asset(Storage::url('uploads/pro_image/user-2_1654779769.jpg'))}}@endif" />
                </label>
            </div>
        </div>



        <div class="col-md-6">
            <div class="form-group">
                <label class="d-block form-label">{{__('Type')}}</label>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input type" id="customRadio5" name="type" value="product" @if($productService->type=='product') checked @endif >
                            <label class="custom-control-label form-label" for="customRadio5">{{__('Product')}}</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input type" id="customRadio6" name="type" value="service" @if($productService->type=='service') checked @endif >
                            <label class="custom-control-label form-label" for="customRadio6">{{__('Service')}}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group col-md-6 quantity {{$productService->type=='service' ? 'd-none':''}}">
            {{ Form::label('quantity', __('Quantity'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::text('quantity',null, array('class' => 'form-control','required'=>'required')) }}
        </div>

        <div class="form-group col-md-12">
            {{ Form::checkbox('is_featured', '1', $productService->is_featured == 1 ? true : false, ['class' => 'summernote-simple']) }}
            {{ Form::label('is_featured', __('Featured'), ['class' => 'form-label']) }}
        </div>

        <div class="form-group  col-md-12">
            {{ Form::label('description', __('Description'),['class'=>'form-label']) }}
            {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'2']) !!}
        </div>

        <div id="attributes-container">
            @if(count($productAttributes) > 0)
                @foreach($productAttributes as $index => $productAttribute)
                    <div class="row attribute-row mb-3">
                        <div class="col-md-4">
                            <select id="attribute-select-{{ $index + 1 }}" name="attributes[]" class="form-control attribute-select">
                                <option value="">Select Attribute</option>
                                @foreach ($attributes as $attribute)
                                    <option value="{{ $attribute->id }}" {{ $attribute->id == $productAttribute->attributeValue->attribute_id ? 'selected' : '' }}>
                                        {{ $attribute->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-7">
                            <select class="form-control value-select" name="attributeValues[]">
                                <option value="">Select an attribute first</option>
                                @foreach($attributes->firstWhere('id', $productAttribute->attributeValue->attribute_id)->values as $value)
                                    <option value="{{ $value->id }}" {{ $value->id == $productAttribute->attribute_value_id ? 'selected' : '' }}>
                                        {{ $value->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger remove-attribute-btn" {{ $index > 0 ? '' : 'style="display: none;"' }}>
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Default empty row if no attributes exist -->
                <div class="row attribute-row mb-3">
                    <div class="col-md-4">
                        <select id="attribute-select-1" name="attributes[]" class="form-control attribute-select">
                            <option value="">Select Attribute</option>
                            @foreach ($attributes as $attribute)
                                <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-7">
                        <select class="form-control value-select" name="attributeValues[]">
                            <option value="">Select an attribute first</option>
                        </select>
                    </div>
                    
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger remove-attribute-btn" style="display: none;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Add attribute button -->
        <div class="row mt-3">
            <div class="col-12">
                <button type="button" class="btn btn-primary" id="add-attribute-btn">
                    <i class="fas fa-plus"></i> Add Another Attribute
                </button>
            </div>
        </div>


    </div>
    @if(!$customFields->isEmpty())
        <div class="col-md-6">
            <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                @include('customFields.formBuilder')
            </div>
        </div>
    @endif
</div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-primary">
</div>
{{Form::close()}}
<script>
    document.getElementById('pro_image').onchange = function () {
        var src = URL.createObjectURL(this.files[0])
        document.getElementById('image').src = src
    }

    //hide & show quantity

    $(document).on('click', '.type', function ()
    {
        var type = $(this).val();
        if (type == 'product') {
            $('.quantity').removeClass('d-none')
            $('.quantity').addClass('d-block');
        } else {
            $('.quantity').addClass('d-none')
            $('.quantity').removeClass('d-block');
        }
    });
</script>

<script>
    $(document).ready(function() {
        $('#product_name').on('keyup change', function() {
            var nameValue = $(this).val();
            var slugValue = nameValue
                .toLowerCase()
                .replace(/\s+/g, '-')           // Replace spaces with -
                .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                .replace(/^-+/, '')             // Trim - from start of text
                .replace(/-+$/, '');            // Trim - from end of text
            
            $('#product_slug').val(slugValue);
        });
    });
</script>
@php
    $attributesJson = $attributes->toJson();
@endphp
<script>
    $(document).ready(function() {
    // Parse attributes data from Blade to JavaScript
    let attributes = {!! $attributesJson !!};
    
    // Track selected attributes
    let selectedAttributes = {};

    $('.attribute-select').each(function() {
        setupAttributeSelect($(this));
        
        // Track currently selected attributes
        let attributeId = $(this).val();
        if (attributeId) {
            selectedAttributes[attributeId] = $(this).attr('id');
            $(this).data('previous-value', attributeId);
        }
    });
    
    // Update all dropdowns to reflect current selections
    updateAllDropdowns();

    $('.remove-attribute-btn').on('click', function() {
        // Get the attribute ID before removing
        let attributeId = $(this).closest('.attribute-row').find('.attribute-select').val();
        
        // Remove the row
        $(this).closest('.attribute-row').remove();
        
        // Remove from selected attributes if it was selected
        if (attributeId) {
            delete selectedAttributes[attributeId];
            updateAllDropdowns();
        }
    });
    
    // Initial setup for the first row
    setupAttributeSelect($('.attribute-select').first());
    
    // Add new attribute row
    $('#add-attribute-btn').on('click', function() {
        // Clone the first row
        let newRow = $('.attribute-row').first().clone();
        let newId = 'attribute-select-' + ($('.attribute-row').length + 1);
        
        // Reset selects and assign new ID
        newRow.find('.attribute-select').val('').attr('id', newId);
        newRow.find('.value-select').html('<option value="">Select an attribute first</option>');
        
        // Show remove button
        newRow.find('.remove-attribute-btn').show();
        
        // Add the new row
        $('#attributes-container').append(newRow);
        
        // Setup the new attribute select
        setupAttributeSelect(newRow.find('.attribute-select'));
        
        // Setup remove functionality
        newRow.find('.remove-attribute-btn').on('click', function() {
            // Get the attribute ID before removing
            let attributeId = $(this).closest('.attribute-row').find('.attribute-select').val();
            
            // Remove the row
            $(this).closest('.attribute-row').remove();
            
            // Remove from selected attributes if it was selected
            if (attributeId) {
                delete selectedAttributes[attributeId];
                updateAllDropdowns();
            }
        });
        
        // Update dropdowns in the new row
        updateDropdown(newRow.find('.attribute-select'));
    });
    
    // Function to setup attribute select change handler
    function setupAttributeSelect(select) {
        select.on('change', function() {
            let row = $(this).closest('.attribute-row');
            let valueSelect = row.find('.value-select');
            let oldValue = $(this).data('previous-value');
            let newValue = $(this).val();
            
            // Remove old selection from tracking
            if (oldValue) {
                delete selectedAttributes[oldValue];
            }
            
            // Add new selection to tracking
            if (newValue) {
                selectedAttributes[newValue] = select.attr('id');
            }
            
            // Store current value for future reference
            $(this).data('previous-value', newValue);
            
            // Clear and update the values dropdown
            valueSelect.html('<option value="">Select an attribute first</option>');
            
            if (newValue) {
                // Find the selected attribute
                let selectedAttribute = attributes.find(attr => attr.id == newValue);
                
                // Populate values
                if (selectedAttribute && selectedAttribute.values.length > 0) {
                    selectedAttribute.values.forEach(value => {
                        valueSelect.append(`<option value="${value.id}">${value.name}</option>`);
                    });
                }
            }
            
            // Update all dropdowns
            updateAllDropdowns();
        });
    }
    
    // Update all attribute dropdowns
    function updateAllDropdowns() {
        $('.attribute-select').each(function() {
            updateDropdown($(this));
        });
    }
    
    // Update a single dropdown
    function updateDropdown(select) {
        let currentValue = select.val();
        let currentId = select.attr('id');
        
        // Disable/enable options based on selections
        select.find('option').each(function() {
            let optionValue = $(this).val();
            
            // Skip the empty option
            if (!optionValue) return;
            
            // Disable if selected elsewhere
            if (selectedAttributes[optionValue] && selectedAttributes[optionValue] !== currentId) {
                $(this).prop('disabled', true);
            } else {
                $(this).prop('disabled', false);
            }
        });
    }
    
    // Initialize dropdowns
    updateAllDropdowns();
});
</script>