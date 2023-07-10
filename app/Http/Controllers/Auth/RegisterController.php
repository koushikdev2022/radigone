<?php

namespace App\Http\Controllers\Auth;

use App\GeneralSetting;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use App\UserLogin;
use Illuminate\Auth\Events\Registered;
use App\Registrationfees;
use App\Stopresume;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
ini_set('memory_limit', '44M');
use Illuminate\Support\Facades\Validator;

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

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('regStatus')->except('registrationNotAllowed');

        $this->activeTemplate = activeTemplate();
    }

    public function referralRegister($reference)
    {


        $page_title = "Register";
        session()->put('reference', $reference);
        $info = json_decode(json_encode(getIpInfo()), true);
        $country_code = @implode(',', $info['code']);
        $registrationfees = Registrationfees::where('id',1)->first();
        return view($this->activeTemplate . 'user.auth.register', compact('reference', 'page_title','country_code','registrationfees'));
    }

    public function showRegistrationForm($id=null)
    {
        if(!empty($id)){
        $agent_id =  base64_decode($id);
        }else{
        $agent_id = 0;
        }
        $page_title = "Register";
        $Stopresume = Stopresume::where('id',1)->first();

        $date1 = $Stopresume->userstatedate;
        $date2 = $Stopresume->userenddate;
            $paymentDate = date('Y-m-d');
            $paymentDate=date('Y-m-d', strtotime($paymentDate));

            $contractDateBegin = date('Y-m-d', strtotime($date1));
            $contractDateEnd = date('Y-m-d', strtotime($date2));

            if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd)){

              if($Stopresume->user =='resume'){

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
        return view($this->activeTemplate . 'user.auth.register', compact('page_title','country_code','registrationfees','val','agent_id'));


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
            'email' => 'required|string|email|max:90|unique:users',
            'mobile' => 'required|string|max:50|unique:users',
            'whatsaap' => 'required|string|max:50',
            'gander'=>'required',
            'marital'=>'required',
            'state'=>'required',
            'pincode'=>'required|min:6|max:6|regex:/^[0-9]+$/',
            'address'=>'required',
            'password' => [
            'required',
            'min:6',             // must be at least 10 characters in length
            'regex:/[a-z]/',      // must contain at least one lowercase letter
            'regex:/[A-Z]/',      // must contain at least one uppercase letter
            'regex:/[0-9]/',      // must contain at least one digit
            'regex:/[@$!%*#?&]/', // must contain a special character
        ],
            'username' => 'required|alpha_num|unique:users|min:6|max:50',
            'captcha' => 'sometimes|required',
            'country_code' => 'required',
            'pan_card'=>'required|mimes:jpg,jpeg,png,pdf',
            'address_proof'=>'required|mimes:jpg,JPEG,png,pdf'
        ]);

        return $validate;
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $exist = User::where('mobile',$request->country_code.$request->mobile)->first();
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
              $pan_card = time().'-'.'pan_card'.'.'.$request->pan_card->getClientOriginalExtension();
              $filepath = '/uploads/user/pan_card/';
              $request->pan_card->move(public_path('/uploads/user/pan_card/'), $pan_card);
              $pan_card_url = $filepath.$pan_card;
              $address_proof = time().'-'.'address_proof'.'.'.$request->address_proof->getClientOriginalExtension();
              $filepath = '/uploads/user/address_proof/';
              $request->address_proof->move(public_path('/uploads/user/address_proof/'), $address_proof);
              $address_proof_url = $filepath.$address_proof;
        event(new Registered($user = $this->create($request->all())));
        
        if($user->id){
                 User::where('id',$user->id)->update([
                       'id_proof'=>$pan_card_url,
                       'address_proof'=>$address_proof_url,
                    
                     ]);
             }

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        $gnl = GeneralSetting::first();


        $referBy = session()->get('reference');
        if ($referBy != null) {
            $referUser = User::where('username', $referBy)->first();
        } else {
            $referUser = null;
        }

        //User Create
        $user = new User();
        $user->firstname = isset($data['firstname']) ? $data['firstname'] : null;
        $user->lastname = isset($data['lastname']) ? $data['lastname'] : null;
        $user->email = strtolower(trim($data['email']));
        $user->password = Hash::make($data['password']);
        $user->username = trim($data['username']);
        $user->ref_by = ($referUser != null) ? $referUser->id : null;
        $user->mobile = $data['country_code'].$data['mobile'];
        $user->age = 0;
        $user->profession = null;
        $user->whatsaap = $data['whatsaap'];
        $user->gander = $data['gander'];
        $user->marital = $data['marital'];


        $user->address = [
            'address' => isset($data['address']) ? $data['address'] : null,
            'state' => isset($data['state']) ? $data['state'] : null,
            'zip' => isset($data['pincode']) ? $data['pincode'] : null,
            'country' => isset($data['country']) ? $data['country'] : null,
            'city' => isset($data['city']) ? $data['city'] : null,
        ];
        $user->status = 1;
//        $user->ev = $gnl->ev ? 0 : 1;
//        $user->sv = $gnl->sv ? 0 : 1;
        $user->ev = 1;
        $user->sv = 0;
        $user->ts = 0;
        $user->tv = 1;
        $user->save();



        //Login Log Create
        $ip = $_SERVER["REMOTE_ADDR"];
        $exist = UserLogin::where('user_ip',$ip)->first();
        $userLogin = new UserLogin();

        //Check exist or not
        if ($exist) {
            $userLogin->longitude =  $exist->longitude;
            $userLogin->latitude =  $exist->latitude;
            $userLogin->location =  $exist->location;
            $userLogin->country_code = $exist->country_code;
            $userLogin->country =  $exist->country;
        }else{
            $info = json_decode(json_encode(getIpInfo()), true);
            $userLogin->longitude =  @implode(',',$info['long']);
            $userLogin->latitude =  @implode(',',$info['lat']);
            $userLogin->location =  @implode(',',$info['city']) . (" - ". @implode(',',$info['area']) ."- ") . @implode(',',$info['country']) . (" - ". @implode(',',$info['code']) . " ");
            $userLogin->country_code = @implode(',',$info['code']);
            $userLogin->country =  @implode(',', $info['country']);
        }

        $userAgent = osBrowser();
        $userLogin->user_id = $user->id;
        $userLogin->user_ip =  $ip;

        $userLogin->browser = @$userAgent['browser'];
        $userLogin->os = @$userAgent['os_platform'];
        $userLogin->save();


        return $user;
    }

    public function registered()
    {
        return redirect()->route('user.home');
    }

}
