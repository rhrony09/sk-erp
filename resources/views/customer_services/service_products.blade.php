<div class="modal-body">
    <div class="card ">
        <div class="card-header card-body table-border-style">
            <h5> {{ __('Service Products') }}</h5>
        </div>

        <div class="card-body table-border-style full-card">
            {{ Form::open(['route' => ['customer_services.store_service_products', $customer_service->id], 'method' => 'post', 'id' => 'add_form']) }}

            @forelse ($service_products as $service_product)
                <div class="row">
                    <div class="form-group col-md-4">
                        {{ Form::label('product_id', __('Product'), ['class' => 'form-label']) }}<span
                            class="text-danger">*</span>
                        {{ Form::select('product_id[]', $products, $service_product->product_id, ['class' => 'form-control select2', 'id' => 'product_id1', 'required' => 'required']) }}
                    </div>
                    <div class="col-md-4">
                        {{ Form::label('quantity', __('Quantity'), ['class' => 'form-label']) }}<span
                            class="text-danger">*</span>
                        {{ Form::number('quantity[]', $service_product->quantity, ['class' => 'form-control', 'id' => 'quantity1', 'required' => 'required', 'step' => '0.01']) }}
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
            @empty
                <div class="row">
                    <div class="form-group col-md-4">
                        {{ Form::label('product_id', __('Product'), ['class' => 'form-label']) }}<span
                            class="text-danger">*</span>
                        {{ Form::select('product_id[]', $products, null, ['class' => 'form-control select2', 'id' => 'product_id1', 'required' => 'required']) }}
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
            @endforelse
            <div class="row">
                <div class="col-md-12">
                    {{ Form::submit(__('Add'), ['class' => 'btn btn-primary']) }}
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
