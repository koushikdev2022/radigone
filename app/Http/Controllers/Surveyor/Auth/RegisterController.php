<?php

namespace App\Http\Controllers\Surveyor\Auth;

use App\Category;
use App\GeneralSetting;
use App\Http\Controllers\Controller;
use App\Surveyor;
use App\SurveyorLogin;
use App\Stopresume;
use App\Registrationfees;
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
    protected $redirectTo = '/surveyor/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('surveyor.guest');
        $this->middleware('surveyorRegStatus');
    }


    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */

    public function showRegistrationForm($id=null)
    {
       if(!empty($id)){
        $agent_id =  base64_decode($id);
        }else{
        $agent_id = 0;
        }

        $page_title = "Sign Up";
         $Stopresume = Stopresume::where('id',1)->first();

        $date1 = $Stopresume->surveyorstatedate;
        $date2 = $Stopresume->surveyorenddate;
            $paymentDate = date('Y-m-d');
            $paymentDate=date('Y-m-d', strtotime($paymentDate));

            $contractDateBegin = date('Y-m-d', strtotime($date1));
            $contractDateEnd = date('Y-m-d', strtotime($date2));

            if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd)){

              if($Stopresume->surveyor =='resume'){

               $val = '1';


              }else{
               $val = '0';
              }
            }else{
               $val = '0';
            }


        $country_code = @implode(',', $info['code']);
        $registrationfees = Registrationfees::where('id',1)->first();
        $categories = Category::where('status','1')->where('subcategories', '!=', null)->latest()->get();
        //$categories = Category::where('status','1')->where('subcategories', '!=', null)->latest()->get();
        return view('surveyor.auth.register', compact('page_title','country_code','registrationfees','val','agent_id', 'categories'));
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
            'email' => 'required|string|email|max:90|unique:surveyors',
            'mobile' => 'required|string|max:50|unique:surveyors',
            'pan'=>'required|min:10|regex:/[A-Za-z. -]/|max:10',
            'username' => 'required|alpha_num|unique:surveyors|min:6|max:50',
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
 protected function validatorcomp(array $data)
    {
        $validate = Validator::make($data, [
            'firstname' => 'sometimes|required|string|max:50',
            'lastname' => 'sometimes|required|string|max:50',
            'email' => 'required|string|email|max:90|unique:surveyors',
            'mobile' => 'required|string|max:50|unique:surveyors',
           
            'username' => 'required|alpha_num|unique:surveyors|min:6|max:50',
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
         

        $exist = Surveyor::where('mobile',$request->country_code.$request->mobile)->first();
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
               $ucfimname = ucfirst($request->firm_name);
            
               $ucfirstname = ucfirst($request->firstname);
              
               $uclastname = ucfirst($request->lastname);
             
             $pan_card = time().'-'.'pan_card'.'.'.$request->pan_card->getClientOriginalExtension();
              $filepath = '/uploads/pan_card/';
              $request->pan_card->move(public_path('/uploads/pan_card/'), $pan_card);
              $pan_card_url = $filepath.$pan_card;
              $address_proof = time().'-'.'address_proof'.'.'.$request->address_proof->getClientOriginalExtension();
              $filepath = '/uploads/address_proof/';
              $request->address_proof->move(public_path('/uploads/address_proof/'), $address_proof);
              $address_proof_url = $filepath.$address_proof;
            
        event(new Registered($surveyor = $this->create($request->all())));
        
             if($surveyor->id){
                 Surveyor::where('id',$surveyor->id)->update([
                       'id_proof'=>$pan_card_url,
                       'address_proof'=>$address_proof_url,
                       'firm_name'=>$ucfimname,
                       'firstname'=>$ucfirstname,
                       'lastname'=>$uclastname
                     ]);
             }
        $this->guard()->login($surveyor);

        return $this->registered($request, $surveyor)
            ?: redirect($this->redirectPath());

    }
    public function usernamecheck(Request $request){
        try{
            $username = $request->username;
            $count = Surveyor::where('username',$username)->count();
         
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
public function registercomp(Request $request)
    {

        $this->validatorcomp($request->all())->validate();
         

        $exist = Surveyor::where('mobile',$request->country_code.$request->mobile)->first();
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
               $ucfimname = ucfirst($request->firm_name);
            
               $ucfirstname = ucfirst($request->firstname);
              
               $uclastname = ucfirst($request->lastname);
             
             $pan_card = time().'-'.'pan_card'.'.'.$request->pan_card->getClientOriginalExtension();
              $filepath = '/uploads/pan_card/';
              $request->pan_card->move(public_path('/uploads/pan_card/'), $pan_card);
              $pan_card_url = $filepath.$pan_card;
              $address_proof = time().'-'.'address_proof'.'.'.$request->address_proof->getClientOriginalExtension();
              $filepath = '/uploads/address_proof/';
              $request->address_proof->move(public_path('/uploads/address_proof/'), $address_proof);
              $address_proof_url = $filepath.$address_proof;
            
        event(new Registered($surveyor = $this->create($request->all())));
        
             if($surveyor->id){
                 Surveyor::where('id',$surveyor->id)->update([
                       'id_proof'=>$pan_card_url,
                       'address_proof'=>$address_proof_url,
                       'firm_name'=>$ucfimname,
                       'firstname'=>$ucfirstname,
                       'lastname'=>$uclastname
                     ]);
             }
        $this->guard()->login($surveyor);

        return $this->registered($request, $surveyor)
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
            $referSurveyor = Surveyor::where('username', $referBy)->first();
        } else {
            $referSurveyor = null;
        }

        //User Create
        $surveyor = new Surveyor();
        $surveyor->firstname = isset($data['firstname']) ? $data['firstname'] : null;
        $surveyor->lastname = isset($data['lastname']) ? $data['lastname'] : null;
        $surveyor->email = strtolower(trim($data['email']));
        $surveyor->password = Hash::make($data['password']);
        $surveyor->username = trim($data['username']);
        $surveyor->ref_by = ($referSurveyor != null) ? $referSurveyor->id : null;
        $surveyor->mobile = $data['country_code'].$data['mobile'];
        $surveyor->agent_id = $data['agent_id'];
        $surveyor->address = [
            'address' => isset($data['address']) ? $data['address'] : null,
            'state' => isset($data['state']) ? $data['state'] : null,
            'zip' => isset($data['zip']) ? $data['zip'] : null,
            'country' => isset($data['country']) ? $data['country'] : null,
            'city' => isset($data['city']) ? $data['city'] : null
        ];
        $surveyor->bought_views = 100000;
        $surveyor->total_views = 0;
        $surveyor->status = 1;
//        $surveyor->ev = $gnl->ev ? 0 : 1;
//        $surveyor->sv = $gnl->sv ? 0 : 1;
        $surveyor->ev = 1;
        $surveyor->sv = 0;
        $surveyor->ts = 0;
        $surveyor->tv = 1;
        $surveyor->post_arrangement = isset($data['post_arrangement']) ? $data['post_arrangement'] : 0;
        $surveyor->post_arrangement_mode = isset($data['post_arrangement_mode']) ? $data['post_arrangement_mode'] : 0;

        if(isset($data['profileservices'])){
            $profile_service_1 = '';
            $profile_services = $data['profileservices'];
            foreach($profile_services as $profile_service){
                $profile_service_1 = $profile_service_1 . $profile_service . ',';
            }
        }

        $registration = Registrationfees::where('id',1)->first();
        if($registration->regfee != 0) {
            $regfee = 0;
        }else {
            $regfee = 1;
        }
        $surveyor->title = isset($data['title']) ? $data['title'] : null;
        $surveyor->business_cat = isset($data['business']) ? $data['business'] : null;
        $surveyor->business_subcat = isset($data['subcategory']) ? $data['subcategory'] : null;
        $surveyor->profiling_service = isset($profile_service_1) ? $profile_service_1 : null;
        $surveyor->multi_login = isset($data['multilogins']) ? (int)$data['multilogins'] : 0;
        $surveyor->num_login = isset($data['numlogins']) ? (int)$data['numlogins'] : 0;
        $surveyor->registration_fees =  $regfee;
        $surveyor->firm_name = isset($data['firm_name']) ? $data['firm_name'] : null;
        $surveyor->firm_type = isset($data['firm_type']) ? $data['firm_type'] : null;
        $surveyor->firm_gstin = isset($data['gstin']) ? $data['gstin'] : null;
        $surveyor->designation = isset($data['designation']) ? $data['designation'] : null;
        $surveyor->pan = isset($data['pan']) ? $data['pan'] : null;

        if(isset($data['firstname']) && isset($data['lastname']) && isset($data['zip'])){
            $create_user_id = substr($data['firstname'],0,3) . substr($data['lastname'],0,3) . substr($data['zip'],0,3);
        }else {
            $create_user_id = substr($data['firstname'],0,3) . substr($data['lastname'],0,3) . '000';
        }
        $surveyor->isfirm = isset($data['firm_name']) ? 1 : 0;

        $surveyor->user_id = isset($create_user_id) ? $create_user_id : null;

        $surveyor->save();



        //Login Log Create
        $ip = $_SERVER["REMOTE_ADDR"];
        $exist = SurveyorLogin::where('surveyor_ip',$ip)->first();
        $surveyorLogin = new SurveyorLogin();

        //Check exist or not
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


        return $surveyor;
    }

        /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */

    protected function guard()
    {
        return Auth::guard('surveyor');
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
        return redirect()->route('surveyor.dashboard');
    }

}
