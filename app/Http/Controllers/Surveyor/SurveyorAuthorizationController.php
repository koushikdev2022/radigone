<?php

namespace App\Http\Controllers\Surveyor;

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

class SurveyorAuthorizationController extends Controller
{
    public function __construct()
    {
        return $this->activeTemplate = activeTemplate();
    }
    public function checkValidCode($surveyor, $code, $add_min = 10000)
    {
        if (!$code) return false;
        if (!$surveyor->ver_code_send_at) return false;
        if ($surveyor->ver_code_send_at->addMinutes($add_min) < Carbon::now()) return false;
        if ($surveyor->ver_code !== $code) return false;
        return true;
    }


    public function authorizeForm()
    {

        if (auth()->guard('surveyor')->check()) {
              $surveyor = Auth::guard('surveyor')->user();
            if (!$surveyor->status) {
//                return '1';
                auth()->guard('surveyor')->logout();
            }elseif (!$surveyor->ev) {

                if (!$this->checkValidCode($surveyor, $surveyor->ver_code)) {
                    $surveyor->ver_code = verificationCode(6);
                    $surveyor->ver_code_send_at = Carbon::now();
                    $surveyor->save();
                    send_email($surveyor, 'EVER_CODE', [
                        'code' => $surveyor->ver_code
                    ]);
                }
                $page_title = 'Email verification form';
                return view('surveyor.auth.authorization.email', compact('surveyor', 'page_title'));
            }elseif (!$surveyor->sv) {
//                 return '3';
                if (!$this->checkValidCode($surveyor, $surveyor->ver_code)) {
                    $surveyor->ver_code = verificationCode(6);
                    $surveyor->ver_code_send_at = Carbon::now();
                    $surveyor->save();
                    send_sms($surveyor, 'SVER_CODE', [
                        'code' => $surveyor->ver_code
                    ]);
                }
                $page_title = 'SMS verification form';
                return view('surveyor.auth.authorization.sms', compact('surveyor', 'page_title'));
            }elseif (!$surveyor->tv) {

                $page_title = 'Google Authenticator';
                return view('surveyor.auth.authorization.2fa', compact('surveyor', 'page_title'));
            }elseif (!$surveyor->rv) {
                $reg_fees = Registrationfees::first();
                if($reg_fees->surveyor_fees == 0) {
                    return redirect()->route('surveyor.dashboard');
                }
                $page_title = 'Registration Fees';
                $reg_fee = $reg_fees->surveyor_fees;
                $gateway = GatewayCurrency::where('name', 'RazorPay')->first();
                $razorAcc = json_decode($gateway->gateway_parameter);

                //  API request and response for creating an order
                $api_key = $razorAcc->key_id;
                $api_secret = $razorAcc->key_secret;
                return view('surveyor.auth.authorization.registration', compact('api_key','surveyor', 'page_title', 'reg_fee'));
            }else{
//                 return '5';
                return redirect()->route('surveyor.dashboard');
            }

        }

        return redirect()->route('surveyor.login');
    }

    public function registrationPayment(Request $request)
    {
     
        $surveyor = Auth::guard('surveyor')->user();
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
                $surveyor->rv = 1;
                $surveyor->save();
                $transaction = new Transaction();
                $transaction->surveyor_id = $surveyor->id;
                $transaction->amount = getAmount($request->price);
                $transaction->post_balance = $surveyor->balance;
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
         public function registrationPaymentDepo(Request $request)
    {
     
        $surveyor = Auth::guard('surveyor')->user();
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
                $surveyor->rv = 1;
                $surveyor->save();
                $transaction = new Transaction();
                $transaction->surveyor_id = $surveyor->id;
                $transaction->amount = getAmount($request->price);
                $transaction->post_balance = $surveyor->balance;
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
        $surveyor = Auth::guard('surveyor')->user();


        if ($this->checkValidCode($surveyor, $surveyor->ver_code, 2)) {
            $target_time = $surveyor->ver_code_send_at->addMinutes(2)->timestamp;
            $delay = $target_time - time();
            throw ValidationException::withMessages(['resend' => 'Please Try after ' . $delay . ' Seconds']);
        }
        if (!$this->checkValidCode($surveyor, $surveyor->ver_code)) {
            $surveyor->ver_code = verificationCode(6);
            $surveyor->ver_code_send_at = Carbon::now();
            $surveyor->save();
        } else {
            $surveyor->ver_code = $surveyor->ver_code;
            $surveyor->ver_code_send_at = Carbon::now();
            $surveyor->save();
        }



        if ($request->type === 'email') {
            send_email($surveyor, 'EVER_CODE',[
                'code' => $surveyor->ver_code
            ]);

            $notify[] = ['success', 'Email verification code sent successfully'];
            return back()->withNotify($notify);
        } elseif ($request->type === 'phone') {
            send_sms($surveyor, 'SVER_CODE', [
                'code' => $surveyor->ver_code
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

        $surveyor = Auth::guard('surveyor')->user();

        if ($this->checkValidCode($surveyor, $email_verified_code)) {
            $surveyor->ev = 1;
            $surveyor->ver_code = null;
            $surveyor->ver_code_send_at = null;
            $surveyor->save();
            return redirect()->intended(route('surveyor.dashboard'));
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
//
        $surveyor = Auth::guard('surveyor')->user();
//        if ($this->checkValidCode($surveyor, $sms_verified_code)) {
//            $surveyor->sv = 1;
//            $surveyor->ver_code = null;
//            $surveyor->ver_code_send_at = null;
//            $surveyor->save();
//            return redirect()->intended(route('surveyor.dashboard'));
//        }
//        throw ValidationException::withMessages(['sms_verified_code' => 'Verification code didn\'t match!']);
        $surveyor->sv = 1;
        $surveyor->ver_code = null;
        $surveyor->ver_code_send_at = null;
        $surveyor->save();
        return response([
            'success' => true
        ]);
    }
    public function g2faVerification(Request $request)
    {
        $surveyor = Auth::guard('surveyor')->user();

        $this->validate(
            $request, [
            'code.*' => 'required',
        ], [
            'code.*.required' => 'Code is required',
        ]);

        $ga = new GoogleAuthenticator();


        $code =  str_replace(',','',implode(',',$request->code));

        $secret = $surveyor->tsc;
        $oneCode = $ga->getCode($secret);
        $surveyorCode = $code;
        if ($oneCode == $surveyorCode) {
            $surveyor->tv = 1;
            $surveyor->save();
            return redirect()->route('surveyor.dashboard');
        } else {
            $notify[] = ['error', 'Wrong Verification Code'];
            return back()->withNotify($notify);
        }
    }

}
