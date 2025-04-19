<div class="modal-body">
    <div class="card ">
        <div class="card-header card-body table-border-style">
            <h5> {{ __("Raw Materials of $product->name") }}</h5>
            {{-- @php
                print_r($product->name);
            @endphp --}}
        </div>

        <div class="card-body table-border-style full-card">
            {{ Form::open(['route' => ['productservice.store_raw_materials', $product->id], 'method' => 'post', 'id' => 'add_form']) }}

            @foreach ($product_raw_materials as $raw_material)
                <div class="row">
                    <div class="form-group col-md-4">
                        {{ Form::label('raw_material_id', __('Raw Material'), ['class' => 'form-label']) }}<span
                            class="text-danger">*</span>
                        {{ Form::select('raw_material_id[]', $raw_materials, $raw_material->id, ['class' => 'form-control select', 'id' => 'raw_material_id1', 'required' => 'required']) }}
                    </div>
                    <div class="col-md-4">
                        {{ Form::label('quantity', __('Quantity'), ['class' => 'form-label']) }}<span
                            class="text-danger">*</span>
                        {{ Form::number('quantity[]', $raw_material->quantity, ['class' => 'form-control', 'id' => 'quantity1', 'required' => 'required', 'step' => '0.01']) }}
                    </div>
                    <div class="col-md-4 pt-4">
                        <div class="form-group mt-2">
                            <button type="button" class="btn btn-primary btn-sm f-14 addNewRow"><i
                                    class="fa fa-plus"></i></button>
                            <button type="button" class="btn btn-danger btn-sm f-14 remove" name="button"><i
                                    class="fa fa-minus"></i></button>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="row">
                <div class="form-group col-md-4">
                    {{ Form::label('raw_material_id', __('Raw Material'), ['class' => 'form-label']) }}<span
                        class="text-danger">*</span>
                    {{ Form::select('raw_material_id[]', $raw_materials, null, ['class' => 'form-control select', 'id' => 'raw_material_id1', 'required' => 'required']) }}
                </div>
                <div class="col-md-4">
                    {{ Form::label('quantity', __('Quantity'), ['class' => 'form-label']) }}<span
                        class="text-danger">*</span>
                    {{ Form::number('quantity[]', null, ['class' => 'form-control', 'id' => 'quantity1', 'required' => 'required', 'step' => '0.01']) }}
                </div>
                <div class="col-md-4 pt-4">
                    <div class="form-group mt-2">
                        <button type="button" class="btn btn-primary btn-sm f-14 addNewRow"><i
                                class="fa fa-plus"></i></button>
                        <button type="button" class="btn btn-danger btn-sm f-14 remove" name="button"><i
                                class="fa fa-minus"></i></button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    {{ Form::submit(__('Add'), ['class' => 'btn btn-primary']) }}
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
