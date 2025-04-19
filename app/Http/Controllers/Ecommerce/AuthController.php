<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Utility;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Auth;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('ecommerce.auth.register');
    }

    public function showLoginForm()
    {
        return view('ecommerce.auth.login');
    }

    public function registerUser(Request $request)
    {
        if (env('RECAPTCHA_MODULE') == 'on') {
            $validation['g-recaptcha-response'] = 'required|captcha';
        } else {
            $validation = [];
        }
        $this->validate($request, $validation);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                    'required',
                    'string',
                    'min:8',
                    Rules\Password::defaults()
                ],
        ]);



        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => 'company',
            'default_pipeline' => 1,
            'plan' => 1,
            'lang' => Utility::getValByName('default_language'),
            'avatar' => '',
            'created_by' => 1,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    public function loginUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // First attempt authentication
        if (Auth::attempt($credentials)) {
            // After successful authentication, regenerate the session
            $request->session()->regenerate();

            $user = Auth::user();

            // Check if user is deleted or inactive
            if ($user->delete_status == 0 || $user->is_active == 0) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account is inactive or has been deleted.']);
            }

            // Update Last Login Time
            $user->update([
                'last_login_at' => Carbon::now()->toDateTimeString(),
            ]);

            return redirect(RouteServiceProvider::HOME);
        }

        return back()->withErrors(['email' => 'Invalid Credentials']);
    }
}
