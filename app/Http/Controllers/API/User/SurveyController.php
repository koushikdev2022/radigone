<?php

namespace App\Http\Controllers\API\User;

use App\Answer;
use App\GeneralSetting;
use App\Http\Controllers\Controller;
use App\Question;
use App\Survey;
use App\Surveyor;
use App\Transaction;
use App\ViewSurvey;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SurveyController extends Controller
{
    public function surveyAvailable()
    {
        $info = ['success' => false, 'message' => __('Something went wrong!'), 'data' => null];

        $all_surveys = Survey::where('status',0)->where('schedule_ad', null)->with('surveyor')->whereHas('questions')->latest()->get();
        $schedule_surveys = Survey::where('status',0)->where('schedule_ad', '!=', null)->where('schedule_ad', '<=', Carbon::now()->format('d-m-Y'))->with('surveyor')->whereHas('questions')->latest()->get();
        $general = GeneralSetting::first();
        $user = auth()->guard('api')->user();

        $surveys = collect([]);

        foreach ($all_surveys as  $item) {

            $question_balance = ($item->per_user*$general->user_amount)/100 ;

            if ($item->surveyor->balance >= $question_balance) {

                if ($item->users) {
                    if(!in_array($user->id,$item->users)){
                        $surveys->push($item);
                    }

//                    if(in_array($user->id,$item->users)){
//                        if($item->repeated_viewers == 1) {
//                            $republish_item = $this->surveyRepublish($item->id);
//                            $surveys->push($republish_item);
//                        }else {
//
//                        }
//                    }


                }else{
                    $surveys->push($item);
                }
            }
        }

        foreach ($schedule_surveys as  $item) {

            $question_balance = ($item->per_user*$general->user_amount)/100 ;

            if ($item->surveyor->balance >= $question_balance) {

                if ($item->users) {
                    if(!in_array($user->id,$item->users)){
                        $surveys->push($item);
                    }

//                    if(in_array($user->id,$item->users)){
//                        if($item->repeated_viewers == 1) {
//                            $republish_item = $this->surveyRepublish($item->id);
//                            $surveys->push($republish_item);
//                        }else {
//
//                        }
//                    }

                }else{
                    $surveys->push($item);
                }
            }
        }

        $surveys =  $surveys->paginate(getPaginate());
        $custom = collect([
            'success' => 'true',
            'message' => null,
        ]);
        return $custom->merge($surveys);
    }

    public function surveyRepublish($id)
    {
        $srv = Survey::where('id', $id)->with('surveyor')->first();

        $survey_image = $srv->image;
        $general = GeneralSetting::first();
        $rdata = "";

        $survey_image = $srv->image;



        $survey = new Survey();
        $survey->image = $survey_image;
        $survey->surveyor_id = $srv->surveyor_id;
        // $survey->store_file=json_encode($store_file);
        $survey->category_id = $srv->category_id;
        $survey->p_name = $srv->p_name;
        $survey->p_specification=$srv->p_specification;
        $survey->p_mrp = (int)$srv->p_mrp;
        $survey->discount = (int)$srv->discount;
        $survey->required_data = $srv->required_data;
        $survey->offer_type = $srv->offer_type;
        $survey->total_views = (int)$srv->total_views;
        $survey->publish = (int)$srv->publish;
        $survey->target_market_category = $srv->target_market_category;
        $survey->total_slides = (int)$srv->total_slides;
        $survey->slides_time = (int)$srv->slides_time;
        $survey->repeated_viewers = (int)$srv->repeated_viewers;
        $survey->ad_duration = (int)$srv->ad_duration;
        $survey->online_purchase = (int)$srv->online_purchase;
        $survey->template = $srv->template;
        $survey->totalprice = $srv->totalprice;
        $survey->per_user = $srv->per_user;
        $survey->ad_type = $srv->ad_type;
        $survey->video_url = $srv->video_url;
        $survey->schedule_ad  = $srv->schedule_ad;
        $survey->purchas_url  = $srv->purchas_url;
        $survey->total_without_gst  = $srv->total_without_gst;
        $survey->status  = 0;


        $survey->save();

        $questions = Question::where('survey_id', $id)->get();
        foreach($questions as $q) {
            Question::create([
                'survey_id' => $survey->id,
                'question' => $q->question,
                'type' => $q->type,
                'custom_input' => $q->custom_input,
                'custom_input_type' => $q->custom_input_type,
                'custom_question' => $q->custom_question,
                'custombox' => $q->custombox,
                'answers' => $q->answers,
            ]);
        }


        return $survey;
    }

    public function surveyAdView($id)
    {
        $survey = Survey::whereId($id)->first();
        if(!is_null($survey)) {
            $s = [
                'id' => $survey->id,
                'ad_type' => $survey->ad_type,
                'per_user' => $survey->per_user,
                'reject_answer' => $survey->reject_answer,
                'p_name' => $survey->p_name,
                'p_specification' => $survey->p_specification,
                'p_mrp' => $survey->p_mrp,
                'discount' => $survey->discount,
                'required_data' => $survey->required_data,
                'offer_type' => $survey->offer_type,
                'total_views' => $survey->total_views,
                'publish' => $survey->publish,
                'target_market_category' => $survey->target_market_category,
                'total_slides' => $survey->total_slides,
                'slides_time' => $survey->slides_time,
                'ad_duration' => $survey->ad_duration,
                'online_purchase' => $survey->online_purchase,
                'store_file' => $survey->store_file,
                'category_id' => $survey->category_id,
                'image' => getImage(imagePath()['survey']['path'].'/'. $survey->image,imagePath()['survey']['size']),
                'age_limit' => $survey->age_limit,
                'repeated_viewers' => $survey->repeated_viewers,
                'country_limit' => $survey->country_limit,
                'start_age' => $survey->start_age,
                'end_age' => $survey->end_age,
                'country' => $survey->country,
                'template' => $survey->template,
                'status' => $survey->status,
                'users' => $survey->users,
                'video_url' => $survey->video_url,
                'schedule_ad' => $survey->schedule_ad,
                'purchas_url' => $survey->purchas_url,
            ];
            $user = auth()->guard('api')->user();
            if (count($survey->questions) <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No question is available for this ad',
                    'data' => null,
                ]);
            }

            if ($survey->users && $survey->repeated_viewers == 0) {

                if(in_array($user->id,$survey->users)){
                    return response()->json([
                        'success' => false,
                        'message' => 'You already participated on this',
                        'data' => null,
                    ]);
                }

            }

            if ($survey->age_limit == 1 && $survey->start_age && $survey->end_age) {
                if($user->age < $survey->start_age || $user->age > $survey->end_age){
                    return response()->json([
                        'success' => false,
                        'message' => 'This ad has age limit from ' .$survey->start_age. ' to ' .$survey->end_age,
                        'data' => null,
                    ]);
                }
            }

            if ($survey->country_limit == 1 && $survey->country) {
                if(!in_array($user->address->country,$survey->country)){
                    return response()->json([
                        'success' => false,
                        'message' => 'This ad is not available for your country',
                        'data' => null,
                    ]);
                }
            }
            return response()->json([
                'success' => true,
                'message' => null,
                'data' => $s,
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'No Ad Found!',
            'data' => null,
        ]);
    }

    public function surveyQuestions($id)
    {
        $survey = Survey::whereId($id)->with('questions')->first();
        if(!is_null($survey)) {
            $user = auth()->guard('api')->user();

            if (count($survey->questions) <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No question is available for this ad',
                    'data' => null,
                ]);
            }

            if ($survey->users && $survey->repeated_viewers == 0) {

                if(in_array($user->id,$survey->users)){
                    return response()->json([
                        'success' => false,
                        'message' => 'You already participated on this',
                        'data' => null,
                    ]);
                }

            }

            if ($survey->age_limit == 1 && $survey->start_age && $survey->end_age) {
                if($user->age < $survey->start_age || $user->age > $survey->end_age){
                    return response()->json([
                        'success' => false,
                        'message' => 'This ad has age limit from ' .$survey->start_age. ' to ' .$survey->end_age,
                        'data' => null,
                    ]);
                }
            }

            if ($survey->country_limit == 1 && $survey->country) {
                if(!in_array($user->address->country,$survey->country)){
                    return response()->json([
                        'success' => false,
                        'message' => 'This ad is not available for your country',
                        'data' => null,
                    ]);
                }
            }
            return response()->json([
                'success' => true,
                'message' => null,
                'data' => $survey->questions->map(function ($data) {
                    return [
                        'id' => $data->id,
                        'survey_id' => $data->survey_id,
                        'question' => $data->question,
                        'type' => $data->type,
                        'custom_input' => $data->custom_input,
                        'custombox' => $data->custombox,
                        'custom_input_type' => $data->custom_input_type,
                        'custom_question' => $data->custom_question,
                        'options' => $data->options,
                    ];
                }),
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'No Ad Found!',
            'data' => null,
        ]);
    }

    public function surveyQuestionsAnswers(Request $request, $id)
    {
        $validateUser = Validator::make($request->all(),
            [
                "answer" => "required|array|min:1",
                "answer.*" => "required_with:answer",
            ]);

        if($validateUser->fails()){
            return response()->json([
                'success' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        $survey = Survey::where('id',$id)->with('questions')->first();

        $user = auth()->guard('api')->user();

        if($survey->repeated_viewers == 1) {
            if ($survey->users) {

                if(!in_array($user->id,$survey->users)){
                    $survey_users = $survey->users;
                    array_push($survey_users,$user->id);
                    $survey->users = $survey_users;
                }

            }

            if(!$survey->users){
                $survey->users = [$user->id];
            }
        }else {
            if ($survey->users) {

                if(in_array($user->id,$survey->users)){
                    return response()->json([
                        'success' => false,
                        'message' => 'You already participated on this',
                        'data' => null,
                    ]);
                }

                if(!in_array($user->id,$survey->users)){
                    $survey_users = $survey->users;
                    array_push($survey_users,$user->id);
                    $survey->users = $survey_users;
                }

            }

            if(!$survey->users){
                $survey->users = [$user->id];
            }
        }

        ViewSurvey::create([
            'survey_id' => $survey->id,
            'user_id' => $user->id,
            'is_repeated' => $survey->repeated_viewers,
        ]);

        $answers = $request['answer'];

        foreach ($survey->questions as $item) {
            $surveyAns = @$answers[$item->id];

            if (!$surveyAns) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please answer all the questions',
                    'data' => null,
                ]);
            }

            //Custom input validation
            if ($item->custom_input == 1 && $item->custom_input_type == 1) {
                $cusInp = $surveyAns['c'];
                if (!$cusInp) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You missed input type answer',
                        'data' => null,
                    ]);
                }
            }

            //radio type validation
            if ($item->type == 1) {
                $radioAns = array_shift($surveyAns);

                if(!$radioAns){
                    return response()->json([
                        'success' => false,
                        'message' => 'You missed radio type answer',
                        'data' => null,
                    ]);
                }
                if(!empty($item->options)){
                    if(!in_array($radioAns,$item->options)){
                        return response()->json([
                            'success' => false,
                            'message' => 'Do not try to cheat us',
                            'data' => null,
                        ]);
                    }
                }

            }

            //checkbox validation
            if ($item->type == 2) {
                $checkBoxValue = $surveyAns;
                unset($checkBoxValue['c']);
                if(@count($checkBoxValue) == 0 || !$checkBoxValue){
                    return response()->json([
                        'success' => false,
                        'message' => 'You missed checkBox type answer',
                        'data' => null,
                    ]);
                }
                $diffAns = array_diff($checkBoxValue,$item->options);
                if(count($diffAns) > 0){
                    return response()->json([
                        'success' => false,
                        'message' => 'Do not try to cheat us',
                        'data' => null,
                    ]);
                }
            }

        }


        $surveyor = Surveyor::where('id',$survey->surveyor_id)->first();
        if (!$surveyor) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to answer this',
                'data' => null,
            ]);
        }

        $general = GeneralSetting::first();

        $answer_balance = $general->get_amount * count($answers);

        // if ($surveyor->balance < $answer_balance) {
        //     $notify[] = ['error', 'surveyor does not  have enough balance to pay your reward. Try another one'];
        //     return back()->withNotify($notify);
        // }

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to answer this',
                'data' => null,
            ]);
        }

        $survey->save();

        foreach ($answers as $key => $item) {
            $custom_ans = $item['c']??null;

            if($custom_ans){
                unset($item['c']);
            }

            $create_ans = new Answer();
            $create_ans->surveyor_id = $survey->surveyor->id;
            $create_ans->survey_id = $survey->id;
            $create_ans->user_id = $user->id;
            $create_ans->question_id = $key;
            $create_ans->answer = array_values($item);
            $qu = Question::where('id',$key)->first();

            if($qu->answers == $custom_ans){
                $create_ans->custom_answer_value = '1';
            }else{
                $create_ans->custom_answer_value = '0';
            }
            $create_ans->custom_answer = $custom_ans;



            $create_ans->save();
        }

        //$surveyor->balance -= $general->get_amount * count($answers);
        $surveyor->save();

        // $user->balance += $general->paid_amount * count($answers);
        $user->balance += ($survey->per_user*$general->user_amount)/100;
        $user->completed_survey += 1;
        $user->save();

        $useramount =($survey->per_user*$general->user_amount)/100;

        $as = Answer::where('survey_id',$survey->id)->sum('custom_answer_value');
//        if($as =='2'){
//            $transaction = new Transaction();
//            $transaction->user_id = $user->id;
//            $transaction->amount = getAmount($useramount);
//            //  $transaction->amount = getAmount($general->paid_amount * count($answers));
//            $transaction->post_balance = getAmount($user->balance);
//            $transaction->trx_type = '+';
//            $transaction->details = 'For Completing ' . $survey->name;
//            $transaction->trx =  getTrx();
//            $transaction->save();
//        }
        $transaction1 = new Transaction();
        $transaction1->user_id = $user->id;
        $transaction1->amount = getAmount($useramount);
        //  $transaction1->amount = getAmount($general->paid_amount * count($answers));
        $transaction1->post_balance = getAmount($user->balance);
        $transaction1->trx_type = '+';
        $transaction1->details = 'For Completing ' . $survey->p_name;
        $transaction1->trx =  getTrx();
        $transaction1->save();

        $transaction = new Transaction();
        $transaction->admin_id = '1';
        $transaction->amount = getAmount($survey->per_user - $useramount );
        $transaction->post_balance = getAmount('0');
        $transaction->trx_type = '+';
        $transaction->details = 'For Get Answerd ' . $survey->name;
        $transaction->trx =  getTrx();
        $transaction->save();



        notify($user, 'SURVEY_COMPLETED', [
            'survey_name' => $survey->name,
            'amount' => getAmount($general->paid_amount * count($answers)),
            'currency' => $general->cur_text,
            'post_balance' => getAmount($user->balance)
        ]);

        notify($surveyor, 'SURVEY_ANSWERD', [
            'survey_name' => $survey->name,
            'total_question' => count($answers),
            'charge' => getAmount($general->get_amount),
            'amount' => getAmount($general->get_amount * count($answers)),
            'currency' => $general->cur_text,
            'post_balance' => getAmount($surveyor->balance)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ad View Completed',
            'data' => null,
        ]);
    }
}
