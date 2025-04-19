<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function accountUpdate(Request $request)
    {
        $user = Auth::user();

        // Base validation rules
        $rules = [
            'name' => 'required|string|max:255',
        ];

        // Check if current password is provided
        if ($request->filled('current_password')) {
            // Add password validation rules only if current password is provided
            $rules['current_password'] = 'required';
            $rules['new_password'] = 'required|min:8';
            $rules['confirm_password'] = 'required|same:new_password';
        }

        // Create validator instance manually to handle custom errors
        $validator = Validator::make($request->all(), $rules);

        // Add custom validation for current password if it's provided
        if ($request->filled('current_password')) {
            $validator->after(function ($validator) use ($request, $user) {
                if (!Hash::check($request->current_password, $user->password)) {
                    $validator->errors()->add('current_password', 'The current password is incorrect.');
                }
            });
        }

        // If validation fails, return JSON response for AJAX
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        // Update user information
        $user->name = $request->name;

        // Update password only if a new password was provided
        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        // Return success JSON response for AJAX
        return response()->json([
            'success' => true,
            'message' => 'Your account information has been updated successfully.'
        ]);
    }

    public function updateBilling(Request $request)
    {
        // Validate form inputs
        $validator = Validator::make($request->all(), [
            'billing_name' => 'string|max:255',
            'billing_country' => 'string|max:255',
            'billing_state' => 'string|max:255',
            'billing_city' => 'string|max:255',
            'billing_phone' => 'string|max:20',
            'billing_zip' => 'string|max:20',
            'billing_address' => 'string|max:255',
        ]);

        // Return validation errors for Ajax
        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ]);
            }

            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Get existing customer or create new
        $existingCustomer = Customer::where('user_id', Auth::user()->id)->first();

        if ($existingCustomer) {
            $existingCustomer->billing_name = $request->billing_name;
            $existingCustomer->billing_country = $request->billing_country;
            $existingCustomer->billing_state = $request->billing_state;
            $existingCustomer->billing_city = $request->billing_city;
            $existingCustomer->billing_phone = $request->billing_phone;
            $existingCustomer->billing_zip = $request->billing_zip;
            $existingCustomer->billing_address = $request->billing_address;

            $existingCustomer->save();
        } else {
            $customer = new Customer();

            $customer->user_id = Auth::user()->id;
            $customer->customer_id = $this->generateCusId();
            $customer->name = Auth::user()->name;
            $customer->email = Auth::user()->email;
            $customer->billing_name = $request->billing_name;
            $customer->billing_country = $request->billing_country;
            $customer->billing_state = $request->billing_state;
            $customer->billing_city = $request->billing_city;
            $customer->billing_phone = $request->billing_phone;
            $customer->billing_zip = $request->billing_zip;
            $customer->billing_address = $request->billing_address;

            $customer->save();
        }

        // Return appropriate response based on request type
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Billing information updated successfully.'
            ]);
        }

        return redirect()->back()->with('success', 'Billing information updated successfully.');
    }


    public function updateShipping(Request $request)
    {
        // Validate form inputs
        $validator = Validator::make($request->all(), [
            'shipping_name' => 'string|max:255',
            'shipping_country' => 'string|max:255',
            'shipping_state' => 'string|max:255',
            'shipping_city' => 'string|max:255',
            'shipping_phone' => 'string|max:20',
            'shipping_zip' => 'string|max:20',
            'shipping_address' => 'string|max:255',
        ]);

        // Return validation errors for Ajax
        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ]);
            }

            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Get existing customer or create new
        $existingCustomer = Customer::where('user_id', Auth::user()->id)->first();

        if ($existingCustomer) {
            $existingCustomer->shipping_name = $request->shipping_name;
            $existingCustomer->shipping_country = $request->shipping_country;
            $existingCustomer->shipping_state = $request->shipping_state;
            $existingCustomer->shipping_city = $request->shipping_city;
            $existingCustomer->shipping_phone = $request->shipping_phone;
            $existingCustomer->shipping_zip = $request->shipping_zip;
            $existingCustomer->shipping_address = $request->shipping_address;

            $existingCustomer->save();
        } else {
            $customer = new Customer();

            $customer->user_id = Auth::user()->id;
            $customer->customer_id = $this->generateCusId();
            $customer->name = Auth::user()->name;
            $customer->email = Auth::user()->email;
            $customer->shipping_name = $request->shipping_name;
            $customer->shipping_country = $request->shipping_country;
            $customer->shipping_state = $request->shipping_state;
            $customer->shipping_city = $request->shipping_city;
            $customer->shipping_phone = $request->shipping_phone;
            $customer->shipping_zip = $request->shipping_zip;
            $customer->shipping_address = $request->shipping_address;

            $customer->save();
        }

        // Return appropriate response based on request type
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Shipping information updated successfully.'
            ]);
        }

        return redirect()->back()->with('success', 'Shipping information updated successfully.');
    }

    protected function generateCusId()
    {
        // Start with 5-digit IDs
        $length = 5;

        while (true) {
            // Generate a random numeric ID of current length
            $customerId = '';
            for ($i = 0; $i < $length; $i++) {
                // First digit shouldn't be zero
                if ($i == 0) {
                    $customerId .= mt_rand(1, 9);
                } else {
                    $customerId .= mt_rand(0, 9);
                }
            }

            // Check if this ID already exists in the database
            $exists = Customer::where('customer_id', $customerId)->exists();

            // If it doesn't exist, return it
            if (!$exists) {
                return $customerId;
            }

            // If we've tried many times at the current length (proportional to possible IDs),
            // increase the length
            if (mt_rand(1, 9 * pow(10, $length - 1)) == 1) {
                $length++;
            }
        }
    }
}
