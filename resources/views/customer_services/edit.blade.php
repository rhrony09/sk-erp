{{ Form::model($customer_service, ['route' => ['customer_services.update', $customer_service->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    {{-- start for ai module --}}
    @php
        $settings = \App\Models\Utility::settings();
    @endphp
    @if ($settings['ai_chatgpt_enable'] == 'on')
        <div class="text-end">
            <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="{{ route('generate', ['productservice']) }}" data-bs-placement="top" data-title="{{ __('Generate content with AI') }}">
                <i class="fas fa-robot"></i> <span>{{ __('Generate with AI') }}</span>
            </a>
        </div>
    @endif
    {{-- end for ai module --}}
    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('customer_id', __('Customer'), ['class' => 'form-label']) }}
            <select name="customer_id" class="form-control" required>
                @foreach ($customers as $key => $customer)
                    <option value="{{ $key }}" class="subAccount" {{ $customer_service->customer_id == $key ? 'selected' : ''}}>{{ $customer }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('employee_id', __('Assign Employee'), ['class' => 'form-label']) }}
            <select name="employee_id" class="form-control" required>
                @foreach ($employees as $key => $employee)
                    <option value="{{ $key }}" class="subAccount" {{ $customer_service->employee_id == $key ? 'selected' : ''}}>{{ $employee }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('phone_number', __('Phone Number'),['class'=>'form-label']) }}
                {{ Form::text('phone_number', null, array('class' => 'form-control')) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('address', __('Address'),['class'=>'form-label']) }}
                {{ Form::text('address', null, array('class' => 'form-control')) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('due_date', __('Due Date'), ['class' => 'form-label']) }}
                {{ Form::date('due_date', null, ['class' => 'form-control']) }}
            </div>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}
            <select name="status" class="form-control" required>
                <option value="" class="subAccount"> -- Select an option -- </option>
                <option value="0" class="subAccount" {{ $customer_service->status == 0 ? 'selected' : ''}}> {{ __('Pending') }} </option>
                <option value="1" class="subAccount" {{ $customer_service->status == 1 ? 'selected' : ''}}> {{ __('Received') }} </option>
                <option value="2" class="subAccount" {{ $customer_service->status == 2 ? 'selected' : ''}}> {{ __('In progress') }} </option>
                <option value="3" class="subAccount" {{ $customer_service->status == 3 ? 'selected' : ''}}> {{ __('On hold') }} </option>
                <option value="4" class="subAccount" {{ $customer_service->status == 4 ? 'selected' : ''}}> {{ __('Completed') }} </option>
                <option value="5" class="subAccount" {{ $customer_service->status == 5 ? 'selected' : ''}}> {{ __('Cancelled') }} </option>
            </select>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('service_charge', __('Service Charge'), ['class' => 'form-label']) }}
                {{ Form::number('service_charge', $customer_service->service_charge, ['class' => 'form-control', 'step' => '0.01']) }}
            </div>
        </div>

        <div class="form-group col-md-12">
            {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
            {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => '2']) !!}
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="d-block form-label">{{ __('Payment') }}</label>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input type" id="customRadio5" name="is_paid" value="0" @if ($customer_service->is_paid == 0) checked @endif>
                            <label class="custom-control-label form-label" for="customRadio5">{{ __('Unpaid') }}</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input type" id="customRadio6" name="is_paid" value="1" @if ($customer_service->is_paid == 1) checked @endif>
                            <label class="custom-control-label form-label" for="customRadio6">{{ __('Paid') }}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
<script>
    document.getElementById('pro_image').onchange = function() {
        var src = URL.createObjectURL(this.files[0])
        document.getElementById('image').src = src
    }

    //hide & show quantity

    $(document).on('click', '.type', function() {
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
