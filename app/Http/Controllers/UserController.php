<?php

namespace App\Http\Controllers;

use App\Answer;
use App\FavoriteSurvey;
use App\GeneralSetting;
use App\Lib\GoogleAuthenticator;
use App\Question;
use App\Category;
use App\Survey;
use App\Surveyor;
use App\User;
use App\SurveyorBlockList;
use App\Transaction;
use App\ViewSurvey;
use App\WithdrawMethod;
use App\Withdrawal;
use App\TargetMarket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Image;
use Validator;
ini_set('memory_limit', '44M');

class UserController extends Controller
{
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }

    public function home()
    {
        
        //return 'hrllo';
        $page_title = 'Dashboard';
        $user = auth()->user();
       
        // $user_dob = auth()->user()->dob;
        // $newformat = strtotime($user_dob);
        // $monthnow = date('m',$newformat);
        $totalWithdraw = Withdrawal::where('user_id',$user->id)->where('status',1)->sum('amount');
        $totalTransaction = Transaction::where('user_id',$user->id)->count();

        $withdraw['month'] = collect([]);
        $withdraw['amount'] = collect([]);

        $withdraw_chart = Withdrawal::where('user_id',$user->id)->where('status',1)->whereYear('created_at', '=', date('Y'))->orderBy('created_at')->groupBy(DB::Raw("MONTH(created_at)"))->get();

        $withdraw_chart_data = $withdraw_chart->map(function ($query) use ($withdraw) {
            $withdraw['month'] = $query->created_at->format('F');
            $withdraw['amount'] = $query->where('status',1)->whereMonth('created_at',$query->created_at)->sum('amount');
            return $withdraw;
        });
         $cat_arr = [];
                    $preferences = DB::table('preferences')->where('user_id', Auth::id())->get();
                    foreach ($preferences as $pref) {
                        array_push($cat_arr, $pref->preferences_name);
                    }
        //here servey
        
        $page_title = 'View Ads';
        $block_array = [];
        $block_list = SurveyorBlockList::where('user_id', auth()->id())->get();
        foreach($block_list as $bl) {
            array_push($block_array, $bl->surveyor_id);
        }
       
     // $all_surveys = Survey::where('status',0)->where('schedule_ad', null)->with('surveyor', 'adtype')->whereHas('questions')->whereIn('target_market_category', $cat_arr)->whereNotIn('surveyor_id', $block_array)->latest()->get();
         $all_surveys = Survey::where('status',0)->where('schedule_ad', null)->with('surveyor', 'adtype')->whereHas('questions')->whereNotIn('surveyor_id', $block_array)->where(function($q){
            $q->where(function($q){
                $q->whereNull('target_market_category');
                  
            })->orWhere(function($q){
                 $cat_arr = [];
                    $preferences = DB::table('preferences')->where('user_id', Auth::id())->get();
                    foreach ($preferences as $pref) {
                        array_push($cat_arr, $pref->preferences_name);
                    }
                $q->whereIn('target_market_category',$cat_arr);
                  
            });
        })->latest()->get();
        // $category_data = Category::pluck('name');
        // // $target_surveys = Survey::with('targetMarket')->where('id',151)->get();
        // //dd($category_data);
        // $target_surveys = Survey::with('targetMarket')->where('status',0)->where('schedule_ad', null)->with('surveyor', 'adtype')->whereHas('questions')->whereNotIn('surveyor_id', $block_array)->where(function($q) use($category_data){
        //     $q->whereNotIn('target_market_category',$category_data);
        //     // $q->where(function($tm){
        //     //     if($tm->targetMarket->target_market_name == 'pincode'){
        //     //         $tm->whereJsonContains('to', [['address' => ['zip' => $tm->targetMarket->target_market_value]]]);
        //     //     }
        //     // });
        // })->latest()->get();
        //  $filter_array = []; 
        //  foreach ($target_surveys as $tm){
        //       $addr = json_decode(json_encode(auth()->user()->address),true);
        //          // dd($addr);
        //      if($tm->targetMarket->target_market_name == 'pincode'){
        //          $zip = $addr['zip'];
        //          if($tm->targetMarket->target_market_value == $zip){
        //              array_push($filter_array, $tm);
        //          }
        //      }elseif($tm->targetMarket->target_market_name == 'state'){
        //          $state = $addr['state'];
        //          if($tm->targetMarket->target_market_value == $state){
        //              array_push($filter_array, $tm);
        //          }
        //      }elseif($tm->targetMarket->target_market_name == 'country'){
        //          $country = $addr['country'];
        //          if($tm->targetMarket->target_market_value == $country){
        //              array_push($filter_array, $tm);
        //          }
        //      }elseif($tm->targetMarket->target_market_name == 'city'){
        //          $city = $addr['city'];
        //          if($tm->targetMarket->target_market_value ==  $city ){
        //              array_push($filter_array, $tm);
        //          }
        //      }elseif($tm->targetMarket->target_market_name == 'gender'){
        //           $gender = auth()->user()->gander;
        //          if($tm->targetMarket->target_market_value ==  $gender ){
        //              array_push($filter_array, $tm);
        //          }
        //      }elseif($tm->targetMarket->target_market_name == 'annual-income'){
        //           $annualincome = auth()->user()->annual_income;
        //          if($tm->targetMarket->target_market_value ==  $annualincome ){
        //              array_push($filter_array, $tm);
        //          }
        //      }elseif($tm->targetMarket->target_market_name == 'marital-status'){
        //           $marital = auth()->user()->marital;
        //          if($tm->targetMarket->target_market_value ==   $marital ){
        //              array_push($filter_array, $tm);
        //          }
        //      }elseif($tm->targetMarket->target_market_name == 'profession'){
        //           $occupation = auth()->user()->occupation;
        //          if($tm->targetMarket->target_market_value ==   $occupation ){
        //              array_push($filter_array, $tm);
        //          }
        //      }elseif($tm->targetMarket->target_market_name == 'anniversary'){
        //          $anniversary = auth()->user()->anniversary_date;
        //          //dd($anniversary);
        //           $time = strtotime($anniversary);
        //           $newformat = date('m',$time);
        //           //dd($newformat);
        //           $timetarget=strtotime($tm->targetMarket->target_market_value);
        //           $newformattarget = date('m',$timetarget);
        //           if($newformat == $newformattarget){
        //               array_push($filter_array, $tm);
        //           }
        //      }elseif($tm->targetMarket->target_market_name == 'birthday'){
        //          $birthday = auth()->user()->dob;
        //          //dd($anniversary);
        //           $time = strtotime($birthday);
        //           $newformat = date('m',$time);
        //           //dd($newformat);
        //           $timetarget=strtotime($tm->targetMarket->target_market_value);
        //           $newformattarget = date('m',$timetarget);
        //           if($newformat == $newformattarget){
        //               array_push($filter_array, $tm);
        //           }
        //      }elseif($tm->targetMarket->target_market_name == 'age'){
        //          $age = auth()->user()->age;
        //          $target_age = $tm->targetMarket->target_market_value;
        //          $age_array = array_map('intval',explode('-',$target_age)) ;
                
        //          $start = $age_array[0];
             
        //          $end=$age_array[1];
        //          $age_collection = [];
        //           for($i = $start;$i <= $end;$i++){
                    
        //              array_push($age_collection, $i);
        //           }
                  
        //           if(in_array( $age, $age_collection ) == true){
        //               array_push($filter_array, $tm);
        //           }
        //      }
        //  }
          $category_data = Category::pluck('name');
       
        $target_surveys = Survey::with('targetMarket')->where('status',0)->where('schedule_ad', null)->with('surveyor', 'adtype')->whereHas('questions')->whereNotIn('surveyor_id', $block_array)->where(function($q) use($category_data){
            $q->whereNotIn('target_market_category',$category_data);
           
        })->latest()->get();
         $filter_array = []; 
         foreach ($target_surveys as $tm){
              $addr = json_decode(json_encode(auth()->user()->address),true);
                 // dd($addr);
                // $array_name_export = explode(',',$tm->targetMarket->target_market_name);
                  $array_name_export = [];
                 $array_name_export_json = json_decode($tm->targetMarket->target_market_name);
                 foreach($array_name_export_json as $array_json){
                      $array_name_export[$array_json->name]=$array_json->value;
                 }
                 
                 if(($key = array_search('name',$array_name_export))  !== false){
                     unset($array_name_export[$key]);
                 }
                 if(($key = array_search('email',$array_name_export))  !== false){
                     unset($array_name_export[$key]);
                 }
                  if(($key = array_search('whatsapp',$array_name_export))  !== false){
                     unset($array_name_export[$key]);
                 }
                  if(($key = array_search('mobile',$array_name_export))  !== false){
                     unset($array_name_export[$key]);
                 }
               
                 
                  //dd($array_value_export);
                  $combine_array = $array_name_export;
                  //dd($combine_array);
                  $age_collection = [];
                  if(!empty($combine_array['age'])){
                     $age_array = array_map('intval',explode('-',$combine_array['age'])) ;
                
                      $start = $age_array[0];
             
                      $end=$age_array[1];
                      $age_collection = [];
                      for($i = $start;$i <= $end;$i++){
                        
                         array_push($age_collection, $i);
                      }
                      if (in_array(auth()->user()->age, $age_collection))
                      {
                        $combine_array =  array_merge($combine_array, array("age"=>auth()->user()->age));
                      }
                  }
                  if(!empty($combine_array['anniversary'])){
                        $time_combine_array = strtotime($combine_array['anniversary']);
                        $newformat_combine_array = date('m',$time_combine_array);
                        unset($combine_array['anniversary']);
                        //array_push($combine_array);
                        $combine_array =  array_merge($combine_array, array("anniversary"=>$newformat_combine_array));
                  }
                  if(!empty($combine_array['birthday'])){
                        $time_combine_array_b = strtotime($combine_array['birthday']);
                        $newformat_combine_array_b = date('m',$time_combine_array_b);
                        unset($combine_array['birthday']);
                        //array_push($combine_array);
                        $combine_array =  array_merge($combine_array, array("birthday"=>$newformat_combine_array_b));
                  }
                 
                  $addr = json_decode(json_encode(auth()->user()->address),true);
                  $anniversary = auth()->user()->anniversary_date;
                  if(!empty($anniversary)){
                        $time = strtotime($anniversary);
                        $newformat = date('m',$time);
                  }else{
                      $newformat = '';
                  }
                
                   $birthday = auth()->user()->dob;
                 
                  
                  if(!empty($anniversary)){
                       $time_birthday = strtotime($birthday);
                       $newformat_birthday = date('m',$time_birthday);
                  }else{
                      $newformat_birthday = '';
                  }
                
                  $making_array =array("pincode" => $addr['zip'],
                                       "country"=>$addr['country'],
                                       "profession"=>auth()->user()->occupation,
                                       "city" => $addr['city'],
                                       "gender" => auth()->user()->gander,
                                       "annual-income"=>auth()->user()->annual_income,
                                       "marital-status"=>auth()->user()->marital,
                                       "anniversary"=>$newformat,
                                       "state" => $addr['state'],
                                        "birthday"=>$newformat_birthday,
                                        "age"=>auth()->user()->age,
                                      );
                
                 $checking_list_array_to_match = array_intersect_key($making_array,$combine_array);
                
                 $difference_array = array_diff_assoc($combine_array, $checking_list_array_to_match);
                
                  if (count($difference_array) == 0) {
                          array_push($filter_array, $tm);
                      } 
         }
        //$schedule_surveys = Survey::where('status',0)->where('schedule_ad', '!=', null)->where('schedule_ad', '<=', Carbon::now()->format('d-m-Y'))->with('surveyor', 'adtype')->whereHas('questions')->whereIn('target_market_category', $cat_arr)->whereNotIn('surveyor_id', $block_array)->latest()->get();
        $schedule_surveys = Survey::where('status',0)->where('schedule_ad', '!=', null)->where('schedule_ad', '<=', Carbon::now()->format('d-m-Y'))->with('surveyor', 'adtype')->whereHas('questions')->whereNotIn('surveyor_id', $block_array)->where(function($q){
            $q->where(function($q){
                $q->whereNull('target_market_category');
                  
            })->orWhere(function($q){
                 $cat_arr = [];
                    $preferences = DB::table('preferences')->where('user_id', Auth::id())->get();
                    foreach ($preferences as $pref) {
                        array_push($cat_arr, $pref->preferences_name);
                    }
                $q->whereIn('target_market_category',$cat_arr);
                  
            });
        })->latest()->get();
         $category_data = Category::pluck('name');
        // $target_surveys = Survey::with('targetMarket')->where('id',151)->get();
        //dd($category_data);
        $target_surveys_sdl = Survey::with('targetMarket')->where('status',0)->where('schedule_ad', '!=', null)->where('schedule_ad', '<=', Carbon::now()->format('d-m-Y'))->with('surveyor', 'adtype')->whereHas('questions')->whereNotIn('surveyor_id', $block_array)->where(function($q) use($category_data){
            $q->whereNotIn('target_market_category',$category_data);
            // $q->where(function($tm){
            //     if($tm->targetMarket->target_market_name == 'pincode'){
            //         $tm->whereJsonContains('to', [['address' => ['zip' => $tm->targetMarket->target_market_value]]]);
            //     }
            // });
        })->latest()->get();
         $filter_array_sdl = []; 
          //$filter_array = []; 
         foreach ($target_surveys_sdl as $tm){
              $addr = json_decode(json_encode(auth()->user()->address),true);
                 // dd($addr);
                // $array_name_export = explode(',',$tm->targetMarket->target_market_name);
                  $array_name_export = [];
                 $array_name_export_json = json_decode($tm->targetMarket->target_market_name);
                 foreach($array_name_export_json as $array_json){
                      $array_name_export[$array_json->name]=$array_json->value;
                 }
                 
                 if(($key = array_search('name',$array_name_export))  !== false){
                     unset($array_name_export[$key]);
                 }
                 if(($key = array_search('email',$array_name_export))  !== false){
                     unset($array_name_export[$key]);
                 }
                  if(($key = array_search('whatsapp',$array_name_export))  !== false){
                     unset($array_name_export[$key]);
                 }
                  if(($key = array_search('mobile',$array_name_export))  !== false){
                     unset($array_name_export[$key]);
                 }
               
                 
                  //dd($array_value_export);
                  $combine_array = $array_name_export;
                  //dd($combine_array);
                  $age_collection = [];
                  if(!empty($combine_array['age'])){
                     $age_array = array_map('intval',explode('-',$combine_array['age'])) ;
                
                      $start = $age_array[0];
             
                      $end=$age_array[1];
                      $age_collection = [];
                      for($i = $start;$i <= $end;$i++){
                        
                         array_push($age_collection, $i);
                      }
                      if (in_array(auth()->user()->age, $age_collection))
                      {
                        $combine_array =  array_merge($combine_array, array("age"=>auth()->user()->age));
                      }
                  }
                  if(!empty($combine_array['anniversary'])){
                        $time_combine_array = strtotime($combine_array['anniversary']);
                        $newformat_combine_array = date('m',$time_combine_array);
                        unset($combine_array['anniversary']);
                        //array_push($combine_array);
                        $combine_array =  array_merge($combine_array, array("anniversary"=>$newformat_combine_array));
                  }
                  if(!empty($combine_array['birthday'])){
                        $time_combine_array_b = strtotime($combine_array['birthday']);
                        $newformat_combine_array_b = date('m',$time_combine_array_b);
                        unset($combine_array['birthday']);
                        //array_push($combine_array);
                        $combine_array =  array_merge($combine_array, array("birthday"=>$newformat_combine_array_b));
                  }
                 
                  $addr = json_decode(json_encode(auth()->user()->address),true);
                  $anniversary = auth()->user()->anniversary_date;
                  if(!empty($anniversary)){
                        $time = strtotime($anniversary);
                        $newformat = date('m',$time);
                  }else{
                      $newformat = '';
                  }
                
                   $birthday = auth()->user()->dob;
                 
                  
                  if(!empty($anniversary)){
                       $time_birthday = strtotime($birthday);
                       $newformat_birthday = date('m',$time_birthday);
                  }else{
                      $newformat_birthday = '';
                  }
                
                  $making_array =array("pincode" => $addr['zip'],
                                       "country"=>$addr['country'],
                                       "profession"=>auth()->user()->occupation,
                                       "city" => $addr['city'],
                                       "gender" => auth()->user()->gander,
                                       "annual-income"=>auth()->user()->annual_income,
                                       "marital-status"=>auth()->user()->marital,
                                       "anniversary"=>$newformat,
                                       "state" => $addr['state'],
                                        "birthday"=>$newformat_birthday,
                                        "age"=>auth()->user()->age,
                                      );
                
                 $checking_list_array_to_match = array_intersect_key($making_array,$combine_array);
                
                 $difference_array = array_diff_assoc($combine_array, $checking_list_array_to_match);
                
                  if (count($difference_array) == 0) {
                          array_push($filter_array_sdl, $tm);
                      } 
         }
        $general = GeneralSetting::first();

        $surveys = collect([]);
        foreach ($filter_array as  $item) {

            $question_balance = ($item->per_user*$general->user_amount)/100 ;

            if ($item->surveyor->balance >= $question_balance) {

                if ($item->users) {
                    if(!in_array(auth()->user()->id,$item->users)){
                        $surveys->push($item);
                    }

//                    if(in_array(auth()->user()->id,$item->users)){
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
        foreach ($filter_array_sdl as  $item) {

            $question_balance = ($item->per_user*$general->user_amount)/100 ;

            if ($item->surveyor->balance >= $question_balance) {

                if ($item->users) {
                    if(!in_array(auth()->user()->id,$item->users)){
                        $surveys->push($item);
                    }

//                    if(in_array(auth()->user()->id,$item->users)){
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

        foreach ($all_surveys as  $item) {

            $question_balance = ($item->per_user*$general->user_amount)/100 ;

            if ($item->surveyor->balance >= $question_balance) {

                if ($item->users) {
                    if(!in_array(auth()->user()->id,$item->users)){
                        $surveys->push($item);
                    }

//                    if(in_array(auth()->user()->id,$item->users)){
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
                    if(!in_array(auth()->user()->id,$item->users)){
                        $surveys->push($item);
                    }

//                    if(in_array(auth()->user()->id,$item->users)){
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

        $empty_message = 'No data found';
        

        return view($this->activeTemplate . 'user.dashboard', compact('page_title','user','totalWithdraw','totalTransaction','withdraw_chart_data','surveys', 'empty_message'));
    }
    public function radigone_point()
    {
     
        //return 'hrllo';
        $page_title = 'Radigone Point';
        $user = auth()->user();
       
        // $user_dob = auth()->user()->dob;
        // $newformat = strtotime($user_dob);
        // $monthnow = date('m',$newformat);
        $totalWithdraw = Withdrawal::where('user_id',$user->id)->where('status',1)->sum('amount');
        $totalTransaction = Transaction::where('user_id',$user->id)->count();

        $withdraw['month'] = collect([]);
        $withdraw['amount'] = collect([]);

        $withdraw_chart = Withdrawal::where('user_id',$user->id)->where('status',1)->whereYear('created_at', '=', date('Y'))->orderBy('created_at')->groupBy(DB::Raw("MONTH(created_at)"))->get();

        $withdraw_chart_data = $withdraw_chart->map(function ($query) use ($withdraw) {
            $withdraw['month'] = $query->created_at->format('F');
            $withdraw['amount'] = $query->where('status',1)->whereMonth('created_at',$query->created_at)->sum('amount');
            return $withdraw;
        });
         $cat_arr = [];
                    $preferences = DB::table('preferences')->where('user_id', Auth::id())->get();
                    foreach ($preferences as $pref) {
                        array_push($cat_arr, $pref->preferences_name);
                    }
        //here servey
        
        $page_title = 'View Ads';
        $block_array = [];
        $block_list = SurveyorBlockList::where('user_id', auth()->id())->get();
        foreach($block_list as $bl) {
            array_push($block_array, $bl->surveyor_id);
        }
       
      //$all_surveys = Survey::where('status',0)->where('schedule_ad', null)->with('surveyor', 'adtype')->whereHas('questions')->whereIn('target_market_category', $cat_arr)->whereNotIn('surveyor_id', $block_array)->latest()->get();
         $all_surveys = Survey::where('status',0)->where('schedule_ad', null)->with('surveyor', 'adtype')->whereHas('questions')->whereNotIn('surveyor_id', $block_array)->where(function($q){
            $q->where(function($q){
                $q->whereNull('target_market_category');
                  
            })->orWhere(function($q){
                 $cat_arr = [];
                    $preferences = DB::table('preferences')->where('user_id', Auth::id())->get();
                    foreach ($preferences as $pref) {
                        array_push($cat_arr, $pref->preferences_name);
                    }
                $q->whereIn('target_market_category',$cat_arr);
                  
            });
        })->latest()->get();
        
        //$schedule_surveys = Survey::where('status',0)->where('schedule_ad', '!=', null)->where('schedule_ad', '<=', Carbon::now()->format('d-m-Y'))->with('surveyor', 'adtype')->whereHas('questions')->whereIn('target_market_category', $cat_arr)->whereNotIn('surveyor_id', $block_array)->latest()->get();
        $schedule_surveys = Survey::where('status',0)->where('schedule_ad', '!=', null)->where('schedule_ad', '<=', Carbon::now()->format('d-m-Y'))->with('surveyor', 'adtype')->whereHas('questions')->whereNotIn('surveyor_id', $block_array)->where(function($q){
            $q->where(function($q){
                $q->whereNull('target_market_category');
                  
            })->orWhere(function($q){
                 $cat_arr = [];
                    $preferences = DB::table('preferences')->where('user_id', Auth::id())->get();
                    foreach ($preferences as $pref) {
                        array_push($cat_arr, $pref->preferences_name);
                    }
                $q->whereIn('target_market_category',$cat_arr);
                  
            });
        })->latest()->get();
        $general = GeneralSetting::first();

        $surveys = collect([]);

        foreach ($all_surveys as  $item) {

            $question_balance = ($item->per_user*$general->user_amount)/100 ;

            if ($item->surveyor->balance >= $question_balance) {

                if ($item->users) {
                    if(!in_array(auth()->user()->id,$item->users)){
                        $surveys->push($item);
                    }

//                    if(in_array(auth()->user()->id,$item->users)){
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
                    if(!in_array(auth()->user()->id,$item->users)){
                        $surveys->push($item);
                    }

//                    if(in_array(auth()->user()->id,$item->users)){
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

        $empty_message = 'No data found';
        

        return view($this->activeTemplate . 'user.radigone_point', compact('page_title','user','totalWithdraw','totalTransaction','withdraw_chart_data','surveys', 'empty_message'));
    }

    public function profile()
    {
        $data['page_title'] = "Profile Setting";
        $data['user'] = Auth::user();
        //return $data;
             $maximumPoints  = 100;

             if($data['user']['firstname']!="" && $data['user']['lastname']!=""){
                $hasfirstnamelastname = 15;

             }else{
                 $hasfirstnamelastname = 0;
             }

             if($data['user']['email']!=""){
                $email = 10;

             }else{
                 $email = 0;
             }

            if($data['user']['whatsaap']!="" && $data['user']['mobile']!=""){
                $haswhatsaapmobile = 15;

             }else{
                 $haswhatsaapmobile = 0;
             }
             if($data['user']['gander']!="" && $data['user']['dob']!=""){
                $ganderdob = 10;

             }else{
                 $ganderdob = 0;
             }


             if($data['user']['occupation']!="" && $data['user']['annual_income']!=""){
                $occupationannual_income = 15;

             }else{
                 $occupationannual_income = 0;
             }


             if($data['user']['pan']!="" && $data['user']['account_number']!="" && $data['user']['bank_ifsc']!=""){
                $panaccount_number = 25;

             }else{
                 $panaccount_number = 0;
             }

//             if($data['user']['age']!="" && $data['user']['address']!=""){
//                $ageaddress = 10;
//
//             }else{
//                 $ageaddress = 0;
//             }

             if($data['user']['address']!=""){
                $ageaddress = 10;

             }else{
                 $ageaddress = 0;
             }

           $percentage = ($hasfirstnamelastname+$email+$haswhatsaapmobile+$ganderdob+$occupationannual_income+$panaccount_number+$ageaddress)*$maximumPoints/100;

             $user = Auth::user();


             $in['percentage'] = $percentage;
             $user->fill($in)->save();
           return view($this->activeTemplate. 'user.profile-setting', $data);
    }

    public function submitProfile(Request $request)
    {

        $request->validate([
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

        $user = Auth::user();


        $in['firstname'] = !is_null($user->firstname) ? $user->firstname :  $request->firstname;
        $in['lastname'] = !is_null($user->lastname) ? $user->lastname :  $request->lastname;

        $in['profession'] = !is_null($user->profession) ? $user->profession :  $request->profession;
        $in['dob'] = !is_null($user->dob) ? $user->dob :  $request->dob;
        $in['occupation'] = !is_null($user->occupation) ? $user->occupation :  $request->occupation;
        $in['anniversary_date'] = !is_null($user->anniversary_date) ? $user->anniversary_date :  $request->anniversary_date;
        $in['annual_income'] = !is_null($user->annual_income) ? $user->annual_income :  $request->annual_income;
        $in['pan'] = !is_null($user->pan) ? $user->pan :  $request->pan;
        $in['account_number'] = !is_null($user->account_number) ? $user->account_number :  $request->account_number;
        $in['re_account_number'] = !is_null($user->re_account_number) ? $user->re_account_number :  $request->re_account_number;
        $in['bank_ifsc'] = !is_null($user->bank_ifsc) ? $user->bank_ifsc :  $request->bank_ifsc;

         $in['whatsaap'] = !is_null($user->whatsapp) ? $user->whatsapp :  $request->whatsaap;
          $in['gander'] = !is_null($user->gander) ? $user->gander :  $request->gander;
           $in['marital'] = !is_null($user->martial) ? $user->martial :  $request->marital;



        $in['address'] = [
            'address' => !is_null($user->address->address) ? $user->address->address :  $request->address,
            'state' => !is_null($user->address->state) ? $user->address->state :  $request->state,
            'zip' => !is_null($user->address->zip) ? $user->address->zip :  $request->zip,
            'country' => !is_null($user->address->country) ? $user->address->country :  $user->address->country,
            'city' => !is_null($user->address->city) ? $user->address->city :  $request->city,
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
        $notify[] = ['success', 'Profile Updated successfully.'];
        return back()->withNotify($notify);
    }

    public function changePassword()
    {
        $data['page_title'] = "CHANGE PASSWORD";
        return view($this->activeTemplate . 'user.password', $data);
    }

    public function submitPassword(Request $request)
    {

        $this->validate($request, [
            'current_password' => 'required',
            'password' => [
            'required',
            'min:6',             // must be at least 10 characters in length
            'regex:/[a-z]/',      // must contain at least one lowercase letter
            'regex:/[A-Z]/',      // must contain at least one uppercase letter
            'regex:/[0-9]/',      // must contain at least one digit
            'regex:/[@$!%*#?&]/', // must contain a special character
        ],
        ]);
        try {
            $user = auth()->user();
            if (Hash::check($request->current_password, $user->password)) {
                $password = Hash::make($request->password);
                $user->password = $password;
                $user->save();
                $notify[] = ['success', 'Password Changes successfully.'];
                return back()->withNotify($notify);
            } else {
                $notify[] = ['error', 'Current password not match.'];
                return back()->withNotify($notify);
            }
        } catch (\PDOException $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }
    }

    /*
     * Deposit History
     */
    public function depositHistory()
    {
        $page_title = 'Deposit History';
        $empty_message = 'No history found.';
        $logs = auth()->user()->deposits()->with(['gateway'])->latest()->paginate(getPaginate());
        return view($this->activeTemplate . 'user.deposit_history', compact('page_title', 'empty_message', 'logs'));
    }

    /*
     * Withdraw Operation
     */

    public function withdrawMoney()
    {
        $data['withdrawMethod'] = WithdrawMethod::whereStatus(1)->get();
        $data['page_title'] = "Withdraw Money";
        return view(activeTemplate() . 'user.withdraw.methods', $data);
    }

    public function withdrawStore(Request $request)
    {
        $this->validate($request, [
            'method_code' => 'required',
            'amount' => 'required|numeric'
        ]);
        $method = WithdrawMethod::where('id', $request->method_code)->where('status', 1)->firstOrFail();
        $user = auth()->user();
        if ($request->amount < $method->min_limit) {
            $notify[] = ['error', 'Your Requested Amount is Smaller Than Minimum Amount.'];
            return back()->withNotify($notify);
        }
        if ($request->amount > $method->max_limit) {
            $notify[] = ['error', 'Your Requested Amount is Larger Than Maximum Amount.'];
            return back()->withNotify($notify);
        }

        if ($request->amount > $user->balance) {
            $notify[] = ['error', 'Your do not have Sufficient Balance For Withdraw.'];
            return back()->withNotify($notify);
        }


        $charge = $method->fixed_charge + ($request->amount * $method->percent_charge / 100);
        $afterCharge = $request->amount - $charge;
        $finalAmount = getAmount($afterCharge * $method->rate);

        $withdraw = new Withdrawal();
        $withdraw->method_id = $method->id; // wallet method ID
        $withdraw->user_id = $user->id;
        $withdraw->amount = getAmount($request->amount);
        $withdraw->currency = $method->currency;
        $withdraw->rate = $method->rate;
        $withdraw->charge = $charge;
        $withdraw->final_amount = $finalAmount;
        $withdraw->after_charge = $afterCharge;
        $withdraw->trx = getTrx();
        $withdraw->save();
        session()->put('wtrx', $withdraw->trx);
        return redirect()->route('user.withdraw.preview');
    }

    public function withdrawPreview()
    {
        $data['withdraw'] = Withdrawal::with('method','user')->where('trx', session()->get('wtrx'))->where('status', 0)->latest()->firstOrFail();
        $data['page_title'] = "Withdraw Preview";
        return view($this->activeTemplate . 'user.withdraw.preview', $data);
    }


    public function withdrawSubmit(Request $request)
    {
        $general = GeneralSetting::first();
        $withdraw = Withdrawal::with('method','user')->where('trx', session()->get('wtrx'))->where('status', 0)->latest()->firstOrFail();

        $rules = [];
        $inputField = [];
        if ($withdraw->method->user_data != null) {
            foreach ($withdraw->method->user_data as $key => $cus) {
                $rules[$key] = [$cus->validation];
                if ($cus->type == 'file') {
                    array_push($rules[$key], 'image');
                    array_push($rules[$key], 'mimes:jpeg,jpg,png');
                    array_push($rules[$key], 'max:2048');
                }
                if ($cus->type == 'text') {
                    array_push($rules[$key], 'max:191');
                }
                if ($cus->type == 'textarea') {
                    array_push($rules[$key], 'max:300');
                }
                $inputField[] = $key;
            }
        }
        $this->validate($request, $rules);
        $user = auth()->user();

        if (getAmount($withdraw->amount) > $user->balance) {
            $notify[] = ['error', 'Your Request Amount is Larger Then Your Current Balance.'];
            return back()->withNotify($notify);
        }

        $directory = date("Y")."/".date("m")."/".date("d");
        $path = imagePath()['verify']['withdraw']['path'].'/'.$directory;
        $collection = collect($request);
        $reqField = [];
        if ($withdraw->method->user_data != null) {
            foreach ($collection as $k => $v) {
                foreach ($withdraw->method->user_data as $inKey => $inVal) {
                    if ($k != $inKey) {
                        continue;
                    } else {
                        if ($inVal->type == 'file') {
                            if ($request->hasFile($inKey)) {
                                try {
                                    $reqField[$inKey] = [
                                        'field_name' => $directory.'/'.uploadImage($request[$inKey], $path),
                                        'type' => $inVal->type,
                                    ];
                                } catch (\Exception $exp) {
                                    $notify[] = ['error', 'Could not upload your ' . $request[$inKey]];
                                    return back()->withNotify($notify)->withInput();
                                }
                            }
                        } else {
                            $reqField[$inKey] = $v;
                            $reqField[$inKey] = [
                                'field_name' => $v,
                                'type' => $inVal->type,
                            ];
                        }
                    }
                }
            }
            $withdraw['withdraw_information'] = $reqField;
        } else {
            $withdraw['withdraw_information'] = null;
        }


        $withdraw->status = 2;
        $withdraw->save();
        $user->balance  -=  $withdraw->amount;
        $user->save();

        $transaction = new Transaction();
        $transaction->user_id = $withdraw->user_id;
        $transaction->amount = getAmount($withdraw->amount);
        $transaction->post_balance = getAmount($user->balance);
        $transaction->charge = getAmount($withdraw->charge);
        $transaction->trx_type = '-';
        $transaction->details = getAmount($withdraw->final_amount) . ' ' . $withdraw->currency . ' Withdraw Via ' . $withdraw->method->name;
        $transaction->trx =  $withdraw->trx;
        $transaction->save();

        notify($user, 'WITHDRAW_REQUEST', [
            'method_name' => $withdraw->method->name,
            'method_currency' => $withdraw->currency,
            'method_amount' => getAmount($withdraw->final_amount),
            'amount' => getAmount($withdraw->amount),
            'charge' => getAmount($withdraw->charge),
            'currency' => $general->cur_text,
            'rate' => getAmount($withdraw->rate),
            'trx' => $withdraw->trx,
            'post_balance' => getAmount($user->balance),
            'delay' => $withdraw->method->delay
        ]);

        $notify[] = ['success', 'Withdraw Request Successfully Send'];
        return redirect()->route('user.withdraw.history')->withNotify($notify);
    }

    public function withdrawLog()
    {
        $data['page_title'] = "Withdraw Log";
        $data['withdraws'] = Withdrawal::where('user_id', Auth::id())->where('status', '!=', 0)->with('method')->latest()->paginate(getPaginate());
        $data['empty_message'] = "No Data Found!";
        return view($this->activeTemplate.'user.withdraw.log', $data);
    }

    public function transaction()
    {
        $page_title = 'Successful Transaction Logs';
        $transactions = Transaction::where('user_id',Auth::id())->orderBy('id','desc')->paginate(getPaginate());
        $empty_message = 'No transactions.';
        return view($this->activeTemplate.'user.transaction', compact('page_title', 'transactions', 'empty_message'));
    }


    public function show2faForm()
    {
        $gnl = GeneralSetting::first();
        $ga = new GoogleAuthenticator();
        $user = auth()->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . $gnl->sitename, $secret);
        $prevcode = $user->tsc;
        $prevqr = $ga->getQRCodeGoogleUrl($user->username . '@' . $gnl->sitename, $prevcode);
        $page_title = 'Two Factor';
        return view($this->activeTemplate.'user.twofactor', compact('page_title', 'secret', 'qrCodeUrl', 'prevcode', 'prevqr'));
    }

    public function create2fa(Request $request)
    {
        $user = auth()->user();
        $this->validate($request, [
            'key' => 'required',
            'code' => 'required',
        ]);

        $ga = new GoogleAuthenticator();
        $secret = $request->key;
        $oneCode = $ga->getCode($secret);

        if ($oneCode === $request->code) {
            $user->tsc = $request->key;
            $user->ts = 1;
            $user->tv = 1;
            $user->save();


            $userAgent = getIpInfo();
            $osBrowser = osBrowser();
            notify($user, '2FA_ENABLE', [
                'operating_system' => @$osBrowser['os_platform'],
                'browser' => @$osBrowser['browser'],
                'ip' => @$userAgent['ip'],
                'time' => @$userAgent['time']
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

        $user = auth()->user();
        $ga = new GoogleAuthenticator();

        $secret = $user->tsc;
        $oneCode = $ga->getCode($secret);
        $userCode = $request->code;

        if ($oneCode == $userCode) {

            $user->tsc = null;
            $user->ts = 0;
            $user->tv = 1;
            $user->save();


            $userAgent = getIpInfo();
            $osBrowser = osBrowser();
            notify($user, '2FA_DISABLE', [
                'operating_system' => @$osBrowser['os_platform'],
                'browser' => @$osBrowser['browser'],
                'ip' => @$userAgent['ip'],
                'time' => @$userAgent['time']
            ]);

            $notify[] = ['success', 'Two Factor Authenticator Disable Successfully'];
            return back()->withNotify($notify);

        } else {
            $notify[] = ['error', 'Wrong Verification Code'];
            return back()->withNotify($notify);
        }
    }

    public function blockSurveyor($surveyor_id)
    {
        $block_count = SurveyorBlockList::where('surveyor_id', $surveyor_id)->where('user_id', auth()->id())->count();

        if($block_count != 0) {
            $notify[] = ['error', 'Sponsor is already blocked'];
            return back()->withNotify($notify);
        }
        SurveyorBlockList::create([
            'surveyor_id' => $surveyor_id,
            'user_id' => auth()->id(),
        ]);
        $notify[] = ['success', 'Sponsor block successfully. You can not see ad of this sponsor. '];
        return back()->withNotify($notify);
    }

    public function unblockSurveyor($surveyor_id)
    {
        $block = SurveyorBlockList::where('surveyor_id', $surveyor_id)->where('user_id', auth()->id())->with('surveyor')->first();

        if(!is_null($block)) {
            $block->delete();
            $notify[] = ['success', 'Sponsor is unblocked. You can see '.$block->surveyor->fullname.' sponsor ad.'];
            return back()->withNotify($notify);
        }
        $notify[] = ['error', 'Sponsor is not blocked'];
        return back()->withNotify($notify);
    }

    public function surveyFavorite(Request $request)
    {
        $info = ['success' => false, 'message' => null, 'data' => null];
        $count_fav = FavoriteSurvey::where('survey_id', $request->survey_id)->where('user_id', $request->user_id)->count();
        if($count_fav != 0) {
            $info['message'] = __('User already press watch later!');
        }else {
            $action = FavoriteSurvey::create([
                'survey_id' => $request->survey_id,
                'user_id' => $request->user_id,
            ]);

            if(!is_null($action)) {
                $info['success'] = true;
                $info['data'] = $action;
            }
        }

        return response()->json($info);
    }

    public function surveyUnfavorite(Request $request)
    {
        $info = ['success' => false, 'message' => null, 'data' => null];
        $fav = FavoriteSurvey::where('survey_id', $request->survey_id)->where('user_id', $request->user_id)->first();
        if(!is_null($fav)) {
            $fav->delete();
            $info['success'] = true;
        }
        return response()->json($info);
    }

    public function sponsorBlockList()
    {
        $page_title = 'Block List';
        $surveyors = SurveyorBlockList::where('user_id', auth()->id())->with('surveyor')->paginate(getPaginate());
        $empty_message = 'No data found';
        return view($this->activeTemplate.'user.survey.block-sponsor-list', compact(
            'page_title',
            'surveyors',
            'empty_message'
        ));
    }

    public function surveyAvailable()
    {
        $page_title = 'View Ads';
        $block_array = [];
        $block_list = SurveyorBlockList::where('user_id', auth()->id())->get();
        foreach($block_list as $bl) {
            array_push($block_array, $bl->surveyor_id);
        }
        $cat_arr = [];
        $preferences = DB::table('preferences')->where('user_id', Auth::id())->get();
        foreach ($preferences as $pref) {
            array_push($cat_arr, $pref->preferences_name);
        }
        // $all_surveys = Survey::where('status',0)->where('schedule_ad', null)->with('surveyor', 'adtype')->whereHas('questions')->whereIn('target_market_category', $cat_arr)->whereNotIn('surveyor_id', $block_array)->latest()->get();
        // $schedule_surveys = Survey::where('status',0)->where('schedule_ad', '!=', null)->where('schedule_ad', '<=', Carbon::now()->format('d-m-Y'))->with('surveyor', 'adtype')->whereHas('questions')->whereIn('target_market_category', $cat_arr)->whereNotIn('surveyor_id', $block_array)->latest()->get();
        $all_surveys = Survey::where('status',0)->where('schedule_ad', null)->with('surveyor', 'adtype')->whereHas('questions')->whereNotIn('surveyor_id', $block_array)->where(function($q){
            $q->where(function($q){
                $q->whereNull('target_market_category');
                  
            })->orWhere(function($q){
                 $cat_arr = [];
                    $preferences = DB::table('preferences')->where('user_id', Auth::id())->get();
                    foreach ($preferences as $pref) {
                        array_push($cat_arr, $pref->preferences_name);
                    }
                $q->whereIn('target_market_category',$cat_arr);
                  
            });
        })->latest()->get();
          $category_data = Category::pluck('name');
        // $target_surveys = Survey::with('targetMarket')->where('id',151)->get();
        //dd($category_data);
        $target_surveys = Survey::with('targetMarket')->where('status',0)->where('schedule_ad', null)->with('surveyor', 'adtype')->whereHas('questions')->whereNotIn('surveyor_id', $block_array)->where(function($q) use($category_data){
            $q->whereNotIn('target_market_category',$category_data);
            // $q->where(function($tm){
            //     if($tm->targetMarket->target_market_name == 'pincode'){
            //         $tm->whereJsonContains('to', [['address' => ['zip' => $tm->targetMarket->target_market_value]]]);
            //     }
            // });
        })->latest()->get();
         $filter_array = []; 
         foreach ($target_surveys as $tm){
              $addr = json_decode(json_encode(auth()->user()->address),true);
                 // dd($addr);
                // $array_name_export = explode(',',$tm->targetMarket->target_market_name);
                  $array_name_export = [];
                 $array_name_export_json = json_decode($tm->targetMarket->target_market_name);
                 foreach($array_name_export_json as $array_json){
                      $array_name_export[$array_json->name]=$array_json->value;
                 }
                 
                 if(($key = array_search('name',$array_name_export))  !== false){
                     unset($array_name_export[$key]);
                 }
                 if(($key = array_search('email',$array_name_export))  !== false){
                     unset($array_name_export[$key]);
                 }
                  if(($key = array_search('whatsapp',$array_name_export))  !== false){
                     unset($array_name_export[$key]);
                 }
                  if(($key = array_search('mobile',$array_name_export))  !== false){
                     unset($array_name_export[$key]);
                 }
               
                 
                  //dd($array_value_export);
                  $combine_array = $array_name_export;
                  //dd($combine_array);
                  $age_collection = [];
                  if(!empty($combine_array['age'])){
                     $age_array = array_map('intval',explode('-',$combine_array['age'])) ;
                
                      $start = $age_array[0];
             
                      $end=$age_array[1];
                      $age_collection = [];
                      for($i = $start;$i <= $end;$i++){
                        
                         array_push($age_collection, $i);
                      }
                      if (in_array(auth()->user()->age, $age_collection))
                      {
                        $combine_array =  array_merge($combine_array, array("age"=>auth()->user()->age));
                      }
                  }
                  if(!empty($combine_array['anniversary'])){
                        $time_combine_array = strtotime($combine_array['anniversary']);
                        $newformat_combine_array = date('m',$time_combine_array);
                        unset($combine_array['anniversary']);
                        //array_push($combine_array);
                        $combine_array =  array_merge($combine_array, array("anniversary"=>$newformat_combine_array));
                  }
                  if(!empty($combine_array['birthday'])){
                        $time_combine_array_b = strtotime($combine_array['birthday']);
                        $newformat_combine_array_b = date('m',$time_combine_array_b);
                        unset($combine_array['birthday']);
                        //array_push($combine_array);
                        $combine_array =  array_merge($combine_array, array("birthday"=>$newformat_combine_array_b));
                  }
                 
                  $addr = json_decode(json_encode(auth()->user()->address),true);
                  $anniversary = auth()->user()->anniversary_date;
                  if(!empty($anniversary)){
                        $time = strtotime($anniversary);
                        $newformat = date('m',$time);
                  }else{
                      $newformat = '';
                  }
                
                   $birthday = auth()->user()->dob;
                 
                  
                  if(!empty($anniversary)){
                       $time_birthday = strtotime($birthday);
                       $newformat_birthday = date('m',$time_birthday);
                  }else{
                      $newformat_birthday = '';
                  }
                
                  $making_array =array("pincode" => $addr['zip'],
                                       "country"=>$addr['country'],
                                       "profession"=>auth()->user()->occupation,
                                       "city" => $addr['city'],
                                       "gender" => auth()->user()->gander,
                                       "annual-income"=>auth()->user()->annual_income,
                                       "marital-status"=>auth()->user()->marital,
                                       "anniversary"=>$newformat,
                                       "state" => $addr['state'],
                                        "birthday"=>$newformat_birthday,
                                        "age"=>auth()->user()->age,
                                      );
                
                 $checking_list_array_to_match = array_intersect_key($making_array,$combine_array);
                
                 $difference_array = array_diff_assoc($combine_array, $checking_list_array_to_match);
                
                  if (count($difference_array) == 0) {
                          array_push($filter_array, $tm);
                      } 
         }
           $category_data = Category::pluck('name');
        // $target_surveys = Survey::with('targetMarket')->where('id',151)->get();
        //dd($category_data);
        $target_surveys_sdl = Survey::with('targetMarket')->where('status',0)->where('schedule_ad', '!=', null)->where('schedule_ad', '<=', Carbon::now()->format('d-m-Y'))->with('surveyor', 'adtype')->whereHas('questions')->whereNotIn('surveyor_id', $block_array)->where(function($q) use($category_data){
            $q->whereNotIn('target_market_category',$category_data);
            // $q->where(function($tm){
            //     if($tm->targetMarket->target_market_name == 'pincode'){
            //         $tm->whereJsonContains('to', [['address' => ['zip' => $tm->targetMarket->target_market_value]]]);
            //     }
            // });
        })->latest()->get();
         $filter_array_sdl = []; 
         foreach ($target_surveys_sdl as $tm){
              $addr = json_decode(json_encode(auth()->user()->address),true);
                 // dd($addr);
                // $array_name_export = explode(',',$tm->targetMarket->target_market_name);
                  $array_name_export = [];
                 $array_name_export_json = json_decode($tm->targetMarket->target_market_name);
                 foreach($array_name_export_json as $array_json){
                      $array_name_export[$array_json->name]=$array_json->value;
                 }
                 
                 if(($key = array_search('name',$array_name_export))  !== false){
                     unset($array_name_export[$key]);
                 }
                 if(($key = array_search('email',$array_name_export))  !== false){
                     unset($array_name_export[$key]);
                 }
                  if(($key = array_search('whatsapp',$array_name_export))  !== false){
                     unset($array_name_export[$key]);
                 }
                  if(($key = array_search('mobile',$array_name_export))  !== false){
                     unset($array_name_export[$key]);
                 }
               
                 
                  //dd($array_value_export);
                  $combine_array = $array_name_export;
                  //dd($combine_array);
                  $age_collection = [];
                  if(!empty($combine_array['age'])){
                     $age_array = array_map('intval',explode('-',$combine_array['age'])) ;
                
                      $start = $age_array[0];
             
                      $end=$age_array[1];
                      $age_collection = [];
                      for($i = $start;$i <= $end;$i++){
                        
                         array_push($age_collection, $i);
                      }
                      if (in_array(auth()->user()->age, $age_collection))
                      {
                        $combine_array =  array_merge($combine_array, array("age"=>auth()->user()->age));
                      }
                  }
                  if(!empty($combine_array['anniversary'])){
                        $time_combine_array = strtotime($combine_array['anniversary']);
                        $newformat_combine_array = date('m',$time_combine_array);
                        unset($combine_array['anniversary']);
                        //array_push($combine_array);
                        $combine_array =  array_merge($combine_array, array("anniversary"=>$newformat_combine_array));
                  }
                  if(!empty($combine_array['birthday'])){
                        $time_combine_array_b = strtotime($combine_array['birthday']);
                        $newformat_combine_array_b = date('m',$time_combine_array_b);
                        unset($combine_array['birthday']);
                        //array_push($combine_array);
                        $combine_array =  array_merge($combine_array, array("birthday"=>$newformat_combine_array_b));
                  }
                 
                  $addr = json_decode(json_encode(auth()->user()->address),true);
                  $anniversary = auth()->user()->anniversary_date;
                  if(!empty($anniversary)){
                        $time = strtotime($anniversary);
                        $newformat = date('m',$time);
                  }else{
                      $newformat = '';
                  }
                
                   $birthday = auth()->user()->dob;
                 
                  
                  if(!empty($anniversary)){
                       $time_birthday = strtotime($birthday);
                       $newformat_birthday = date('m',$time_birthday);
                  }else{
                      $newformat_birthday = '';
                  }
                
                  $making_array =array("pincode" => $addr['zip'],
                                       "country"=>$addr['country'],
                                       "profession"=>auth()->user()->occupation,
                                       "city" => $addr['city'],
                                       "gender" => auth()->user()->gander,
                                       "annual-income"=>auth()->user()->annual_income,
                                       "marital-status"=>auth()->user()->marital,
                                       "anniversary"=>$newformat,
                                       "state" => $addr['state'],
                                        "birthday"=>$newformat_birthday,
                                        "age"=>auth()->user()->age,
                                      );
                
                 $checking_list_array_to_match = array_intersect_key($making_array,$combine_array);
                
                 $difference_array = array_diff_assoc($combine_array, $checking_list_array_to_match);
                
                  if (count($difference_array) == 0) {
                          array_push($filter_array_sdl, $tm);
                      } 
         }
        //$schedule_surveys = Survey::where('status',0)->where('schedule_ad', '!=', null)->where('schedule_ad', '<=', Carbon::now()->format('d-m-Y'))->with('surveyor', 'adtype')->whereHas('questions')->whereIn('target_market_category', $cat_arr)->whereNotIn('surveyor_id', $block_array)->latest()->get();
        $schedule_surveys = Survey::where('status',0)->where('schedule_ad', '!=', null)->where('schedule_ad', '<=', Carbon::now()->format('d-m-Y'))->with('surveyor', 'adtype')->whereHas('questions')->whereNotIn('surveyor_id', $block_array)->where(function($q){
            $q->where(function($q){
                $q->whereNull('target_market_category');
                  
            })->orWhere(function($q){
                 $cat_arr = [];
                    $preferences = DB::table('preferences')->where('user_id', Auth::id())->get();
                    foreach ($preferences as $pref) {
                        array_push($cat_arr, $pref->preferences_name);
                    }
                $q->whereIn('target_market_category',$cat_arr);
                  
            });
        })->latest()->get();
        
        $general = GeneralSetting::first();

        $surveys = collect([]);
        foreach ($filter_array_sdl as  $item) {

            $question_balance = ($item->per_user*$general->user_amount)/100 ;

            if ($item->surveyor->balance >= $question_balance) {

                if ($item->users) {
                    if(!in_array(auth()->user()->id,$item->users)){
                        $surveys->push($item);
                    }

//                    if(in_array(auth()->user()->id,$item->users)){
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

        foreach ($all_surveys as  $item) {

            $question_balance = ($item->per_user*$general->user_amount)/100 ;

            if ($item->surveyor->balance >= $question_balance) {

                if ($item->users) {
                    if(!in_array(auth()->user()->id,$item->users)){
                        $surveys->push($item);
                    }

//                    if(in_array(auth()->user()->id,$item->users)){
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
        foreach ($filter_array as  $item) {

            $question_balance = ($item->per_user*$general->user_amount)/100 ;

            if ($item->surveyor->balance >= $question_balance) {

                if ($item->users) {
                    if(!in_array(auth()->user()->id,$item->users)){
                        $surveys->push($item);
                    }

//                    if(in_array(auth()->user()->id,$item->users)){
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
                    if(!in_array(auth()->user()->id,$item->users)){
                        $surveys->push($item);
                    }

//                    if(in_array(auth()->user()->id,$item->users)){
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

        $empty_message = 'No data found';
        return view($this->activeTemplate.'user.survey.index', compact('page_title', 'surveys', 'empty_message'));
    }

    public function surveyCompleted()
    {
         
        $page_title = 'View Ads';
        $block_array = [];
        $block_list = SurveyorBlockList::where('user_id', auth()->id())->get();
        foreach($block_list as $bl) {
            array_push($block_array, $bl->surveyor_id);
        }
        //  $all_surveys = Survey::where('status',0)->where('schedule_ad', null)->with('surveyor', 'adtype')->whereHas('questions')->whereNotIn('surveyor_id', $block_array)->latest()->get();
        //  $schedule_surveys = Survey::where('status',0)->where('schedule_ad', '!=', null)->where('schedule_ad', '<=', Carbon::now()->format('d-m-Y'))->with('surveyor', 'adtype')->whereHas('questions')->whereNotIn('surveyor_id', $block_array)->latest()->get();
        $all_surveys = Survey::where('status',0)->where('schedule_ad', null)->with('surveyor', 'adtype')->whereHas('questions')->whereNotIn('surveyor_id', $block_array)->where(function($q){
            $q->where(function($q){
                $q->whereNull('target_market_category');
                  
            })->orWhere(function($q){
                 $cat_arr = [];
                    $preferences = DB::table('preferences')->where('user_id', Auth::id())->get();
                    foreach ($preferences as $pref) {
                        array_push($cat_arr, $pref->preferences_name);
                    }
                $q->whereIn('target_market_category',$cat_arr);
                  
            });
        })->latest()->get();
      $category_data = Category::pluck('name');
       
        $target_surveys = Survey::with('targetMarket')->where('status',0)->where('schedule_ad', null)->with('surveyor', 'adtype')->whereHas('questions')->whereNotIn('surveyor_id', $block_array)->where(function($q) use($category_data){
            $q->whereNotIn('target_market_category',$category_data);
           
        })->latest()->get();
         $filter_array = []; 
         foreach ($target_surveys as $tm){
              $addr = json_decode(json_encode(auth()->user()->address),true);
                 // dd($addr);
                // $array_name_export = explode(',',$tm->targetMarket->target_market_name);
                  $array_name_export = [];
                 $array_name_export_json = json_decode($tm->targetMarket->target_market_name);
                 foreach($array_name_export_json as $array_json){
                      $array_name_export[$array_json->name]=$array_json->value;
                 }
                 
                 if(($key = array_search('name',$array_name_export))  !== false){
                     unset($array_name_export[$key]);
                 }
                 if(($key = array_search('email',$array_name_export))  !== false){
                     unset($array_name_export[$key]);
                 }
                  if(($key = array_search('whatsapp',$array_name_export))  !== false){
                     unset($array_name_export[$key]);
                 }
                  if(($key = array_search('mobile',$array_name_export))  !== false){
                     unset($array_name_export[$key]);
                 }
               
                 
                  //dd($array_value_export);
                  $combine_array = $array_name_export;
                  //dd($combine_array);
                  $age_collection = [];
                  if(!empty($combine_array['age'])){
                     $age_array = array_map('intval',explode('-',$combine_array['age'])) ;
                
                      $start = $age_array[0];
             
                      $end=$age_array[1];
                      $age_collection = [];
                      for($i = $start;$i <= $end;$i++){
                        
                         array_push($age_collection, $i);
                      }
                      if (in_array(auth()->user()->age, $age_collection))
                      {
                        $combine_array =  array_merge($combine_array, array("age"=>auth()->user()->age));
                      }
                  }
                  if(!empty($combine_array['anniversary'])){
                        $time_combine_array = strtotime($combine_array['anniversary']);
                        $newformat_combine_array = date('m',$time_combine_array);
                        unset($combine_array['anniversary']);
                        //array_push($combine_array);
                        $combine_array =  array_merge($combine_array, array("anniversary"=>$newformat_combine_array));
                  }
                  if(!empty($combine_array['birthday'])){
                        $time_combine_array_b = strtotime($combine_array['birthday']);
                        $newformat_combine_array_b = date('m',$time_combine_array_b);
                        unset($combine_array['birthday']);
                        //array_push($combine_array);
                        $combine_array =  array_merge($combine_array, array("birthday"=>$newformat_combine_array_b));
                  }
                 
                  $addr = json_decode(json_encode(auth()->user()->address),true);
                  $anniversary = auth()->user()->anniversary_date;
                  if(!empty($anniversary)){
                        $time = strtotime($anniversary);
                        $newformat = date('m',$time);
                  }else{
                      $newformat = '';
                  }
                
                   $birthday = auth()->user()->dob;
                 
                  
                  if(!empty($anniversary)){
                       $time_birthday = strtotime($birthday);
                       $newformat_birthday = date('m',$time_birthday);
                  }else{
                      $newformat_birthday = '';
                  }
                
                  $making_array =array("pincode" => $addr['zip'],
                                       "country"=>$addr['country'],
                                       "profession"=>auth()->user()->occupation,
                                       "city" => $addr['city'],
                                       "gender" => auth()->user()->gander,
                                       "annual-income"=>auth()->user()->annual_income,
                                       "marital-status"=>auth()->user()->marital,
                                       "anniversary"=>$newformat,
                                       "state" => $addr['state'],
                                        "birthday"=>$newformat_birthday,
                                        "age"=>auth()->user()->age,
                                      );
                
                 $checking_list_array_to_match = array_intersect_key($making_array,$combine_array);
                
                 $difference_array = array_diff_assoc($combine_array, $checking_list_array_to_match);
                
                  if (count($difference_array) == 0) {
                          array_push($filter_array, $tm);
                      } 
         }
        //$schedule_surveys = Survey::where('status',0)->where('schedule_ad', '!=', null)->where('schedule_ad', '<=', Carbon::now()->format('d-m-Y'))->with('surveyor', 'adtype')->whereHas('questions')->whereIn('target_market_category', $cat_arr)->whereNotIn('surveyor_id', $block_array)->latest()->get();
        $schedule_surveys = Survey::where('status',0)->where('schedule_ad', '!=', null)->where('schedule_ad', '<=', Carbon::now()->format('d-m-Y'))->with('surveyor', 'adtype')->whereHas('questions')->whereNotIn('surveyor_id', $block_array)->where(function($q){
            $q->where(function($q){
                $q->whereNull('target_market_category');
                  
            })->orWhere(function($q){
                 $cat_arr = [];
                    $preferences = DB::table('preferences')->where('user_id', Auth::id())->get();
                    foreach ($preferences as $pref) {
                        array_push($cat_arr, $pref->preferences_name);
                    }
                $q->whereIn('target_market_category',$cat_arr);
                  
            });
        })->latest()->get();
           $category_data = Category::pluck('name');
        // $target_surveys = Survey::with('targetMarket')->where('id',151)->get();
        //dd($category_data);
        $target_surveys_sdl = Survey::with('targetMarket')->where('status',0)->where('schedule_ad', '!=', null)->where('schedule_ad', '<=', Carbon::now()->format('d-m-Y'))->with('surveyor', 'adtype')->whereHas('questions')->whereNotIn('surveyor_id', $block_array)->where(function($q) use($category_data){
            $q->whereNotIn('target_market_category',$category_data);
            // $q->where(function($tm){
            //     if($tm->targetMarket->target_market_name == 'pincode'){
            //         $tm->whereJsonContains('to', [['address' => ['zip' => $tm->targetMarket->target_market_value]]]);
            //     }
            // });
        })->latest()->get();
         $filter_array_sdl = []; 
          //$filter_array = []; 
         foreach ($target_surveys_sdl as $tm){
              $addr = json_decode(json_encode(auth()->user()->address),true);
                 // dd($addr);
                // $array_name_export = explode(',',$tm->targetMarket->target_market_name);
                  $array_name_export = [];
                 $array_name_export_json = json_decode($tm->targetMarket->target_market_name);
                 foreach($array_name_export_json as $array_json){
                      $array_name_export[$array_json->name]=$array_json->value;
                 }
                 
                 if(($key = array_search('name',$array_name_export))  !== false){
                     unset($array_name_export[$key]);
                 }
                 if(($key = array_search('email',$array_name_export))  !== false){
                     unset($array_name_export[$key]);
                 }
                  if(($key = array_search('whatsapp',$array_name_export))  !== false){
                     unset($array_name_export[$key]);
                 }
                  if(($key = array_search('mobile',$array_name_export))  !== false){
                     unset($array_name_export[$key]);
                 }
               
                 
                  //dd($array_value_export);
                  $combine_array = $array_name_export;
                  //dd($combine_array);
                  $age_collection = [];
                  if(!empty($combine_array['age'])){
                     $age_array = array_map('intval',explode('-',$combine_array['age'])) ;
                
                      $start = $age_array[0];
             
                      $end=$age_array[1];
                      $age_collection = [];
                      for($i = $start;$i <= $end;$i++){
                        
                         array_push($age_collection, $i);
                      }
                      if (in_array(auth()->user()->age, $age_collection))
                      {
                        $combine_array =  array_merge($combine_array, array("age"=>auth()->user()->age));
                      }
                  }
                  if(!empty($combine_array['anniversary'])){
                        $time_combine_array = strtotime($combine_array['anniversary']);
                        $newformat_combine_array = date('m',$time_combine_array);
                        unset($combine_array['anniversary']);
                        //array_push($combine_array);
                        $combine_array =  array_merge($combine_array, array("anniversary"=>$newformat_combine_array));
                  }
                  if(!empty($combine_array['birthday'])){
                        $time_combine_array_b = strtotime($combine_array['birthday']);
                        $newformat_combine_array_b = date('m',$time_combine_array_b);
                        unset($combine_array['birthday']);
                        //array_push($combine_array);
                        $combine_array =  array_merge($combine_array, array("birthday"=>$newformat_combine_array_b));
                  }
                 
                  $addr = json_decode(json_encode(auth()->user()->address),true);
                  $anniversary = auth()->user()->anniversary_date;
                  if(!empty($anniversary)){
                        $time = strtotime($anniversary);
                        $newformat = date('m',$time);
                  }else{
                      $newformat = '';
                  }
                
                   $birthday = auth()->user()->dob;
                 
                  
                  if(!empty($anniversary)){
                       $time_birthday = strtotime($birthday);
                       $newformat_birthday = date('m',$time_birthday);
                  }else{
                      $newformat_birthday = '';
                  }
                
                  $making_array =array("pincode" => $addr['zip'],
                                       "country"=>$addr['country'],
                                       "profession"=>auth()->user()->occupation,
                                       "city" => $addr['city'],
                                       "gender" => auth()->user()->gander,
                                       "annual-income"=>auth()->user()->annual_income,
                                       "marital-status"=>auth()->user()->marital,
                                       "anniversary"=>$newformat,
                                       "state" => $addr['state'],
                                        "birthday"=>$newformat_birthday,
                                        "age"=>auth()->user()->age,
                                      );
                
                 $checking_list_array_to_match = array_intersect_key($making_array,$combine_array);
                
                 $difference_array = array_diff_assoc($combine_array, $checking_list_array_to_match);
                
                  if (count($difference_array) == 0) {
                          array_push($filter_array_sdl, $tm);
                      } 
         }
        $general = GeneralSetting::first();

        $surveys = collect([]);

        foreach ($all_surveys as  $item) {

            $question_balance = ($item->per_user*$general->user_amount)/100 ;

            if ($item->surveyor->balance >= $question_balance) {

                if ($item->users) {
                    if(in_array(auth()->user()->id,$item->users)){
                        $surveys->push($item);
                    }


                }
            }
        }

        foreach ($schedule_surveys as  $item) {

            $question_balance = ($item->per_user*$general->user_amount)/100 ;

            if ($item->surveyor->balance >= $question_balance) {

                if ($item->users) {
                    if(in_array(auth()->user()->id,$item->users)){
                        $surveys->push($item);
                    }

                }
            }
        }
         foreach ($filter_array as  $item) {

            $question_balance = ($item->per_user*$general->user_amount)/100 ;

            if ($item->surveyor->balance >= $question_balance) {

                if ($item->users) {
                    if(in_array(auth()->user()->id,$item->users)){
                        $surveys->push($item);
                    }


                }
            }
        }
        foreach ($filter_array_sdl as  $item) {

           $question_balance = ($item->per_user*$general->user_amount)/100 ;

            if ($item->surveyor->balance >= $question_balance) {

                if ($item->users) {
                    if(in_array(auth()->user()->id,$item->users)){
                        $surveys->push($item);
                    }


                }
            }
        }
        $surveys =  $surveys->paginate(getPaginate());

        $empty_message = 'No data found';
        return view($this->activeTemplate.'user.survey.index', compact('page_title', 'surveys', 'empty_message'));
    }

    public function surveyFavoriteList()
    {
        $page_title = 'View Ads';
        $block_array = [];
        $block_list = SurveyorBlockList::where('user_id', auth()->id())->get();
        foreach($block_list as $bl) {
            array_push($block_array, $bl->surveyor_id);
        }
        $surveys = collect([]);
        $favs = FavoriteSurvey::where('user_id', auth()->id())->get();
        foreach ($favs as $fav) {
            // $all_surveys = Survey::where('status',0)->where('schedule_ad', null)->where('id', $fav->survey_id)->with('surveyor', 'adtype')->whereHas('questions')->whereNotIn('surveyor_id', $block_array)->latest()->get();
            // $schedule_surveys = Survey::where('status',0)->where('schedule_ad', '!=', null)->where('schedule_ad', '<=', Carbon::now()->format('d-m-Y'))->where('id', $fav->survey_id)->with('surveyor', 'adtype')->whereHas('questions')->whereNotIn('surveyor_id', $block_array)->latest()->get();
            $all_surveys = Survey::where('status',0)->where('schedule_ad', null)->with('surveyor', 'adtype')->whereHas('questions')->whereNotIn('surveyor_id', $block_array)->where(function($q){
            $q->where(function($q){
                $q->whereNull('target_market_category');
                  
            })->orWhere(function($q){
                 $cat_arr = [];
                    $preferences = DB::table('preferences')->where('user_id', Auth::id())->get();
                    foreach ($preferences as $pref) {
                        array_push($cat_arr, $pref->preferences_name);
                    }
                $q->whereIn('target_market_category',$cat_arr);
                  
            });
        })->latest()->get();
        //$schedule_surveys = Survey::where('status',0)->where('schedule_ad', '!=', null)->where('schedule_ad', '<=', Carbon::now()->format('d-m-Y'))->with('surveyor', 'adtype')->whereHas('questions')->whereIn('target_market_category', $cat_arr)->whereNotIn('surveyor_id', $block_array)->latest()->get();
        $schedule_surveys = Survey::where('status',0)->where('schedule_ad', '!=', null)->where('schedule_ad', '<=', Carbon::now()->format('d-m-Y'))->with('surveyor', 'adtype')->whereHas('questions')->whereNotIn('surveyor_id', $block_array)->where(function($q){
            $q->where(function($q){
                $q->whereNull('target_market_category');
                  
            })->orWhere(function($q){
                 $cat_arr = [];
                    $preferences = DB::table('preferences')->where('user_id', Auth::id())->get();
                    foreach ($preferences as $pref) {
                        array_push($cat_arr, $pref->preferences_name);
                    }
                $q->whereIn('target_market_category',$cat_arr);
                  
            });
        })->latest()->get();
            
            $general = GeneralSetting::first();

            foreach ($all_surveys as  $item) {

                $question_balance = ($item->per_user*$general->user_amount)/100 ;

                if ($item->surveyor->balance >= $question_balance) {

                    $surveys->push($item);
                }
            }

            foreach ($schedule_surveys as  $item) {

                $question_balance = ($item->per_user*$general->user_amount)/100 ;

                if ($item->surveyor->balance >= $question_balance) {

                    $surveys->push($item);
                }
            }
        }


        $surveys =  $surveys->paginate(getPaginate());

        $empty_message = 'No data found';
        return view($this->activeTemplate.'user.survey.index', compact('page_title', 'surveys', 'empty_message'));
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
        $page_title = 'You Are Viewing Advertisement';
        $empty_message = 'No data found';
        $survey = Survey::findOrFail($id);
        $user = auth()->user();

        $total_views = $survey->total_views;
        $views = !is_null($survey->users) ? count($survey->users) : 0;
        $res_views = $total_views - $views;
        $demo_route = false;

        if($res_views == 0) {
            $demo_route = true;
        }
//        if($survey->users) {
//            if(!in_array($user->id,$survey->users)){
//                $demo_route = true;
//            }
//        }

        if (count($survey->questions) <= 0) {
            $notify[] = ['error', 'No question is available for this survey'];
            return back()->withNotify($notify);
        }

        if ($survey->users) {

            if(in_array($user->id,$survey->users)){

                $notify[] = ['error', 'You already participated on this'];
                return redirect()->route('user.survey')->withNotify($notify);
            }

        }

        if ($survey->age_limit == 1 && $survey->start_age && $survey->end_age) {
            if($user->age < $survey->start_age || $user->age > $survey->end_age){
                $notify[] = ['error', 'This ad has age limit from ' .$survey->start_age. ' to ' .$survey->end_age];
                return redirect()->route('user.survey')->withNotify($notify);
            }
        }

        if ($survey->country_limit == 1 && $survey->country) {
            if(!in_array($user->address->country,$survey->country)){
                $notify[] = ['error', 'This ad is not available for your country'];
                return redirect()->route('user.survey')->withNotify($notify);
            }
        }

        return view($this->activeTemplate.'user.survey.ad-view', compact('page_title', 'survey', 'empty_message', 'demo_route'));
    }

    public function surveyQuestions($id, Request $request)
    {
        $page_title = 'View Ads Questions';
        $empty_message = 'No data found';
        $survey = Survey::findOrFail($id);
        $user = auth()->user();
        $total_views = $survey->total_views;
        $views = !is_null($survey->users) ? count($survey->users) : 0;
        $res_views = $total_views - $views;
        $demo_route = false;

        if(!is_null($request->get('t'))) {
            $t = $request->get('t');
        }else {
            return redirect()->route('user.survey.ad.view', $id);
        }

        if($res_views == 0) {
            $demo_route = true;
        }
//        if($survey->users) {
//            if(!in_array($user->id,$survey->users)){
//                $demo_route = true;
//            }
//        }


        if (count($survey->questions) <= 0) {
            $notify[] = ['error', 'No question is available for this survey'];
            return back()->withNotify($notify);
        }

        if ($survey->users) {

            if(in_array($user->id,$survey->users)){

                $notify[] = ['error', 'You already participated on this'];
                return redirect()->route('user.survey')->withNotify($notify);
            }

        }

        if ($survey->age_limit == 1 && $survey->start_age && $survey->end_age) {
            if($user->age < $survey->start_age || $user->age > $survey->end_age){
                $notify[] = ['error', 'This ad has age limit from ' .$survey->start_age. ' to ' .$survey->end_age];
                return redirect()->route('user.survey')->withNotify($notify);
            }
        }

        if ($survey->country_limit == 1 && $survey->country) {
            if(!in_array($user->address->country,$survey->country)){
                $notify[] = ['error', 'This ad is not available for your country'];
                return redirect()->route('user.survey')->withNotify($notify);
            }
        }

        return view($this->activeTemplate.'user.survey.question', compact('page_title', 'survey', 'empty_message', 'demo_route', 't'));
    }

    public function dummyQuestionAnswer()
    {
        $notify[] = ['success', 'Ad View Completed'];
        return redirect()->route('user.home')->withNotify($notify);
    }

    public function surveyQuestionsAnswers(Request $request, $id){

        $request->validate([
            "answer" => "required|array|min:1",
            "answer.*" => "required_with:answer",
        ]);

        $survey = Survey::where('id',$id)->with('questions')->first();

        $user = auth()->user();

//        if($survey->repeated_viewers == 1) {
//            if ($survey->users) {
//
////                if(in_array($user->id,$survey->users)){
////                    $notify[] = ['error', 'You already participated on this'];
////                    return back()->withNotify($notify);
////                }
//
//                if(!in_array($user->id,$survey->users)){
//                    $survey_users = $survey->users;
//                    array_push($survey_users,$user->id);
//                    $survey->users = $survey_users;
//                }
//
//            }
//
//            if(!$survey->users){
//                $survey->users = [$user->id];
//            }
//        }else {
//            if ($survey->users) {
//
//                if(in_array($user->id,$survey->users)){
//                    $notify[] = ['error', 'You already participated on this'];
//                    return back()->withNotify($notify);
//                }
//
//                if(!in_array($user->id,$survey->users)){
//                    $survey_users = $survey->users;
//                    array_push($survey_users,$user->id);
//                    $survey->users = $survey_users;
//                }
//
//            }
//
//            if(!$survey->users){
//                $survey->users = [$user->id];
//            }
//        }

        if ($survey->users) {

            if(in_array($user->id,$survey->users)){
                $notify[] = ['error', 'You already participated on this'];
                return back()->withNotify($notify);
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

        ViewSurvey::create([
            'survey_id' => $survey->id,
            'user_id' => $user->id,
            'is_repeated' => $survey->repeated_viewers,
        ]);

        $answers = $request['answer'];

        foreach ($survey->questions as $item) {
            $surveyAns = @$answers[$item->id];

            if (!$surveyAns) {
                $notify[] = ['error','Please answer all the questions'];
                return back()->withNotify($notify);
            }

            //Custom input validation
            if ($item->custom_input == 1 && $item->custom_input_type == 1) {
                $cusInp = $surveyAns['c'];
                if (!$cusInp) {
                    $notify[] = ['error','You missed input type answer'];
                    return back()->withNotify($notify);
                }
            }

            //radio type validation
            if ($item->type == 1) {
                $radioAns = array_shift($surveyAns);

                if(!$radioAns){
                    $notify[] = ['error','You missed radio type answer'];
                    return back()->withNotify($notify);
                }
                if(!empty($item->options)){
                if(!in_array($radioAns,$item->options)){
                    $notify[] = ['error','Do not try to cheat us'];
                    return back()->withNotify($notify);
                }
                }

            }

            //checkbox validation
            if ($item->type == 2) {
                $checkBoxValue = $surveyAns;
                unset($checkBoxValue['c']);
                if(@count($checkBoxValue) == 0 || !$checkBoxValue){
                    $notify[] = ['error','You missed checkBox type answer'];
                    return back()->withNotify($notify);
                }
                $diffAns = array_diff($checkBoxValue,$item->options);
                if(count($diffAns) > 0){
                    $notify[] = ['error','Do not try to cheat us'];
                    return back()->withNotify($notify);
                }
            }

        }


        $surveyor = Surveyor::where('id',$survey->surveyor_id)->first();
        if (!$surveyor) {
            $notify[] = ['error', 'You are not authorized to answer this'];
            return back()->withNotify($notify);
        }

        $general = GeneralSetting::first();

        $answer_balance = $general->get_amount * count($answers);

        // if ($surveyor->balance < $answer_balance) {
        //     $notify[] = ['error', 'surveyor does not  have enough balance to pay your reward. Try another one'];
        //     return back()->withNotify($notify);
        // }

        if (!$user) {
            $notify[] = ['error', 'You are not authorized to answer this'];
            return back()->withNotify($notify);
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



        // notify($user, 'SURVEY_COMPLETED', [
        //     'survey_name' => $survey->name,
        //     'amount' => getAmount($general->paid_amount * count($answers)),
        //     'currency' => $general->cur_text,
        //     'post_balance' => getAmount($user->balance)
        // ]);

        // notify($surveyor, 'SURVEY_ANSWERD', [
        //     'survey_name' => $survey->name,
        //     'total_question' => count($answers),
        //     'charge' => getAmount($general->get_amount),
        //     'amount' => getAmount($general->get_amount * count($answers)),
        //     'currency' => $general->cur_text,
        //     'post_balance' => getAmount($surveyor->balance)
        // ]);

        // $notify[] = ['success', 'Ad View Completed'];
        //return redirect()->route('user.home')->withNotify($notify);
        return redirect()->route('user.home');
    }
    public function adddashboard(){
         $categories = Category::where('status','1')->get();
         $page_title = "Advertisement Preferences";
         $data['user'] = Auth::user();
         //return $data;
         //return view($this->activeTemplate . 'user.add-preferences', $data);
         //return view($this->activeTemplate. 'user.add-preferences', $data)->with('categories');
         return view($this->activeTemplate . 'user.add-preferences', compact('page_title', 'categories'));
    }
    public function adddashboardpost(Request $request){
         $user = Auth::user()->id;
         $ids = $request->preferences;
         DB::table('preferences')->where('user_id',$user)->delete();

        foreach ($ids as $id) {
             $student = Category::findOrfail($id); // assume you use this model
             $name = $student->name;
             $id = $student->id;
             $values = array('preferences_id' => $id ,'preferences_name' => $name,'user_id'=>$user);
             $users = DB::table('preferences')->insert($values);
        }
        $notify[] = ['success', 'You have done this Advertisement Preferences successfully'];
        return redirect()->route('user.add-prefernce')->withNotify($notify);
    }
        public function usernamecheck(Request $request){
        try{
            $username = $request->username;
            $count = Surveyor::where('username',$username)->count();
            echo json_encode($count);die;
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
      public function usercheckmobile(Request $request){
        try{
            $mobile = $request->mobile;
            $postal = $request->postal;
       
            $combinemobile = $postal.$mobile;
            
            $count = User::where('mobile',$combinemobile)->count();
           
            echo json_encode($count);die;
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
     public function usercheckmobilewapp(Request $request){
        try{
            $mobile = $request->mobile;
           
            
            $count = User::where('whatsaap',$mobile)->count();
         
            echo json_encode($count);die;
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
     public function usernameuser(Request $request){
        try{
            $username = $request->username;
           
            
            $count = User::where('username',$username)->count();
         
            echo json_encode($count);die;
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
     public function smobile(Request $request){
        try{
            $mobile = $request->mobile;
            $postal = $request->postal;
       
            $combinemobile = $postal.$mobile;
            
            $count = Surveyor::where('mobile',$combinemobile)->count();
           
            echo json_encode($count);die;
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
      public function email(Request $request){
        try{
            $email = $request->email;
            $count = Surveyor::where('email',$email)->count();
           
            echo json_encode($count);die;
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
      public function userinputemail(Request $request){
        try{
            $email = $request->email;
            $count = User::where('email',$email)->count();
           
            echo json_encode($count);die;
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function downloadagreement(Request $request){
        try{
            $pdfurl = url('agreement/agreement.pdf');
            echo  json_encode($pdfurl);
        }catch(exception $e){
            echo $e->getMessage();
        }
    }
}
