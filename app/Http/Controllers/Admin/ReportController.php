<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\SurveyorLogin;
use App\Transaction;
use App\UserLogin;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function transaction()
    {
        $page_title = 'User Transaction Logs';
        $transactions = Transaction::where('user_id','!=',null)->orderBy('id','desc')->paginate(getPaginate());
        $empty_message = 'No transactions.';
        return view('admin.reports.transactions', compact('page_title', 'transactions', 'empty_message'));
    }

    public function transactionSearch(Request $request)
    {
        $request->validate(['search' => 'required']);
        $search = $request->search;
        $page_title = 'Transactions Search - ' . $search;
        $empty_message = 'No transactions.';

        $transactions = Transaction::whereHas('user', function ($user) use ($search) {
            $user->where('username', 'like',"%$search%");
        })->orWhere('trx', $search)->orderBy('id','desc')->where('user_id','!=',null)->paginate(getPaginate());

        return view('admin.reports.transactions', compact('page_title', 'transactions', 'empty_message'));
    }

    public function surveyorTransaction()
    {
        $page_title = 'Sponsor Transaction Logs';
        $transactions = Transaction::where('surveyor_id','!=',null)->orderBy('id','desc')->paginate(getPaginate());
        $empty_message = 'No transactions.';
        return view('admin.reports.transactions', compact('page_title', 'transactions', 'empty_message'));
    }

    public function surveyorTransactionSearch(Request $request)
    {
        $request->validate(['search' => 'required']);
        $search = $request->search;
        $page_title = 'Transactions Search - ' . $search;
        $empty_message = 'No transactions.';

        $transactions = Transaction::whereHas('surveyor', function ($surveyor) use ($search) {
            $surveyor->where('username', 'like',"%$search%");
        })->orWhere('trx', $search)->where('surveyor_id','!=',null)->orderBy('id','desc')->paginate(getPaginate());

        return view('admin.reports.transactions', compact('page_title', 'transactions', 'empty_message'));
    }

    public function loginHistory(Request $request)
    {
        if ($request->search) {
            $search = $request->search;
            $page_title = 'User Login History Search - ' . $search;
            $empty_message = 'No search result found.';
            $login_logs = UserLogin::where('user_id','!=',null)->whereHas('user', function ($query) use ($search) {
                $query->where('username', $search);
            })->orderBy('id','desc')->paginate(getPaginate());
            return view('admin.reports.logins', compact('page_title', 'empty_message', 'search', 'login_logs'));
        }
        $page_title = 'User Login History';
        $empty_message = 'No users login found.';
        $login_logs = UserLogin::orderBy('id','desc')->paginate(getPaginate());
        return view('admin.reports.logins', compact('page_title', 'empty_message', 'login_logs'));
    }

    public function surveyorLoginHistory(Request $request)
    {
        if ($request->search) {
            $search = $request->search;
            $page_title = 'Sponsor Login History Search - ' . $search;
            $empty_message = 'No search result found.';
            $login_logs = SurveyorLogin::where('surveyor_id','!=',null)->whereHas('surveyor', function ($query) use ($search) {
                $query->where('username', $search);
            })->orderBy('id','desc')->paginate(getPaginate());
            return view('admin.reports.logins', compact('page_title', 'empty_message', 'search', 'login_logs'));
        }

        $page_title = 'Sponsor Login History';
        $empty_message = 'No surveyors login found.';
        $login_logs = SurveyorLogin::orderBy('id','desc')->paginate(getPaginate());
        return view('admin.reports.logins', compact('page_title', 'empty_message', 'login_logs'));
    }

    public function loginIpHistory($ip)
    {
        $page_title = 'Login By - ' . $ip;
        $login_logs = UserLogin::where('user_id','!=',null)->where('user_ip',$ip)->orderBy('id','desc')->paginate(getPaginate());
        $empty_message = 'No users login found.';
        return view('admin.reports.logins', compact('page_title', 'empty_message', 'login_logs'));

    }

    public function surveyorLoginIpHistory($ip)
    {
        $page_title = 'Login By - ' . $ip;
        $login_logs = SurveyorLogin::where('surveyor_ip',$ip)->orderBy('id','desc')->paginate(getPaginate());
        $empty_message = 'No surveyors login found.';
        return view('admin.reports.logins', compact('page_title', 'empty_message', 'login_logs'));

    }
}
