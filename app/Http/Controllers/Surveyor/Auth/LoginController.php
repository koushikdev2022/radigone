<?php
namespace App\Http\Controllers\Surveyor\Auth;


use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\SurveyorLogin;

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
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    public $redirectTo = 'surveyor';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('surveyor.guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        $page_title = "Sponsor Login";
        return view('surveyor.auth.login', compact('page_title'));
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('surveyor');
    }

    public function username()
    {
        return 'username';
    }

    public function login(Request $request)
    {


        $this->validateLogin($request);

        if(isset($request->captcha)){
            if(!captchaVerify($request->captcha, $request->captcha_secret)){
                $notify[] = ['error',"Invalid Captcha"];
                return back()->withNotify($notify)->withInput();
            }
        }

//

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function validateLogin(Request $request)
    {

        $customRecaptcha = \App\Extension::where('act', 'custom-captcha')->where('status', 1)->first();
        $validation_rule = [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ];

        if ($customRecaptcha) {
            $validation_rule['captcha'] = 'required';
        }

        $request->validate($validation_rule);

    }


    public function logout(Request $request)
    {
        $this->guard('surveyor')->logout();
        $request->session()->invalidate();
        return $this->loggedOut($request) ?: redirect('/surveyor');
    }

    public function resetPassword()
    {
        $page_title = 'Sponsor Recovery';
        return view('surveyor.reset', compact('page_title'));
    }

    public function authenticated(Request $request, $surveyor)
    {
        if ($surveyor->status == 0) {
            $this->guard('surveyor')->logout();
            return redirect()->route('surveyor.login')->withErrors(['Your account has been deactivated.']);
        }

        $surveyor = auth()->guard('surveyor')->user();
        $surveyor->tv = $surveyor->ts == 1 ? 0 : 1;
        $surveyor->save();
        $ip = $_SERVER["REMOTE_ADDR"];
        $exist = SurveyorLogin::where('surveyor_ip',$ip)->first();
        $surveyorLogin = new SurveyorLogin();
        if ($exist) {
            $surveyorLogin->longitude =  $exist->longitude;
            $surveyorLogin->latitude =  $exist->latitude;
            $surveyorLogin->location =  $exist->location;
            $surveyorLogin->country_code = $exist->country_code;
            $surveyorLogin->country =  $exist->country;
        }else{
            $info = json_decode(json_encode(getIpInfo()), true);
            $surveyorLogin->longitude =  @implode(',',$info['long']);
            $surveyorLogin->latitude =  @implode(',',$info['lat']);
            $surveyorLogin->location =  @implode(',',$info['city']) . (" - ". @implode(',',$info['area']) ."- ") . @implode(',',$info['country']) . (" - ". @implode(',',$info['code']) . " ");
            $surveyorLogin->country_code = @implode(',',$info['code']);
            $surveyorLogin->country =  @implode(',', $info['country']);
        }

        $surveyorAgent = osBrowser();
        $surveyorLogin->surveyor_id = $surveyor->id;
        $surveyorLogin->surveyor_ip =  $ip;

        $surveyorLogin->browser = @$surveyorAgent['browser'];
        $surveyorLogin->os = @$surveyorAgent['os_platform'];
        $surveyorLogin->save();

        return redirect()->route('surveyor.dashboard');
    }
}
