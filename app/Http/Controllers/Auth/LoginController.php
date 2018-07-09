<?php

namespace Sync\Http\Controllers\Auth;

use Sync\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Adldap\Adldap;
use Sync\ldap;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        
        if (Auth::attempt($request->only(['username', 'password']))) {
       
            // Returns \App\User model configured in `config/auth.php`.
            $ldap= new ldap;
            $user = Auth::user();              
            if ($ldap->isMemberUPRedes($user->username)) {
                return redirect()->to('/')->withMessage('Logged in!');
            }                           
            
        }
        
        return redirect()->to('login')
            ->withMessage('Hmm... Your username or password is incorrect');
    }
}
