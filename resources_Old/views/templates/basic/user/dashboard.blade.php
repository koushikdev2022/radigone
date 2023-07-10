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
                                data-duration="1500" data-delay="0">{{getAmount($user->balance)}} {{$general->cur_text}}</div>
                            <h3 class="title text-white">@lang('Total Balance')</h3>
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
                                data-duration="1500" data-delay="0">{{getAmount($totalWithdraw)}} {{$general->cur_text}}</div>
                            <h3 class="title text-white">@lang('Total Withdarw')</h3>
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
                            <h3 class="title text-white">@lang('Total Transaction')</h3>
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
         <div class="Upcomingspecified">
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
            </div>

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
@endpush

