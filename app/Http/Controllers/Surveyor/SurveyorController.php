<?php

namespace App\Http\Controllers\Surveyor;

use App\Answer;
use App\Offertype;
use App\Category;
use App\AdType;
use App\Deposit;
use App\Refund;
use App\SliderPrice;
use App\Exports\AdVisitorReport;
use App\GeneralSetting;
use App\Lib\GoogleAuthenticator;
use App\Http\Controllers\Controller;
use App\Option;
use App\Question;
use App\Registrationfees;
use App\SubCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rules\FileTypeValidate;
use App\Survey;
use App\SurveysTemp;
use App\TargetMarket;
use App\Surveyor;
use session;
use App\Transaction;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Illuminate\Support\Facades\DB;
use App\campaign;
ini_set('memory_limit', '44M');
class SurveyorController extends Controller
{
    public function viewcalendar(){
        $page_title = 'Calendar';
        $surveyor = Auth::guard('surveyor')->user();
        return view('surveyor/calendar', compact('page_title', 'surveyor'));
    }
    public function dashboard()
    {
        $page_title = 'Dashboard';
        $surveyor = auth()->guard('surveyor')->user();
        if($surveyor->registration_fees =='1'){
            $bought_views = 0;
            $total_views = 0;
            $pending_views = 0;
            $surveys = Survey::where('surveyor_id', $surveyor->id)->get();
            foreach ($surveys as $sv) {
                $bv = !is_null($sv->users) ? count($sv->users) : 0;
                $bought_views = $bought_views + $bv;
                $total_views = $total_views + $sv->total_views;
                $pv = $sv->total_views - $bv;
                $pending_views = $pending_views + $pv;
            }
        $totalDeposit = Deposit::where('surveyor_id',$surveyor->id)->where('status',1)->sum('amount');
     
        $totalTransaction = Transaction::where('surveyor_id',$surveyor->id)->count();
        $approvedSurvey = $surveyor->surveys->where('status',0)->count();
        $pendingSurvey = $surveyor->surveys->where('status',1)->count();
        $rejectedSurvey = $surveyor->surveys->where('status',3)->count();

        $survey_chart = Answer::where('surveyor_id',$surveyor->id)->groupBy('survey_id')->orderBy('created_at')->get()->groupBy(function ($d) {
            return $d->created_at->format('F');
        });
        $survey_all = [];
        $month_survey = [];
        foreach ($survey_chart as $key => $value) {
            $survey_all[] = count($value);
            $month_survey[] = $key;
        }
        return   view('surveyor.dashboard',compact('page_title','totalDeposit','surveyor','totalTransaction','approvedSurvey','pendingSurvey','rejectedSurvey','survey_all','month_survey', 'bought_views', 'total_views', 'pending_views'));
        }else{
            $page_title = 'Registration fees';
            $registration = Registrationfees::where('id',1)->first();
            return view('surveyor.registrationfees', compact('page_title', 'surveyor','registration'));
        }
    }

    public function profile()
    {
        $page_title = 'Profile';
        $surveyor = Auth::guard('surveyor')->user();
        if(!is_null($surveyor->business_cat)) {
            $subcategories = [];
            $category = Category::where('id', $surveyor->business_cat)->first();
            $user_subcategory = SubCategoryRequest::where('category_id', $surveyor->business_cat)->where('surveyor_id', $surveyor->id)->where('status', 1)->get();
            foreach ($user_subcategory as $us) {
                array_push($subcategories, $us->name);
            }
            if(!is_null($category) || !is_null($category->subcategories)) {
                $vs = explode(',',$category->subcategories);
                foreach ($vs as $v) {
                    array_push($subcategories, $v);
                }

            }

        }else {
            $category = null;
            $subcategories = [];
        }
        $c = surveyorProfileCompletePercent($surveyor);
        $categories = Category::where('status','1')->latest()->get();
        return view('surveyor.profile', compact('page_title', 'surveyor', 'c', 'categories', 'category', 'subcategories'));
    }

    public function profileUpdate(Request $request)
    {
        $this->validate($request, [
            'image' => [new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'firstname' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
            'address' => "sometimes|required|max:191",
            'state' => 'sometimes|required|max:80',
            'zip' => 'sometimes|required|max:40',
            'city' => 'sometimes|required|max:50',
        ],[
            'firstname.required'=>'First Name Field is required',
            'lastname.required'=>'Last Name Field is required'
        ]);

        $surveyor = Auth::guard('surveyor')->user();

        if(!is_null($request->isfirm)) {
            $isfirm = 1;
        }else {
            $isfirm = 0;
        }

        if(!is_null($request->new_sub_category)) {
            $sub_cat_request = new SubCategoryRequest();
            $sub_cat_request->category_id = $request->business_cat;
            $sub_cat_request->name = $request->new_sub_category;
            $sub_cat_request->surveyor_id = $surveyor->id;
            $sub_cat_request->status = 0;
            $sub_cat_request->save();
        }

        if(!is_null($surveyor->firm_name)) {
            $firm_name = $surveyor->firm_name;
        }else{
            $firm_name = $request->firm_name;
        }

        if(!is_null($surveyor->firm_type)) {
            $firm_type = $surveyor->firm_type;
        }else{
            $firm_type = $request->firm_type;
        }

        if(!is_null($surveyor->designation)) {
            $designation = $surveyor->designation;
        }else{
            $designation = $request->designation;
        }

        if(!is_null($surveyor->pan)) {
            $pan = $surveyor->pan;
        }else{
            $pan = $request->pan;
        }

        $in['firstname'] = !is_null($surveyor->firstname) ? $surveyor->firstname :  $request->firstname;
        $in['lastname'] = !is_null($surveyor->lastname) ? $surveyor->lastname :  $request->lastname;
        $in['business_cat'] = !is_null($surveyor->business_cat) ? $surveyor->business_cat :  $request->business_cat;
        $in['business_subcat'] = $request->business_subcat;
        $in['profiling_service'] = !is_null($surveyor->profile_service) ? $surveyor->profile_service :  $request->profiling_service;
        $in['opt_out_msg'] = !is_null($surveyor->opt_out_msg) ? $surveyor->opt_out_msg :  $request->opt_out_msg;
        $in['isfirm'] = $isfirm;
        $in['firm_name'] = $isfirm == 1 ? $firm_name : null;
        $in['firm_type'] = $isfirm == 1 ? $firm_type : null;
        $in['designation'] = $isfirm == 1 ? $designation : null;
        $in['pan'] = $isfirm != 1 ? $pan : null;


        $in['address'] = [
            'address' => !is_null($surveyor->address->address) ? $surveyor->address->address :  $request->address,
            'state' => !is_null($surveyor->address->state) ? $surveyor->address->state :  $request->state,
            'zip' => !is_null($surveyor->address->zip) ? $surveyor->address->zip :  $request->zip,
            'country' => !is_null($surveyor->address->country) ? $surveyor->address->country :  $surveyor->address->country,
            'city' => !is_null($surveyor->address->city) ? $surveyor->address->city :  $request->city,
        ];


        $surveyor_image = $surveyor->image;
        if($request->hasFile('image')) {
            try{

                $location = imagePath()['profile']['surveyor']['path'];
                $size = imagePath()['profile']['surveyor']['size'];
                $old = $surveyor->image;
                $surveyor_image = uploadImage($request->image, $location , $size, $old);

            }catch(\Exception $exp) {
                return back()->withNotify(['error', 'Could not upload the image.']);
            }
        }

        $in['image'] = $surveyor_image;
        $surveyor->fill($in)->save();

        $notify[] = ['success', 'Your profile has been updated.'];
        return redirect()->route('surveyor.profile')->withNotify($notify);
    }

    public function categoryRequest(Request $request)
    {
        $sub_cat_request = new SubCategoryRequest();
        $sub_cat_request->category_id = $request->category_id;
        $sub_cat_request->name = $request->name;
        $sub_cat_request->surveyor_id = $request->surveyor_id;
        $sub_cat_request->status = 0;
        $sub_cat_request->save();

            $notify[] = ['success', 'A subcategory request is submitted to admin. Subcategory will be active after admin approval.'];
        return redirect()->route('surveyor.profile')->withNotify($notify);
    }

    public function password()
    {
        $page_title = 'Password Setting';
        $surveyor = Auth::guard('surveyor')->user();
        return view('surveyor.password', compact('page_title', 'surveyor'));
    }

    public function passwordUpdate(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
              'password' => [
            'required',
            'min:6',             // must be at least 10 characters in length
            'regex:/[a-z]/',      // must contain at least one lowercase letter
            'regex:/[A-Z]/',      // must contain at least one uppercase letter
            'regex:/[0-9]/',      // must contain at least one digit
            'regex:/[@$!%*#?&]/', // must contain a special character
        ],
        ]);

        $surveyor = Auth::guard('surveyor')->user();
        if (!Hash::check($request->old_password, $surveyor->password)) {
            $notify[] = ['error', 'Password Do not match !!'];
            return back()->withErrors(['Invalid old password.']);
        }

        $surveyor->update([
            'password' => Hash::make($request->password),
        ]);

        $notify[] = ['success', 'Password Changed Successfully.'];
        return redirect()->route('surveyor.password')->withNotify($notify);
    }

    public function show2faForm()
    {
        $gnl = GeneralSetting::first();
        $ga = new GoogleAuthenticator();
        $surveyor = Auth::guard('surveyor')->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($surveyor->username . '@' . $gnl->sitename, $secret);
        $prevcode = $surveyor->tsc;
        $prevqr = $ga->getQRCodeGoogleUrl($surveyor->username . '@' . $gnl->sitename, $prevcode);
        $page_title = 'Two Factor';
        return view('surveyor.twofactor', compact('page_title', 'secret', 'qrCodeUrl', 'prevcode', 'prevqr'));
    }

    public function create2fa(Request $request)
    {
        $surveyor = Auth::guard('surveyor')->user();
        $this->validate($request, [
            'key' => 'required',
            'code' => 'required',
        ]);

        $ga = new GoogleAuthenticator();
        $secret = $request->key;
        $oneCode = $ga->getCode($secret);

        if ($oneCode === $request->code) {
            $surveyor->tsc = $request->key;
            $surveyor->ts = 1;
            $surveyor->tv = 1;
            $surveyor->save();


            $surveyorAgent = getIpInfo();
            $osBrowser = osBrowser();
            notify($surveyor, '2FA_ENABLE', [
                'operating_system' => @$osBrowser['os_platform'],
                'browser' => @$osBrowser['browser'],
                'ip' => @$surveyorAgent['ip'],
                'time' => @$surveyorAgent['time']
            ]);


            $notify[] = ['success', 'Google Authenticator Enabled Successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong Verification Code'];
            return back()->withNotify($notify);
        }
    }


    public function disable2fa(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);

        $surveyor = Auth::guard('surveyor')->user();
        $ga = new GoogleAuthenticator();

        $secret = $surveyor->tsc;
        $oneCode = $ga->getCode($secret);
        $surveyorCode = $request->code;

        if ($oneCode == $surveyorCode) {

            $surveyor->tsc = null;
            $surveyor->ts = 0;
            $surveyor->tv = 1;
            $surveyor->save();


            $surveyorAgent = getIpInfo();
            $osBrowser = osBrowser();
            notify($surveyor, '2FA_DISABLE', [
                'operating_system' => @$osBrowser['os_platform'],
                'browser' => @$osBrowser['browser'],
                'ip' => @$surveyorAgent['ip'],
                'time' => @$surveyorAgent['time']
            ]);


            $notify[] = ['success', 'Two Factor Authenticator Disable Successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong Verification Code'];
            return back()->withNotify($notify);
        }
    }

    public function depositHistory()
    {
        
        $page_title = 'Deposit History';
        $empty_message = 'No history found.';
        $logs = auth()->guard('surveyor')->user()->deposits()->with(['gateway'])->latest()->paginate(getPaginate());
        return view('surveyor.deposit_history', compact('page_title', 'empty_message', 'logs'));
    }

    public function transactionHistory()
    {
        $page_title = 'Successfull Transactions';
        $transactions = auth()->guard('surveyor')->user()->transactions()->with('surveyor', 'refund')->latest()->paginate(getPaginate());
        $empty_message = 'No transactions';
        return view('surveyor.transactions', compact('page_title', 'transactions', 'empty_message'));
    }

    public function transactionSearch(Request $request)
    {
        $request->validate(['search' => 'required']);
        $search = $request->search;
        $page_title = 'Transactions Search - ' . $search;
        $empty_message = 'No transactions.';

        $transactions = Transaction::where('surveyor_id',auth()->guard('surveyor')->user()->id)->where('trx', $search)->orderBy('id','desc')->paginate(getPaginate());

        return view('surveyor.transactions', compact('page_title', 'transactions', 'empty_message'));
    }


    public function surveyNew()
    {
      
        $surveyor = auth()->guard('surveyor')->user();
        $profile_complete = surveyorProfileCompletePercent($surveyor);
        if($profile_complete != 100) {
            $notify[] = ['error', "You can't add new campaign without 100% profile complete."];
            return redirect()->route('surveyor.profile')->withNotify($notify);
        }
        $page_title = 'New Campaign';
        $categories = Category::where('status','1')->latest()->get();
//        $category = Category::where('status','1')->where('id', $surveyor->business_cat)->first();
           
        if(!is_null($surveyor->business_cat)) {
            $subcategories = [];
            $category = Category::where('id', $surveyor->business_cat)->first();
            $user_subcategory = SubCategoryRequest::where('category_id', $surveyor->business_cat)->where('surveyor_id', $surveyor->id)->where('status', 1)->get();
            foreach ($user_subcategory as $us) {
                array_push($subcategories, $us->name);
            }
            if(!is_null($category) || !is_null($category->subcategories)) {
                $vs = explode(',',$category->subcategories);
                foreach ($vs as $v) {
                    array_push($subcategories, $v);
                }

            }

        }else {
            $category = null;
            $subcategories = [];
        }

        $adtype = AdType::where('status','1')->orderBy('order', 'ASC')->get();
        $sliderPrice = SliderPrice::latest()->get();
        $offerType = Offertype::get();
        $gnl = GeneralSetting::first();
        $gst = $gnl->gst_amount;
        $user_percentage = $gnl->user_amount;
         
        return view('surveyor.survey.new', compact('page_title','categories','category', 'subcategories', 'adtype','sliderPrice','gst','offerType', 'surveyor', 'user_percentage'));
    }

//    public function surveyRepublish()
//    {
//        $page_title = 'New Campaign';
//        $categories = Category::where('status','1')->latest()->get();
//        $adtype = AdType::where('status','1')->latest()->get();
//        $sliderPrice = SliderPrice::latest()->get();
//        $offerType = Offertype::get();
//        $gnl = GeneralSetting::first();
//        $gst = $gnl->gst_amount;
//        return view('surveyor.survey.new', compact('page_title','categories','adtype','sliderPrice','gst','offerType'));
//    }
    public function surveyAll()
    {
         
        if(isset($_GET['rejected'])){
            $surveys = Survey::where('surveyor_id',auth()->guard('surveyor')->user()->id)->where('status',$_GET['rejected'])->latest()->paginate(getPaginate());
        }else{
          $surveys = Survey::where('surveyor_id',auth()->guard('surveyor')->user()->id)->latest()->paginate(getPaginate());
        }
        $page_title = 'New Campaign';
        //$surveys = Survey::where('surveyor_id',auth()->guard('surveyor')->user()->id)->latest()->paginate(getPaginate());
        $empty_message = 'No data found';
        return view('surveyor.survey.index', compact('page_title','surveys','empty_message'));
    }
     public function surveydump()
    {
         $surveyor = auth()->guard('surveyor')->user();
        $profile_complete = surveyorProfileCompletePercent($surveyor);
        if($profile_complete != 100) {
            $notify[] = ['error', "You can't add new campaign without 100% profile complete."];
            return redirect()->route('surveyor.profile')->withNotify($notify);
        }
        $page_title = 'New Campaign';
        $categories = Category::where('status','1')->latest()->get();
//        $category = Category::where('status','1')->where('id', $surveyor->business_cat)->first();

        if(!is_null($surveyor->business_cat)) {
            $subcategories = [];
            $category = Category::where('id', $surveyor->business_cat)->first();
            $user_subcategory = SubCategoryRequest::where('category_id', $surveyor->business_cat)->where('surveyor_id', $surveyor->id)->where('status', 1)->get();
            foreach ($user_subcategory as $us) {
                array_push($subcategories, $us->name);
            }
            if(!is_null($category) || !is_null($category->subcategories)) {
                $vs = explode(',',$category->subcategories);
                foreach ($vs as $v) {
                    array_push($subcategories, $v);
                }

            }

        }else {
            $category = null;
            $subcategories = [];
        }

        $adtype = AdType::where('status','1')->orderBy('order', 'ASC')->get();
        $sliderPrice = SliderPrice::latest()->get();
        $offerType = Offertype::get();
        $gnl = GeneralSetting::first();
        $gst = $gnl->gst_amount;
        $user_percentage = $gnl->user_amount;
        $surveys = SurveysTemp::where('surveyor_id',auth()->guard('surveyor')->user()->id)->where('status',1)->latest()->paginate(getPaginate());
     
        $page_title = 'Draft Campaign';
        //$surveys = Survey::where('surveyor_id',auth()->guard('surveyor')->user()->id)->latest()->paginate(getPaginate());
        $empty_message = 'No data found';
        return view('surveyor.survey.ind', compact('page_title','surveys','empty_message','categories','category', 'subcategories', 'adtype','sliderPrice','gst','offerType', 'surveyor', 'user_percentage'));
    }
   
    public function downloadSurveyInvoice($id)
    {
        $data['survey'] = Survey::where('id', $id)->with('surveyor', 'adtype', 'category')->first();
        $gnl = GeneralSetting::first();
        $data['gst'] = $gnl->gst_amount;
        $data['currency'] = $gnl->cur_sym;
//        $data['answers'] = Answer::where('survey_id', $id)->groupBy('user_id')->with('user')->get();

        // make a file download_pdf.blade.php in views folder.
//        return view('surveyor.survey.invoice', $data);
        $customPaper = array(0,0,1240,874);
        $pdf = PDF::loadView('surveyor.survey.invoice', $data)->setPaper('A4', 'portrait');
        return $pdf->download(time().'.pdf');
    }

    public function surveyStore(Request $request)
    {
    
        session()->forget('survey');
        $surveyor = auth()->guard('surveyor')->user();
        $surveyor->business_subcat = $request->subcat;
        $surveyor->save();
        $totalSurvey = auth()->guard('surveyor')->user()->balance;
        $surveyoy_total_views = $surveyor->total_views + $request->n_views;
        
        if($request->totalprice  <=  $totalSurvey){

          
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
           // dd($request->audience_cat);
            $survey = new Survey();
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
             


            $transaction = new Transaction();
            $transaction->surveyor_id = auth()->guard('surveyor')->user()->id;
            $transaction->amount = getAmount($request->totalprice);
            $transaction->post_balance = getAmount($totalSurvey-$request->totalprice);
            $transaction->trx_type = '-';
            $transaction->details = 'For Add New Campaign ';
            $transaction->trx =  getTrx();
            $transaction->save();

            $survey->txn = $transaction->trx;
            $survey->save();

          if(!empty($request->audience_cat)){
                if($request->audience_cat == 'General'){
                     $market =TargetMarket::create([
                      
                      'target_market_value'=>$request->target_market_value,
                      'servey_id' => $survey->id,
                      'ref_table'=>'servey'
                    ]);
                }else{
                     $market =TargetMarket::create([
                      'target_market_name'=> $request->audience_cat,
                      'target_market_value'=>$request->target_market_value,
                      'servey_id' => $survey->id,
                      'ref_table'=>'servey'
                    ]);
                }
               
              if($market){
                  $update = SurveysTemp::where('id',$survey->id)->update([
                            'target_market_id'=>$market->id,
                            
                      ]);
              }
            }

            Surveyor::where('id', auth()->guard('surveyor')->user()->id)
                ->update([
                    'total_views' => $surveyoy_total_views,
                    'balance' => $totalSurvey-$request->totalprice
                ]);
            $data = [
                ['survey_id'=>$survey->id, 'question'=> '','type'=>1,'custom_input'=>1,'custom_input_type'=>1,'custom_question'=>'What is Product/Service Name?','custombox'=>1,'answers'=>$request->p_name],
                ['survey_id'=>$survey->id, 'question'=> '','type'=>1,'custom_input'=>1,'custom_input_type'=>1,'custom_question'=>'What is Radigone Offer Price?','custombox'=>1,'answers'=>$request->d_offer],
            ];
            Question::insert($data);


            $notify[] = ['success', 'Campaign has been added'];
            return redirect()->route('surveyor.survey.question.new',$survey->id)->withNotify($notify);

        }else{
            $notify[] = ['error', "You can't add due to low amount"];
            return back()->withNotify($notify);

        }
        //return redirect()->route('surveyor.survey.all');
    }

    public function surveyEditBeforeSubmit($id)
    {
        $surveyor = auth()->guard('surveyor')->user();
        $profile_complete = surveyorProfileCompletePercent($surveyor);
        if($profile_complete != 100) {
            $notify[] = ['error', "You can't add new campaign without 100% profile complete."];
            return redirect()->route('surveyor.profile')->withNotify($notify);
        }
        $page_title = 'Edit Campaign';
        $survey = Survey::whereId($id)->first();
        $categories = Category::where('status','1')->latest()->get();
        $category = Category::where('status','1')->where('id', $surveyor->business_cat)->first();
        $adtype = AdType::where('status','1')->orderBy('order', 'ASC')->get();
        $sliderPrice = SliderPrice::latest()->get();
        $offerType = Offertype::get();
        $gnl = GeneralSetting::first();
        $gst = $gnl->gst_amount;
        return view('surveyor.survey.edit-survey', compact('survey','surveyor', 'page_title','categories','category','adtype','sliderPrice','gst','offerType'));
    }

    public function surveyUpdateBeforeSubmit($id, Request $request)
    {
        $survey = Survey::findOrFail($id);

        if ($survey->surveyor_id != auth()->guard('surveyor')->user()->id) {
            $notify[] = ['success', 'You are not authorized to edit this survey'];
            return back()->withNotify($notify);
        }

        $general = GeneralSetting::first();

        $survey_image = $survey->image;
        if($request->hasFile('image')) {
            try{
                $location = imagePath()['survey']['path'];
                $size = imagePath()['survey']['size'];
                $old = $survey_image;
                $survey_image = uploadImage($request->image, $location , $size, $old);

            }catch(\Exception $exp) {
                return back()->withNotify(['error', 'Could not upload the image.']);
            }
        }

        $user_percentage = $general->user_amount;
//        $price_per_user = $request->total/$request->n_views;
//        $per_user = ($price_per_user * $user_percentage) / 100;

        $survey->image = $survey_image;
        // $survey->store_file=json_encode($store_file);
        $survey->category_id = $request->category_id;
        $survey->p_name = $request->p_name;
        $survey->p_specification=$request->p_specifications;
        $survey->p_mrp = (int)$request->p_mrp;
        $survey->discount = (int)$request->d_offer;
        $survey->offer_type = $request->t_offer;
        $survey->repeated_viewers = (int)$request->repeated;
        $survey->save();

        $notify[] = ['success', 'Campaign has been added'];
        return redirect()->route('surveyor.dashboard',$survey->id)->withNotify($notify);
    }

    public function surveyEditRejectAd($id)
    {
        $surveyor = auth()->guard('surveyor')->user();
        $profile_complete = surveyorProfileCompletePercent($surveyor);
        if($profile_complete != 100) {
            $notify[] = ['error', "You can't add new campaign without 100% profile complete."];
            return redirect()->route('surveyor.profile')->withNotify($notify);
        }
        $page_title = 'Edit Campaign';
        $survey = Survey::whereId($id)->first();
        if($survey->status != 3) {
            $notify[] = ['error', "Survey is not rejected!"];
            return redirect()->route('surveyor.survey.all')->withNotify($notify);
        }
        $categories = Category::where('status','1')->latest()->get();
        $category = Category::where('status','1')->where('id', $surveyor->business_cat)->first();
        $adtype = AdType::where('status','1')->orderBy('order', 'ASC')->get();
        $sliderPrice = SliderPrice::latest()->get();
        $offerType = Offertype::get();
        $gnl = GeneralSetting::first();
        $gst = $gnl->gst_amount;
        $rejected = 1;
        return view('surveyor.survey.edit-survey', compact('survey','surveyor', 'page_title','categories','category','adtype','sliderPrice','gst','offerType', 'rejected'));
    }

    public function surveyUpdateRejectAd($id, Request $request)
    {
        $survey = Survey::findOrFail($id);

        $surveyor = auth()->guard('surveyor')->user();
        $surveyoy_total_views = $surveyor->total_views + $survey->total_views;

        if ($survey->surveyor_id != auth()->guard('surveyor')->user()->id) {
            $notify[] = ['success', 'You are not authorized to edit this survey'];
            return back()->withNotify($notify);
        }

        $general = GeneralSetting::first();

        $survey_image = $survey->image;
        if($request->hasFile('image')) {
            try{
                $location = imagePath()['survey']['path'];
                $size = imagePath()['survey']['size'];
                $old = $survey_image;
                $survey_image = uploadImage($request->image, $location , $size, $old);

            }catch(\Exception $exp) {
                return back()->withNotify(['error', 'Could not upload the image.']);
            }
        }

        $user_percentage = $general->user_amount;
//        $price_per_user = $request->total/$request->n_views;
//        $per_user = ($price_per_user * $user_percentage) / 100;

        $survey->image = $survey_image;
        // $survey->store_file=json_encode($store_file);
        $survey->category_id = $request->category_id;
        $survey->p_name = $request->p_name;
        $survey->p_specification=$request->p_specifications;
        $survey->p_mrp = (int)$request->p_mrp;
        $survey->discount = (int)$request->d_offer;
        $survey->offer_type = $request->t_offer;
        $survey->repeated_viewers = (int)$request->repeated;
        $survey->status = 1;
        $survey->online_purchase = $request->o_purchase;
        $survey->purchas_url = $request->opurl;
        $survey->reject_Answer = null;
        $survey->users = null;

        $transaction = new Transaction();
        $transaction->surveyor_id = auth()->guard('surveyor')->user()->id;
        $transaction->amount = getAmount($request->totalprice);
        $transaction->post_balance = getAmount($surveyor->balance-$request->totalprice);
        $transaction->trx_type = '-';
        $transaction->details = 'For Add New Campaign (Rejected) ';
        $transaction->trx =  getTrx();
        $transaction->save();

        $survey->txn = $transaction->trx;
        $survey->save();

        $surveyor->balance = $surveyor->balance-$request->totalprice;
        $surveyor->total_views = $surveyoy_total_views;
        $surveyor->save();

        $notify[] = ['success', 'Campaign has been added'];
        return redirect()->route('surveyor.dashboard',$survey->id)->withNotify($notify);
    }

    public function surveyRepublishGet($id)
    {
        $id = decrypt($id);
        $surveyor = auth()->guard('surveyor')->user();
        $profile_complete = surveyorProfileCompletePercent($surveyor);
        if($profile_complete != 100) {
            $notify[] = ['error', "You can't add new campaign without 100% profile complete."];
            return redirect()->route('surveyor.profile')->withNotify($notify);
        }
        $survey = Survey::whereId($id)->first();
        if(is_null($survey)) {
            $notify[] = ['error', "No survey found!"];
            return redirect()->back()->withNotify($notify);
        }
        if($survey->surveyor_id != $surveyor->id) {
            $notify[] = ['error', "You are not authorized for this survey!"];
            return redirect()->back()->withNotify($notify);
        }
        $page_title = 'Republish Campaign';
        $categories = Category::where('status','1')->latest()->get();
        $category = Category::where('status','1')->where('id', $surveyor->business_cat)->first();
        $adtype = AdType::where('status','1')->orderBy('order', 'ASC')->get();
        $sliderPrice = SliderPrice::latest()->get();
        $offerType = Offertype::get();
        $gnl = GeneralSetting::first();
        $gst = $gnl->gst_amount;
        $user_percentage = $gnl->user_amount;
        return view('surveyor.survey.republish', compact('page_title','survey', 'categories','category','adtype','sliderPrice','gst','offerType', 'surveyor', 'user_percentage'));
    }

    public function surveyRepublish($id, Request $request)
    {
        //return $request;
        session()->forget('survey');
        $totalSurvey = auth()->guard('surveyor')->user()->balance;
        $srv = Survey::whereId($id)->first();
        $surveyor = auth()->guard('surveyor')->user();
        $surveyoy_total_views = $surveyor->total_views + $request->n_views;;
        if(is_null($srv)) {
            $notify[] = ['error', "No survey found!"];
            return redirect()->back()->withNotify($notify);
        }

        if($request->totalprice  <=  $totalSurvey){


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
                       $filepath = '/uploads/campaigns/'.$survey_image;
                       $request->image->move(public_path('/uploads/campaigns/'), $survey_image);
                       $srvurl = '/uploads/campaigns/'.$survey_image;

                }catch(\Exception $exp) {
                    return back()->withNotify(['error', 'Could not upload the image.']);
                }
            }

            $user_percentage = $general->user_amount;
            $price_per_user = $request->total/$request->n_views;
            $per_user = ($price_per_user * $user_percentage) / 100;

            $survey = new Survey();
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
                      'ref_table'=>'servey'
                    ]);
                }else{
                     $market =TargetMarket::create([
                      'target_market_name'=> $request->audience_cat,
                      'target_market_value'=>$request->target_market_value,
                      'servey_id' => $survey->id,
                      'ref_table'=>'servey'
                    ]);
                }
              if($market){
                  $update = Survey::where('id',$survey->id)->update([
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



            $transaction = new Transaction();
            $transaction->surveyor_id = auth()->guard('surveyor')->user()->id;
            $transaction->amount = getAmount($request->totalprice);
            $transaction->post_balance = getAmount($totalSurvey-$request->totalprice);
            $transaction->trx_type = '-';
            $transaction->details = 'For Add New Campaign ';
            $transaction->trx =  getTrx();
            $transaction->save();



            Surveyor::where('id', auth()->guard('surveyor')->user()->id)
                ->update([
                    'total_views' => $surveyoy_total_views,
                    'balance' => $totalSurvey-$request->totalprice
                ]);
            $data = [
                ['survey_id'=>$survey->id, 'question'=> '','type'=>1,'custom_input'=>1,'custom_input_type'=>1,'custom_question'=>'What is Product/Service Name?','custombox'=>1,'answers'=>$request->p_name],
                ['survey_id'=>$survey->id, 'question'=> '','type'=>1,'custom_input'=>1,'custom_input_type'=>1,'custom_question'=>'What is Radigone Offer Price?','custombox'=>1,'answers'=>$request->d_offer],
            ];
            Question::insert($data);


            $notify[] = ['success', 'Campaign has been added'];
            return redirect()->route('surveyor.survey.question.new',$survey->id)->withNotify($notify);

        }else{
            $notify[] = ['error', "You can't add due to low amount"];
            return back()->withNotify($notify);

        }


        //return redirect()->route('surveyor.survey.all');
    }

    public function surveyEdit($id)
    {
        $survey = Survey::findOrFail($id);


        if ($survey->surveyor_id != auth()->guard('surveyor')->user()->id) {
            $notify[] = ['success', 'You are not authorized to edit this survey'];
            return back()->withNotify($notify);
        }

        if ($survey->status == 3) {
            $notify[] = ['error', 'Do no try to cheat us'];
            return back()->withNotify($notify);
        }

        $page_title = 'Edit Survey';
        $categories = Category::where('status','1')->latest()->get();
        return view('surveyor.survey.edit',compact('page_title','survey','categories'));
    }

    public function surveyUpdate(Request $request, $id)
    {
        $this->validate($request, [
            'image' => [new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'category_id' => 'required|numeric|gt:0',
            'name' => 'required|string|max:191',
            'age_limit' => 'required|min:0|max:1',
            'country_limit' => 'required|min:0|max:1',
            'start_age' => 'sometimes|required|numeric|min:1|max:200',
            'end_age' => "sometimes|required|numeric|min:1|max:200|gt:$request->start_age",
            'country' => 'required_if:country_limit,1|array|min:1',
            'country.*' => 'required_with:country|string',
        ],[
            'country.required_if'=>'Country field is required when country limit is yes'
        ]);

        $survey = Survey::findOrFail($id);

        if ($survey->surveyor_id != auth()->guard('surveyor')->user()->id) {
            $notify[] = ['success', 'You are not authorized to edit this survey'];
            return back()->withNotify($notify);
        }

        $general = GeneralSetting::first();

        $survey_image = $survey->image;
        if($request->hasFile('image')) {
            try{
                $location = imagePath()['survey']['path'];
                $size = imagePath()['survey']['size'];
                $old = $survey_image;
                $survey_image = uploadImage($request->image, $location , $size, $old);

            }catch(\Exception $exp) {
                return back()->withNotify(['error', 'Could not upload the image.']);
            }
        }

        $survey->image = $survey_image;
        $survey->name = $request->name;
        $survey->age_limit = $request->age_limit;
        $survey->country_limit = $request->country_limit;
        $survey->start_age = $request->start_age;
        $survey->end_age = $request->end_age;
        $survey->country = $request->country;
        $survey->category_id = $request->category_id;
        $survey->status = $general->survey_approval;
        $survey->save();

        $notify[] = ['success', 'Survey has been updated'];
        return back()->withNotify($notify);
    }

    // =============================================================================

    public function business_cardAll()
    {
        $page_title = 'New Business card';
        $surveys = campaign::where('surveyor_id',auth()->guard('surveyor')->user()->id)->latest()->paginate(getPaginate());
        $empty_message = 'No data found';
        return view('surveyor.survey.index2', compact('page_title','surveys','empty_message'));
    }

    public function business_cardNew()
    {
        $page_title = 'New Business card';
        $categories = Category::where('status','1')->latest()->get();
        return view('surveyor.survey.new2', compact('page_title','categories'));
    }

    public function business_cardStore(Request $request)
    {

        $general = GeneralSetting::first();
        $rdata = "";

        if(isset($request->r_data)){
            foreach($request->r_data as $data){
                $rdata .= $data;
            }
        }

        DB::table('campaigns')->insert([
            'p_name' => $request->p_name,
            'surveyor_id' => auth()->guard('surveyor')->user()->id,
            'p_specification' => $request->p_specifications,
            'p_mrp' => (int)$request->p_mrp,
            'discount' => (int)$request->d_offer,
            'required_data' => $rdata,
            'offer_type' => $request->t_offer,
            'total_views' => (int)$request->n_views,
            'publish' => $request->audience,
            'target_market_category' => $request->audience_cat,
            'total_slides' => (int)$request->slides,
            'slides_time' => (int)$request->slides_time,
            'repeated_viewers' => (int)$request->repeated,
            'ad_duration' => (int)$request->ad_duration,
            'online_purchase' => (int)$request->o_purchase,
            'template' => $request->template,
        ]);



        $notify[] = ['success', 'Business card has been added'];
        return redirect()->route('surveyor.survey.all2');
    }

    public function business_cardEdit($id)
    {
        $survey = Survey::findOrFail($id);


        if ($survey->surveyor_id != auth()->guard('surveyor')->user()->id) {
            $notify[] = ['success', 'You are not authorized to edit this survey'];
            return back()->withNotify($notify);
        }

        if ($survey->status == 3) {
            $notify[] = ['error', 'Do no try to cheat us'];
            return back()->withNotify($notify);
        }

        $page_title = 'Edit Survey';
        $categories = Category::where('status','1')->latest()->get();
        return view('surveyor.survey.edit2',compact('page_title','survey','categories'));
    }

    public function business_cardUpdate(Request $request, $id)
    {
        $this->validate($request, [
            'image' => [new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'category_id' => 'required|numeric|gt:0',
            'name' => 'required|string|max:191',
            'age_limit' => 'required|min:0|max:1',
            'country_limit' => 'required|min:0|max:1',
            'start_age' => 'sometimes|required|numeric|min:1|max:200',
            'end_age' => "sometimes|required|numeric|min:1|max:200|gt:$request->start_age",
            'country' => 'required_if:country_limit,1|array|min:1',
            'country.*' => 'required_with:country|string',
        ],[
            'country.required_if'=>'Country field is required when country limit is yes'
        ]);

        $survey = Survey::findOrFail($id);

        if ($survey->surveyor_id != auth()->guard('surveyor')->user()->id) {
            $notify[] = ['success', 'You are not authorized to edit this survey'];
            return back()->withNotify($notify);
        }

        $general = GeneralSetting::first();

        $survey_image = $survey->image;
        if($request->hasFile('image')) {
            try{
                $location = imagePath()['survey']['path'];
                $size = imagePath()['survey']['size'];
                $old = $survey_image;
                $survey_image = uploadImage($request->image, $location , $size, $old);

            }catch(\Exception $exp) {
                return back()->withNotify(['error', 'Could not upload the image.']);
            }
        }

        $survey->image = $survey_image;
        $survey->name = $request->name;
        $survey->age_limit = $request->age_limit;
        $survey->country_limit = $request->country_limit;
        $survey->start_age = $request->start_age;
        $survey->end_age = $request->end_age;
        $survey->country = $request->country;
        $survey->category_id = $request->category_id;
        $survey->status = $general->survey_approval;
        $survey->save();

        $notify[] = ['success', 'Survey has been updated'];
        return back()->withNotify($notify);
    }

    // =======================================================

    public function questionAll($id)
    {

        $page_title = 'All Campaigns';
        $survey = Survey::findOrFail($id);

        if ($survey->surveyor_id != auth()->guard('surveyor')->user()->id) {
            $notify[] = ['success', 'You are not authorized to see this survey'];
            return back()->withNotify($notify);
        }
        $countq = $survey->questions()->count();
        $questions = $survey->questions()->paginate(getPaginate());
        $empty_message = 'No campaigns found';
        return view('surveyor.question.index', compact('page_title','survey','empty_message','questions','countq'));
    }


    public function questionNew($id)
    {
        $survey = Survey::findOrFail($id);
        $page_title = 'New Question';
        return view('surveyor.question.new', compact('page_title','survey'));

    }

    public function questionStore(Request $request)
    {
        $this->validate($request, [
            // 'survey_id' => 'required|gt:0',
            // 'type' => 'required|min:1|max:2',
            // 'custom_input' => 'required|min:0|max:1',
            // 'custom_input_type' => 'sometimes|required|min:0|max:1',
            // 'custom_question' => 'sometimes|required|max:255',
            // 'question' => 'required|max:255',
            // 'options.*' => 'required|max:191',
        ],[
            'options.*.required' => 'Please add all options',
            'options.*.max' => 'Total options should not be more than 191 charecters'
        ]);

        $count = Question::where('survey_id',$request->survey_id)->count();
        if($count == '3'){
        $notify[] = ['error', 'Question limte end'];
        return back()->withNotify($notify);
        }else{
        $survey = Survey::findOrFail($request->survey_id);

        $question = new Question();
        $question->survey_id = $survey->id;
        $question->question = $request->question;
        $question->type = $request->type;
        $question->custom_input = $request->custom_input;
        $question->custom_input_type = $request->custom_input_type??0;
        $question->custom_question = $request->custom_question;
        $question->options = array_values($request->options);
        $question->save();
//        $notify[] = ['success', 'Question has been added'];
//        return back()->withNotify($notify);
        $notify[] = ['success', 'Question has been added. You can edit campaign it before submitting.'];
        return redirect()->route('surveyor.survey.edit_before_submit',$survey->id)->withNotify($notify);
       }

    }

    public function questionEdit($q_id,$s_id)
    {
        $question = Question::findOrFail($q_id);

        if ($question->survey_id != $s_id) {
            $notify[] = ['error', 'You are not authorized to edit this question'];
            return back()->withNotify($notify);
        }

        $page_title = 'Edit Question';
        return view('surveyor.question.edit',compact('page_title','question','s_id'));
    }

    public function questionUpdate(Request $request,$id)
    {
        $this->validate($request, [
            'survey_id' => 'required|gt:0',
            'type' => 'required|min:1|max:2',
            'custom_input' => 'required|min:0|max:1',
            'custom_input_type' => 'sometimes|required|min:0|max:1',
            'custom_question' => 'sometimes|required|max:255',
            'question' => 'required|max:255',
            'options.*' => 'required|max:191',
        ],[
            'options.*.required' => 'Please add all options',
            'options.*.max' => 'Option should not be more than 191 charecters'
        ]);


        $survey = Survey::findOrFail($request->survey_id);
        $general = GeneralSetting::first();
        $survey->status = $general->survey_approval;
        $survey->save();

        $question = Question::findOrFail($id);
        if ($question->survey_id != $survey->id) {
            $notify[] = ['error', 'You are not authorized to update this question'];
            return back()->withNotify($notify);
        }


        if(!$request->options){

            $options = $question->options;
        }
        if($request->options){

            $options = array_merge( $question->options,$request->options);
        }

        $question->question = $request->question;
        $question->type = $request->type;
        $question->custom_input = $request->custom_input;
        $question->custom_input_type = $request->custom_input_type??0;
        $question->custom_question = $request->custom_question;
        $question->options = $options;
        $question->save();
        $notify[] = ['success', 'Question has been updated'];
        return back()->withNotify($notify);
    }

    public function questionView($q_id,$s_id)
    {
        $question = Question::findOrFail($q_id);

        if ($question->survey_id != $s_id) {
            $notify[] = ['error', 'You are not authorized to view this question'];
            return back()->withNotify($notify);
        }

        $page_title = 'View Question';
        return view('surveyor.question.view',compact('page_title','question','s_id'));
    }

    public function report()
    {
        $page_title = 'Campaign Report';
        $surveys = Survey::where('surveyor_id',auth()->guard('surveyor')->user()->id)->where('status',0)->latest()->paginate(getPaginate());
        $empty_message = 'No survey found';
        return view('surveyor.report.index', compact('page_title','surveys','empty_message'));
    }

    public function reportQuestion($id)
    {
        $page_title = 'Campaign Report';
        $survey = Survey::where('surveyor_id',auth()->guard('surveyor')->user()->id)->findOrFail($id);

        if (count($survey->answers) <= 0) {
            $notify[] = ['error', 'Not report ready yet'];
            return back()->withNotify($notify);
        }
        return view('surveyor.report.question', compact('page_title','survey'));
    }

    public function reportQuestionProfiles($question_id)
    {
        $page_title = 'Profiles';
        $answers = Answer::where('question_id', $question_id)->where('surveyor_id',auth()->guard('surveyor')->user()->id)->with('survey', 'user', 'question')->paginate(10);

        if (count($answers) <= 0) {
            $notify[] = ['error', 'Not user reply to this question yet'];
            return back()->withNotify($notify);
        }
        return view('surveyor.report.profiles', compact('page_title','answers'));
    }

    public function visitorPDFReport($id)
    {
        $data['survey'] = Survey::where('id', $id)->first();
        $data['answers'] = Answer::where('survey_id', $id)->groupBy('user_id')->with('user')->get();

        // make a file download_pdf.blade.php in views folder.
        $customPaper = array(0,0,1240,874);
        $pdf = PDF::loadView('surveyor.report.visitor-pdf', $data)->setPaper($customPaper, 'portrait');
        return $pdf->download(time().'.pdf');
    }

    public function visitorReportDownload($id)
    {
        $survey = Survey::where('id', $id)->where('surveyor_id',auth()->guard('surveyor')->user()->id)->first();
        if(!is_null($survey)) {
            return Excel::download(new AdVisitorReport($survey->ad_type, $survey->id), time().'.xlsx');
        }
        $notify[] = ['error', 'No survey exist!'];
        return back()->withNotify($notify);
    }

    public function reportDownload($id)
    {
        $survey = Survey::where('surveyor_id',auth()->guard('surveyor')->user()->id)->findOrFail($id);

        if(count($survey->questions) <= 0) {
            $notify[] = ['error', 'No report available'];
            return back()->withNotify($notify);
        }

        $page_title = 'Report Download';
        $filename = strtolower(str_replace(' ','_',$survey->name));
//        return $survey->questions;
        return view('surveyor.report.report',compact('survey','page_title','filename'));
    }

    public function refundRequest(Request $request)
    {
        $surveyor = auth()->guard('surveyor')->user();
        if($surveyor->id != $request->surveyor_id) {
            $notify[] = ['error', 'Do not cheat with us'];
            return back()->withNotify($notify);
        }
        $refund = new Refund();
        $refund->transaction_id = $request->transaction_id;
        $refund->amount = $request->amount;
        $refund->surveyor_id = $request->surveyor_id;
        $refund->save();
        $notify[] = ['success', 'A request has submitted for admin approval'];
        return back()->withNotify($notify);
    }
      public function surveynewsave(Request $request){
         try{
             $dump_id = $request->dump_id;
            
             $data_transac= SurveysTemp::where('id',$dump_id)->get();
              $srv_data = SurveysTemp::select("*")
            ->where('id', $dump_id)
            ->each(function ($article) use($dump_id) {

                //getting the record one by one that want to be copied
                $newUser = $article->replicate();

                //copy them using replicate and setting destination table by setTable()
                $newUser->setTable('surveys');
                $newUser->save();
                 if($newUser->id > 0){
                       $update = TargetMarket::where('servey_id',$dump_id)->update([
                            'servey_id'=>$newUser->id,
                            'ref_table'=>'servey'
                     ]);
                 }
                //add following command if you need to remove records from first table
                $article->delete();
            });
            $total_balance = $request->totalSurvey;
            $total_diduction = $request->totalprice;
            $new_balance = $total_balance - $total_diduction;
            $transaction = new Transaction();
            $transaction->surveyor_id =auth()->guard('surveyor')->user()->id;
            $transaction->amount = $total_diduction;
            $transaction->post_balance =  $new_balance;
            $transaction->trx_type = '-';
            $transaction->details = 'For Add New Campaign ';
            $transaction->trx = getTrx();
            $trnsid =  $transaction->save();
            $serveynew_id = Survey::latest('created_at')->first();
            $trans_id = Transaction::latest('created_at')->first();
              Surveyor::where('id', auth()->guard('surveyor')->user()->id)
                ->update([
                    // 'total_views' => Session::get('surveyoy_total_views'),
                    'balance' => $new_balance
                    
                ]);
                  $data = [
                ['survey_id'=>$serveynew_id->id, 'question'=> '','type'=>1,'custom_input'=>1,'custom_input_type'=>1,'custom_question'=>'What is Product/Service Name?','custombox'=>1,'answers'=>$request->p_name],
                ['survey_id'=>$serveynew_id->id, 'question'=> '','type'=>1,'custom_input'=>1,'custom_input_type'=>1,'custom_question'=>'What is Radigone Offer Price?','custombox'=>1,'answers'=>$request->d_offer],
            ];
            Question::insert($data);
            $notify[] = ['success', 'Campaign has been added'];
            return redirect()->route('surveyor.survey.question.new',$serveynew_id->id)->withNotify($notify);
         }catch(Exception $e){
             echo $e->getException();
         }
     }
}

