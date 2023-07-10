<?php

namespace App\Http\Controllers\Agent\Auth;

use App\Category;
use App\GeneralSetting;
use App\Http\Controllers\Controller;
use App\Agent;
use App\AgentLogin;
use App\Registrationfees;
use App\Stopresume;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
ini_set('memory_limit', '44M');
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/agent/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('agent.guest');
        $this->middleware('agentRegStatus');
    }


    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */

    public function showRegistrationForm()
    {
        $page_title = "Sign Up";
        $Stopresume = Stopresume::where('id',1)->first();

        $date1 = $Stopresume->agentstatedate;
        $date2 = $Stopresume->agentenddate;
            $paymentDate = date('Y-m-d');
            $paymentDate=date('Y-m-d', strtotime($paymentDate));

            $contractDateBegin = date('Y-m-d', strtotime($date1));
            $contractDateEnd = date('Y-m-d', strtotime($date2));

            if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd)){

              if($Stopresume->agents =='resume'){

               $val = '1';


              }else{
               $val = '0';
              }
            }else{
               $val = '0';
            }
        $info = json_decode(json_encode(getIpInfo()), true);
        $country_code = @implode(',', $info['code']);

        $registrationfees = Registrationfees::where('id',1)->first();
        $categories = Category::where('status','1')->latest()->get();
        return view('agent.auth.register', compact('page_title','country_code','registrationfees','val', 'categories'));
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validate = Validator::make($data, [
            'firstname' => 'sometimes|required|string|max:50',
            'lastname' => 'sometimes|required|string|max:50',
            'email' => 'required|string|email|max:90|unique:agents',
            'mobile' => 'required|string|max:50|unique:agents',
            
            'username' => 'required|alpha_num|unique:agents|min:6|max:50',
            'captcha' => 'sometimes|required',
            'country_code' => 'required',
            
            'pan_card'=>'required|mimes:jpg,jpeg,png,pdf',
            'address_proof'=>'required|mimes:jpg,JPEG,png,pdf',
            'password' => [
            'required',
            'min:6',             // must be at least 10 characters in length
            'regex:/[a-z]/',      // must contain at least one lowercase letter
            'regex:/[A-Z]/',      // must contain at least one uppercase letter
            'regex:/[0-9]/',      // must contain at least one digit
            'regex:/[@$!%*#?&]/', // must contain a special character
        ],
        ]);

        return $validate;
    }


    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */

    public function register(Request $request)
    {

        $this->validator($request->all())->validate();
          $ucfimname = ucfirst($request->firm_name);
            
               $ucfirstname = ucfirst($request->firstname);
              
               $uclastname = ucfirst($request->lastname);
         $pan_card = time().'-'.'pan_card'.'.'.$request->pan_card->getClientOriginalExtension();
              $filepath = '/uploads/agent/pan_card/';
              $request->pan_card->move(public_path('/uploads/agent/pan_card/'), $pan_card);
              $pan_card_url = $filepath.$pan_card;
              $address_proof = time().'-'.'address_proof'.'.'.$request->address_proof->getClientOriginalExtension();
              $filepath = '/uploads/agent/address_proof/';
              $request->address_proof->move(public_path('/uploads/agent/address_proof/'), $address_proof);
              $address_proof_url = $filepath.$address_proof;

        $exist = Agent::where('mobile',$request->country_code.$request->mobile)->first();
        if ($exist) {
            $notify[] = ['error', 'Mobile number already exist'];
            return back()->withNotify($notify)->withInput();
        }

        if (isset($request->captcha)) {
            if (!captchaVerify($request->captcha, $request->captcha_secret)) {
                $notify[] = ['error', "Invalid Captcha"];
                return back()->withNotify($notify)->withInput();
            }
        }

        event(new Registered($agent = $this->create($request->all())));
               if($agent->id){
                 Agent::where('id',$agent->id)->update([
                       'id_proof'=>$pan_card_url,
                       'address_proof'=>$address_proof_url,
                        'firm_name'=>$ucfimname,
                       'firstname'=>$ucfirstname,
                       'lastname'=>$uclastname,
                       'pan'=>$request->pan
                     ]);
             }
        $this->guard()->login($agent);

        return $this->registered($request, $agent)
            ?: redirect($this->redirectPath());

    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\Surveyor
     */
    protected function create(array $data)
    {

        $gnl = GeneralSetting::first();


        $referBy = session()->get('reference');
        if ($referBy != null) {
            $referSurveyor = Agent::where('username', $referBy)->first();
        } else {
            $referSurveyor = null;
        }

        //User Create
        $agent = new Agent();
        
        $agent->firstname = isset($data['firstname']) ? $data['firstname'] : null;
        $agent->lastname = isset($data['lastname']) ? $data['lastname'] : null;
        $agent->email = strtolower(trim($data['email']));
        $agent->password = Hash::make($data['password']);
        $agent->username = trim($data['username']);
        $agent->ref_by = ($referSurveyor != null) ? $referSurveyor->id : null;
        $agent->mobile = $data['country_code'].$data['mobile'];
        $agent->address = [
            'address' => isset($data['address']) ? $data['address'] : null,
            'state' => isset($data['state']) ? $data['state'] : null,
            'zip' => isset($data['zip']) ? $data['zip'] : null,
            'country' => isset($data['country']) ? $data['country'] : null,
            'city' => isset($data['city']) ? $data['city'] : null
        ];
        $agent->status = 1;
//        $agent->ev = $gnl->ev ? 0 : 1;
//        $agent->sv = $gnl->sv ? 0 : 1;
        $agent->ev = 1;
        $agent->sv = 0;
        $agent->ts = 0;
        $agent->tv = 1;

        if(isset($data['profileservices'])){
            $profile_service_1 = '';
            $profile_services = $data['profileservices'];
            foreach($profile_services as $profile_service){
                $profile_service_1 = $profile_service_1 . $profile_service . ',';
            }
        }
        $registration = Registrationfees::where('id',1)->first();
        if($registration->agent_fees != 0) {
            $regfee = 0;
        }else {
            $regfee = 1;
        }
        $agent->title = isset($data['title']) ? $data['title'] : null;
        $agent->business_cat = isset($data['business']) ? $data['business'] : null;
        $agent->business_subcat = isset($data['subcategory']) ? $data['subcategory'] : null;
        $agent->profiling_service = isset($profile_service_1) ? $profile_service_1 : null;
        $agent->multi_login = isset($data['multilogins']) ? (int)$data['multilogins'] : 0;
        $agent->num_login = isset($data['numlogins']) ? (int)$data['numlogins'] : 0;
//        $agent->registration_fees = isset($data['registration-fees']) ? (int)$data['registration-fees'] : 0;
        $agent->registration_fees = $regfee;
        $agent->firm_name = isset($data['firm_name']) ? $data['firm_name'] : null;
        $agent->firm_type = isset($data['firm_type']) ? $data['firm_type'] : null;
        $agent->firm_gstin = isset($data['gstin']) ? $data['gstin'] : null;
        $agent->designation = isset($data['designation']) ? $data['designation'] : null;

        if(isset($data['firstname']) && isset($data['lastname']) && isset($data['zip'])){
            $create_user_id = substr($data['firstname'],0,3) . substr($data['lastname'],0,3) . substr($data['zip'],0,3);
        }else {
            $create_user_id = substr($data['firstname'],0,3) . substr($data['lastname'],0,3) . '000';
        }
        $agent->isfirm = isset($data['firm_name']) ? 1 : 0;

        $agent->user_id = isset($create_user_id) ? $create_user_id : null;

         $agent->save();
       
        
        


        //Login Log Create
        $ip = $_SERVER["REMOTE_ADDR"];
        $exist = AgentLogin::where('agent_ip',$ip)->first();
        $agentLogin = new AgentLogin();

        //Check exist or not
        if ($exist) {
            $agentLogin->longitude =  $exist->longitude;
            $agentLogin->latitude =  $exist->latitude;
            $agentLogin->location =  $exist->location;
            $agentLogin->country_code = $exist->country_code;
            $agentLogin->country =  $exist->country;
        }else{
            $info = json_decode(json_encode(getIpInfo()), true);
            $agentLogin->longitude =  @implode(',',$info['long']);
            $agentLogin->latitude =  @implode(',',$info['lat']);
            $agentLogin->location =  @implode(',',$info['city']) . (" - ". @implode(',',$info['area']) ."- ") . @implode(',',$info['country']) . (" - ". @implode(',',$info['code']) . " ");
            $agentLogin->country_code = @implode(',',$info['code']);
            $agentLogin->country =  @implode(',', $info['country']);
        }

        // $agentLogin = osBrowser();
        // return $agentLogin;
        // $agentLogin->agent_id = $agent->id;
        // $agentLogin->agent_ip =  $ip;

        // $agentLogin->browser = @$agentLogin['browser'];
        // $agentLogin->os = @$agentLogin['os_platform'];
        // $agentLogin->save();


        return $agent;
    }

        /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */

    protected function guard()
    {
        return Auth::guard('agent');
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $surveyor
     * @return mixed
     */

    public function registered()
    {
        return redirect()->route('agent.dashboard');
    }

}
