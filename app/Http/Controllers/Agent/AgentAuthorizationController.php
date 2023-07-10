<?php

namespace App\Http\Controllers\Agent;

use App\GatewayCurrency;
use App\Lib\GoogleAuthenticator;
use App\Http\Controllers\Controller;
use App\Registrationfees;
use App\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Razorpay\Api\Api;

class AgentAuthorizationController extends Controller
{
    public function __construct()
    {
        return $this->activeTemplate = activeTemplate();
    }
    public function checkValidCode($agent, $code, $add_min = 10000)
    {
        if (!$code) return false;
        if (!$agent->ver_code_send_at) return false;
        if ($agent->ver_code_send_at->addMinutes($add_min) < Carbon::now()) return false;
        if ($agent->ver_code !== $code) return false;
        return true;
    }


    public function authorizeForm()
    {

        if (auth()->guard('agent')->check()) {
            $agent = Auth::guard('agent')->user();
            if (!$agent->status) {
                auth()->guard('agent')->logout();
            }elseif (!$agent->ev) {
                if (!$this->checkValidCode($agent, $agent->ver_code)) {
                    $agent->ver_code = verificationCode(6);
                    $agent->ver_code_send_at = Carbon::now();
                    $agent->save();
                    send_email($agent, 'EVER_CODE', [
                        'code' => $agent->ver_code
                    ]);
                }
                $page_title = 'Email verification form';
            }elseif (!$agent->sv) {
                if (!$this->checkValidCode($agent, $agent->ver_code)) {
                    $agent->ver_code = verificationCode(6);
                    $agent->ver_code_send_at = Carbon::now();
                    $agent->save();
                    send_sms($agent, 'SVER_CODE', [
                        'code' => $agent->ver_code
                    ]);
                }
                $page_title = 'SMS verification form';
                return view('agent.auth.authorization.sms', compact('agent', 'page_title'));
            }elseif (!$agent->tv) {
                $page_title = 'Google Authenticator';
                return view('agent.auth.authorization.2fa', compact('agent', 'page_title'));
            }elseif (!$agent->rv) {
                $reg_fees = Registrationfees::first();
                if($reg_fees->surveyor_fees == 0) {
                    return redirect()->route('agent.dashboard');
                }
                $page_title = 'Registration Fees';
                $reg_fee = $reg_fees->agent_fees;
                $gateway = GatewayCurrency::where('name', 'RazorPay')->first();
                $razorAcc = json_decode($gateway->gateway_parameter);

                //  API request and response for creating an order
                $api_key = $razorAcc->key_id;
                $api_secret = $razorAcc->key_secret;
                return view('agent.auth.authorization.registration', compact('api_key','agent', 'page_title', 'reg_fee'));
            }else{
                return redirect()->route('agent.dashboard');
            }

        }

        return redirect()->route('agent.login');
    }

    public function registrationPayment(Request $request)
    {
        $agent = Auth::guard('agent')->user();
        $trx = getTrx();
        $input = $request->all();
        $gateway = GatewayCurrency::where('name', 'RazorPay')->first();
        $razorAcc = json_decode($gateway->gateway_parameter);

        //  API request and response for creating an order
        $api_key = $razorAcc->key_id;
        $api_secret = $razorAcc->key_secret;

        $api = new Api($api_key, $api_secret);

        $payment = $api->payment->fetch($input['razorpay_payment_id']);
        if(count($input) && !empty($input['razorpay_payment_id'])) {
            try {
                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount' => $payment['amount']));
                $agent->rv = 1;
                $agent->save();
                $transaction = new Transaction();
                $transaction->agent_id = $agent->id;
                $transaction->amount = getAmount($request->price);
                $transaction->post_balance = $agent->balance;
                $transaction->charge = getAmount(0);
                $transaction->trx_type = '+';
                $transaction->details = 'Payment For Sponsor Registration Fee';
                $transaction->trx = $trx;
                $transaction->save();

//                notify($user, 'REGISTRATION_COMPLETE', [
//                    'method_name' => 'RAZORPAY',
//                    'method_currency' => 'INR',
//                    'method_amount' => getAmount($payment['amount']),
//                    'amount' => getAmount($payment['amount']),
//                    'charge' => getAmount(0),
//                    'currency' => 'INR',
//                    'rate' => getAmount(0),
//                    'trx' => $trx,
//                    'post_balance' => getAmount(0)
//                ]);
                return redirect()->back();

            } catch(\Exception $e) {
//                return $e->getMessage();
//                Session::put('error',$e->getMessage());
                return redirect()->back();
            }
        }
    }

    public function sendVerifyCode(Request $request)
    {
        $agent = Auth::guard('agent')->user();


        if ($this->checkValidCode($agent, $agent->ver_code, 2)) {
            $target_time = $agent->ver_code_send_at->addMinutes(2)->timestamp;
            $delay = $target_time - time();
            throw ValidationException::withMessages(['resend' => 'Please Try after ' . $delay . ' Seconds']);
        }
        if (!$this->checkValidCode($agent, $agent->ver_code)) {
            $agent->ver_code = verificationCode(6);
            $agent->ver_code_send_at = Carbon::now();
            $agent->save();
        } else {
            $agent->ver_code = $agent->ver_code;
            $agent->ver_code_send_at = Carbon::now();
            $agent->save();
        }



        if ($request->type === 'email') {
            send_email($agent, 'EVER_CODE',[
                'code' => $agent->ver_code
            ]);

            $notify[] = ['success', 'Email verification code sent successfully'];
            return back()->withNotify($notify);
        } elseif ($request->type === 'phone') {
            send_sms($agent, 'SVER_CODE', [
                'code' => $agent->ver_code
            ]);
            $notify[] = ['success', 'SMS verification code sent successfully'];
            return back()->withNotify($notify);
        } else {
            throw ValidationException::withMessages(['resend' => 'Sending Failed']);
        }
    }

    public function emailVerification(Request $request)
    {
        $rules = [
            'email_verified_code.*' => 'required',
        ];
        $msg = [
            'email_verified_code.*.required' => 'Email verification code is required',
        ];
        $validate = $request->validate($rules, $msg);


        $email_verified_code =  str_replace(',','',implode(',',$request->email_verified_code));

        $agent = Auth::guard('agent')->user();

        if ($this->checkValidCode($agent, $email_verified_code)) {
            $agent->ev = 1;
            $agent->ver_code = null;
            $agent->ver_code_send_at = null;
            $agent->save();
            return redirect()->intended(route('agent.dashboard'));
        }
        throw ValidationException::withMessages(['email_verified_code' => 'Verification code didn\'t match!']);
    }

    public function smsVerification(Request $request)
    {
//        $request->validate([
//            'sms_verified_code.*' => 'required',
//        ], [
//            'sms_verified_code.*.required' => 'SMS verification code is required',
//        ]);
//
//
//        $sms_verified_code =  str_replace(',','',implode(',',$request->sms_verified_code));

        $agent = Auth::guard('agent')->user();
//        if ($this->checkValidCode($agent, $sms_verified_code)) {
//            $agent->sv = 1;
//            $agent->ver_code = null;
//            $agent->ver_code_send_at = null;
//            $sagent->save();
//            return redirect()->intended(route('agent.dashboard'));
//        }
//        throw ValidationException::withMessages(['sms_verified_code' => 'Verification code didn\'t match!']);

        $agent->sv = 1;
        $agent->ver_code = null;
        $agent->ver_code_send_at = null;
        $agent->save();
        return response([
            'success' => true
        ]);
    }
    public function g2faVerification(Request $request)
    {
        $agent = Auth::guard('agent')->user();

        $this->validate(
            $request, [
            'code.*' => 'required',
        ], [
            'code.*.required' => 'Code is required',
        ]);

        $ga = new GoogleAuthenticator();


        $code =  str_replace(',','',implode(',',$request->code));

        $secret = $agent->tsc;
        $oneCode = $ga->getCode($secret);
        $surveyorCode = $code;
        if ($oneCode == $surveyorCode) {
            $agent->tv = 1;
            $agent->save();
            return redirect()->route('agent.dashboard');
        } else {
            $notify[] = ['error', 'Wrong Verification Code'];
            return back()->withNotify($notify);
        }
    }

}
