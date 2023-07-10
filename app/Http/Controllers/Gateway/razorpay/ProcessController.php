<?php

namespace App\Http\Controllers\Gateway\razorpay;

use App\Deposit;
use App\Survey;
use App\SurveysTemp;
use App\CampaingTemp;
use App\GatewayCurrency;
use App\Transaction;
use App\Surveyor;
use App\Question;
use App\TransactionTemp;
use App\TargetMarket;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\BuyViewController;
use App\Http\Controllers\Gateway\PaymentController;
use Illuminate\Http\Request;
use Session;
use Razorpay\Api\Api;
use Auth;
ini_set('memory_limit', '44M');


class ProcessController extends Controller
{
    /*
     * RazorPay Gateway
     */

    public static function process($deposit)
    {
        $razorAcc = json_decode($deposit->gateway_currency()->gateway_parameter);

        //  API request and response for creating an order
        $api_key = $razorAcc->key_id;
        $api_secret = $razorAcc->key_secret;

        $api = new Api($api_key, $api_secret);

        $order = $api->order->create(
            array(
                'receipt' => $deposit->trx,
                'amount' => round($deposit->final_amo * 100),
                'currency' => $deposit->method_currency,
                'payment_capture' => '0'
            )
        );


        $val['key'] = $razorAcc->key_id;
        $val['amount'] = round($deposit->final_amo * 100);
        $val['currency'] = $deposit->method_currency;
        $val['order_id'] = $order['id'];
        $val['buttontext'] = "Pay with Razorpay";
        $val['name'] = auth()->guard('surveyor')->user()->username;
        $val['description'] = "Payment By Razorpay";
        $val['image'] = asset( 'assets/images/logoIcon/logo.png');
        $val['prefill.name'] = auth()->guard('surveyor')->user()->firstname . ' ' . auth()->guard('surveyor')->user()->lastname;
        $val['prefill.email'] = auth()->guard('surveyor')->user()->email;
        $val['prefill.contact'] = auth()->guard('surveyor')->user()->mobile;
        $val['theme.color'] = "#2ecc71";
        $send['val'] = $val;

        $send['method'] = 'POST';


        $alias = $deposit->gateway->alias;

        $send['url'] = route('ipn.'.$alias);
        $send['custom'] = $deposit->trx;
        $send['checkout_js'] = "https://checkout.razorpay.com/v1/checkout.js";
        $send['view'] = 'surveyor.payment.'.$alias;
        return json_encode($send);
    }

    public static function processRegistration($amount)
    {
        $gateway = GatewayCurrency::where('name', 'RazorPay')->first();
        $razorAcc = json_decode($gateway->gateway_parameter);

        //  API request and response for creating an order
        $api_key = $razorAcc->key_id;
        $api_secret = $razorAcc->key_secret;

        $api = new Api($api_key, $api_secret);

        $order = $api->order->create(
            array(
                'receipt' => getTrx(),
                'amount' => round($amount * 100),
                'currency' => 'INR',
                'payment_capture' => '0'
            )
        );


        $val['key'] = $razorAcc->key_id;
        $val['amount'] = round($amount * 100);
        $val['currency'] = 'INR';
        $val['order_id'] = $order['id'];
        $val['buttontext'] = "Pay with Razorpay";
        $val['name'] = auth()->guard('surveyor')->user()->username;
        $val['description'] = "Payment By Razorpay";
        $val['image'] = asset( 'assets/images/logoIcon/logo.png');
        $val['prefill.name'] = auth()->guard('surveyor')->user()->firstname . ' ' . auth()->guard('surveyor')->user()->lastname;
        $val['prefill.email'] = auth()->guard('surveyor')->user()->email;
        $val['prefill.contact'] = auth()->guard('surveyor')->user()->mobile;
        $val['theme.color'] = "#2ecc71";
        $send['val'] = $val;

        $send['method'] = 'POST';
        $alias = 'razorpay';

        $send['url'] = route('ipn.'.$alias);
        $send['custom'] = $order['receipt'];
        $send['checkout_js'] = "https://checkout.razorpay.com/v1/checkout.js";
        $send['view'] = 'surveyor.payment.'.$alias;
        return json_encode($send);
    }

    public function ipn(Request $request)
    {
        $track = Session::get('Track');
        $data = Deposit::where('trx', $track)->orderBy('id', 'DESC')->first();
        $razorAcc = json_decode($data->gateway_currency()->gateway_parameter);
        
        if (!$data) {
            $notify[] = ['error', 'Invalid Request'];
        }

        $sig = hash_hmac('sha256', $request->razorpay_order_id . "|" . $request->razorpay_payment_id, $razorAcc->key_secret);
       
        if ($sig == $request->razorpay_signature && $data->status == '0') {
           
            PaymentController::userDataUpdate($data->trx);
            
            $notify[] = ['success', 'Transaction was successful, Ref: ' . $track];
            
        } else {
          
            $notify[] = ['error', "Invalid Request"];
        }
          $total_balance = Session::get('newchg') +  session::get('totalSurveys');
          $setbalancen = Session::get('newchg') +  session::get('totalSurveys');
           
             
         if( !empty(Session::get('srvid'))){
             $user = auth()->guard('surveyor')->user();
             $user_id = $user->id;
            
             $price_details = SurveysTemp::latest('created_at')->where('surveyor_id',$user_id)->first();
           
             $price_need = $price_details->totalprice;
             $target_market = $price_details->target_market_id;
             session::put('target_market',$target_market);
             $session_var =  Session::get('srvid');
             
             Session::get('sspricel');
             if($setbalancen > $price_need){
         $srv_data = SurveysTemp::select("*")
            ->where('id', Session::get('srvid'))
            ->each(function ($article) {

                //getting the record one by one that want to be copied
                $newUser = $article->replicate();

                //copy them using replicate and setting destination table by setTable()
                $newUser->setTable('surveys');
                $newUser->save();
                $target_count = TargetMarket::where('id',session::get('target_market'))->count();
                if($target_count>0){
                  if($newUser->id > 0){
                       $update = TargetMarket::where('id',session::get('target_market'))->update([
                            'servey_id'=>$newUser->id,
                            'ref_table'=>'servey'
                     ]);
              }
             }
              session::forget('target_market');
                //add following command if you need to remove records from first table
                $article->delete();
            });
            
           
           
             session::get('totalSurveys');
             session::get('rqstprice');
             
             $setbalance = Session::get('newchg') +  session::get('totalSurveys');
            
             session::get('total');
             
             $afterbalance = $setbalance - $price_need;
             
            $transaction = new Transaction();
            $transaction->surveyor_id =session::get('surveyor_id_s');
            $transaction->amount = session::get('amount_s');
            $transaction->post_balance =  $afterbalance;
            $transaction->trx_type = '-';
            $transaction->details = 'For Add New Campaign ';
            $transaction->trx = session::get('trx_s');
            $trnsid =  $transaction->save();
            $serveynew_id = Survey::latest('created_at')->first();
            $trans_id = Transaction::latest('created_at')->first();
              Surveyor::where('id', auth()->guard('surveyor')->user()->id)
                ->update([
                    'total_views' => Session::get('surveyoy_total_views'),
                    'balance' => $afterbalance
                    
                ]);
            $data = [
                ['survey_id'=>$serveynew_id->id, 'question'=> '','type'=>1,'custom_input'=>1,'custom_input_type'=>1,'custom_question'=>'What is Product/Service Name?','custombox'=>1,'answers'=>$request->p_name],
                ['survey_id'=>$serveynew_id->id, 'question'=> '','type'=>1,'custom_input'=>1,'custom_input_type'=>1,'custom_question'=>'What is Radigone Offer Price?','custombox'=>1,'answers'=>$request->d_offer],
            ];
            Question::insert($data);
            //  Session::forget('trnsid');
            //  Session::forget('srvid');
            //  Session::forget('newflag');
            //  Session::forget('surveyoy_total_views');
            //  Session::forget('totalSurveynew');
            //  Session::forget('totalSurveys');
            //  Session::forget('rqstprice');
            //  Session::forget('newchg');
            $notify[] = ['success', 'Campaign has been added'];
            return redirect()->route('surveyor.survey.question.new',$serveynew_id->id)->withNotify($notify);
             }else{
                  return redirect()->route(gatewayRedirectUrl())->withNotify($notify);
             }
        }elseif(!empty(Session::get('srvid_republish'))){
                   
                   
                  $total_balance = Session::get('newchgrepublish') +  session::get('totalSurveysrepublish');
                  $setbalancen = Session::get('newchgrepublish') +  session::get('totalSurveysrepublish');
                        $user = auth()->guard('surveyor')->user();
             $user_id = $user->id;
            
             $price_details = SurveysTemp::latest('created_at')->where('surveyor_id',$user_id)->first();
           
             $price_need = $price_details->totalprice;
             $target_market = $price_details->target_market_id;
             session::put('target_market',$target_market);
             $republish_id = session::get('srvid_republish');
           
            
           
             sleep(3);
           
           
             if($setbalancen > $price_need){
         
         $srv_data = SurveysTemp::select("*")
            ->where('id',  $republish_id)
            ->each(function ($article) {

                //getting the record one by one that want to be copied
                $newUser = $article->replicate();

                //copy them using replicate and setting destination table by setTable()
                $newUser->setTable('surveys');
                $newUser->save();
                 $target_count = TargetMarket::where('id',session::get('target_market'))->count();
                if($target_count>0){
                  if($newUser->id > 0){
                       $update = TargetMarket::where('id',session::get('target_market'))->update([
                            'servey_id'=>$newUser->id,
                            'ref_table'=>'servey'
                     ]);
                   }
                 }
                 session::forget('target_market');
                //add following command if you need to remove records from first table
                $article->delete();
            });
           
             session::get('totalSurveys');
             session::get('rqstprice');
             
             $setbalance = Session::get('newchg') +  session::get('totalSurveys');
            
             session::get('total');
             
             $afterbalance = $setbalance - $price_need;
             
            $transaction = new Transaction();
            $transaction->surveyor_id =session::get('surveyor_id_s');
            $transaction->amount = session::get('amount_s');
            $transaction->post_balance =  $afterbalance;
            $transaction->trx_type = '-';
            $transaction->details = 'For Add New Campaign ';
            $transaction->trx = session::get('trx_s');
            $trnsid =  $transaction->save();
            $serveynew_id = Survey::latest('created_at')->first();
            $trans_id = Transaction::latest('created_at')->first();
              Surveyor::where('id', auth()->guard('surveyor')->user()->id)
                ->update([
                    'total_views' => Session::get('surveyoy_total_views'),
                    'balance' => $afterbalance
                    
                ]);
            $data = [
                ['survey_id'=>$serveynew_id->id, 'question'=> '','type'=>1,'custom_input'=>1,'custom_input_type'=>1,'custom_question'=>'What is Product/Service Name?','custombox'=>1,'answers'=>$request->p_name],
                ['survey_id'=>$serveynew_id->id, 'question'=> '','type'=>1,'custom_input'=>1,'custom_input_type'=>1,'custom_question'=>'What is Radigone Offer Price?','custombox'=>1,'answers'=>$request->d_offer],
            ];
            Question::insert($data);
            //  Session::forget('trnsid');
            //  Session::forget('srvid');
            //  Session::forget('newflag');
            //  Session::forget('srvid_republish');
            //  Session::forget('surveyoy_total_views');
            //  Session::forget('totalSurveynew');
            //  Session::forget('totalSurveys');
            //  Session::forget('rqstprice');
            //  Session::forget('newchg');
            $notify[] = ['success', 'Campaign has been added'];
            return redirect()->route('surveyor.survey.question.new',$serveynew_id->id)->withNotify($notify);
             }else{
                  return redirect()->route(gatewayRedirectUrl())->withNotify($notify);
             }
            
        }elseif(!empty(Session::get('srvid_new'))){
             $user = auth()->guard('surveyor')->user();
             $user_id = $user->id;
            
             $price_details = SurveysTemp::latest('created_at')->where('surveyor_id',$user_id)->first();
           
            
             $target_market = $price_details->target_market_id;
             session::put('target_market',$target_market);
          
                $price_details = SurveysTemp::where('id',Session::get('srvid_new'))->first();
             $price_need = $price_details->totalprice;
             Session::get('sspricel');
             
             if($setbalancen > $price_need){
                 
         $srv_data = SurveysTemp::select("*")
            ->where('id', Session::get('srvid_new'))
            ->each(function ($article) {

                //getting the record one by one that want to be copied
                $newUser = $article->replicate();

                //copy them using replicate and setting destination table by setTable()
                $newUser->setTable('surveys');
                $newUser->save();
                $trgt_count = TargetMarket::where('servey_id',Session::get('srvid_new'))->count();
               
                if($trgt_count>0){
                   if($newUser->id > 0){
                       $update = TargetMarket::where('servey_id',Session::get('srvid_new'))->update([
                            'servey_id'=>$newUser->id,
                            'ref_table'=>'servey'
                     ]);
                 }
                }
                //add following command if you need to remove records from first table
                $article->delete();
            });
           
             session::get('totalSurveys');
             session::get('rqstprice');
             
             $setbalance = Session::get('newchg') +  session::get('totalSurveys');
            // dd(Session::get('newchg'));//tran
             //dd(session::get('totalSurveys'));//previous
             session::get('total');
             $afterbalance = $setbalance - $price_need;
            $transaction = new Transaction();
            $transaction->surveyor_id =session::get('surveyor_id_s');
            $transaction->amount = session::get('amount_s');
            $transaction->post_balance =  $afterbalance;
            $transaction->trx_type = '-';
            $transaction->details = 'For Add New Campaign ';
            $transaction->trx = session::get('trx_s');
            $trnsid =  $transaction->save();
            $serveynew_id = Survey::latest('created_at')->first();
            $trans_id = Transaction::latest('created_at')->first();
              Surveyor::where('id', auth()->guard('surveyor')->user()->id)
                ->update([
                    'total_views' => Session::get('surveyoy_total_views'),
                    'balance' => $afterbalance
                    
                ]);
            $data = [
                ['survey_id'=>$serveynew_id->id, 'question'=> '','type'=>1,'custom_input'=>1,'custom_input_type'=>1,'custom_question'=>'What is Product/Service Name?','custombox'=>1,'answers'=>$request->p_name],
                ['survey_id'=>$serveynew_id->id, 'question'=> '','type'=>1,'custom_input'=>1,'custom_input_type'=>1,'custom_question'=>'What is Radigone Offer Price?','custombox'=>1,'answers'=>$request->d_offer],
            ];
            Question::insert($data);
            //  Session::forget('trnsid');
            //  Session::forget('srvid_new');
            //  Session::forget('newflag');
            //  Session::forget('surveyoy_total_views');
            //  Session::forget('totalSurveynew');
            //  Session::forget('totalSurveys');
            //  Session::forget('rqstprice');
            //  Session::forget('newchg');
            $notify[] = ['success', 'Campaign has been added'];
            return redirect()->route('surveyor.survey.question.new',$serveynew_id->id)->withNotify($notify);
             }else{
                  return redirect()->route(gatewayRedirectUrl())->withNotify($notify);
             }
        }else{
        return redirect()->route(gatewayRedirectUrl())->withNotify($notify);
        }
    }

    public function ipnRegistration(Request $request)
    {
       
//        $track = Session::get('Track');
//        $data = Deposit::where('trx', $track)->orderBy('id', 'DESC')->first();
//        $razorAcc = json_decode($data->gateway_currency()->gateway_parameter);

        $gateway = GatewayCurrency::where('name', 'RazorPay')->first();
        $razorAcc = json_decode($gateway->gateway_parameter);

//        if (!$data) {
//            $notify[] = ['error', 'Invalid Request'];
//        }

        $sig = hash_hmac('sha256', $request->razorpay_order_id . "|" . $request->razorpay_payment_id, $razorAcc->key_secret);

        if ($sig == $request->razorpay_signature) {
            PaymentController::userDataUpdate($data->trx);
            $notify[] = ['success', 'Transaction was successful, Ref: ' . $track];
        } else {
            $notify[] = ['error', "Invalid Request"];
        }

        return redirect()->route(gatewayRedirectUrl())->withNotify($notify);
    }

    public static function processBuyViews($deposit)
    {
       
        $razorAcc = json_decode($deposit->gateway_currency()->gateway_parameter);

        //  API request and response for creating an order
        $api_key = $razorAcc->key_id;
        $api_secret = $razorAcc->key_secret;

        $api = new Api($api_key, $api_secret);

        $order = $api->order->create(
            array(
                'receipt' => $deposit->trx,
                'amount' => round($deposit->final_amo * 100),
                'currency' => $deposit->method_currency,
                'payment_capture' => '0'
            )
        );


        $val['key'] = $razorAcc->key_id;
        $val['amount'] = round($deposit->final_amo * 100);
        $val['currency'] = $deposit->method_currency;
        $val['order_id'] = $order['id'];
        $val['buttontext'] = "Pay with Razorpay";
        $val['name'] = auth()->guard('surveyor')->user()->username;
        $val['description'] = "Payment By Razorpay";
        $val['image'] = asset( 'assets/images/logoIcon/logo.png');
        $val['prefill.name'] = auth()->guard('surveyor')->user()->firstname . ' ' . auth()->guard('surveyor')->user()->lastname;
        $val['prefill.email'] = auth()->guard('surveyor')->user()->email;
        $val['prefill.contact'] = auth()->guard('surveyor')->user()->mobile;
        $val['theme.color'] = "#2ecc71";
        $send['val'] = $val;

        $send['method'] = 'POST';


        $alias = $deposit->gateway->alias;

        $send['url'] = route('ipn.buy_views.'.$alias);
        $send['custom'] = $deposit->trx;
        $send['checkout_js'] = "https://checkout.razorpay.com/v1/checkout.js";
        $send['view'] = 'surveyor.payment.'.$alias;
        return json_encode($send);
    }

    public function ipnBuyViews(Request $request)
    {
        
        $track = Session::get('Track');
        $data = Deposit::where('trx', $track)->orderBy('id', 'DESC')->first();
        $razorAcc = json_decode($data->gateway_currency()->gateway_parameter);

        if (!$data) {
            $notify[] = ['error', 'Invalid Request'];
        }

        $sig = hash_hmac('sha256', $request->razorpay_order_id . "|" . $request->razorpay_payment_id, $razorAcc->key_secret);

        if ($sig == $request->razorpay_signature && $data->status == '0') {
            BuyViewController::userDataUpdate($data->trx);
            $notify[] = ['success', 'Transaction was successful, Ref: ' . $track];
        } else {
            $notify[] = ['error', "Invalid Request"];
        }

        return redirect()->route(gatewayRedirectUrl())->withNotify($notify);
    }
}
