<?php

namespace App\Http\Controllers\Agent
;

use App\Answer;
use App\Category;
use App\Deposit;
use App\GeneralSetting;
use App\Lib\GoogleAuthenticator;
use App\Http\Controllers\Controller;
use App\Option;
use App\Question;
use App\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rules\FileTypeValidate;
use App\Agent;
use App\User;
use App\Surveyor;
use App\Transaction;
use Illuminate\Support\Facades\Hash;
use PDF;
use Illuminate\Support\Facades\DB;
use App\campaign;
use Twilio\Rest\Client;

class AgentController extends Controller
{
    // public function viewcalendar(){
    //     $page_title = 'Calendar';
    //     $agent = Auth::guard('agent')->user();
    //     return view('agent/calendar', compact('page_title', 'agent'));
    // }
    public function dashboard()
    {


        $page_title = 'Dashboard';
        $agent= auth()->guard('agent')->user();
        //$totalDeposit = Deposit::where('surveyor_id',$surveyor->id)->where('status',1)->sum('amount');
        $totalDeposit ='100';
        //$totalTransaction = Transaction::where('surveyor_id',$surveyor->id)->count();
        $totalTransaction= Transaction::where('agent_id',auth()->guard('agent')->user()->id)->count();
       // $approvedSurvey = $surveyor->surveys->where('status',0)->count();
       $approvedSurvey ='';
        //$pendingSurvey = $surveyor->surveys->where('status',1)->count();
        $pendingSurvey='';
        //$rejectedSurvey = $surveyor->surveys->where('status',3)->count();
       $rejectedSurvey='';
        // $survey_chart = Answer::where('surveyor_id',$surveyor->id)->groupBy('survey_id')->orderBy('created_at')->get()->groupBy(function ($d) {
        //     return $d->created_at->format('F');
        // });
        $total_referrals = Surveyor::where('agent_id',$agent->id)->count();
        $count_approved_survey = Survey::with('surveyor')->where('status', 0)->whereHas('surveyor', function($q) use($agent) {
            return $q->where('agent_id', $agent->id);
        })->count();
        $count_pending_survey = Survey::with('surveyor')->where('status', 1)->whereHas('surveyor', function($q) use($agent) {
            return $q->where('agent_id', $agent->id);
        })->count();
        $total_earning = Transaction::where('agent_id',auth()->guard('agent')->user()->id)->where('trx_type', '+')->sum('amount');
        $survey_all = [];
        $month_survey = [];
        // foreach ($survey_chart as $key => $value) {
        //     $survey_all[] = count($value);
        //     $month_survey[] = $key;
        // }
        return   view('agent.dashboard',compact('page_title','totalDeposit','agent','totalTransaction','approvedSurvey','pendingSurvey','rejectedSurvey','survey_all','month_survey', 'total_referrals', 'count_approved_survey', 'count_pending_survey', 'total_earning'));
    }

    public function sendMsgShare(Request $request)
    {
        try {
            $account_sid = config('services.twilio.sid');
            $auth_token = config('services.twilio.token');
            $twilio_number = config('services.twilio.from');
            $client = new Client($account_sid, $auth_token);
            $client->messages->create($request->mobile,
                ['from' => $twilio_number, 'body' => $request->message] );
            return back()->withNotify(['success', 'SMS send successfully!']);
        } catch (\Exception $e) {
            return back()->withNotify(['error', $e->getMessage()]);
        }

    }

    public function profile()
    {
        $page_title = 'Profile';
        $agent = Auth::guard('agent')->user();
        return view('agent.profile', compact('page_title', 'agent'));
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

        $agent = Auth::guard('agent')->user();


        $in['firstname'] = !is_null($agent->firstname) ? $agent->firstname : $request->firstname;
        $in['lastname'] =  !is_null($agent->lastname) ? $agent->lastname : $request->lastname;

        $in['address'] = [
            'address' => !is_null($agent->address->address) ? $agent->address->address :  $request->address,
            'state' => !is_null($agent->address->state) ? $agent->address->state :  $request->state,
            'zip' => !is_null($agent->address->zip) ? $agent->address->zip :  $request->zip,
            'country' => !is_null($agent->address->country) ? $agent->address->country :  $agent->address->country,
            'city' => !is_null($agent->address->city) ? $agent->address->city :  $request->city,
        ];


        $agent_image = $agent->image;
        if($request->hasFile('image')) {
            try{

                $location = imagePath()['profile']['agent']['path'];
                $size = imagePath()['profile']['agent']['size'];
                $old = $agent_image->image;
                $agent_image = uploadImage($request->image, $location , $size, $old);

            }catch(\Exception $exp) {
                return back()->withNotify(['error', 'Could not upload the image.']);
            }
        }

        $in['image'] = $agent_image;
        $agent->fill($in)->save();

        $notify[] = ['success', 'Your profile has been updated.'];
        return redirect()->route('agent.profile')->withNotify($notify);
    }

    public function password()
    {
        $page_title = 'Password Setting';
        $agent = Auth::guard('agent')->user();
        return view('agent.password', compact('page_title', 'agent'));
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

        $agent = Auth::guard('agent')->user();
        if (!Hash::check($request->old_password, $agent->password)) {
            $notify[] = ['error', 'Password Do not match !!'];
            return back()->withErrors(['Invalid old password.']);
        }

        $agent->update([
            'password' => Hash::make($request->password),
        ]);

        $notify[] = ['success', 'Password Changed Successfully.'];
        return redirect()->route('agent.password')->withNotify($notify);
    }

    public function show2faForm()
    {
        $gnl = GeneralSetting::first();
        $ga = new GoogleAuthenticator();
        $agent = Auth::guard('agent')->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($agent->username . '@' . $gnl->sitename, $secret);
        $prevcode = $agent->tsc;
        $prevqr = $ga->getQRCodeGoogleUrl($agent->username . '@' . $gnl->sitename, $prevcode);
        $page_title = 'Two Factor';
        return view('agent.twofactor', compact('page_title', 'secret', 'qrCodeUrl', 'prevcode', 'prevqr'));
    }

    public function create2fa(Request $request)
    {
        $agent = Auth::guard('agent')->user();
        $this->validate($request, [
            'key' => 'required',
            'code' => 'required',
        ]);

        $ga = new GoogleAuthenticator();
        $secret = $request->key;
        $oneCode = $ga->getCode($secret);

        if ($oneCode === $request->code) {
            $agent->tsc = $request->key;
            $agent->ts = 1;
            $agent->tv = 1;
            $agent->save();


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

        $agent = Auth::guard('agent')->user();
        $ga = new GoogleAuthenticator();

        $secret = $agent->tsc;
        $oneCode = $ga->getCode($secret);
        $surveyorCode = $request->code;

        if ($oneCode == $surveyorCode) {

            $agent->tsc = null;
            $agent->ts = 0;
            $agent->tv = 1;
            $agent->save();


            $surveyorAgent = getIpInfo();
            $osBrowser = osBrowser();
            notify($agent, '2FA_DISABLE', [
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
        $logs = auth()->guard('agent')->user()->deposits()->with(['gateway'])->latest()->paginate(getPaginate());
        return view('agent.deposit_history', compact('page_title', 'empty_message', 'logs'));
    }

    public function transactionHistory()
    {

        $page_title = 'Successfull Transactions';
        $transactions = Transaction::where('agent_id',auth()->guard('agent')->user()->id)->latest()->paginate(getPaginate());
        $empty_message = 'No transactions';
        return view('agent.transactions', compact('page_title', 'transactions', 'empty_message'));
    }

    public function earnings()
    {
        $page_title = 'Earnings';
        $agent_id = auth()->guard('agent')->user()->id;
        $surveys = Survey::with('surveyor')->whereHas('surveyor', function($q) use($agent_id) {
            return $q->where('agent_id', $agent_id);
        })->latest()->paginate(getPaginate());
        $empty_message = 'No Earnings';
        return view('agent.earnings', compact('page_title', 'surveys', 'empty_message'));
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

    public function surveyAll()
    {
        $page_title = 'New Campaign';
        $surveys = campaign::where('surveyor_id',auth()->guard('surveyor')->user()->id)->latest()->paginate(getPaginate());
        $empty_message = 'No data found';
        return view('surveyor.survey.index', compact('page_title','surveys','empty_message'));
    }

    public function surveyNew()
    {
        $page_title = 'New Campaign';
        $categories = Category::where('status','1')->latest()->get();
        return view('surveyor.survey.new', compact('page_title','categories'));
    }

    public function surveyStore(Request $request)
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



        $notify[] = ['success', 'Campaign has been added'];
        return redirect()->route('surveyor.survey.all');
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

    public function questionAll($id)
    {
        $page_title = 'All Campaigns';
        $survey = Survey::findOrFail($id);

        if ($survey->surveyor_id != auth()->guard('surveyor')->user()->id) {
            $notify[] = ['success', 'You are not authorized to see this survey'];
            return back()->withNotify($notify);
        }

        $questions = $survey->campaigns()->paginate(getPaginate());
        $empty_message = 'No campaigns found';
        return view('surveyor.question.index', compact('page_title','survey','empty_message','questions'));
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

        $notify[] = ['success', 'Question has been added'];
        return back()->withNotify($notify);
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
        $page_title = 'Survey Report';
        $surveys = Survey::where('surveyor_id',auth()->guard('surveyor')->user()->id)->where('status',0)->latest()->paginate(getPaginate());
        $empty_message = 'No survey found';
        return view('surveyor.report.index', compact('page_title','surveys','empty_message'));
    }

    public function reportQuestion($id)
    {
        $page_title = 'Survey Report';
        $survey = Survey::where('surveyor_id',auth()->guard('surveyor')->user()->id)->findOrFail($id);

        if (count($survey->answers) <= 0) {
            $notify[] = ['error', 'Not report ready yet'];
            return back()->withNotify($notify);
        }
        return view('surveyor.report.question', compact('page_title','survey'));
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
        return view('surveyor.report.report',compact('survey','page_title','filename'));
    }
    public function refferrals(){
        $page_title = 'Refferrals';
        $agent = Auth::guard('agent')->user();
        $agent_id =  $agent->id;
        $user = Surveyor::where('agent_id',$agent_id)->get();
        $empty_message = 'No survey found';
        return view('agent.refferrals', compact('page_title', 'user','empty_message'));
    }
}

