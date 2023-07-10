@extends('agent.layouts.app')

@section('panel')
  <div class="row">
    <div class="col-xl-2 col-lg-2 col-sm-2 mb-30">
            <p>Refer and earn up to 1% </p>
    </div>
    <div class="col-xl-10 col-lg-10 col-sm-10 mb-30">
           <?php
           $user_id = base64_encode(auth()->guard('agent')->user()->id);
//           $url = "https://radigone.com/surveyor/register/".$user_id;
           $url = route('surveyor.register', $user_id);
           ?>
           <input type="text" id="url" value="<?php echo $url ?>" style="width: 78%;"/>
           <input type="button" value="Copy Url" onclick="Copy();" />
               <input type="button" value="Share" data-toggle="modal" data-target="#exampleModalShare" />
               <input type="button" value="SMS" data-toggle="modal" data-target="#exampleModalSMS" />
    </div>
  </div>

    <div class="row">

        <div class="col-xl-4 col-lg-4 col-sm-4 mb-30">
            <div class="dashboard-w1 bg--primary b-radius--10 box-shadow">
                <a href="" class="item--link"></a>
                <div class="icon">
                    <i class="fa fa-credit-card"></i>
                </div>
                <div class="details">
                    <div class="numbers">

                        <span class="amount">{{getAmount($agent->balance)}}</span>
                        <span class="currency-sign"></span>
                    </div>
                    <div class="desciption">
                        <span>@lang('Balance')</span>
                    </div>
                    <a href="{{route('agent.transactions')}}" class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-sm-4 mb-30">
            <div class="dashboard-w1 bg--cyan b-radius--10 box-shadow">
                <a href="" class="item--link"></a>
                <div class="icon">
                    <i class="fa fa-credit-card"></i>
                </div>
                <div class="details">
                    <div class="numbers">

                        <span class="amount">{{$total_referrals}}</span>
                        <span class="currency-sign"></span>
                    </div>
                    <div class="desciption">
                        <span>@lang('Total Refferrals')</span>
                    </div>
                    <a href="{{route('agent.refferrals')}}" class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-sm-4 mb-30">
            <div class="dashboard-w1 bg--gradi-44 b-radius--10 box-shadow">
                <a href="" class="item--link"></a>
                <div class="icon">
                    <i class="fa fa-credit-card"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{$totalTransaction}}</span>
                        <span class="currency-sign"></span>
                    </div>
                    <div class="desciption">
                        <span>@lang('Total Transation')</span>
                    </div>
                    <a href="{{route('agent.transactions')}}" class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-sm-4 mb-30">
            <div class="dashboard-w1 bg--gradi-7 b-radius--10 box-shadow">
                <a href="" class="item--link"></a>
                <div class="icon">
                    <i class="fa fa-credit-card"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{$count_approved_survey}}</span>
                        <span class="currency-sign"></span>
                    </div>
                    <div class="desciption">
                        <span>@lang('Approved Campaigns')</span>
                    </div>
                    <a href="{{route('agent.earnings')}}" class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-sm-4 mb-30">
            <div class="dashboard-w1 bg--orange b-radius--10 box-shadow ">
                <a href="" class="item--link"></a>
                <div class="icon">
                    <i class="fa fa-credit-card"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{$count_pending_survey}}</span>
                        <span class="currency-sign"></span>
                    </div>
                    <div class="desciption">
                        <span>@lang('Pending Survey')</span>
                    </div>
                    <a href="{{route('agent.earnings')}}" class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-sm-4 mb-30">
            <div class="dashboard-w1 bg--pink b-radius--10 box-shadow">
                <a href="" class="item--link"></a>
                <div class="icon">
                    <i class="fa fa-credit-card"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{getAmount($total_earning)}}</span>
                        <span class="currency-sign"></span>
                    </div>
                    <div class="desciption">
                        <span>@lang('Total Earned')</span>
                    </div>
                    <a href="{{route('agent.earnings')}}" class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
                </div>
            </div>
        </div>
        <!--<div class="col-xl-4 col-lg-4 col-sm-4 mb-30">-->
        <!--    <div class="dashboard-w1 bg--green b-radius--10 box-shadow">-->
        <!--        <a href="" class="item--link"></a>-->
        <!--        <div class="icon">-->
        <!--            <i class="fa fa-credit-card"></i>-->
        <!--        </div>-->
        <!--        <div class="details">-->
        <!--            <div class="numbers">-->

        <!--                 <span class="amount">9</span>-->
        <!--                <span class="currency-sign"></span>-->
        <!--            </div>-->
        <!--            <div class="desciption">-->
        <!--                <span>@lang('Bought Views')</span>-->
        <!--            </div>-->
                    <!-- <a href="{{route('surveyor.calendarview')}}" class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a> -->
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->
        <!--<div class="col-xl-4 col-lg-4 col-sm-4 mb-30">-->
        <!--    <div class="dashboard-w1 bg--gradi-12 b-radius--10 box-shadow">-->
        <!--        <a href="" class="item--link"></a>-->
        <!--        <div class="icon">-->
        <!--            <i class="fa fa-credit-card"></i>-->
        <!--        </div>-->
        <!--        <div class="details">-->
        <!--            <div class="numbers">-->
        <!--                <span class="amount">8</span>-->
        <!--                <span class="currency-sign"></span>-->
        <!--            </div>-->
        <!--            <div class="desciption">-->
        <!--                <span>@lang('Total Views')</span>-->
        <!--            </div>-->
                    <!-- <a href="{{route('surveyor.deposit.history')}}" class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a> -->
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-sm-12 mt-30">
                            <h5 class="card-title">@lang('Monthly Survey Response')</h5>
                            <div id="apex-line"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

  <div class="modal fade" id="exampleModalShare" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <strong class="modal-title method-name" id="exampleModalLabel">Social Share</strong>
                  <a href="javascript:void(0)" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </a>
              </div>
              <div class="modal-body">
                  <div class="a2a_kit a2a_kit_size_32 a2a_default_style" data-a2a-url="{{$url}}" data-a2a-title="{{auth()->guard('agent')->user()->fullname}} - Business Correspondent">
                      <a class="a2a_button_facebook"></a>
                      <a class="a2a_button_twitter"></a>
                      <a class="a2a_button_linkedin"></a>
                      <a class="a2a_button_facebook_messenger"></a>
                      <a class="a2a_button_telegram"></a>
                      <a class="a2a_button_pinterest"></a>
                      <a class="a2a_button_whatsapp"></a>
                      <a class="a2a_button_email"></a>
                      <a class="a2a_button_google_gmail"></a>
{{--                      <a class="a2a_dd" href="https://www.addtoany.com/share"></a>--}}
                  </div>

                  <script async src="https://static.addtoany.com/menu/page.js"></script>
              </div>
          </div>
      </div>
  </div>

  <div class="modal fade" id="exampleModalSMS" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
{{--              <div class="modal-header">--}}
{{--                  <strong class="modal-title method-name" id="exampleModalLabel"></strong>--}}
{{--                  <a href="javascript:void(0)" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                      <span aria-hidden="true">&times;</span>--}}
{{--                  </a>--}}
{{--              </div>--}}
              <form action="{{route('agent.send_msg_share')}}" method="post">
                  @csrf
                  <input type="hidden" name="message" value="{{auth()->guard('agent')->user()->fullname}} - Business Correspondent | Check now: {{$url}}">
                  <div class="modal-body">
                      <h5 class="text--primary text-center depositLimit"></h5>
                      <h5 class="text--primary text-center depositCharge"></h5>
                      <div class="form-group">
                          <input type="hidden" name="currency" class="edit-currency" value="">
                          <input type="hidden" name="method_code" class="edit-method-code" value="">
                      </div>
                      <div class="form-group">
                          <label>@lang('Enter Mobile Number'):</label>
                          <div class="input-group">
                              <input type="text" class="form-control form-control-lg" name="mobile" required=""  value="+91">
                          </div>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                      <button type="submit" class="btn btn--primary">@lang('Send')</button>
                  </div>
              </form>
          </div>
      </div>
  </div>

@endsection

@push('script')

    <script src="{{asset('assets/surveyor/js/vendor/apexcharts.min.js')}}"></script>
    <script src="{{asset('assets/surveyor/js/vendor/chart.js.2.8.0.js')}}"></script>
    <script>
        'use strict';
        // apex-line chart
        var options = {
          chart: {
            height: 400,
            type: "area",
            toolbar: {
              show: false
            },
            dropShadow: {
              enabled: true,
              enabledSeries: [0],
              top: -2,
              left: 0,
              blur: 10,
              opacity: 0.08
            },
            animations: {
              enabled: true,
              easing: 'linear',
              dynamicAnimation: {
                speed: 1000
              }
            },
          },
          dataLabels: {
            enabled: false
          },
          series: [
            {
              name: "@lang('Survey')",
              data: @php echo json_encode($survey_all) @endphp,
            }
          ],
          fill: {
            type: "gradient",
            gradient: {
              shadeIntensity: 1,
              opacityFrom: 0.7,
              opacityTo: 0.9,
              stops: [0, 90, 100]
            }
          },
          xaxis: {
            categories: @php echo json_encode($month_survey) @endphp,
          },
          grid: {
            padding: {
              left: 5,
              right: 5
            },
            xaxis: {
              lines: {
                  show: false
              }
            },
            yaxis: {
              lines: {
                  show: false
              }
            },
          },
        };

        var chart = new ApexCharts(document.querySelector("#apex-line"), options);

        chart.render();
        function Copy() {
          var Url = document.getElementById("url");
          //   console.log(Url)
          // Url.innerHTML = window.location.href;
          // console.log(Url.innerHTML)
          Url.select();
          // document.execCommand("copy");
            navigator.clipboard.writeText(Url.value);
            alert("Copied referral link: " + Url.value);
        }
    </script>
@endpush
