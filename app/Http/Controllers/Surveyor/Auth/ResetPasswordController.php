<?php

namespace App\Http\Controllers\Surveyor\Auth;

use App\Surveyor;
use App\SurveyorPasswordReset;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;


class ResetPasswordController extends Controller
{
    /*
        |--------------------------------------------------------------------------
        | Password Reset Controller
        |--------------------------------------------------------------------------
        |
        | This controller is responsible for handling password reset requests
        | and uses a simple trait to include this behavior. You're free to
        | explore this trait and override any methods you wish to tweak.
        |
        */

    use ResetsPasswords;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    public $redirectTo = '/surveyor/dashboard';


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('surveyor.guest');
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Http\Response
     */
    public function showResetForm(Request $request, $token)
    {
        $page_title = "Account Recovery";
        $tk = SurveyorPasswordReset::where('token', $token)->where('status', 0)->first();

        if (empty($tk)) {
            $notify[] = ['error', 'Token Not Found!'];
            return redirect()->route('surveyor.password.reset')->withNotify($notify);
        }
        $email = $tk->email;
        return view('surveyor.auth.passwords.reset', compact('page_title', 'email', 'token'));
    }


    public function reset(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|confirmed|min:4',
        ]);

        $reset = SurveyorPasswordReset::where('token', $request->token)->orderBy('created_at', 'desc')->first();
        $surveyor = Surveyor::where('email', $reset->email)->first();
        if ($reset->status == 1) {
            $notify[] = ['error', 'Invalid code'];
            return redirect()->route('surveyor.login')->withNotify($notify);
        }

        $surveyor->password = bcrypt($request->password);
        $surveyor->save();
        SurveyorPasswordReset::where('email', $surveyor->email)->update(['status' => 1]);

        $surveyorAgent = getIpInfo();
        $osBrowser = osBrowser();
        notify($surveyor, 'PASS_RESET_DONE', [
            'operating_system' => @$osBrowser['os_platform'],
            'browser' => @$osBrowser['browser'],
            'ip' => @$surveyorAgent['ip'],
            'time' => @$surveyorAgent['time']
        ]);

        $notify[] = ['success', 'Password Changed'];
        return redirect()->route('surveyor.login')->withNotify($notify);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('surveyors');
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('surveyor');
    }
}
