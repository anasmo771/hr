<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\Models\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;


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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function logout(Request $request)
    {
       $this->guard()->logout();
       $request->session()->invalidate();
       return $this->loggedOut($request) ?: redirect('/login');
     }

     public function login(Request $request)
{
    $input = $request->all();
    $this->validate($request, [
        'name' => 'required',
        'password' => 'required',
    ]);

    $fieldType = filter_var($request->name, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

    if (auth()->attempt([$fieldType => $input['name'], 'password' => $input['password']])) {

        // Check if the 'active' column is true
        if (auth()->user()->active) {
            $log = new Log;
            $log->user_id = auth()->user()->id;
            $log->type = 10;
            $log->title = "تسجيل دخول ناجح (" . request('name') . ")";
            $log->log = "تم تسجيل دخول ناجح من قبل (" . request('name') . ")";
            $log->save();

            return redirect()->route('admin.home');
        } else {
            // If the user is not active, log them out and redirect with a message
            auth()->logout();
            $log = new Log;
            $log->type = 10;
            $log->title = "محاولة تسجيل دخول لحساب غير مفعل (" . request('name') . ")";
            $log->log = "تمت محاولة تسجيل دخول لحساب غير مفعل من قبل (" . request('name') . ")";
            $log->save();

            return redirect()->route('login')
                ->with('error', 'حسابك غير مفعل. يرجى التواصل مع الإدارة.');
        }

    } else {
        $log = new Log;
        $log->type = 10;
        $log->title = "محاولة تسجيل دخول فاشلة (" . request('name') . ")";
        $log->log = "تمت محاولة تسجيل دخول فاشلة من قبل (" . request('name') . ")";
        $log->save();

        return redirect()->route('login')
            ->with('error', 'البريد الالكتروني او كلمة المرور غير صحيحة.');
    }
}


    public function username()
    {
        return 'name';
    }
}
