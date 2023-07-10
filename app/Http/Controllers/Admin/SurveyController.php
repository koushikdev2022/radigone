<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Question;
use App\Survey;
use App\Surveyor;
use App\Agent;
use App\Transaction;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function pending(){
        $page_title = 'Pending Campaign';
        $empty_message = 'No pending survey';
        $count = Survey::where('status',1)->whereHas('surveyor', function($q){
            $q->where('status',1);
        })->count();
        $surveys = Survey::where('status',1)->whereHas('surveyor', function($q){
            $q->where('status',1);
        })->latest()->paginate(getPaginate());

        return view('admin.survey.index',compact('page_title','empty_message','surveys','count'));
    }

    public function approved(){
        $page_title = 'Approved Campaign';
        $empty_message = 'No approved survey';

        $surveys = Survey::where('status',0)->whereHas('surveyor', function($q){
            $q->where('status',1);
        })->latest()->paginate(getPaginate());

        $count = Survey::where('status',0)->whereHas('surveyor', function($q){
            $q->where('status',1);
        })->count();

        return view('admin.survey.index',compact('page_title','empty_message','surveys','count'));
    }

    public function rejected(){
        $page_title = 'Rejected Campaign';
        $empty_message = 'No rejected survey';

        $surveys = Survey::where('status',3)->whereHas('surveyor', function($q){
            $q->where('status',1);
        })->latest()->paginate(getPaginate());


        $count = Survey::where('status',3)->whereHas('surveyor', function($q){
            $q->where('status',1);
        })->count();

        return view('admin.survey.index',compact('page_title','empty_message','surveys'));
    }

    public function approve(Request $request, $id)
    {

        $survey = Survey::findOrFail($id);
        $surveyor = Surveyor::findOrFail($survey->surveyor_id);
        if(!empty($surveyor->agent_id)){
            $agent = Agent::findOrFail($surveyor->agent_id);
            $transaction = new Transaction();
            $transaction->agent_id = $surveyor->agent_id;
            $transaction->amount = getAmount(($survey->total_without_gst*1)/100);
            $transaction->post_balance = getAmount($agent->balance+($survey->total_without_gst*1)/100);
            $transaction->trx_type = '+';
            $transaction->details = 'For Commission this user name '.$surveyor->firstname;
            $transaction->trx =  getTrx();
            $transaction->save();


            Agent::where('id', $surveyor->agent_id)
           ->update([
               'balance' => $agent->balance+($survey->total_without_gst*1)/100
            ]);
        }



        $survey->status = 0;
        $survey->save();

        $total_views = $surveyor->total_views + $survey->total_views;
        $surveyor->total_views = $total_views;
        $surveyor->save();



        $notify[] = ['success', 'Approved Successfully'];
        return back()->withNotify($notify);
    }
     public function surveydump()
    {
        $page_title = 'Dump Campaign';
//        $surveys = campaign::where('surveyor_id',auth()->guard('surveyor')->user()->id)->latest()->paginate(getPaginate());
        $surveys = Survey::where('surveyor_id',auth()->guard('surveyor')->user()->id)->latest()->paginate(getPaginate());
        $empty_message = 'No data found';
        return view('surveyor.survey.index', compact('page_title','surveys','empty_message'));
    }
    public function reject(Request $request, $id){
       //return $request;

       $survey = Survey::findOrFail($id);
       $surveyor = Surveyor::findOrFail($survey->surveyor_id);
       // Survey total views logic
       $survey_total_views = $survey->total_views;
       $survey_used_views = !is_null($survey->users) ? count($survey->users) : 0;
       $survey_view_available = $survey_total_views - $survey_used_views;
       $surveyoy_total_views = $surveyor->total_views - $survey_view_available;

       // Survey available amount logic
        $survey_total_amount = $survey->totalprice;
        $survey_per_view_amount = $survey_total_amount / $survey_total_views;
        $survey_available_amount = $survey_per_view_amount * $survey_view_available;

       $survey->reject_answer = $request->reject_answer;
       $survey->status = 3;
       $survey->save();

        $transaction = new Transaction();
        $transaction->surveyor_id = $survey->surveyor_id;
        $transaction->amount = getAmount($survey_available_amount);
        $transaction->post_balance = getAmount($surveyor->balance+$survey_available_amount);
        $transaction->trx_type = '+';
        $transaction->details = 'For Reject Campaign ';
        $transaction->trx =  getTrx();
        $transaction->is_refundable = 1;
        $transaction->save();



            Surveyor::where('id',$survey->surveyor_id)
                   ->update([
                       'total_views' => $surveyoy_total_views,
                       'balance' => $surveyor->balance+$survey_available_amount
                    ]);

        $notify[] = ['success', 'Rejected Successfully'];
        return back()->withNotify($notify);
    }

    public function search(Request $request, $scope)
    {
        $search = $request->search;
        $surveys = Survey::whereHas('surveyor', function($q){
            $q->where('status',1);
        })->where(function($query) use($search){
            $query->whereHas('category', function($d) use($search) {
                $d->where('name', 'like', "%$search%");
            })->orWhere('name', 'like', "%$search%");
        });


        $page_title = '';

        switch ($scope) {
            case 'pending':
                $page_title .= 'Pending ';
                $surveys = $surveys->where('status', 1);
                break;
            case 'approved':
                $page_title .= 'Approved ';
                $surveys = $surveys->where('status', 0);
                break;
            case 'rejected':
                $page_title .= 'Rejected ';
                $surveys = $surveys->where('status', 3);
                break;
        }
        $surveys = $surveys->paginate(getPaginate());
        $page_title .= 'Survey Search - ' . $search;
        $empty_message = 'No search result found';
        return view('admin.survey.index', compact('page_title', 'search', 'scope', 'empty_message', 'surveys'));
    }

    public function questionAll($id)
    {
        $page_title = 'All Questions';
        $survey = Survey::findOrFail($id);
        $questions = $survey->questions()->paginate(getPaginate());
        $empty_message = 'No question found';
        return view('admin.survey.question', compact('page_title','survey','empty_message','questions'));
    }

    public function questionView($q_id,$s_id)
    {
        $question = Question::findOrFail($q_id);

        if ($question->survey_id != $s_id) {
            $notify[] = ['error', 'Something went Wrong  '];
            return back()->withNotify($notify);
        }

        $page_title = 'View Question';
        return view('admin.survey.view',compact('page_title','question'));
    }
}
