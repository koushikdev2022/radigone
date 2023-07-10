<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use App\UserLogin;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class AuthController extends Controller
{
    public function login()
    {
        return response()->json([
                'message' => 'Authentication Failed',
                'success' => false,
                'code' => Response::HTTP_UNAUTHORIZED
            ])->setStatusCode(Response::HTTP_UNAUTHORIZED);
    }
    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
                [
                    'username' => 'required',
                    'password' => 'required'
                ]);

            if($validateUser->fails()){
                return response()->json([
                    'success' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['username', 'password']))){
                return response()->json([
                    'success' => false,
                    'message' => 'Username & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('username', $request->username)->first();
            if ($user->status == 0) {
                $user->guard()->token()->revoke();
                return redirect()->route('user.login')->withErrors(['Your account has been deactivated.']);
            }

            $user->tv = $user->ts == 1 ? 0 : 1;
            $user->save();
            $ip = $_SERVER["REMOTE_ADDR"];
            $exist = UserLogin::where('user_ip',$ip)->first();
            $userLogin = new UserLogin();
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

            return response()->json([
                'success' => true,
                'message' => 'User Logged In Successfully',
                'data' => $user,
                'token' => $user->createToken('app')->accessToken,
                'token_type' => 'Bearer'
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function logout()
    {
        $info = ['success' => false, 'message' => __('Something went wrong!'), 'data' => null];

        if(Auth::guard('api')->check()) {
            Auth::guard('api')->user()->token()->revoke();
            $info['success'] = true;
            $info['message'] = __('Successfully logged out');
        }
        return \response()->json($info);
    }

    public function userProfileInfo()
    {
        $info = ['success' => false, 'message' => null, 'data' => null];
        $user = User::whereId(Auth::guard('api')->id())->first();
        if(!is_null($user)) {
            $info['success'] = true;
            $info['data'] = $user;
        }else{
            $info['message'] = __('No data found!');
        }

        return response()->json($info);
    }

    public function userProfileUpdate(Request $request)
    {
        $info = ['success' => false, 'message' => __('Something went wrong!'), 'data' => null];
        $validateUser = Validator::make($request->all(),
            [
                'firstname' => 'required|string|max:50',
                'lastname' => 'required|string|max:50',
                'address' => "sometimes|required|max:80",
                'state' => 'sometimes|required|max:80',
                'zip' => 'sometimes|required|max:40',
                'city' => 'sometimes|required|max:50',
                'image' => 'mimes:png,jpg,jpeg',
                'account_number'=>'required',
                're_account_number' => 'required|same:account_number'
            ],[
                'firstname.required'=>'First Name Field is required',
                'lastname.required'=>'Last Name Field is required',
                'account_number.required'=>'Re account number and account number could not be mach',
                're_account_number.required'=>'Re account number and account number could not be mach'
            ]);

        if($validateUser->fails()){
            return response()->json([
                'success' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        $user = Auth::guard('api')->user();


        $in['firstname'] = $request->firstname;
        $in['lastname'] = $request->lastname;

        $in['dob'] = $request->dob;
        $in['occupation'] = $request->occupation;
        $in['anniversary_date'] = $request->anniversary_date;
        $in['annual_income'] = $request->annual_income;
        $in['pan'] = $request->pan;
        $in['account_number'] = $request->account_number;
        $in['re_account_number'] = $request->re_account_number;
        $in['bank_ifsc'] = $request->bank_ifsc;

        $in['whatsaap'] = $request->whatsaap;
        $in['gander'] = $request->gander;
        $in['marital'] = $request->marital;



        $in['address'] = [
            'address' => $request->address,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => $user->address->country,
            'city' => $request->city,
        ];


        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $user->username . '.jpg';
            $location = 'assets/images/user/profile/' . $filename;
            $in['image'] = $filename;

            $path = './assets/images/user/profile/';
            $link = $path . $user->image;
            if (file_exists($link)) {
                @unlink($link);
            }
            $size = imagePath()['profile']['user']['size'];
            $image = Image::make($image);
            $size = explode('x', strtolower($size));
            $image->resize($size[0], $size[1]);
            $image->save($location);
        }

        $user->fill($in)->save();
        $info['success'] = true;
        $info['message'] = __('Profile Updated successfully.');
        $info['data'] = $user;
        return \response($info);
    }

    public function userChangePassword(Request $request)
    {
        
        $info = ['success' => false, 'message' => __('Something went wrong!'), 'data' => null];
        $validateUser = Validator::make($request->all(),
            [
                'current_password' => 'required',
                'password' => 'required|min:5|confirmed'
            ]);

        if($validateUser->fails()){
            return response()->json([
                'success' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        $user = auth()->guard('api')->user();
        if (Hash::check($request->current_password, $user->password)) {
            $password = Hash::make($request->password);
            $user->password = $password;
            $user->save();

            $info['success'] = true;
            $info['message'] = __('Password Changes successfully.');
        } else {
            $info['success'] = false;
            $info['message'] = __('Current password not match.');
        }
        return \response()->json($info);
    }
}
