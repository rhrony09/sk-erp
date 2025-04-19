@extends('ecommerce.layouts.master')

@section('content')
<div class="breadcrumb-area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-wrap">
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Register</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="login-wrapper pb-70">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <main id="primary" class="site-main">
                    <div class="user-login">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12">
                                <div class="section-title text-center">
                                    <h3>Create an Account</h3>
                                </div>
                            </div>
                        </div> <!-- end of row -->
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-8 offset-xl-2">
                                <div class="registration-form login-form">
                                    <form action="{{ route('ecom.register.store') }}" method="POST">
                                        @csrf

                                        <div class="login-info mb-20">
                                            <p>Already have an account? <a href="{{ route('ecom.login') }}">Log in instead!</a></p>
                                        </div>
                                        <div class="form-group mb-3 row">
                                            <label for="name" class="col-12 col-sm-12 col-md-4 col-form-label">Name</label>
                                            <div class="col-12 col-sm-12 col-md-8 col-lg-8">
                                                <input type="text" class="form-control" id="name" required="" name="name">
                                            </div>
                                            @if ($errors->has('name'))
                                                <span class="text-danger offset-md-4 offset-md-0">{{ $errors->first('name') }}</span>
                                            @endif
                                        </div>
                                        <div class="form-group mb-3 row">
                                            <label for="email" class="col-12 col-sm-12 col-md-4 col-form-label">Email Address</label>
                                            <div class="col-12 col-sm-12 col-md-8 col-lg-8">
                                                <input type="text" class="form-control" id="email" required="" name="email">
                                            </div>
                                            @if ($errors->has('email'))
                                                <span class="text-danger offset-md-4 offset-md-0">{{ $errors->first('email') }}</span>
                                            @endif
                                        </div>
                                        <div class="form-group mb-3 row">
                                            <label for="password" class="col-12 col-sm-12 col-md-4 col-form-label">Password</label>
                                            <div class="col-12 col-sm-12 col-md-8 col-lg-8" style="position: relative;">
                                                <input type="password" class="form-control" id="password" required="" name="password">
                                            </div>
                                            @if ($errors->has('password'))
                                                <span class="text-danger offset-md-4 offset-md-0">{{ $errors->first('password') }}</span>
                                            @endif
                                        </div>
                                        <div class="register-box d-flex justify-content-end mt-20">
                                            <button type="submit" class="btn btn-secondary">Register</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end of user-login -->
                </main> <!-- end of #primary -->
            </div>
        </div> <!-- end of row -->
    </div> <!-- end of container -->
</div>
@endsection