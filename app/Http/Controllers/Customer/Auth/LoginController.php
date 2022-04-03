<?php

namespace App\Http\Controllers\Customer\Auth;

use App\CPU\CartManager;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Wishlist;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public $company_name;

    public function __construct()
    {
        $this->middleware('guest:customer', ['except' => ['logout']]);
    }

    public function login()
    {
        session()->put('keep_return_url', url()->previous());
        $user = session()->get('user');
        $pass = session()->get('password');

        if (isset($user) && isset($pass)) {
            $auth = User::where('email', 'like', "%{$user}%")->first();
            if (isset($auth)) {
                $phone_verification = Helpers::get_business_settings('phone_verification');
                $email_verification = Helpers::get_business_settings('email_verification');
                if ($phone_verification && !$auth->is_phone_verified) {
                    return redirect(route('customer.auth.check', [$auth->id]));
                }
                if ($email_verification && !$auth->is_email_verified) {
                    return redirect(route('customer.auth.check', [$auth->id]));
                }
            }

            if (isset($auth) && auth('customer')->attempt(['email' => $user, 'password' => $pass])) {
                session()->put('wish_list', Wishlist::where('customer_id', auth('customer')->user()->id)->pluck('product_id')->toArray());
                Toastr::info('Welcome to '.Helpers::get_business_settings('company_name').'!');
                CartManager::cart_to_db();

                session()->forget('user');
                session()->forget('password');

                return redirect(session('keep_return_url'));
            }
        }
        $sk = config('captcha.site_key');

        return view('customer-view.auth.login', compact('sk'));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'password' => 'required|min:8',
            'g-recaptcha-response' => 'required',
        ], [
            'g-recaptcha-response.required' => 'Please validate if you are not a robot',
        ]);

        $remember = ($request['remember']) ? true : false;

        $user_id = $request->user_id;
        if (filter_var($user_id, FILTER_VALIDATE_EMAIL)) {
            $medium = 'email';
        } else {
            $count = strlen(preg_replace("/[^\d]/", '', $user_id));
            if ($count >= 9 && $count <= 15) {
                $medium = 'phone';
            } else {
                Toastr::error('Invalid user email or phone number.');
            }
        }

        $user = User::where($medium, 'like', "%{$user_id}%")->first();

        if (isset($user) == false) {
            Toastr::error('Credentials do not match or account has been suspended.');

            return back()->withInput();
        }

        // VERIFIKASI HP/EMAIL

        $phone_verification = Helpers::get_business_settings('phone_verification');
        $email_verification = Helpers::get_business_settings('email_verification');
        if ($phone_verification && !$user->is_phone_verified) {
            return redirect(route('customer.auth.check', [$user->id]));
        }
        if ($email_verification && !$user->is_email_verified) {
            return redirect(route('customer.auth.check', [$user->id]));
        }

        // CHECK VERIFIKASI

        if (isset($user) && $user->is_active && auth('customer')->attempt(['email' => $user->email, 'password' => $request->password], $remember)) {
            session()->put('wish_list', Wishlist::where('customer_id', auth('customer')->user()->id)->pluck('product_id')->toArray());
            Toastr::info('Welcome to '.Helpers::get_business_settings('company_name').'!');
            CartManager::cart_to_db();

            return redirect(session('keep_return_url'));
        }

        if (isset($user) && auth('customer')->attempt(['email' => $user->email, 'password' => $request->password], $remember)) {
            session()->put('wish_list', Wishlist::where('customer_id', auth('customer')->user()->id)->pluck('product_id')->toArray());
            Toastr::info('Welcome to '.Helpers::get_business_settings('company_name').'!');
            CartManager::cart_to_db();

            return redirect(session('keep_return_url'));
        }

        Toastr::error('Credentials do not match or account has been suspended.');

        return redirect()->back()->withInput();
    }

    public function logout(Request $request)
    {
        auth()->guard('customer')->logout();
        session()->forget('wish_list');
        Toastr::info('Come back soon, '.'!');

        return redirect()->route('home');
    }
}
