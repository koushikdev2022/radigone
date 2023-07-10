<?php

namespace App\Http\Controllers\Gateway;

use App\GeneralSetting;
use App\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GatewayCurrency;
use App\Deposit;
use App\Surveyor;
use App\Survey;
use App\SurveysTemp;
use App\CampaingTemp;
use App\TransactionTemp;
use App\TargetMarket;
use Illuminate\Support\Facades\Auth;
use Session;
ini_set('memory_limit', '44M');

class PaymentController extends Controller
{
    public function __construct()
    {
        return $this->activeTemplate = activeTemplate();
    }

    public function deposit(Request $request)
    {
             
             if(!empty(session::get('srvid_new')) && empty($request->dump_id)){
                    
                     Session::forget('srvid_new');
                     Session::forget('newflag');
                     Session::forget('surveyoy_total_views');
                     Session::forget('totalSurveynew');
                     Session::forget('totalSurveys');
                     Session::forget('rqstprice');
                     Session::forget('newchg');
             
             }elseif(!empty(session::get('srvid')) && empty($request->ischeck)){
                     Session::forget('srvid');
                     Session::forget('newflag');
                     Session::forget('surveyoy_total_views');
                     Session::forget('totalSurveynew');
                     Session::forget('totalSurveys');
                     Session::forget('rqstprice');
                     Session::forget('newchg');
             }elseif(!empty(session::get('srvid_republish')) && empty($request->ischeckrepublish)){
                     Session::forget('srvid_republish');
                     Session::forget('newflagrepublish');
                     Session::forget('surveyoy_total_viewsrepublish');
                     Session::forget('totalSurveynewrepublish');
                     Session::forget('totalSurveysrepublish');
                     Session::forget('rqstpricerepublish');
                     Session::forget('newchgrepublish');
             }
             
        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', 1);
        })->with('method')->orderby('method_code')->get();
        $page_title = 'Deposit Methods';
        return view('surveyor.payment.deposit', compact('gatewayCurrency', 'page_title'));
    }

    public function insufficientBalance(Request $request)
    {
        
        session()->forget('survey');
        session()->put('survey', $request->except(['image']));
        $survey = session()->get('survey');
        
    if(!empty($request->ischeck)){
      
             session()->forget('survey');
           
        $surveyor = auth()->guard('surveyor')->user();
        $surveyor->business_subcat = $request->subcat;
        $surveyor->save();
        $totalSurvey = auth()->guard('surveyor')->user()->balance;
        $surveyoy_total_views = $surveyor->total_views + $request->n_views;
          $total = $request->total;
           session::put('total',$total);
            if($request->hasFile('fileToUpload')){
                
                $store_file = [];
                $files = $request->file('fileToUpload');
                foreach ($files as $file) {
                  
                    $location = imagePath()['survey']['path'];
                    $size = imagePath()['survey']['size'];
                    $old = $file;
                    $survey_image = uploadImage($request->image, $location , $size, $old);
                    // $filename = $file->getClientOriginalName();
                    // $images = $file->store('public/photos');

                    $store_file[] =  $survey_image;
                }
         

            }
           
            $general = GeneralSetting::first();
            $rdata = "";
          
            if(isset($request->r_data)){
               
                foreach($request->r_data as $data){
                
                    $rdata .= $data;
                  
                }
            }

            $survey_image = $request->image;
            $survey_image_demo = "demo.png";
            $srvurl = '/uploads/campaigns/'.$survey_image_demo;
            //dd($survey_image);
            if($request->hasFile('image')) {
           // if(!empty($survey_image)){
                try{
                   
                    // $location = imagePath()['survey']['path'];
                    // $size = imagePath()['survey']['size'];
                    // $old = $survey_image;
                       $survey_image = time().'-'.'serv'.'.'.$survey_image->getClientOriginalExtension();
                       $filepath = '/uploads/campaigns/'.$survey_image;
                       $request->image->move(public_path('/uploads/campaigns/'), $survey_image);
                       $srvurl = '/uploads/campaigns/'.$survey_image;
                      
                        // $address_proof = time().'-'.'address_proof'.'.'.$request->address_proof->getClientOriginalExtension();
                        // $filepath = '/uploads/address_proof/';
                        // $request->address_proof->move(public_path('/uploads/address_proof/'), $address_proof);
                        // $address_proof_url = $filepath.$address_proof;
                   // $survey_image = uploadImage($request->image, $location , $size, $old);
                   
                }catch(\Exception $exp) {
                    return back()->withNotify(['error', 'Could not upload the image.']);
                }
            }
                 
        
            $user_percentage = $general->user_amount;
            $price_per_user = $request->total/$request->n_views;
            $per_user = ($price_per_user * $user_percentage) / 100;
           
            $survey = new SurveysTemp();
            $survey->image =  $srvurl;
            $survey->surveyor_id = auth()->guard('surveyor')->user()->id;
            // $survey->store_file=json_encode($store_file);
            $survey->category_id = $request->category_id;
            $survey->p_name = $request->p_name;
            $survey->p_specification=$request->p_specifications;
            $survey->p_mrp = (int)$request->p_mrp;
            $survey->discount = (int)$request->d_offer;
            $survey->required_data = $rdata;
            $survey->offer_type = $request->t_offer;
            $survey->total_views = (int)$request->n_views;
            $survey->publish = (int)$request->audience;
            if($request->audience_cat == 'General'){
               
            }else{
                $survey->target_market_category = $request->audience_cat;
            }
            
            $survey->total_slides = (int)$request->slides;
            $survey->slides_time = (int)$request->slides_time;
            $survey->repeated_viewers = (int)$request->repeated;
            $survey->ad_duration = (int)$request->ad_duration;
            $survey->online_purchase = (int)$request->o_purchase;
            $survey->template = $request->template;
            $survey->totalprice = $request->totalprice;
//          $survey->per_user = $request->totalprice/$request->n_views;
            $survey->per_user = $request->peruserprice;
            $survey->ad_type = $request->type_of_ad;
            $survey->video_url = $request->video_url;
            $survey->schedule_ad  = $request->date;
            $survey->purchas_url  = $request->opurl;
            $survey->total_without_gst  = $request->total;
            



            // $survey->name = $request->name;
            // $survey->age_limit = $request->age_limit;
            // $survey->country_limit = $request->country_limit;
            // $survey->start_age = $request->start_age;
            // $survey->end_age = $request->end_age;
            // $survey->country = $request->country;
            // $survey->category_id = $request->category_id;
            // $survey->surveyor_id = auth()->guard('surveyor')->user()->id;
            // $survey->status = $general->survey_approval;


            // DB::table('campaigns')->insert([
            //     'p_name' => $request->p_name,
            //     'surveyor_id' => auth()->guard('surveyor')->user()->id,
            //     'p_specification' => $request->p_specifications,
            //     'p_mrp' => (int)$request->p_mrp,
            //     'discount' => (int)$request->d_offer,
            //     'required_data' => $rdata,
            //     'offer_type' => $request->t_offer,
            //     'total_views' => (int)$request->n_views,
            //     'publish' => $request->audience,
            //     'target_market_category' => $request->audience_cat,
            //     'total_slides' => (int)$request->slides,
            //     'slides_time' => (int)$request->slides_time,
            //     'repeated_viewers' => (int)$request->repeated,
            //     'ad_duration' => (int)$request->ad_duration,
            //     'online_purchase' => (int)$request->o_purchase,
            //     'template' => $request->template,
            //     'image'=>$survey_image,
            //     'store_file'=>json_encode($store_file),
            // ]);
            $transaction = new TransactionTemp();
            $transaction->surveyor_id = auth()->guard('surveyor')->user()->id;
            $transaction->amount = getAmount($request->totalprice);
            $transaction->post_balance = getAmount($totalSurvey-$request->totalprice);
            $transaction->trx_type = '-';
            $transaction->details = 'For Add New Campaign ';
            $transaction->trx =  getTrx();
             $trnsid =  $transaction->save();
             
             
            session::put('surveyor_id_s',auth()->guard('surveyor')->user()->id);
            session::put('amount_s',getAmount($request->totalprice));
             session::put('post_balance_s',getAmount($totalSurvey-$request->totalprice));
            session::put('totalSurveys',$totalSurvey);
            session::put('rqstprice',$request->totalprice);
            
             session::put('trx_s', getTrx());
             
             
            $survey->txn = $transaction->trx;
            $survey->save();
            if(!empty($request->audience_cat)){
                if($request->audience_cat == 'General'){
                     $market =TargetMarket::create([
                     
                      'target_market_value'=>$request->target_market_value,
                      'servey_id' => $survey->id,
                      'ref_table'=>'dump'
                    ]);
                }else{
                     $market =TargetMarket::create([
                      'target_market_name'=> $request->audience_cat,
                      'target_market_value'=>$request->target_market_value,
                      'servey_id' => $survey->id,
                      'ref_table'=>'dump'
                    ]);
                }
               
              if($market){
                  $update = SurveysTemp::where('id',$survey->id)->update([
                            'target_market_id'=>$market->id,
                            
                      ]);
              }
            }
            
            Session::put('trnsid', $trnsid);
            Session::put('srvid', $survey->id);
            Session::put('newflag', $request->ischeck);
            Session::put('surveyoy_total_views', $surveyoy_total_views);
            Session::put('totalSurveynew', $totalSurvey-$request->totalprice);
            //  SurveyorsTemp::where('id', auth()->guard('surveyor')->user()->id)
            //     ->update([
            //         'total_views' => $surveyoy_total_views,
                   
            //     ]);
            // $data = [
            //     ['survey_id'=>$survey->id, 'question'=> '','type'=>1,'custom_input'=>1,'custom_input_type'=>1,'custom_question'=>'What is Product/Service Name?','custombox'=>1,'answers'=>$request->p_name],
            //     ['survey_id'=>$survey->id, 'question'=> '','type'=>1,'custom_input'=>1,'custom_input_type'=>1,'custom_question'=>'What is Radigone Offer Price?','custombox'=>1,'answers'=>$request->d_offer],
            // ];
            // Question::insert($data);


            // $notify[] = ['success', 'Campaign has been added'];
            // return redirect()->route('surveyor.survey.question.new',$survey->id)->withNotify($notify);

             return redirect()->route('surveyor.deposit',['ischeck'=>$request->ischeck]);
            
        }
    elseif(!empty($request->dump_id)){
         session()->forget('survey');
         $serv_data =SurveysTemp::where('id',$request->dump_id)->get();
            session::put('surveyor_id_s',auth()->guard('surveyor')->user()->id);
            session::put('amount_s',getAmount($request->totalprice));
            session::put('post_balance_s',$request->totalSurvey-$request->totalprice);
            session::put('totalSurveys',$request->totalSurvey);
            session::put('rqstprice',$request->totalprice);
            
          session::put('trx_s', getTrx());
            Session::put('srvid_new', $request->dump_id);
           // Session::put('newflag','ischeck');
            // Session::put('surveyoy_total_views', $surveyoy_total_views);
            Session::put('totalSurveynew', $request->totalSurvey-$request->totalprice);
            return redirect()->route('surveyor.deposit',['dump_id'=>$request->dump_id]); 
    }else if($request->ischeckrepublish){
                  session()->forget('survey');
                  
        $totalSurvey = auth()->guard('surveyor')->user()->balance;
        $srv = Survey::whereId($request->new_id)->first();
        $surveyor = auth()->guard('surveyor')->user();
        $surveyoy_total_views = $surveyor->total_views + $request->n_views;;
        if(is_null($srv)) {
            $notify[] = ['error', "No survey found!"];
            return redirect()->back()->withNotify($notify);
        }

        


            if($request->hasFile('fileToUpload')){

                $store_file = [];
                $files = $request->file('fileToUpload');
                foreach ($files as $file) {

                    $location = imagePath()['survey']['path'];
                    $size = imagePath()['survey']['size'];
                    $old = $file;
                    $survey_image = uploadImage($request->image, $location , $size, $old);
                    // $filename = $file->getClientOriginalName();
                    // $images = $file->store('public/photos');

                    $store_file[] =  $survey_image;
                }


            }
            $general = GeneralSetting::first();
            $rdata = "";

            if(isset($request->r_data)){
                foreach($request->r_data as $data){
                    $rdata .= $data;
                }
            }

            $survey_image = $request->image;
             $survey_image_demo = "demo.png";
            $srvurl = '/uploads/campaigns/'.$survey_image_demo;
            if($request->hasFile('image')) {
                try{
                    // $location = imagePath()['survey']['path'];
                    // $size = imagePath()['survey']['size'];
                    // $old = $survey_image;
                    // $survey_image = uploadImage($request->image, $location , $size, $old);
                       $survey_image = time().'-'.'serv'.'.'.$survey_image->getClientOriginalExtension();
                       $filepath = '/uploads/republishcampaigns/'.$survey_image;
                       $request->image->move(public_path('/uploads/republishcampaigns/'), $survey_image);
                       $srvurl = '/uploads/republishcampaigns/'.$survey_image;

                }catch(\Exception $exp) {
                    return back()->withNotify(['error', 'Could not upload the image.']);
                }
            }else {
                $survey_image = $srv->image;
            }

            $user_percentage = $general->user_amount;
            $price_per_user = $request->total/$request->n_views;
            $per_user = ($price_per_user * $user_percentage) / 100;

            $survey = new SurveysTemp();
            $survey->image = $srvurl;
            $survey->surveyor_id = auth()->guard('surveyor')->user()->id;
            // $survey->store_file=json_encode($store_file);
            $survey->category_id = $request->category_id;
            $survey->p_name = $request->p_name;
            $survey->p_specification=$request->p_specifications;
            $survey->p_mrp = (int)$request->p_mrp;
            $survey->discount = (int)$request->d_offer;
            $survey->required_data = $rdata;
            $survey->offer_type = $request->t_offer;
            $survey->total_views = (int)$request->n_views;
            $survey->publish = (int)$request->audience;
             if($request->audience_cat == 'General'){
               
            }else{
                $survey->target_market_category = $request->audience_cat;
            }
            $survey->total_slides = (int)$request->slides;
            $survey->slides_time = (int)$request->slides_time;
            $survey->repeated_viewers = (int)$request->repeated;
            $survey->ad_duration = (int)$request->ad_duration;
            $survey->online_purchase = (int)$request->o_purchase;
            $survey->template = $request->template;
            $survey->totalprice = $request->totalprice;
//        $survey->per_user = $request->totalprice/$request->n_views;
            $survey->per_user = $request->peruserprice;
            $survey->ad_type = $request->type_of_ad;
            $survey->video_url = $request->video_url;
            $survey->schedule_ad  = $request->date;
            $survey->purchas_url  = $request->opurl;
            $survey->total_without_gst  = $request->total;



            // $survey->name = $request->name;
            // $survey->age_limit = $request->age_limit;
            // $survey->country_limit = $request->country_limit;
            // $survey->start_age = $request->start_age;
            // $survey->end_age = $request->end_age;
            // $survey->country = $request->country;
            // $survey->category_id = $request->category_id;
            // $survey->surveyor_id = auth()->guard('surveyor')->user()->id;
            // $survey->status = $general->survey_approval;
            $survey->save();
            if(!empty($request->audience_cat)){
                if($request->audience_cat == 'General'){
                     $market =TargetMarket::create([
                     
                      'target_market_value'=>$request->target_market_value,
                      'servey_id' => $survey->id,
                      'ref_table'=>'dump'
                    ]);
                }else{
                     $market =TargetMarket::create([
                      'target_market_name'=> $request->audience_cat,
                      'target_market_value'=>$request->target_market_value,
                      'servey_id' => $survey->id,
                      'ref_table'=>'dump'
                    ]);
                }
               
              if($market){
                  $update = SurveysTemp::where('id',$survey->id)->update([
                            'target_market_id'=>$market->id,
                      ]);
              }
            }

            // DB::table('campaigns')->insert([
            //     'p_name' => $request->p_name,
            //     'surveyor_id' => auth()->guard('surveyor')->user()->id,
            //     'p_specification' => $request->p_specifications,
            //     'p_mrp' => (int)$request->p_mrp,
            //     'discount' => (int)$request->d_offer,
            //     'required_data' => $rdata,
            //     'offer_type' => $request->t_offer,
            //     'total_views' => (int)$request->n_views,
            //     'publish' => $request->audience,
            //     'target_market_category' => $request->audience_cat,
            //     'total_slides' => (int)$request->slides,
            //     'slides_time' => (int)$request->slides_time,
            //     'repeated_viewers' => (int)$request->repeated,
            //     'ad_duration' => (int)$request->ad_duration,
            //     'online_purchase' => (int)$request->o_purchase,
            //     'template' => $request->template,
            //     'image'=>$survey_image,
            //     'store_file'=>json_encode($store_file),
            // ]);

               

             session::put('surveyor_id_s',auth()->guard('surveyor')->user()->id);
             session::put('amount_s',getAmount($request->totalprice));
              session::put('post_balance_s',getAmount($totalSurvey-$request->totalprice));
             session::put('totalSurveys',$totalSurvey);
             session::put('rqstprice',$request->totalprice);
             
             
            Session::put('srvid_republish',$survey->id);
            Session::put('newflag','ischeck');
            Session::put('surveyoy_total_views', $surveyoy_total_views);
            Session::put('totalSurveynew', $totalSurvey-$request->totalprice);

              Session::put('newflagrepublish',session::get('newflag'));
              session::put('surveyoy_total_viewsrepublish',Session::get('surveyoy_total_views'));
              session::put('totalSurveynewrepublish',Session::get('totalSurveynew'));
              session::put ( 'totalSurveysrepublish',Session::get('totalSurveys'));
               session::put('rqstpricerepublish',Session::get('rqstprice'));
                session::put('newchgrepublish',Session::get('newchg'));

            // Surveyor::where('id', auth()->guard('surveyor')->user()->id)
            //     ->update([
            //         'total_views' => $surveyoy_total_views,
            //         'balance' => $totalSurvey-$request->totalprice
            //     ]);
            // $data = [
            //     ['survey_id'=>$survey->id, 'question'=> '','type'=>1,'custom_input'=>1,'custom_input_type'=>1,'custom_question'=>'What is Product/Service Name?','custombox'=>1,'answers'=>$request->p_name],
            //     ['survey_id'=>$survey->id, 'question'=> '','type'=>1,'custom_input'=>1,'custom_input_type'=>1,'custom_question'=>'What is Radigone Offer Price?','custombox'=>1,'answers'=>$request->d_offer],
            // ];
            // Question::insert($data);


            // $notify[] = ['success', 'Campaign has been added'];
            // return redirect()->route('surveyor.survey.question.new',$survey->id)->withNotify($notify);
             return redirect()->route('surveyor.deposit',['ischeckrepublish'=>$request->ischeckrepublish]);
        
    }
        return redirect()->route('surveyor.deposit');
    }

    public function depositInsert(Request $request)
    {
        
        $request->validate([
            'amount' => 'required|numeric|gt:0',
            'method_code' => 'required',
            'currency' => 'required',
        ]);

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
        return redirect()->route('surveyor.deposit.preview');
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
      
        return view('surveyor.payment.preview', compact('data', 'page_title'));
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

        $data = $new::process($deposit);
       
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
            $surveyor->balance += $data->amount;
            $surveyor->save();

            $gateway = $data->gateway;

            

            $transaction = new Transaction();
            $transaction->surveyor_id = $data->surveyor_id;
            $transaction->amount = $data->amount;
            $transaction->post_balance = getAmount($surveyor->balance);
            $transaction->charge = getAmount($data->charge);
            $transaction->trx_type = '+';
            $transaction->details = 'Deposit Via ' . $data->gateway_currency()->name;
            $transaction->trx = $data->trx;
            $transaction->save();
           
            Session::put('newtran',$surveyor->balance);
            Session::put('newchg', $data->amount);
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

    public static function userRegistrationUpdate($amount)
    {
        $gnl = GeneralSetting::first();
        $surveyor = Auth::guard('surveyor')->user();
        $surveyor->balance += $amount;
        $surveyor->save();

        $transaction = new Transaction();
        $transaction->surveyor_id = $surveyor->id;
        $transaction->amount = $amount;
        $transaction->post_balance = getAmount($surveyor->balance);
        $transaction->charge = getAmount(0);
        $transaction->trx_type = '+';
        $transaction->details = 'Deposit Via Razorpay';
        $transaction->trx = $data->trx;
        $transaction->save();

        notify($surveyor, 'DEPOSIT_COMPLETE', [
            'method_name' => $data->gateway_currency()->name,
            'method_currency' => $data->method_currency,
            'method_amount' => getAmount($data->final_amo),
            'amount' => getAmount($data->amount),
            'charge' => getAmount($data->charge),
            'currency' => $gnl->cur_text,
            'rate' => getAmount($data->rate),
            'trx' => $data->trx,
            'post_balance' => getAmount($surveyor->balance)
        ]);
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
