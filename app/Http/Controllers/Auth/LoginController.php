<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

use App\Models\User;








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
    protected $redirectAfterLogout = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Logout, Clear Session, and Return.
     *
     * @return void
     */
    public function logout()
    {
        $user = Auth::user();
        Log::info('User Logged Out. ', [$user]);
        Auth::logout();
        Session::flush();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }



       /**
     * Check either username or email.
     * @return string
     */
    public function username()
    {
        $identity  = request()->get('nifcifemail');
        $fieldName = filter_var($identity, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        request()->merge([$fieldName => $identity]);
        return $fieldName;
    }
    /**
     * Validate the user login.
     * @param Request $request
     */
    protected function validateLogin(Request $request)
    {
        $this->validate(
            $request,
            [
                'nifcifemail' => 'required|string',
                'password' => 'required|string',
            ],
            [
                'nifcifemail.required' => 'Se requiere NIF - CIF o Email',
                'password.required' => 'Se requiere una clave',
            ]
        );
    }
    /**
     * @param Request $request
     * @throws ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {

        if($request->has('name')){



            if ( ! User::where('name', $request->name)->first() ) {


                 $request->session()->put('login_error', trans('auth.failed'));
                    throw ValidationException::withMessages(
                        [
                            'error' => [trans('auth.failed')],
                        ]
                    );


                /*return redirect()->back()
                    ->withInput($request->only($this->username(), 'remember'))
                    ->withErrors([
                        $this->username() => trans('auth.email'),
                    ]);*/
            }

            if ( ! User::where('name', $request->name)->where('password', bcrypt($request->password))->first() ) {

                 $request->session()->put('login_error', trans('auth.password'));
                    throw ValidationException::withMessages(
                        [
                            'error' => [trans('auth.password')],
                        ]
                    );


                /*return redirect()->back()
                    ->withInput($request->only($this->username(), 'remember'))
                    ->withErrors([
                        'password' => trans('auth.password'),
                    ]);*/

            }



        }


         if($request->has('email')){


            if ( ! User::where('email', $request->email)->first() ) {

                 $request->session()->put('login_error', trans('auth.failed'));
                    throw ValidationException::withMessages(
                        [
                            'error' => [trans('auth.failed')],
                        ]
                    );


                /*return redirect()->back()
                    ->withInput($request->only($this->username(), 'remember'))
                    ->withErrors([
                        $this->username() => trans('auth.email'),
                    ]);*/
            }

            if ( ! User::where('email', $request->email)->where('password', bcrypt($request->password))->first() ) {

                $request->session()->put('login_error', trans('auth.password'));
                    throw ValidationException::withMessages(
                        [
                            'error' => [trans('auth.password')],
                        ]
                    );


                /*return redirect()->back()
                    ->withInput($request->only($this->username(), 'remember'))
                    ->withErrors([
                        'password' => trans('auth.password'),
                    ]);*/
            }



        }



       /* Esta parte no se utiliza, ya que necesitamos el código anterior
       $request->session()->put('login_error', trans('auth.failed'));
        throw ValidationException::withMessages(
            [
                'error' => [trans('auth.failed')],
            ]
        );
        */




    }

}
