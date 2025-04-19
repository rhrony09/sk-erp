{{ Form::model($provident_fund, ['route' => ['provident_fund.store_transaction', $provident_fund->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
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
        <div class="form-group col-md-6">
            {{ Form::label('transaction_type', __('Transaction Type'), ['class' => 'form-label']) }}
            <select name="transaction_type" class="form-control" required>
                <option value="0" class="subAccount"> -- Select an option -- </option>
                <option value="1" class="subAccount">
                    {{ __('Employee Contribution') }} </option>
                <option value="2" class="subAccount">
                    {{ __('Withdrawal') }} </option>
                <option value="3" class="subAccount">
                    {{ __('Loan') }} </option>
                <option value="4" class="subAccount">
                    {{ __('Company Contribution') }} </option>
            </select>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('amount', __('Amount'),['class'=>'form-label']) }}
                {{ Form::number('amount', '', array('class' => 'form-control', 'step' => '0.01')) }}
            </div>
        </div>

        <div class="form-group col-md-12">
            {{ Form::label('note', __('Note'), ['class' => 'form-label']) }}
            {!! Form::textarea('note', '', ['class' => 'form-control', 'rows' => '2']) !!}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">
</div>
{{ Form::close() }}
