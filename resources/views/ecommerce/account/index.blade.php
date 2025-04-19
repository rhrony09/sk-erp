@extends('ecommerce.layouts.master')
@section('content')
    <div class="breadcrumb-area mb-70">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-wrap">
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">My Account</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="my-account-wrapper pb-20">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                    <main id="primary" class="site-main">
                        <div class="user-dashboard pb-50">
                            <div class="user-info mb-30">
                                <div class="row align-items-center no-gutters">
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                        <div class="single-info">
                                            <p class="user-name">Hello <span>{{ Auth::user()->name }}</span> <br>not
                                                {{ Auth::user()->name }}? <a class="log-out" href="javascript:void(0);"
                                                    onclick="document.getElementById('logout-form').submit();">Log Out</a>
                                            </p>
                                        </div>
                                        <form action="{{ route('logout') }}" method="post" id="logout-form">
                                            @csrf
                                        </form>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-3">
                                        <div class="single-info justify-content-lg-center">
                                            <a class="btn btn-secondary" href="{{ route('ecommerce.cartPage') }}">View
                                                Cart</a>
                                        </div>
                                    </div>
                                </div> <!-- end of row -->
                            </div> <!-- end of user-info -->

                            <div class="main-dashboard">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-2">
                                        <ul class="nav flex-column dashboard-list" role="tablist">
                                            <li>
                                                <button class="nav-link active" id="dashboard-tab" data-bs-toggle="tab"
                                                    data-bs-target="#dashboard" type="button" role="tab"
                                                    aria-controls="dashboard" aria-selected="true">Dashboard</button>
                                            </li>
                                            <li>
                                                <button class="nav-link" id="orders-tab" data-bs-toggle="tab"
                                                    data-bs-target="#orders" type="button" role="tab" aria-controls="orders"
                                                    aria-selected="false">Orders</button>
                                            </li>
                                            <li>
                                                <button class="nav-link" id="address-tab" data-bs-toggle="tab"
                                                    data-bs-target="#address" type="button" role="tab"
                                                    aria-controls="address" aria-selected="false">Addresses</button>
                                            </li>
                                            <li>
                                                <button class="nav-link" id="account-details-tab" data-bs-toggle="tab"
                                                    data-bs-target="#account-details" type="button" role="tab"
                                                    aria-controls="account-details" aria-selected="false">Account
                                                    details</button>
                                            </li>
                                            <li>
                                                <a class="nav-link" href="javascript:void(0);"
                                                    onclick="document.getElementById('logout-form').submit();">logout</a>
                                            </li>
                                        </ul> <!-- end of dashboard-list -->
                                    </div>

                                    <div class="col-12 col-sm-12 col-md-12 col-lg-10">
                                        <!-- Tab panes -->
                                        <div class="tab-content dashboard-content">
                                            <div id="dashboard" class="tab-pane fade active show" role="tabpanel"
                                                aria-labelledby="dashboard-tab">
                                                <h3>Dashboard </h3>
                                                <p>From your account dashboard. you can easily check &amp; view your <a
                                                        href="#">recent orders</a>, manage your <a href="#">shipping and
                                                        billing addresses</a> and <a href="#">edit your password and account
                                                        details.</a></p>
                                            </div> <!-- end of tab-pane -->

                                            <div id="orders" class="tab-pane fade" role="tabpanel"
                                                aria-labelledby="orders-tab">
                                                <h3>Orders</h3>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Order</th>
                                                                <th>Date</th>
                                                                <th>Status</th>
                                                                <th>Total</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($orders as $order)
                                                                <tr>
                                                                    <td>{{$loop->iteration}}</td>
                                                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                                                    <td>Processing</td>
                                                                    <td> {{ $order->total }}৳ </td>
                                                                    <td><a class="btn btn-secondary" href="{{ route('ecommerce.orderDetails',$order->order_id) }}">view</a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div> <!-- end of tab-pane -->

                                            <div id="address" class="tab-pane" role="tabpanel"
                                                aria-labelledby="address-tab">
                                                <p>The following addresses will be used on the checkout page by default.</p>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h4 class="billing-address">Billing address</h4>
                                                        <form action="{{ route('ecommerce.updateBilling') }}" method="post">
                                                            @csrf
                                                            <div class="form-group row mb-3">
                                                                <label for="f-name"
                                                                    class="col-12 col-sm-12 col-md-4 col-lg-3 col-form-label">Name</label>
                                                                <div class="col-12 col-sm-12 col-md-8 col-lg-6">
                                                                    <input type="text" class="form-control" id="f-name"
                                                                        name="billing_name" required=""
                                                                        value="{{ Auth::user()->customer->billing_name ?? '' }}">
                                                                    @error('billing_name')
                                                                        <div class="invalid-feedback">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <div class="form-group row mb-3">
                                                                <label for="f-name"
                                                                    class="col-12 col-sm-12 col-md-4 col-lg-3 col-form-label">Phone</label>
                                                                <div class="col-12 col-sm-12 col-md-8 col-lg-6">
                                                                    <input type="text" class="form-control" id="f-name"
                                                                        name="billing_phone" required=""
                                                                        value="{{ Auth::user()->customer->billing_phone ?? '' }}">
                                                                    @error('billing_phone')
                                                                        <div class="invalid-feedback">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <div class="form-group row mb-3">
                                                                <label for="f-name"
                                                                    class="col-12 col-sm-12 col-md-4 col-lg-3 col-form-label">Address
                                                                    line</label>
                                                                <div class="col-12 col-sm-12 col-md-8 col-lg-6">
                                                                    <input type="text" class="form-control" id="f-name"
                                                                        name="billing_address" required=""
                                                                        value="{{ Auth::user()->customer->billing_address ?? '' }}">
                                                                    @error('billing_address')
                                                                        <div class="invalid-feedback">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <div class="form-group row mb-3">
                                                                <label for="f-name"
                                                                    class="col-12 col-sm-12 col-md-4 col-lg-3 col-form-label">City</label>
                                                                <div class="col-12 col-sm-12 col-md-8 col-lg-6">
                                                                    <input type="text" class="form-control" id="f-name"
                                                                        name="billing_city" required=""
                                                                        value="{{ Auth::user()->customer->billing_city ?? '' }}">
                                                                    @error('billing_city')
                                                                        <div class="invalid-feedback">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <div class="form-group row mb-3">
                                                                <label for="f-name"
                                                                    class="col-12 col-sm-12 col-md-4 col-lg-3 col-form-label">State</label>
                                                                <div class="col-12 col-sm-12 col-md-8 col-lg-6">
                                                                    <input type="text" class="form-control" id="f-name"
                                                                        name="billing_state" required=""
                                                                        value="{{ Auth::user()->customer->billing_state ?? '' }}">
                                                                    @error('billing_state')
                                                                        <div class="invalid-feedback">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <div class="form-group row mb-3">
                                                                <label for="f-name"
                                                                    class="col-12 col-sm-12 col-md-4 col-lg-3 col-form-label">Country</label>
                                                                <div class="col-12 col-sm-12 col-md-8 col-lg-6">
                                                                    <input type="text" class="form-control" id="f-name"
                                                                        name="billing_country" required=""
                                                                        value="{{ Auth::user()->customer->billing_country ?? '' }}">
                                                                    @error('billing_country')
                                                                        <div class="invalid-feedback">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <div class="form-group row mb-3">
                                                                <label for="f-name"
                                                                    class="col-12 col-sm-12 col-md-4 col-lg-3 col-form-label">Zip</label>
                                                                <div class="col-12 col-sm-12 col-md-8 col-lg-6">
                                                                    <input type="text" class="form-control" id="f-name"
                                                                        name="billing_zip" required=""
                                                                        value="{{ Auth::user()->customer->billing_zip ?? '' }}">
                                                                    @error('billing_zip')
                                                                        <div class="invalid-feedback">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <div class="register-box d-flex justify-content-center mt-half">
                                                                <button type="submit"
                                                                    class="btn btn-secondary">Save</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h4 class="billing-address">Shipping address</h4>
                                                        <form action="{{ route('ecommerce.updateShipping') }}"
                                                            method="post">
                                                            @csrf
                                                            <div class="form-group row mb-3">
                                                                <label for="f-name"
                                                                    class="col-12 col-sm-12 col-md-4 col-lg-3 col-form-label">Name</label>
                                                                <div class="col-12 col-sm-12 col-md-8 col-lg-6">
                                                                    <input type="text" class="form-control" id="f-name"
                                                                        name="shipping_name" required=""
                                                                        value="{{ Auth::user()->customer->shipping_name ?? '' }}">
                                                                    @error('shipping_name')
                                                                        <div class="invalid-feedback">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <div class="form-group row mb-3">
                                                                <label for="f-name"
                                                                    class="col-12 col-sm-12 col-md-4 col-lg-3 col-form-label">Phone</label>
                                                                <div class="col-12 col-sm-12 col-md-8 col-lg-6">
                                                                    <input type="text" class="form-control" id="f-name"
                                                                        name="shipping_phone" required=""
                                                                        value="{{ Auth::user()->customer->shipping_phone ?? '' }}">
                                                                    @error('shipping_phone')
                                                                        <div class="invalid-feedback">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <div class="form-group row mb-3">
                                                                <label for="f-name"
                                                                    class="col-12 col-sm-12 col-md-4 col-lg-3 col-form-label">Address
                                                                    line</label>
                                                                <div class="col-12 col-sm-12 col-md-8 col-lg-6">
                                                                    <input type="text" class="form-control" id="f-name"
                                                                        name="shipping_address" required=""
                                                                        value="{{ Auth::user()->customer->shipping_address ?? '' }}">
                                                                    @error('shipping_address')
                                                                        <div class="invalid-feedback">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <div class="form-group row mb-3">
                                                                <label for="f-name"
                                                                    class="col-12 col-sm-12 col-md-4 col-lg-3 col-form-label">State</label>
                                                                <div class="col-12 col-sm-12 col-md-8 col-lg-6">
                                                                    <input type="text" class="form-control" id="f-name"
                                                                        name="shipping_state" required=""
                                                                        value="{{ Auth::user()->customer->shipping_state ?? '' }}">
                                                                    @error('shipping_state')
                                                                        <div class="invalid-feedback">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <div class="form-group row mb-3">
                                                                <label for="f-name"
                                                                    class="col-12 col-sm-12 col-md-4 col-lg-3 col-form-label">City</label>
                                                                <div class="col-12 col-sm-12 col-md-8 col-lg-6">
                                                                    <input type="text" class="form-control" id="f-name"
                                                                        name="shipping_city" required=""
                                                                        value="{{ Auth::user()->customer->shipping_city ?? '' }}">
                                                                    @error('shipping_city')
                                                                        <div class="invalid-feedback">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <div class="form-group row mb-3">
                                                                <label for="f-name"
                                                                    class="col-12 col-sm-12 col-md-4 col-lg-3 col-form-label">Country</label>
                                                                <div class="col-12 col-sm-12 col-md-8 col-lg-6">
                                                                    <input type="text" class="form-control" id="f-name"
                                                                        name="shipping_state" required=""
                                                                        value="{{ Auth::user()->customer->shipping_state ?? '' }}">
                                                                    @error('shipping_state')
                                                                        <div class="invalid-feedback">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <div class="form-group row mb-3">
                                                                <label for="f-name"
                                                                    class="col-12 col-sm-12 col-md-4 col-lg-3 col-form-label">Zip</label>
                                                                <div class="col-12 col-sm-12 col-md-8 col-lg-6">
                                                                    <input type="text" class="form-control" id="f-name"
                                                                        name="shipping_zip" required=""
                                                                        value="{{ Auth::user()->customer->shipping_zip ?? '' }}">
                                                                    @error('shipping_zip')
                                                                        <div class="invalid-feedback">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <div class="register-box d-flex justify-content-center mt-half">
                                                                <button type="submit"
                                                                    class="btn btn-secondary">Save</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div> <!-- end of tab-pane -->

                                            <div id="account-details" class="tab-pane fade" role="tabpanel"
                                                aria-labelledby="account-details-tab">
                                                <h3>Account details </h3>
                                                <div class="login-form">
                                                    <form action="{{ route('ecommerce.accountUpdate') }}" method="post">
                                                        @csrf
                                                        @method('put')
                                                        <div class="form-group row mb-3">
                                                            <label for="f-name"
                                                                class="col-12 col-sm-12 col-md-4 col-lg-3 col-form-label">Name</label>
                                                            <div class="col-12 col-sm-12 col-md-8 col-lg-6">
                                                                <input type="text" class="form-control" id="f-name"
                                                                    name="name" required=""
                                                                    value="{{ Auth::user()->name }}">
                                                                @error('name')
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="form-group row mb-3">
                                                            <label for="email"
                                                                class="col-12 col-sm-12 col-md-4 col-lg-3 col-form-label">Email
                                                                Address</label>
                                                            <div class="col-12 col-sm-12 col-md-8 col-lg-6">
                                                                <input type="text" class="form-control" id="email"
                                                                    required="" readonly value="{{ Auth::user()->email }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row mb-3">
                                                            <label for="inputpassword"
                                                                class="col-12 col-sm-12 col-md-4 col-lg-3 col-form-label">Current
                                                                Password</label>
                                                            <div class="col-12 col-sm-12 col-md-8 col-lg-6">
                                                                <input type="password" class="form-control"
                                                                    id="inputpassword" name="current_password">
                                                                @error('current_password')
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="form-group row mb-3">
                                                            <label for="newpassword"
                                                                class="col-12 col-sm-12 col-md-4 col-lg-3 col-form-label">New
                                                                Password</label>
                                                            <div class="col-12 col-sm-12 col-md-8 col-lg-6">
                                                                <input type="password" class="form-control" id="newpassword"
                                                                    name="new_password">
                                                                @error('newpassword')
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="form-group row mb-3">
                                                            <label for="c-password"
                                                                class="col-12 col-sm-12 col-md-4 col-lg-3 col-form-label">Confirm
                                                                Password</label>
                                                            <div class="col-12 col-sm-12 col-md-8 col-lg-6">
                                                                <input type="password" class="form-control" id="c-password"
                                                                    name="confirm_password">
                                                                @error('confirm_password')
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="register-box d-flex justify-content-end mt-half">
                                                            <button type="submit" class="btn btn-secondary">Save</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div> <!-- end of tab-pane -->
                                        </div>
                                    </div>
                                </div> <!-- end of row -->
                            </div> <!-- end of main-dashboard -->
                        </div> <!-- end of user-dashboard -->
                    </main> <!-- end of #primary -->
                </div>
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Get the form element
            const form = document.querySelector('form[action*="accountUpdate"]');

            // Add event listener for form submission
            form.addEventListener('submit', function (e) {
                // Prevent the default form submission
                e.preventDefault();

                // Get form data
                const formData = new FormData(form);

                // Clear previous error messages
                clearErrorMessages();

                // Send AJAX request
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            showSuccessMessage(data.message);

                            // Clear password fields
                            document.getElementById('inputpassword').value = '';
                            document.getElementById('newpassword').value = '';
                            document.getElementById('c-password').value = '';
                        } else {
                            // Handle validation errors
                            if (data.errors) {
                                displayErrors(data.errors);
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });

            // Function to clear error messages
            function clearErrorMessages() {
                // Remove all invalid-feedback divs that are displayed
                const errorMessages = document.querySelectorAll('.invalid-feedback');
                errorMessages.forEach(function (element) {
                    element.style.display = 'none';
                });

                // Remove is-invalid class from inputs
                const invalidInputs = document.querySelectorAll('.is-invalid');
                invalidInputs.forEach(function (element) {
                    element.classList.remove('is-invalid');
                });

                // Remove any success message
                const successAlert = document.querySelector('.alert-success');
                if (successAlert) {
                    successAlert.remove();
                }
            }

            // Function to display validation errors
            function displayErrors(errors) {
                for (const field in errors) {
                    // Find the input field
                    const input = document.querySelector(`[name="${field}"]`);
                    if (input) {
                        // Add is-invalid class to the input
                        input.classList.add('is-invalid');

                        // Find or create error message container
                        let errorDiv = input.nextElementSibling;
                        if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
                            errorDiv = document.createElement('div');
                            errorDiv.className = 'invalid-feedback';
                            input.parentNode.insertBefore(errorDiv, input.nextSibling);
                        }

                        // Set error message and display it
                        errorDiv.textContent = errors[field][0];
                        errorDiv.style.display = 'block';
                    }
                }
            }

            // Function to show success message
            function showSuccessMessage(message) {
                // Create success alert
                const successAlert = document.createElement('div');
                successAlert.className = 'alert alert-success mt-3';
                successAlert.textContent = message || 'Your account information has been updated successfully.';

                // Insert at the top of the form
                form.prepend(successAlert);

                // Scroll to the top of the form
                successAlert.scrollIntoView({ behavior: 'smooth' });

                // Auto-dismiss after 5 seconds
                setTimeout(function () {
                    successAlert.remove();
                }, 5000);
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const billingForm = document.querySelector('form[action*="updateBilling"]');

            if (billingForm) {
                billingForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    // Clear previous error messages
                    clearErrors();

                    // Show loading indicator
                    const submitButton = billingForm.querySelector('button[type="submit"]');
                    const originalButtonText = submitButton.textContent;
                    submitButton.textContent = 'Saving...';
                    submitButton.disabled = true;

                    // Create form data
                    const formData = new FormData(billingForm);

                    // Send AJAX request
                    fetch(billingForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            // Reset button state
                            submitButton.textContent = originalButtonText;
                            submitButton.disabled = false;

                            if (data.success) {
                                // Show success message
                                showMessage('success', data.message || 'Billing information updated successfully.');
                            } else {
                                // Show validation errors
                                if (data.errors) {
                                    handleErrors(data.errors);
                                } else {
                                    showMessage('error', 'An error occurred while updating billing information.');
                                }
                            }
                        })
                        .catch(error => {
                            // Reset button state
                            submitButton.textContent = originalButtonText;
                            submitButton.disabled = false;

                            // Show error message
                            showMessage('error', 'An error occurred. Please try again.');
                            console.error('Error:', error);
                        });
                });
            }

            // Clear all error messages
            function clearErrors() {
                const errorMessages = document.querySelectorAll('.invalid-feedback');
                errorMessages.forEach(function (element) {
                    element.style.display = 'none';
                });

                const invalidInputs = document.querySelectorAll('.is-invalid');
                invalidInputs.forEach(function (element) {
                    element.classList.remove('is-invalid');
                });

                // Remove any existing alerts
                const existingAlerts = document.querySelectorAll('.alert');
                existingAlerts.forEach(function (alert) {
                    alert.remove();
                });
            }

            // Handle validation errors
            function handleErrors(errors) {
                for (const field in errors) {
                    const input = document.querySelector(`[name="${field}"]`);
                    if (input) {
                        input.classList.add('is-invalid');

                        let errorDiv = input.nextElementSibling;
                        while (errorDiv && !errorDiv.classList.contains('invalid-feedback')) {
                            errorDiv = errorDiv.nextElementSibling;
                        }

                        if (errorDiv) {
                            errorDiv.textContent = errors[field][0];
                            errorDiv.style.display = 'block';
                        } else {
                            // Create error div if it doesn't exist
                            const newErrorDiv = document.createElement('div');
                            newErrorDiv.className = 'invalid-feedback';
                            newErrorDiv.textContent = errors[field][0];
                            newErrorDiv.style.display = 'block';
                            input.parentNode.appendChild(newErrorDiv);
                        }
                    }
                }

                // Show general error message at the top
                showMessage('error', 'Please correct the errors below.');
            }

            // Show message (success/error)
            function showMessage(type, message) {
                const messageDiv = document.createElement('div');
                messageDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} mb-4`;
                messageDiv.textContent = message;

                // Insert at the top of the form
                billingForm.prepend(messageDiv);

                // Scroll to top of form
                messageDiv.scrollIntoView({ behavior: 'smooth' });

                // Auto-dismiss after 5 seconds for success messages
                if (type === 'success') {
                    setTimeout(function () {
                        messageDiv.remove();
                    }, 5000);
                }
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const billingForm = document.querySelector('form[action*="updateShipping"]');

            if (billingForm) {
                billingForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    // Clear previous error messages
                    clearErrors();

                    // Show loading indicator
                    const submitButton = billingForm.querySelector('button[type="submit"]');
                    const originalButtonText = submitButton.textContent;
                    submitButton.textContent = 'Saving...';
                    submitButton.disabled = true;

                    // Create form data
                    const formData = new FormData(billingForm);

                    // Send AJAX request
                    fetch(billingForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            // Reset button state
                            submitButton.textContent = originalButtonText;
                            submitButton.disabled = false;

                            if (data.success) {
                                // Show success message
                                showMessage('success', data.message || 'Shipping information updated successfully.');
                            } else {
                                // Show validation errors
                                if (data.errors) {
                                    handleErrors(data.errors);
                                } else {
                                    showMessage('error', 'An error occurred while updating shipping information.');
                                }
                            }
                        })
                        .catch(error => {
                            // Reset button state
                            submitButton.textContent = originalButtonText;
                            submitButton.disabled = false;

                            // Show error message
                            showMessage('error', 'An error occurred. Please try again.');
                            console.error('Error:', error);
                        });
                });
            }

            // Clear all error messages
            function clearErrors() {
                const errorMessages = document.querySelectorAll('.invalid-feedback');
                errorMessages.forEach(function (element) {
                    element.style.display = 'none';
                });

                const invalidInputs = document.querySelectorAll('.is-invalid');
                invalidInputs.forEach(function (element) {
                    element.classList.remove('is-invalid');
                });

                // Remove any existing alerts
                const existingAlerts = document.querySelectorAll('.alert');
                existingAlerts.forEach(function (alert) {
                    alert.remove();
                });
            }

            // Handle validation errors
            function handleErrors(errors) {
                for (const field in errors) {
                    const input = document.querySelector(`[name="${field}"]`);
                    if (input) {
                        input.classList.add('is-invalid');

                        let errorDiv = input.nextElementSibling;
                        while (errorDiv && !errorDiv.classList.contains('invalid-feedback')) {
                            errorDiv = errorDiv.nextElementSibling;
                        }

                        if (errorDiv) {
                            errorDiv.textContent = errors[field][0];
                            errorDiv.style.display = 'block';
                        } else {
                            // Create error div if it doesn't exist
                            const newErrorDiv = document.createElement('div');
                            newErrorDiv.className = 'invalid-feedback';
                            newErrorDiv.textContent = errors[field][0];
                            newErrorDiv.style.display = 'block';
                            input.parentNode.appendChild(newErrorDiv);
                        }
                    }
                }

                // Show general error message at the top
                showMessage('error', 'Please correct the errors below.');
            }

            // Show message (success/error)
            function showMessage(type, message) {
                const messageDiv = document.createElement('div');
                messageDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} mb-4`;
                messageDiv.textContent = message;

                // Insert at the top of the form
                billingForm.prepend(messageDiv);

                // Scroll to top of form
                messageDiv.scrollIntoView({ behavior: 'smooth' });

                // Auto-dismiss after 5 seconds for success messages
                if (type === 'success') {
                    setTimeout(function () {
                        messageDiv.remove();
                    }, 5000);
                }
            }
        });
    </script>
@endsection