<?php

/**
 * @Author Zeeshan N
 * @Class Login
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->component = 'components.auth.';
        $this->user = new User();
    }

    public function index(Request $request)
    {
        return view($this->component . 'login');
    }

    /**
     * Description - This is to Authenticate User from login
     * @param $email
     * @param $password
     * @return view
     */
    public function authenticate(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                return redirect()->route('admin.dashboard');
            }

            return back()->withErrors(['email' => 'Credentials mismatch!'])->onlyInput('email');
        } catch (Exception $e) {
            session()->flash('error', __('general.error_msg'));
            return redirect()->route('logout');
        }
    }

    public function logout()
    {
        Auth::logout();
        session()->flush();

        return redirect()->route('login');
    }
}
