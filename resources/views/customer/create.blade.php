{{ Form::open(['route' => 'customer.store', 'method' => 'post']) }}
<div class="modal-body">

    <h6 class="sub-title">{{ __('Basic Info') }}</h6>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
                {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Name')]) }}
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                {{ Form::label('contact', __('Contact'), ['class' => 'form-label']) }}
                {{ Form::text('contact', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Contact')]) }}
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}
                {{ Form::email('email', null, ['class' => 'form-control', 'placeholder' => __('Enter email')]) }}
            </div>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                {{ Form::label('tax_number', __('Tax Number'), ['class' => 'form-label']) }}
                {{ Form::text('tax_number', null, ['class' => 'form-control', 'placeholder' => __('Enter Tax Number')]) }}
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                {{ Form::label('password', __('Password'), ['class' => 'form-label']) }}
                {{ Form::password('password', ['class' => 'form-control', 'placeholder' => __('Enter New Customer Password'), 'minlength' => '6']) }}
            </div>
        </div>
        @if (!$customFields->isEmpty())
            <div class="col-lg-4 col-md-4 col-sm-6">
                <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                    @include('customFields.formBuilder')
                </div>
            </div>
        @endif
    </div>

    <h6 class="sub-title">{{ __('Billing Address') }}</h6>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('billing_name', __('Name'), ['class' => '', 'class' => 'form-label']) }}
                {{ Form::text('billing_name', null, ['class' => 'form-control', 'placeholder' => __('Enter Name')]) }}
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('billing_phone', __('Phone'), ['class' => 'form-label']) }}
                {{ Form::text('billing_phone', null, ['class' => 'form-control', 'placeholder' => __('Enter Phone')]) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('billing_address', __('Address'), ['class' => 'form-label']) }}
                {{ Form::textarea('billing_address', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Enter Address')]) }}

            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('billing_city', __('City'), ['class' => 'form-label']) }}
                {{ Form::text('billing_city', null, ['class' => 'form-control', 'placeholder' => __('Enter City')]) }}

            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('billing_state', __('State'), ['class' => 'form-label']) }}
                {{ Form::text('billing_state', null, ['class' => 'form-control', 'placeholder' => __('Enter State')]) }}
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('billing_country', __('Country'), ['class' => 'form-label']) }}
                {{ Form::text('billing_country', null, ['class' => 'form-control', 'placeholder' => __('Enter Country')]) }}
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('billing_zip', __('Zip Code'), ['class' => 'form-label']) }}
                {{ Form::text('billing_zip', null, ['class' => 'form-control', 'placeholder' => __('Enter Zip Code')]) }}

            </div>
        </div>

    </div>

    @if (App\Models\Utility::getValByName('shipping_display') == 'on')
        <div class="col-md-12 text-end">
            <input type="button" id="billing_data" value="{{ __('Shipping Same As Billing') }}" class="btn btn-primary">
        </div>
        <h6 class="sub-title">{{ __('Shipping Address') }}</h6>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    {{ Form::label('shipping_name', __('Name'), ['class' => 'form-label']) }}
                    {{ Form::text('shipping_name', null, ['class' => 'form-control', 'placeholder' => __('Enter Name')]) }}

                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    {{ Form::label('shipping_phone', __('Phone'), ['class' => 'form-label']) }}
                    {{ Form::text('shipping_phone', null, ['class' => 'form-control', 'placeholder' => __('Enter Phone')]) }}

                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('shipping_address', __('Address'), ['class' => 'form-label']) }}
                    <label class="form-label" for="example2cols1Input"></label>
                    {{ Form::textarea('shipping_address', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Enter Address')]) }}

                </div>
            </div>


            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    {{ Form::label('shipping_city', __('City'), ['class' => 'form-label']) }}
                    {{ Form::text('shipping_city', null, ['class' => 'form-control', 'placeholder' => __('Enter City')]) }}

                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    {{ Form::label('shipping_state', __('State'), ['class' => 'form-label']) }}
                    {{ Form::text('shipping_state', null, ['class' => 'form-control', 'placeholder' => __('Enter State')]) }}

                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    {{ Form::label('shipping_country', __('Country'), ['class' => 'form-label']) }}
                    {{ Form::text('shipping_country', null, ['class' => 'form-control', 'placeholder' => __('Enter Country')]) }}

                </div>
            </div>


            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    {{ Form::label('shipping_zip', __('Zip Code'), ['class' => 'form-label']) }}
                    {{ Form::text('shipping_zip', null, ['class' => 'form-control', 'placeholder' => __('Enter Zip Code')]) }}

                </div>
            </div>

        </div>

        <script>
            $(document).ready(function() {
                $('#billing_data').on('click', function () {
                    console.log('Billing data button clicked');
                    // Populate shipping name from billing name
                    $('input[name="shipping_name"]').val(
                        $('input[name="billing_name"]').val()
                    );

                    // Populate shipping phone from billing phone
                    $('input[name="shipping_phone"]').val(
                        $('input[name="billing_phone"]').val()
                    );

                    // Populate shipping email from billing email
                    $('input[name="shipping_email"]').val(
                        $('input[name="billing_email"]').val()
                    );

                    // Populate shipping address from billing address
                    $('textarea[name="shipping_address"]').val(
                        $('textarea[name="billing_address"]').val()
                    );

                    // Populate shipping city from billing city
                    $('input[name="shipping_city"]').val(
                        $('input[name="billing_city"]').val()
                    );

                    // Populate shipping state from billing state
                    $('input[name="shipping_state"]').val(
                        $('input[name="billing_state"]').val()
                    );

                    // Populate shipping country from billing country
                    $('input[name="shipping_country"]').val(
                        $('input[name="billing_country"]').val()
                    );

                    // Populate shipping zip from billing zip
                    $('input[name="shipping_zip"]').val(
                        $('input[name="billing_zip"]').val()
                    );
                });
            });
        </script>
    @endif

</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>
{{ Form::close() }}