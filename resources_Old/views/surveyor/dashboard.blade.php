@extends('surveyor.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-xl-4 col-lg-4 col-sm-4 mb-30">
            <div class="dashboard-w1 bg--primary b-radius--10 box-shadow">
                <a href="" class="item--link"></a>
                <div class="icon">
                    <i class="fa fa-credit-card"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{getAmount($surveyor->balance)}} {{ $general->cur_sym }}</span>
                        <span class="currency-sign"></span>
                    </div>
                    <div class="desciption">
                        <span>@lang('Balance')</span>
                    </div>
                    <a href="{{route('surveyor.deposit.history')}}" class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
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
                        <span class="amount">{{getAmount($totalDeposit)}} {{ $general->cur_sym }}</span>
                        <span class="currency-sign"></span>
                    </div>
                    <div class="desciption">
                        <span>@lang('Total Deposit')</span>
                    </div>
                    <a href="{{route('surveyor.deposit.history')}}" class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
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
                    <a href="{{route('surveyor.transactions')}}" class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
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
                        <span class="amount">{{$approvedSurvey}}</span>
                        <span class="currency-sign"></span>
                    </div>
                    <div class="desciption">
                        <span>@lang('Total Approved Campaign')</span>
                    </div>
                    <a href="{{ route('surveyor.survey.all') }}" class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
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
                        <span class="amount">{{$pendingSurvey}}</span>
                        <span class="currency-sign"></span>
                    </div>
                    <div class="desciption">
                        <span>@lang('Total Pending Campaign')</span>
                    </div>
                    <a href="{{ route('surveyor.survey.all') }}" class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
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
                        <span class="amount">{{$rejectedSurvey}}</span>
                        <span class="currency-sign"></span>
                    </div>
                    <div class="desciption">
                        <span>@lang('Total Rejected Campaign')</span>
                    </div>
                    <a href="{{ route('surveyor.survey.all') }}?rejected=3" class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-sm-4 mb-30">
            <div class="dashboard-w1 bg--green b-radius--10 box-shadow">
                <a href="" class="item--link"></a>
                <div class="icon">
                    <i class="fa fa-credit-card"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{getAmount($bought_views)}}</span>
                        <span class="currency-sign"></span>
                    </div>
                    <div class="desciption">
                        <span>@lang('Bought Views')</span>
                    </div>
                    <!-- <a href="{{route('surveyor.calendarview')}}" class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a> -->
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-sm-4 mb-30">
            <div class="dashboard-w1 bg--gradi-12 b-radius--10 box-shadow">
                <a href="" class="item--link"></a>
                <div class="icon">
                    <i class="fa fa-credit-card"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{getAmount($pending_views)}} </span>
                        <span class="currency-sign"></span>
                    </div>
                    <div class="desciption">
                        <span>@lang('Pending Views')</span>
                    </div>
                    <!-- <a href="{{route('surveyor.deposit.history')}}" class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a> -->
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-sm-4 mb-30">
            <div class="dashboard-w1 bg--1 b-radius--10 box-shadow">
                <a href="" class="item--link"></a>
                <div class="icon">
                    <i class="fa fa-credit-card"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{getAmount($total_views)}} </span>
                        <span class="currency-sign"></span>
                    </div>
                    <div class="desciption">
                        <span>@lang('Total Views')</span>
                    </div>
                    <!-- <a href="{{route('surveyor.deposit.history')}}" class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a> -->
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-sm-12 mt-30">
                            <h5 class="card-title">@lang('Monthly Campaign Response')</h5>
                            <div id="apex-line"></div>
                        </div>
                    </div>
                </div>
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
    </script>
@endpush
