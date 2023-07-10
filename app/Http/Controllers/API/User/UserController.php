<?php

namespace App\Http\Controllers\API\User;

use App\Category;
use App\Http\Controllers\Controller;
use App\Transaction;
use App\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function withdrawLog()
    {
        $data = Withdrawal::where('user_id', Auth::guard('api')->id())->where('status', '!=', 0)->with('method')->latest()->paginate(getPaginate());
        $custom = collect([
            'success' => 'true',
            'message' => null,
        ]);
        return $custom->merge($data);
    }

    public function transactionLog()
    {
        $data = Transaction::where('user_id',Auth::guard('api')->id())->orderBy('id','desc')->paginate(getPaginate());
        $custom = collect([
            'success' => 'true',
            'message' => null,
        ]);
        return $custom->merge($data);
    }

    public function adPreferenceList()
    {
        $info = ['success' => true, 'message' => null, 'data' => null];
        $user = Auth::guard('api')->user()->id;
        $data = DB::table('preferences')->where('user_id',$user)->get()->map(function ($data) {
            return [
                'id' => $data->id,
                'name' => $data->preferences_name
            ];
        });
        $info['data'] = $data;
        return response()->json($info);
    }

    public function allAdPreferences()
    {
        $info = ['success' => true, 'message' => null, 'data' => null];
        $data = Category::where('status','1')->get();
        $info['data'] = $data;
        return response()->json($info);
    }

    public function adPreferenceUpdate(Request $request)
    {
        $user = Auth::guard('api')->user()->id;
        $ids = $request->preferences;
        DB::table('preferences')->where('user_id',$user)->delete();

        foreach ($ids as $id) {
            $student = Category::findOrfail($id); // assume you use this model
            $name = $student->name;
            $id = $student->id;
            $values = array('preferences_id' => $id ,'preferences_name' => $name,'user_id'=>$user);
            $users = DB::table('preferences')->insert($values);
        }
        return response()->json([
            'success' => true,
            'message' => __('User ad preferences updated!'),
            'data' => null,
        ]);
    }
}
