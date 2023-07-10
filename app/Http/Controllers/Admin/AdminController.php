<?php

namespace App\Http\Controllers\Admin;

use App\Answer;
use App\Deposit;
use App\Gateway;
use App\Refund;
use App\User;
use App\Registrationfees;
use App\Stopresume;
use App\Transaction;

use App\UserLogin;
use App\Withdrawal;
use App\WithdrawMethod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Survey;
use App\Surveyor;
use App\Agent;

class AdminController extends Controller
{

    public function dashboard()
    {
        $page_title = 'Dashboard';

        // User Info
        $widget['total_users'] = User::count();
        $widget['verified_users'] = User::where('status', 1)->count();
        $widget['email_unverified_users'] = User::where('ev', 0)->count();
        $widget['sms_unverified_users'] = User::where('sv', 0)->count();

        // Surveyor Info
        $widget['total_surveyors'] = Surveyor::count();
        $widget['verified_surveyors'] = Surveyor::where('status', 1)->count();
        $widget['email_unverified_surveyors'] = Surveyor::where('ev', 0)->count();
        $widget['sms_unverified_surveyors'] = Surveyor::where('sv', 0)->count();

       //
        $widget['total_agent'] = Agent::count();
        // Survey Info
        $widget['pending_surveys'] = Survey::where('status',1)->count();
        $widget['approved_surveys'] = Survey::where('status',0)->count();
        $widget['rejected_surveys'] = Survey::where('status',3)->count();

        $report['months'] = collect([]);

        $report['deposit_month_amount'] = collect([]);
        $report['withdraw_month_amount'] = collect([]);

        $depositsMonth = Deposit::whereYear('created_at', '>=', Carbon::now()->subYear())
            ->selectRaw("SUM( CASE WHEN status = 1 THEN amount END) as depositAmount")
            ->selectRaw("DATE_FORMAT(created_at,'%M') as months")
            ->orderBy('created_at')
            ->groupBy(DB::Raw("MONTH(created_at)"))->get();

        $depositsMonth->map(function ($aaa) use ($report) {
            $report['months']->push($aaa->months);
            $report['deposit_month_amount']->push(getAmount($aaa->depositAmount));
        });

        $withdrawalMonth = Withdrawal::whereYear('created_at', '>=', Carbon::now()->subYear())->where('status', 1)
            ->selectRaw("SUM( CASE WHEN status = 1 THEN amount END) as withdrawAmount")
            ->selectRaw("DATE_FORMAT(created_at,'%M') as months")
            ->orderBy('created_at')
            ->groupBy(DB::Raw("MONTH(created_at)"))->get();

        $withdrawalMonth->map(function ($bb) use ($report){
            $report['withdraw_month_amount']->push(getAmount($bb->withdrawAmount));
        });

        // Withdraw Graph
        $withdrawal = Withdrawal::where('created_at', '>=', \Carbon\Carbon::now()->subDays(30))->where('status', 1)
            ->select(array(DB::Raw('sum(amount)   as totalAmount'), DB::Raw('DATE(created_at) day')))
            ->groupBy('day')->get();

        $withdrawals['per_day'] = collect([]);
        $withdrawals['per_day_amount'] = collect([]);
        $withdrawal->map(function ($a) use ($withdrawals) {
            $withdrawals['per_day']->push(date('d M', strtotime($a->day)));
            $withdrawals['per_day_amount']->push($a->totalAmount + 0);
        });



        // user Browsing, Country, Operating Log
        $user_login_data = UserLogin::whereDate('created_at', '>=', \Carbon\Carbon::now()->subDay(30))->get(['browser', 'os', 'country']);

        $chart['user_browser_counter'] = $user_login_data->groupBy('browser')->map(function ($item, $key) {
            return collect($item)->count();
        });
        $chart['user_os_counter'] = $user_login_data->groupBy('os')->map(function ($item, $key) {
            return collect($item)->count();
        });
        $chart['user_country_counter'] = $user_login_data->groupBy('country')->map(function ($item, $key) {
            return collect($item)->count();
        })->sort()->reverse()->take(5);


        $payment['total_deposit_amount'] = Deposit::where('status',1)->sum('amount');
        $payment['total_deposit_charge'] = Deposit::where('status',1)->sum('charge');
        $payment['total_deposit_pending'] = Deposit::where('status',2)->count();
        $payment['total_deposit'] = Deposit::where('status',1)->count();

        $paymentWithdraw['total_withdraw_amount'] = Withdrawal::where('status',1)->sum('amount');
        $paymentWithdraw['total_withdraw'] = Withdrawal::where('status',1)->count();
        $paymentWithdraw['total_withdraw_charge'] = Withdrawal::where('status',1)->sum('charge');
        $paymentWithdraw['total_withdraw_pending'] = Withdrawal::where('status',2)->count();


        $latestUser = User::latest()->limit(6)->get();
        $latestSurveyor = Surveyor::latest()->limit(6)->get();
        $empty_message = 'No Data Found';

        $survey_chart = Answer::groupBy('user_id')->orderBy('created_at')->get()->groupBy(function ($d) {
            return $d->created_at->format('F');
        });

        $survey_all = [];
        $month_survey = [];
        foreach ($survey_chart as $key => $value) {
            $survey_all[] = count($value);
            $month_survey[] = $key;
        }

        return view('admin.dashboard', compact('page_title', 'widget', 'report', 'withdrawals', 'chart','payment','paymentWithdraw','latestUser','latestSurveyor','empty_message','depositsMonth','withdrawalMonth','survey_all','month_survey'));
    }


    public function profile()
    {
        $page_title = 'Profile';
        $admin = Auth::guard('admin')->user();
        return view('admin.profile', compact('page_title', 'admin'));
    }

    public function profileUpdate(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'image' => 'nullable|image|mimes:jpg,jpeg,png'
        ]);

        $user = Auth::guard('admin')->user();

        if ($request->hasFile('image')) {
            try {
                $old = $user->image ?: null;
                $user->image = uploadImage($request->image, imagePath()['profile']['admin']['path'], imagePath()['profile']['admin']['size'], $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Image could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        $notify[] = ['success', 'Your profile has been updated.'];
        return redirect()->route('admin.profile')->withNotify($notify);
    }


    public function password()
    {
        $page_title = 'Password Setting';
        $admin = Auth::guard('admin')->user();
        return view('admin.password', compact('page_title', 'admin'));
    }


    public function stopResume()
    {
        $page_title = 'Stop Resume';
        $admin = Auth::guard('admin')->user();
        return view('admin.stopResume', compact('page_title', 'admin'));
    }


    public function registration()
    {
        $page_title = 'Registration fees';
        $admin = Auth::guard('admin')->user();
        $getall = Registrationfees::where('id',1)->first();
        return view('admin.registration', compact('page_title', 'admin','getall'));
    }




    public function storeRegistration(Request $request){
         $newUser = Registrationfees ::updateOrCreate([
          'id'   => 1,
          ],[
            'agent_fees'     => $request->agent_fees,
            'surveyor_fees' => $request->surveyor_fees,
            'user_fees'    => $request->user_fees,

        ]);
        $notify[] = ['success', 'Update Successfully.'];
        return redirect()->route('admin.registration')->withNotify($notify);
    }


    public function stopResumenew(Request $request){
        //return $request;
          $newUser = Stopresume::updateOrCreate([
          'id'   => 1,
          ],[
            'agentstatedate'     => date('Y-m-d',strtotime($request->agentstatedate)),
            'agentenddate' => date('Y-m-d',strtotime($request->agentenddate)),
            'agents'    => $request->agents,

            'surveyorstatedate'     => date('Y-m-d',strtotime($request->surveyorstatedate)),
            'surveyorenddate' => date('Y-m-d',strtotime($request->surveyorenddate)),
            'surveyor'    => $request->surveyor,

            'userstatedate'     => date('Y-m-d',strtotime($request->surveyorstatedate)),
            'userenddate' => date('Y-m-d',strtotime($request->surveyorenddate)),
            'user'    => $request->user,

        ]);
         $notify[] = ['success', 'Update Successfully.'];
        return redirect()->route('admin.stopresume')->withNotify($notify);
    }

    public function passwordUpdate(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|min:5|confirmed',
        ]);

        $user = Auth::guard('admin')->user();
        if (!Hash::check($request->old_password, $user->password)) {
            $notify[] = ['error', 'Password Do not match !!'];
            return back()->withErrors(['Invalid old password.']);
        }
        $user->password = bcrypt($request->password);
        $user->save();
        $notify[] = ['success', 'Password Changed Successfully.'];
        return redirect()->route('admin.password')->withNotify($notify);
    }
    public function transactions(){
        $page_title = 'Transactions';
        $empty_message = 'No Transactions Found';
        $transactions = Transaction::where('admin_id','1')->latest()->paginate(getPaginate());;
        return view('admin.transactions', compact('page_title', 'empty_message','transactions'));
    }

    public function refunds()
    {
        $page_title = 'Refunds';
        $empty_message = 'No Refund History Found';
        $refunds = Refund::with('transaction', 'surveyor')->latest()->paginate(getPaginate());;
        return view('admin.refunds.index', compact('page_title', 'empty_message','refunds'));
    }

    public function refundsTransfer(Request $request)
    {
        $refund = Refund::whereId($request->id)->first();
        if(is_null($refund)) {
            $notify[] = ['error', 'No Request FOund'];
            return back()->withNotify($notify);
        }

        $surveyor = Surveyor::whereId($refund->surveyor_id)->first();
        $transaction = new Transaction();
        $transaction->surveyor_id = $surveyor->id;
        $transaction->amount = getAmount($refund->amount);
        $transaction->post_balance = getAmount($surveyor->balance-$refund->amount);
        $transaction->trx_type = '-';
        $transaction->details = 'For Refund Balance';
        $transaction->trx =  getTrx();
        $transaction->save();

        $surveyor->balance = $surveyor->balance-$refund->amount;
        $surveyor->save();

        $refund->transfer_at = Carbon::now()->toDateTimeString();
        $refund->status = 1;
        $refund->save();

        $notify[] = ['success', 'Balance Transfer Successful'];
        return back()->withNotify($notify);
    }


}
