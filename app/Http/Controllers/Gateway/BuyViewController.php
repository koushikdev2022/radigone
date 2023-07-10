<?php

namespace App\Http\Controllers\Gateway;

use App\Category;
use App\Deposit;
use App\GatewayCurrency;
use App\GeneralSetting;
use App\Http\Controllers\Controller;
use App\Surveyor;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BuyViewController extends Controller
{
    public function __construct()
    {
        return $this->activeTemplate = activeTemplate();
    }

    public function deposit()
    {
        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', 1);
        })->with('method')->orderby('method_code')->get();
        $surveyor = auth()->guard('surveyor')->user();
        $category = Category::whereId($surveyor->business_cat)->first();
        $page_title = 'Deposit Methods';
        return view('surveyor.payment.buy-view.deposit', compact('gatewayCurrency', 'page_title', 'category'));
    }

    public function insufficientBalance(Request $request)
    {
        session()->forget('survey');
        session()->put('survey', $request->except(['image']));
        $survey = session()->get('survey');
        return redirect()->route('surveyor.buy_views.deposit');
    }

    public function depositInsert(Request $request)
    {

        $request->validate([
            'add_views' => 'required',
            'amount' => 'required|numeric|gt:0',
            'method_code' => 'required',
            'currency' => 'required',
        ]);

        session()->forget('add_views');
        session()->put('add_views', $request->add_views);

        $surveyor = auth()->guard('surveyor')->user();

        $gate = GatewayCurrency::where('method_code', $request->method_code)->where('currency', $request->currency)->first();
        if (!$gate) {
            $notify[] = ['error', 'Invalid Gateway'];
            return back()->withNotify($notify);
        }

        if ($gate->min_amount > $request->amount || $gate->max_amount < $request->amount) {
            $notify[] = ['error', 'Please Follow Deposit Limit'];
            return back()->withNotify($notify);
        }

        $charge = getAmount($gate->fixed_charge + ($request->amount * $gate->percent_charge / 100));
        $payable = getAmount($request->amount + $charge);
        $final_amo = getAmount($payable * $gate->rate);

        $data = new Deposit();
        $data->surveyor_id = $surveyor->id;
        $data->method_code = $gate->method_code;
        $data->method_currency = strtoupper($gate->currency);
        $data->amount = $request->amount;
        $data->charge = $charge;
        $data->rate = $gate->rate;
        $data->final_amo = getAmount($final_amo);
        $data->btc_amo = 0;
        $data->btc_wallet = "";
        $data->trx = getTrx();
        $data->try = 0;
        $data->status = 0;
        $data->save();
        session()->put('Track', $data['trx']);
        return redirect()->route('surveyor.buy_views.deposit.preview');
    }


    public function depositPreview()
    {

        $track = session()->get('Track');
        $data = Deposit::where('trx', $track)->orderBy('id', 'DESC')->firstOrFail();

        if (is_null($data)) {
            $notify[] = ['error', 'Invalid Deposit Request'];
            return redirect()->route(gatewayRedirectUrl())->withNotify($notify);
        }
        if ($data->status != 0) {
            $notify[] = ['error', 'Invalid Deposit Request'];
            return redirect()->route(gatewayRedirectUrl())->withNotify($notify);
        }
        $page_title = 'Payment Preview';
        return view('surveyor.payment.buy-view.preview', compact('data', 'page_title'));
    }


    public function depositConfirm()
    {
        $track = Session::get('Track');
        $deposit = Deposit::where('trx', $track)->orderBy('id', 'DESC')->with('gateway')->first();
        if (is_null($deposit)) {
            $notify[] = ['error', 'Invalid Deposit Request'];
            return redirect()->route(gatewayRedirectUrl())->withNotify($notify);
        }
        if ($deposit->status != 0) {
            $notify[] = ['error', 'Invalid Deposit Request'];
            return redirect()->route(gatewayRedirectUrl())->withNotify($notify);
        }

        if ($deposit->method_code >= 1000) {
            $this->userDataUpdate($deposit);
            $notify[] = ['success', 'Your deposit request is queued for approval.'];
            return back()->withNotify($notify);
        }


        $dirName = $deposit->gateway->alias;
        $new = __NAMESPACE__ . '\\' . $dirName . '\\ProcessController';

        $data = $new::processBuyViews($deposit);
        $data = json_decode($data);


        if (isset($data->error)) {
            $notify[] = ['error', $data->message];
            return redirect()->route(gatewayRedirectUrl())->withNotify($notify);
        }
        if (isset($data->redirect)) {
            return redirect($data->redirect_url);
        }

        // for Stripe V3
        if(@$data->session){
            $deposit->btc_wallet = $data->session->id;
            $deposit->save();
        }

        $page_title = 'Payment Confirm';
        return view($data->view, compact('data', 'page_title', 'deposit'));
    }


    public static function userDataUpdate($trx)
    {
        $gnl = GeneralSetting::first();
        $data = Deposit::where('trx', $trx)->first();
        if ($data->status == 0) {
            $data->status = 1;
            $data->save();

            $surveyor = Surveyor::find($data->surveyor_id);
            $newBalance = $surveyor->balance + $data->amount;
            $surveyor->bought_views += session('add_views');
            $surveyor->save();

            $gateway = $data->gateway;



            $transaction1 = new Transaction();
            $transaction1->surveyor_id = $data->surveyor_id;
            $transaction1->amount = $data->amount;
            $transaction1->post_balance = getAmount($newBalance);
            $transaction1->charge = getAmount($data->charge);
            $transaction1->trx_type = '+';
            $transaction1->details = 'Deposit Via ' . $data->gateway_currency()->name;
            $transaction1->trx = $data->trx;
            $transaction1->save();

            $transaction2 = new Transaction();
            $transaction2->surveyor_id = $data->surveyor_id;
            $transaction2->amount = $data->amount;
            $transaction2->post_balance = getAmount($surveyor->balance);
            $transaction2->charge = getAmount($data->charge);
            $transaction2->trx_type = '-';
            $transaction2->details = 'For buying '.session('add_views').' views';
            $transaction2->trx = $data->trx;
            $transaction2->save();

            session()->forget('add_views');

            // notify($surveyor, 'DEPOSIT_COMPLETE', [
            //     'method_name' => $data->gateway_currency()->name,
            //     'method_currency' => $data->method_currency,
            //     'method_amount' => getAmount($data->final_amo),
            //     'amount' => getAmount($data->amount),
            //     'charge' => getAmount($data->charge),
            //     'currency' => $gnl->cur_text,
            //     'rate' => getAmount($data->rate),
            //     'trx' => $data->trx,
            //     'post_balance' => getAmount($surveyor->balance)
            // ]);
        }
    }

    public function manualDepositConfirm()
    {
        $track = session()->get('Track');
        $data = Deposit::with('gateway')->where('status', 0)->where('trx', $track)->first();
        if (!$data) {
            return redirect()->route(gatewayRedirectUrl());
        }
        if ($data->status != 0) {
            return redirect()->route(gatewayRedirectUrl());
        }
        if ($data->method_code > 999) {

            $page_title = 'Deposit Confirm';
            $method = $data->gateway_currency();
            return view('surveyor.manual_payment.manual_confirm', compact('data', 'page_title', 'method'));
        }
        abort(404);
    }

    public function manualDepositUpdate(Request $request)
    {
        $track = session()->get('Track');
        $data = Deposit::with('gateway')->where('status', 0)->where('trx', $track)->first();
        if (!$data) {
            return redirect()->route(gatewayRedirectUrl());
        }
        if ($data->status != 0) {
            return redirect()->route(gatewayRedirectUrl());
        }

        $params = json_decode($data->gateway_currency()->gateway_parameter);

        $rules = [];
        $inputField = [];
        $verifyImages = [];

        if ($params != null) {
            foreach ($params as $key => $cus) {
                $rules[$key] = [$cus->validation];
                if ($cus->type == 'file') {
                    array_push($rules[$key], 'image');
                    array_push($rules[$key], 'mimes:jpeg,jpg,png');
                    array_push($rules[$key], 'max:2048');

                    array_push($verifyImages, $key);
                }
                if ($cus->type == 'text') {
                    array_push($rules[$key], 'max:191');
                }
                if ($cus->type == 'textarea') {
                    array_push($rules[$key], 'max:300');
                }
                $inputField[] = $key;
            }
        }


        $this->validate($request, $rules);


        $directory = date("Y")."/".date("m")."/".date("d");
        $path = imagePath()['verify']['deposit']['path'].'/'.$directory;
        $collection = collect($request);
        $reqField = [];
        if ($params != null) {
            foreach ($collection as $k => $v) {
                foreach ($params as $inKey => $inVal) {
                    if ($k != $inKey) {
                        continue;
                    } else {
                        if ($inVal->type == 'file') {
                            if ($request->hasFile($inKey)) {
                                try {
                                    $reqField[$inKey] = [
                                        'field_name' => $directory.'/'.uploadImage($request[$inKey], $path),
                                        'type' => $inVal->type,
                                    ];
                                } catch (\Exception $exp) {
                                    $notify[] = ['error', 'Could not upload your ' . $inKey];
                                    return back()->withNotify($notify)->withInput();
                                }
                            }
                        } else {
                            $reqField[$inKey] = $v;
                            $reqField[$inKey] = [
                                'field_name' => $v,
                                'type' => $inVal->type,
                            ];
                        }
                    }
                }
            }
            $data->detail = $reqField;
        } else {
            $data->detail = null;
        }



        $data->status = 2; // pending
        $data->save();

        $gnl = GeneralSetting::first();

        notify($data->surveyor, 'DEPOSIT_REQUEST', [
            'method_name' => $data->gateway_currency()->name,
            'method_currency' => $data->method_currency,
            'method_amount' => getAmount($data->final_amo),
            'amount' => getAmount($data->amount),
            'charge' => getAmount($data->charge),
            'currency' => $gnl->cur_text,
            'rate' => getAmount($data->rate),
            'trx' => $data->trx
        ]);

        $notify[] = ['success', 'You have deposit request has been taken.'];
        return redirect()->route('surveyor.deposit.history')->withNotify($notify);
    }
}
