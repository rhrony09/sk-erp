{{ Form::open(array('route' => 'chart-of-account.store')) }}
<div class="modal-body">
    {{-- start for ai module--}}
    @php
        $settings = \App\Models\Utility::settings();
    @endphp
    @if($settings['ai_chatgpt_enable'] == 'on')
        <div class="text-end">
            <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="{{ route('generate',['chart of account']) }}"
               data-bs-placement="top" data-title="{{ __('Generate content with AI') }}">
                <i class="fas fa-robot"></i> <span>{{__('Generate with AI')}}</span>
            </a>
        </div>
    @endif
    {{-- end for ai module--}}
    <div class="row">

        <div class="form-group col-md-12">
            {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
            {{ Form::text('name', '', ['class' => 'form-control', 'required' => 'required' , 'placeholder'=>__('Enter Name')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('code', __('Code'), ['class' => 'form-label']) }}
            {{ Form::number('code', '', ['class' => 'form-control', 'required' => 'required' , 'placeholder'=>__('Enter Code')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('sub_type', __('Account Type'), ['class' => 'form-label']) }}
            {{ Form::select('sub_type', $account_type, null, ['class' => 'form-control select', 'required' => 'required']) }}
        </div>

        <div class="col-md-2">
            <div class="form-group ">
                {{ Form::label('is_enabled', __('Is Enabled'), ['class' => 'form-label']) }}
                <div class="form-check form-switch">
                    <input type="checkbox" class="form-check-input" name="is_enabled" id="is_enabled" checked>
                    <label class="custom-control-label form-check-label" for="is_enabled"></label>
                </div>
            </div>
        </div>

        <div class="col-md-4 mt-4 acc_check">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="account">
                <label class="form-check-label" for="account">{{__('Make this a sub-account')}}</label>
            </div>
        </div>

        <div class="form-group col-md-6 acc_type d-none">
            {{ Form::label('parent', __('Parent Account'), ['class' => 'form-label']) }}
            <select class="form-control select" name="parent" id="parent">
                <option value="">{{__('Select Account')}}</option>
                @foreach($accounts as $account)
                    <option value="{{$account->id}}">{{$account->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-md-12">
            {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
            {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => '2' , 'placeholder'=>__('Enter Description')]) !!}
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
</div>
{{ Form::close() }}

@push('script-page')
<script>
    $(document).ready(function() {
        // Handle sub-account checkbox
        $('#account').on('change', function() {
            if($(this).is(':checked')) {
                $('.acc_type').removeClass('d-none');
            } else {
                $('.acc_type').addClass('d-none');
            }
        });

        // Handle account type change
        $('select[name="sub_type"]').on('change', function() {
            var type = $(this).val();
            if(type) {
                $.ajax({
                    url: "{{ route('chart-of-account.get-accounts-by-type', '') }}/" + type,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        var parentSelect = $('select[name="parent"]');
                        parentSelect.empty();
                        parentSelect.append('<option value="">{{__("Select Account")}}</option>');
                        $.each(data, function(key, value) {
                            parentSelect.append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            }
        });
    });
</script>
@endpush
