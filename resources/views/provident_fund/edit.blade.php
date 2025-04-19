{{ Form::model($provident_fund, ['route' => ['provident_fund.update', $provident_fund->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
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
        <div class="form-group col-md-6">
            {{ Form::label('employee_id', __('Employee Name'), ['class' => 'form-label']) }}
            <select name="employee_id" class="form-control" required>
                <option value="{{ $employee->id }}" class="subAccount">{{ $employee->name }}</option>
            </select>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('contribution_rate', __('Contribution Rate (%)'), ['class' => 'form-label']) }}
                {{ Form::number('contribution_rate', null, ['class' => 'form-control', 'step' => '0.01']) }}
            </div>
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}
            <select name="status" class="form-control" required>
                <option value="0" class="subAccount"> -- Select an option -- </option>
                <option value="1" class="subAccount" {{ $provident_fund->status == 1 ? 'selected' : '' }}>
                    {{ __('Active') }} </option>
                <option value="2" class="subAccount" {{ $provident_fund->status == 2 ? 'selected' : '' }}>
                    {{ __('Inactive') }} </option>
                <option value="3" class="subAccount" {{ $provident_fund->status == 3 ? 'selected' : '' }}>
                    {{ __('Closed') }} </option>
                <option value="4" class="subAccount" {{ $provident_fund->status == 4 ? 'selected' : '' }}>
                    {{ __('Suspended') }} </option>
            </select>
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('note', __('Note'), ['class' => 'form-label']) }}
            {!! Form::textarea('note', null, ['class' => 'form-control', 'rows' => '2']) !!}
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
