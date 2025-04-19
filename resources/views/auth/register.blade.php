@extends('layouts.auth')
@php
    $logo = asset(Storage::url('uploads/logo/'));
    $company_logo = Utility::getValByName('company_logo');
    $settings = Utility::settings();
@endphp
@push('custom-scripts')
    @if ($settings['recaptcha_module'] == 'on')
        {!! NoCaptcha::renderJs() !!}
    @endif
@endpush
@section('page-title')
    {{ __('Register') }}
@endsection

@php
    $languages = App\Models\Utility::languages();
@endphp

@section('language-bar')
    <div class="lang-dropdown-only-desk">
        <li class="dropdown dash-h-item drp-language">
            <a class="dash-head-link btn" href="#" aria-expanded="false">
                <span class="drp-text"> {{ $languages[$lang] }} </span>
            </a>
            {{-- <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">
                @foreach ($languages as $code => $language)
                    <a href="{{ route('register', $code) }}" tabindex="0" class="dropdown-item ">
                        <span>{{ Str::ucfirst($language) }}</span>
                    </a>
                @endforeach
            </div> --}}
        </li>
    </div>
@endsection

@section('content')
    <div class="card-body">
        <div>
            <h2 class="mb-3 f-w-600">{{ __('Register') }}</h2>
        </div>
        @if (session('status'))
            <div class="mb-4 font-medium text-lg text-green-600 text-danger">
                {{ session('status') }}
            </div>
        @endif
        {{ Form::open(['route' => 'makeregister', 'method' => 'post', 'id' => 'registerForm', 'class' => 'register-form']) }}
        <div class="custom-register-form">
            <div class="form-group mb-3">
                <label class="form-label">{{ __('Name') }}</label>
                {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Your Name')]) }}
                @error('name')
                    <span class="error invalid-name text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label class="form-label">{{ __('Email') }}</label>
                {{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => __('Enter Your Email')]) }}
                @error('email')
                    <span class="error invalid-email text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label class="form-label">{{ __('Password') }}</label>
                {{ Form::password('password', ['class' => 'form-control', 'placeholder' => __('Enter Your Password')]) }}
                @error('password')
                    <span class="error invalid-password text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label class="form-label">{{ __('Confirm Password') }}</label>
                {{ Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => __('Confirm Your Password')]) }}
            </div>
            <div class="form-group mb-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    @if (Route::has('login'))
                        <span><a href="{{ route('login', $lang) }}" tabindex="0">{{ __('Already have an account?') }}</a></span>
                    @endif
                </div>
            </div>

            @if ($settings['recaptcha_module'] == 'on')
                <div class="form-group col-lg-12 col-md-12 mt-3">
                    {!! NoCaptcha::display($settings['cust_darklayout'] == 'on' ? ['data-theme' => 'dark'] : []) !!}
                    @error('g-recaptcha-response')
                        <span class="small text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            @endif

            <div class="d-grid">
                {{ Form::submit(__('Register'), ['class' => 'btn btn-primary mt-2', 'id' => 'registerBtn']) }}
            </div>
        </div>
        {{ Form::close() }}
    </div>
@endsection

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $("#registerForm").submit(function(e) {
            $("#registerBtn").attr("disabled", true);
            return true;
        });
    });
</script>
