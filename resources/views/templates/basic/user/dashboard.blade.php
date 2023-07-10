@extends($activeTemplate.'layouts.master')
@section('content')

    <div class="dashboard-area mt-30">
        <?php if($user->percentage !='100'){ ?>
              <p>Dear Viewer your profile is {{$user->percentage}} % completed kindly complete to earn radigone points to fulfil your daily needs <a href="/user/profile-setting" class="btn btn--base btn-lg mt-3 mb-1">Edit profile</a></p>

        <?php } ?>
      <div class="panel-card-header section--bg text-white">
            <div class="panel-card-title"><i class="las la-cloud-upload-alt"></i> @lang('User Activity')</div>
        </div>
        <div>
             <div class="row justify-content-center mb-30-none">
                <div class="col-xl-3 col-md-6 col-sm-8" style="margin:26px 0px 50px 0px">
                    <div class="dashboard-item bg--danger">
                        <div class="dashboard-content">
                            <div class="dashboard-icon">
                                <i class="las la-wallet"></i>
                            </div>
                            <div class="num text-white" data-start="0" data-end="0" data-postfix=""
                                data-duration="1500" data-delay="0">{{getAmount($user->balance)}} Points</div>
                            <h3 class="title text-white">@lang('Total Radigone Points')</h3>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-sm-8" style="margin:26px 0px 50px 0px">
                    <div class="dashboard-item bg--success">
                        <div class="dashboard-content">
                            <div class="dashboard-icon">
                                <i class="las la-ticket-alt"></i>
                            </div>
                            <div class="num text-white" data-start="0" data-end="0" data-postfix=""
                                data-duration="1500" data-delay="0">{{getAmount($user->completed_survey)}}</div>
                            <h3 class="title text-white">@lang('Completed Ads')</h3>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-sm-8" style="margin:26px 0px 50px 0px">
                    <div class="dashboard-item bg--warning">
                        <div class="dashboard-content">
                            <div class="dashboard-icon">
                                <i class="las la-address-card"></i>
                            </div>
                            <div class="num text-white" data-start="0" data-end="0" data-postfix=""
                                data-duration="1500" data-delay="0">{{getAmount($totalWithdraw)}} points</div>
                            <h3 class="title text-white">@lang('Radigone Withdarw points')</h3>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-sm-8" style="margin:26px 0px 50px 0px">
                    <div class="dashboard-item bg--primary">
                        <div class="dashboard-content">
                            <div class="dashboard-icon">
                                <i class="las la-th-list"></i>
                            </div>
                            <div class="num text-white" data-start="0" data-end="0" data-postfix=""
                                data-duration="1500" data-delay="0">{{$totalTransaction}}</div>
                            <h3 class="title text-white">@lang('Radigone Transaction')</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
      
{{--        <div class="Upcomingspecified">--}}
{{--             <div class="panel-card-header section--bg text-white">--}}
{{--                <div class="panel-card-title"><i class="las la-cloud-upload-alt"></i> Upcoming specified</div>--}}
{{--            </div>    --}}
{{--            <div class="row justify-content-center mb-30-none">--}}
{{--                <div class="col-xl-3 col-md-6 col-sm-8" style="margin:26px 0px 50px 0px">--}}
{{--                    <div class="survey-list-item">--}}
{{--                        <div class="survey-list-body">--}}
{{--                            <div class="survey-list-thumb">--}}
{{--                                <img src="https://smartads.website/assets/images/survey/61e2cc7fcf7771642253439.jpg" alt="survey">--}}
{{--                            </div>--}}
{{--                            <div class="survey-list-content">--}}
{{--                                <div class="survey-list-header d-flex flex-wrap justify-content-between">--}}
{{--                                    <h3 class="title">Sales</h3>--}}
{{--                                    <div class="survey-price">Reward: 3 INR</div>--}}
{{--                                </div>--}}
{{--                                <p>Test</p>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="survey-list-footer bg--primary text-center">--}}
{{--                            <div class="survey-btn">--}}
{{--                                <a href="https://smartads.website/user/survey/1/qustions" class="text-white text-center">View Ady</a>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-xl-3 col-md-6 col-sm-8" style="margin:26px 0px 50px 0px">--}}
{{--                    <div class="survey-list-item">--}}
{{--                        <div class="survey-list-body">--}}
{{--                            <div class="survey-list-thumb">--}}
{{--                                <img src="https://smartads.website/assets/images/survey/61e2cc7fcf7771642253439.jpg" alt="survey">--}}
{{--                            </div>--}}
{{--                            <div class="survey-list-content">--}}
{{--                                <div class="survey-list-header d-flex flex-wrap justify-content-between">--}}
{{--                                    <h3 class="title">Sales</h3>--}}
{{--                                    <div class="survey-price">Reward: 3 INR</div>--}}
{{--                                </div>--}}
{{--                                <p>Test</p>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="survey-list-footer bg--primary text-center">--}}
{{--                            <div class="survey-btn">--}}
{{--                                <a href="https://smartads.website/user/survey/1/qustions" class="text-white text-center">View Ad</a>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-xl-3 col-md-6 col-sm-8" style="margin:26px 0px 50px 0px">--}}
{{--                    <div class="survey-list-item">--}}
{{--                        <div class="survey-list-body">--}}
{{--                            <div class="survey-list-thumb">--}}
{{--                                <img src="https://smartads.website/assets/images/survey/61e2cc7fcf7771642253439.jpg" alt="survey">--}}
{{--                            </div>--}}
{{--                            <div class="survey-list-content">--}}
{{--                                <div class="survey-list-header d-flex flex-wrap justify-content-between">--}}
{{--                                    <h3 class="title">Sales</h3>--}}
{{--                                    <div class="survey-price">Reward: 3 INR</div>--}}
{{--                                </div>--}}
{{--                                <p>Test</p>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="survey-list-footer bg--primary text-center">--}}
{{--                            <div class="survey-btn">--}}
{{--                                <a href="https://smartads.website/user/survey/1/qustions" class="text-white text-center">View Ad</a>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-xl-3 col-md-6 col-sm-8" style="margin:26px 0px 50px 0px">--}}
{{--                    <div class="survey-list-item">--}}
{{--                        <div class="survey-list-body">--}}
{{--                            <div class="survey-list-thumb">--}}
{{--                                <img src="https://smartads.website/assets/images/survey/61e2cc7fcf7771642253439.jpg" alt="survey">--}}
{{--                            </div>--}}
{{--                            <div class="survey-list-content">--}}
{{--                                <div class="survey-list-header d-flex flex-wrap justify-content-between">--}}
{{--                                    <h3 class="title">Sales</h3>--}}
{{--                                    <div class="survey-price">Reward: 3 INR</div>--}}
{{--                                </div>--}}
{{--                                <p>Test</p>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="survey-list-footer bg--primary text-center">--}}
{{--                            <div class="survey-btn">--}}
{{--                                <a href="https://smartads.website/user/survey/1/qustions" class="text-white text-center">View Ad</a>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
   {{--   start comment   <div class="Upcomingspecified">
             <div class="panel-card-header section--bg text-white">
                <div class="panel-card-title"><i class="las la-cloud-upload-alt"></i> Birthday month</div>
            </div>
             <div class="row justify-content-center mb-30-none">
                <div class="col-xl-3 col-md-6 col-sm-8" style="margin:26px 0px 50px 0px">
                    <div class="survey-list-item">
                        <div class="survey-list-body">
                            <div class="survey-list-thumb">
                                <img src="https://smartads.website/assets/images/survey/61e2cc7fcf7771642253439.jpg" alt="survey">
                            </div>
                            <div class="survey-list-content">
                                <div class="survey-list-header d-flex flex-wrap justify-content-between">
                                    <h3 class="title">Sales</h3>
                                    <div class="survey-price">Reward: 3 INR</div>
                                </div>
                                <p>Test</p>
                            </div>
                        </div>
                        <div class="survey-list-footer bg--primary text-center">
                            <div class="survey-btn">
                                <a href="https://smartads.website/user/survey/1/qustions" class="text-white text-center">View Ad</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-sm-8" style="margin:26px 0px 50px 0px">
                    <div class="survey-list-item">
                        <div class="survey-list-body">
                            <div class="survey-list-thumb">
                                <img src="https://smartads.website/assets/images/survey/61e2cc7fcf7771642253439.jpg" alt="survey">
                            </div>
                            <div class="survey-list-content">
                                <div class="survey-list-header d-flex flex-wrap justify-content-between">
                                    <h3 class="title">Sales</h3>
                                    <div class="survey-price">Reward: 3 INR</div>
                                </div>
                                <p>Test</p>
                            </div>
                        </div>
                        <div class="survey-list-footer bg--primary text-center">
                            <div class="survey-btn">
                                <a href="https://smartads.website/user/survey/1/qustions" class="text-white text-center">View Ad</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-sm-8" style="margin:26px 0px 50px 0px">
                    <div class="survey-list-item">
                        <div class="survey-list-body">
                            <div class="survey-list-thumb">
                                <img src="https://smartads.website/assets/images/survey/61e2cc7fcf7771642253439.jpg" alt="survey">
                            </div>
                            <div class="survey-list-content">
                                <div class="survey-list-header d-flex flex-wrap justify-content-between">
                                    <h3 class="title">Sales</h3>
                                    <div class="survey-price">Reward: 3 INR</div>
                                </div>
                                <p>Test</p>
                            </div>
                        </div>
                        <div class="survey-list-footer bg--primary text-center">
                            <div class="survey-btn">
                                <a href="https://smartads.website/user/survey/1/qustions" class="text-white text-center">View Ady</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-sm-8" style="margin:26px 0px 50px 0px">
                    <div class="survey-list-item">
                        <div class="survey-list-body">
                            <div class="survey-list-thumb">
                                <img src="https://smartads.website/assets/images/survey/61e2cc7fcf7771642253439.jpg" alt="survey">
                            </div>
                            <div class="survey-list-content">
                                <div class="survey-list-header d-flex flex-wrap justify-content-between">
                                    <h3 class="title">Sales</h3>
                                    <div class="survey-price">Reward: 3 INR</div>
                                </div>
                                <p>Test</p>
                            </div>
                        </div>
                        <div class="survey-list-footer bg--primary text-center">
                            <div class="survey-btn">
                                <a href="https://smartads.website/user/survey/1/qustions" class="text-white text-center">View Ad</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <div class="Upcomingspecified">
             <div class="panel-card-header section--bg text-white">
                <div class="panel-card-title"><i class="las la-cloud-upload-alt"></i> Anniversary month</div>
            </div>
             <div class="row justify-content-center mb-30-none">
                <div class="col-xl-3 col-md-6 col-sm-8" style="margin:26px 0px 50px 0px">
                    <div class="survey-list-item">
                        <div class="survey-list-body">
                            <div class="survey-list-thumb">
                                <img src="https://smartads.website/assets/images/survey/61e2cc7fcf7771642253439.jpg" alt="survey">
                            </div>
                            <div class="survey-list-content">
                                <div class="survey-list-header d-flex flex-wrap justify-content-between">
                                    <h3 class="title">Sales</h3>
                                    <div class="survey-price">Reward: 3 INR</div>
                                </div>
                                <p>Test</p>
                            </div>
                        </div>
                        <div class="survey-list-footer bg--primary text-center">
                            <div class="survey-btn">
                                <a href="https://smartads.website/user/survey/1/qustions" class="text-white text-center">View Ad</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-sm-8" style="margin:26px 0px 50px 0px">
                    <div class="survey-list-item">
                        <div class="survey-list-body">
                            <div class="survey-list-thumb">
                                <img src="https://smartads.website/assets/images/survey/61e2cc7fcf7771642253439.jpg" alt="survey">
                            </div>
                            <div class="survey-list-content">
                                <div class="survey-list-header d-flex flex-wrap justify-content-between">
                                    <h3 class="title">Sales</h3>
                                    <div class="survey-price">Reward: 3 INR</div>
                                </div>
                                <p>Test</p>
                            </div>
                        </div>
                        <div class="survey-list-footer bg--primary text-center">
                            <div class="survey-btn">
                                <a href="https://smartads.website/user/survey/1/qustions" class="text-white text-center">View Ady</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-sm-8" style="margin:26px 0px 50px 0px">
                    <div class="survey-list-item">
                        <div class="survey-list-body">
                            <div class="survey-list-thumb">
                                <img src="https://smartads.website/assets/images/survey/61e2cc7fcf7771642253439.jpg" alt="survey">
                            </div>
                            <div class="survey-list-content">
                                <div class="survey-list-header d-flex flex-wrap justify-content-between">
                                    <h3 class="title">Sales</h3>
                                    <div class="survey-price">Reward: 3 INR</div>
                                </div>
                                <p>Test</p>
                            </div>
                        </div>
                        <div class="survey-list-footer bg--primary text-center">
                            <div class="survey-btn">
                                <a href="https://smartads.website/user/survey/1/qustions" class="text-white text-center">View Ad</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-sm-8" style="margin:26px 0px 50px 0px">
                    <div class="survey-list-item">
                        <div class="survey-list-body">
                            <div class="survey-list-thumb">
                                <img src="https://smartads.website/assets/images/survey/61e2cc7fcf7771642253439.jpg" alt="survey">
                            </div>
                            <div class="survey-list-content">
                                <div class="survey-list-header d-flex flex-wrap justify-content-between">
                                    <h3 class="title">Sales</h3>
                                    <div class="survey-price">Reward: 3 INR</div>
                                </div>
                                <p>Test</p>
                            </div>
                        </div>
                        <div class="survey-list-footer bg--primary text-center">
                            <div class="survey-btn">
                                <a href="https://smartads.website/user/survey/1/qustions" class="text-white text-center">View Ad</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-card-body">


            <div class="row justify-content-center mb-30-none mt-30">
                <div class="col-xl-12 col-md-12 col-sm-12 mb-30">
                    <div class="chart-area">
                        <div class="chart-scroll">
                            <div class="chart-wrapper m-0">
                                <canvas id="myChart" width="400" height="150"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  comment add--}}
           {{--   new code for add --}}
    <!--  <div class="survey-area mt-30">-->
    <!--<div class="panel-card-header section--bg text-white">-->
    <!--    <div class="panel-card-title"><i class="las la-exclamation-circle"></i> @lang('Notice Board')</div>-->
    <!--</div>-->
    <div class="panel-card-body">
        <div class="row justify-content-center mb-30-none">
                <div class="col-xl-12 col-md-12 col-sm-12 mb-30">
                    <p>{{__(@$notice_content->data_values->description)}}</p>
                </div>
        </div>
    </div>
</div>
<div class="survey-area mt-30">
    <div class="panel-card-header section--bg text-white">
        <div class="panel-card-title"><i class="lar la-question-circle"></i> @lang('Ads List')</div>
    </div>
    <div class="panel-card-body">
        <div class="row justify-content-center mb-30-none">
            @forelse ($surveys as $item)
                @php
                    $total_views = $item->total_views;
                    $views = !is_null($item->users) ? count($item->users) : 0;
                    $res_views = $total_views - $views;
                @endphp



                <div class="col-xl-3 col-md-6 col-sm-8 mb-30">
                    <div class="survey-list-item">
                        <div class="survey-list-body">
                            <div class="survey-list-thumb">
                                <img src="{{url($item->image)}}" alt="ads">
                            </div>
                            <div class="survey-list-content">
                                <div class="survey-list-header d-flex flex-wrap justify-content-between">
                                    <h3 class="title">{{__($item->category->name)}}</h3>
                                    <div class="survey-price">@lang('Reward'): {{($item->per_user*$general->user_amount)/100}} {{__($general->cur_text)}}</div>
                                </div>
                                <p>{{__($item->p_name)}}</p>
                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="bold">Price</div>
                                    <div class="bold">Discount Price</div>
                                </div>
                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="bold">{{$item->p_mrp}} INR</div>
                                    <div class="bold">{{$item->discount}} INR</div>
                                </div>
                                <p>Views Left: {{$res_views}}</p>
                            </div>
                        </div>
                        <div class="survey-list-footer bg--primary text-center">
                            <div class="survey-btn">
{{--                                <a href="{{route('user.survey.ad.view',$item->id)}}" class="text-white text-center">@lang('Start Survey')</a>--}}
                                <a href="javascript:void(0)" class="text-white text-center" id="strt-btn{{$item->id}}" onclick="changeButtonContent({{$item->id}})" data-toggle="modal" data-target="#exampleModalCenter{{$item->id}}">@lang('View Ad')</a>
                            </div>
                        </div>
                    </div>
                </div>


            @empty
                <p class="mb-4">{{__($empty_message)}}</p>
            @endforelse
        </div>
    </div>
    <div class="panel-card-footer-area d-flex flex-wrap align-items-center justify-content-center">
        <div class="panel-card-footer-right">
            <nav>
                {{$surveys->links()}}
            </nav>
        </div>
    </div>
</div>

<!-- Modal -->
@foreach ($surveys as $item)
    @php
        $total_views = $item->total_views;
        $views = !is_null($item->users) ? count($item->users) : 0;
        $res_views = $total_views - $views;
    @endphp
<div class="modal fade bd-example-modal-lg" id="exampleModalCenter{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Advertise Details</h5>
                <button type="button" onclick="removeStartText({{$item->id}})" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container mb-30">
                    <div class="card">
                        <div class="card-header">
                            {{__($item->p_name)}}
                            <span class="float-right">Paid Views Remaining: <span class="text-white">{{$res_views}}</span></span>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3 card border-info text-info d-none opt-msg-box" id="opt-msg-box">
                                    <div class="p-2 card-body">
                                        <div class="font-weight-bold">Sharing Information:</div>
                                        @if($item->ad_type == 1)
                                            <div class="font-weight-normal">Name, Mobile Number, Whatsapp Number, Email, Age, Anniversary, City</div>
                                        @elseif($item->ad_type == 2)
                                            <div class="font-weight-normal">Name, Contact, Email</div>
                                        @elseif($item->ad_type == 3)
                                            <div class="font-weight-normal">Name, Gender, Martial Status, Mobile Number, Whatsapp Number, Email, Age, Birthday, Anniversary, City</div>
                                        @elseif($item->ad_type == 4)
                                            <div class="font-weight-normal">Name, Gender, Martial Status, Mobile Number, Whatsapp Number, Email, Age, Birthday, Anniversary, ProfessionAnnual Income, City,Country</div>
                                        @endif
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <img src="{{ getImage(imagePath()['survey']['path'].'/'. $item->image,imagePath()['survey']['size']) }}" alt="ads">
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex flex-row">
                                        <div class="mr-auto p-2 font-weight-bold">Campaign Pass:</div>
                                        <div class="p-2">{{$item->adtype->name}}</div>
                                    </div>
                                    <div class="d-flex flex-row">
                                        <div class="mr-auto p-2 font-weight-bold">Company/Sponsor Name:</div>
                                        <div class="p-2 font-weight-bold">Campaign ID:</div>
                                    </div>
                                    <div class="d-flex flex-row">
                                        <div class="mr-auto p-2">{{$item->surveyor->firstname.' '.$item->surveyor->lastname}}</div>
                                        <div class="p-2">{{$item->id}}</div>
                                    </div>
                                    <div class="d-flex flex-row">
                                        <div class="mr-auto p-2 font-weight-bold">Product Name:</div>
                                        <div class="p-2 font-weight-bold">Radigone Points:</div>
                                    </div>
                                    <div class="d-flex flex-row">
                                        <div class="mr-auto p-2">{{__($item->p_name)}}</div>
                                        <div class="p-2">{{__(($item->per_user*$general->user_amount)/100)}}</div>
                                    </div>
                                    <div class="d-flex flex-row">
                                        <div class="mr-auto p-2 font-weight-bold">Data shared with sponsor (As per pass chosen by Sponsor)</div>
{{--                                        <div class="p-2 font-weight-bold opt-out-msg" style="cursor: pointer" id="opt-out-msg">To opt-out {{!is_null($item->surveyor->opt_out_msg) ? '('.$item->surveyor->opt_out_msg.')' : '(Share format of sponsor)'}}</div>--}}
                                        <div class="p-2 font-weight-bold opt-out-msg" style="cursor: pointer" id="opt-out-msg">To opt-out {{!is_null($item->surveyor->opt_out_msg) ? '('.$item->surveyor->opt_out_msg.')' : '(Not Available)'}}</div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="d-flex flex-row">
{{--                                        <div class="p-2 font-weight-bold">Sponsor's Rights</div>--}}
                                        @if($item->surveyor->post_arrangement == \App\Surveyor::PA_ACTIVE)
                                            <button type="button" class="btn btn-outline-success btn-rounded font-weight-bold" data-toggle="popover" title="Postpaid Active" data-content="The Radigone points will be credited in 7 Days.">Post Paid <i class="fa fa fa-exclamation-triangle"></i></button>
                                        @else
                                            <button type="button" class="btn btn-outline-danger btn-rounded font-weight-bold" data-toggle="popover" title="Postpaid Not Available" data-content="This facility is not available">Post Paid <i class="fa fa fa-exclamation-triangle"></i></button>
                                        @endif

                                        <div class="p-2 font-weight-bold">Discount Coupons</div>
                                        @if($item->surveyor->bought_views - $item->surveyor->total_views)
                                            <button type="button" class="btn btn-outline-success btn-rounded font-weight-bold" data-toggle="popover" title="Notice" data-content="This sponsor can get your contact details in case company does not credit Radigone points in your profile">Legal Abide<i class="fa fa fa-exclamation-triangle"></i></button>
                                        @else
                                            <button type="button" class="btn btn-outline-danger btn-rounded font-weight-bold" data-toggle="popover" title="Notice" data-content="This sponsor can not get your contact details in case company does not credit Radigone points in your profile">Legal Abide <i class="fa fa fa-exclamation-triangle"></i></button>
                                        @endif
                                    </div>
                                </div>
                                @if($item->online_purchase == 1)
                                <div class="col-md-12">
                                    <div class="d-flex flex-row">
                                        <div class="p-2 font-weight-bold">Purchase Link:</div>
                                        <div class="p-2 font-weight-bold"><a href="{{$item->purchas_url}}" target="_blank">{{$item->purchas_url}}</a></div>
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-12 mt-3">
                                    <div class="d-flex flex-row">
                                        @if(surveyForUserAvailable($item->id, auth()->id(), $res_views))
                                            <div class="p-2"><a href="{{route('user.survey.ad.view',$item->id)}}" class="btn btn-success text-white text-center">@lang('Continue Watching')</a></div>
                                        @else
                                            <div class="p-2"><a href="javascript:void(0)" class="btn btn-success text-white text-center" data-dismiss="modal" data-toggle="modal" data-target="#notAvailPop{{$item->id}}">@lang('Continue Watching')</a></div>
                                        @endif

                                        <div id="watch-later-snip{{$item->id}}">
                                            @if(surveyFavorite($item->id, auth()->id()))
                                                <div class="p-2"><a href="javascript:void(0)" class="btn bg--gray text-center" style="color: lightgray" onclick="watchLaterClose({{$item->id}}, {{auth()->id()}})">Watch Later</a></div>
                                            @else
                                                <div class="p-2"><a href="javascript:void(0)" class="btn btn-primary text-white text-center" onclick="watchLater({{$item->id}}, {{auth()->id()}})">Watch Later</a></div>
                                            @endif

                                        </div>
                                        <div class="p-2"><a href="{{route('user.home')}}" class="btn btn-warning text-white text-center">@lang('Skip')</a></div>
                                        <div class="p-2"><a href="{{route('user.block_surveyor', $item->surveyor_id)}}" class="btn btn-danger text-white text-center">@lang('Block Company/Sponsor')</a></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
{{--            <div class="modal-footer">--}}
{{--                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>--}}
{{--                <button type="button" class="btn btn-primary">Save changes</button>--}}
{{--            </div>--}}
        </div>
    </div>
</div>

    <div class="modal fade" id="notAvailPop{{$item->id}}" tabindex="-1" aria-labelledby="notAvailPopModalLabel{{$item->id}}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Alert</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>You will not getting paid for this because you already watch this ad.</p>
                    <div class="col-md-12 mt-3">
                        <div class="d-flex flex-row">
                            <div class="p-2"><a href="{{route('user.survey.ad.view',$item->id)}}" class="btn btn-success text-white text-center">@lang('Continue Watching')</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endforeach
    {{-- new code end--}}
        </div>
    </div>
@endsection

@push('script')
<!--chart js-->
@php
    $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    $itr = 0;
@endphp
<script src="{{asset($activeTemplateTrue.'js/chart.js')}}"></script>
<script>
   var config = {
			type: 'line',
			data: {
				labels: @php echo json_encode($months) @endphp,
				datasets: [{
					label: '@lang('Amount')',
					backgroundColor: '#{{$general->base_color}}',
					borderColor: '#{{$general->base_color}}',
					data: [
                        @foreach($months as $k => $month)
                            @if(@$withdraw_chart_data[$itr]['month'] == $month)
                                {{ @$withdraw_chart_data[$itr]['amount'] }},
                                @php $itr++; @endphp
                            @else
                                0,
                            @endif
                        @endforeach
                    ],
					fill: false,
				}]
			},
			options: {
				responsive: true,
				title: {
					display: true,
					text: '@lang('Withdraw Data Monthly')'
				},
				scales: {
					yAxes: [{
						ticks: {
							// the data minimum used for determining the ticks is Math.min(dataMin, suggestedMin)
							suggestedMin: 10,

							// the data maximum used for determining the ticks is Math.max(dataMax, suggestedMax)
							suggestedMax: 50
						}
					}]
				}
			}
		};

		window.onload = function() {
			var ctx = document.getElementById('myChart').getContext('2d');
			window.myLine = new Chart(ctx, config);
		};
</script>

    <script>
     function changeButtonContent(id)
    {
        $('#strt-btn'+id).text('Start Viewing');
    }

    function removeStartText(id)
    {
        $('#strt-btn'+id).text('Start View');
    }

    function watchLater(survey_id, user_id)
    {
        $.ajax({
            type: 'POST',
            url: '/user/survey-favorite',
            data: JSON.stringify({
                '_token'  : $('meta[name="csrf-token"]').attr('content'),
                'survey_id' : survey_id,
                'user_id' : user_id,
            }),
            // dataType: 'json',
            processData: false,
            contentType: 'application/json',
            success: function(data) {
                console.log(data);
                if(data.success) {
                    $('#watch-later-snip'+survey_id).html(`<div class="p-2"><a href="javascript:void(0)" class="btn bg--gray text-center" style="color: lightgray" onclick="watchLaterClose(${survey_id}, ${user_id})">Watch Later</a></div>`)
                }
            },
            error: function(data) {
                console.log(data)
            }
        });
    }

    function watchLaterClose(survey_id, user_id)
    {
        $.ajax({
            type: 'POST',
            url: '/user/survey-unfavorite',
            data: JSON.stringify({
                '_token'  : $('meta[name="csrf-token"]').attr('content'),
                'survey_id' : survey_id,
                'user_id' : user_id,
            }),
            // dataType: 'json',
            processData: false,
            contentType: 'application/json',
            success: function(data) {
                console.log(data);
                if(data.success) {
                    $('#watch-later-snip'+survey_id).html(`<div class="p-2"><a href="javascript:void(0)" class="btn btn-primary text-white text-center" onclick="watchLater(${survey_id}, ${user_id})">Watch Later</a></div>`)
                }
            },
            error: function(data) {
                console.log(data)
            }
        });
    }

</script>
    @push('script')
        <script>
            $(document).ready(function(){
                $('.opt-out-msg').hover(function () {
                    $('.opt-msg-box').removeClass('d-none');
                }, function () {
                    $('.opt-msg-box').addClass('d-none');
                });
            });
        </script>
@endpush

