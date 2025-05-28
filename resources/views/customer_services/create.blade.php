{{ Form::open(['route' => 'customer_services.store', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    {{-- start for ai module --}}
    @php
        $settings = \App\Models\Utility::settings();
    @endphp
    @if ($settings['ai_chatgpt_enable'] == 'on')
        <div class="text-end">
            <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true"
                data-url="{{ route('generate', ['productservice']) }}" data-bs-placement="top"
                data-title="{{ __('Generate content with AI') }}">
                <i class="fas fa-robot"></i> <span>{{ __('Generate with AI') }}</span>
            </a>
        </div>
    @endif
    {{-- end for ai module --}}
    <div class="row">
        @if (Auth::user()->type != 'client')
            <div class="form-group col-md-6">
                {{ Form::label('customer_id', __('Customer'), ['class' => 'form-label']) }}
                <select name="customer_id" class="form-control select2" id="select" required>
                    <option value="">{{ __('Select Customer') }}</option>
                    @foreach($customers as $key => $customer)
                        <option value="{{ $key }}">{{ $customer }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-6">
                {{ Form::label('employee_id', __('Assigned Employee'), ['class' => 'form-label']) }}
                <select name="employee_id" class="form-control" required>
                    @foreach ($employees as $key => $employee)
                        <option value="{{ $key }}" class="subAccount">{{ $employee }}</option>
                    @endforeach
                </select>
            </div>
        @endif
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('phone_number', __('Phone Number'), ['class' => 'form-label']) }}
                {{ Form::text('phone_number', '', ['class' => 'form-control']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('address', __('Address'), ['class' => 'form-label']) }}
                {{ Form::text('address', '', ['class' => 'form-control']) }}
            </div>
        </div>
        @if (Auth::user()->type != 'client')
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
                    <option value="0" class="subAccount" selected> {{ __('Pending') }} </option>
                    <option value="1" class="subAccount"> {{ __('Received') }} </option>
                    <option value="2" class="subAccount"> {{ __('In progress') }} </option>
                    <option value="3" class="subAccount"> {{ __('On hold') }} </option>
                    <option value="4" class="subAccount"> {{ __('Completed') }} </option>
                    <option value="5" class="subAccount"> {{ __('Cancelled') }} </option>
                </select>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('service_charge', __('Service Charge'), ['class' => 'form-label']) }}
                    {{ Form::number('service_charge', '', ['class' => 'form-control', 'step' => '0.01']) }}
                </div>
            </div>
        @endif
        <div class="form-group col-md-12">
            {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
            {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => '2']) !!}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">
</div>
{{ Form::close() }}


<script>
    document.getElementById('pro_image').onchange = function() {
        var src = URL.createObjectURL(this.files[0])
        document.getElementById('image').src = src
    }

    //hide & show quantity

    // $(document).on('click', '.type', function ()
    // {
    //     var type = $(this).val();
    //     if (type == 'product') {
    //         $('.quantity').removeClass('d-none')
    //         $('.quantity').addClass('d-block');
    //     } else {
    //         $('.quantity').addClass('d-none')
    //         $('.quantity').removeClass('d-block');
    //     }
    // });

    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
            dropdownParent: $('.modal-body')
        });
    });
</script>