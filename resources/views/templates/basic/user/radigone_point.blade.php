@extends($activeTemplate.'layouts.master')
@section('content')

    <div class="dashboard-area mt-30">
        <?php if($user->percentage !='100'){ ?>
              <p>Dear Viewer your profile is {{$user->percentage}} % completed kindly complete to earn radigone points to fulfil your daily needs <a href="/user/profile-setting" class="btn btn--base btn-lg mt-3 mb-1">Edit profile</a></p>

        <?php } ?>
      <div class="panel-card-header section--bg text-white">
            <div class="panel-card-title"><i class="las la-cloud-upload-alt"></i> @lang('Your Activity Point')</div>
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
              
        </div>
    </div>

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

