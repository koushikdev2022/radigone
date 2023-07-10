@extends('admin.layouts.app')

@section('panel')
    <div class="row mb-none-30">
      

        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                   
                   <!--{{ route('admin.password.update') }}-->
                    <form action="{{ route('admin.stopresume.store') }}" method="POST">
                        @csrf

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label form-control-label">@lang('Agents')</label>
                            <div class="col-lg-5">

                                <div id="reportrangeagents" name="agentsdate" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                    <i class="fa fa-calendar"></i>&nbsp;
                                    <span></span> <i class="fa fa-caret-down"></i>
                                </div>
                                <input type="hidden" class="form-control form--control" id="agentstatedate" name="agentstatedate">
                                <input type="hidden" class="form-control form--control" id="agentenddate" name="agentenddate">
                            </div>
                            <div class="col-lg-5">
                                 <select class="custom-select" id="inputGroupSelect01" name="agents" required>
                                    <!--<option selected>Choose...</option>-->
                                    <option value="stop">Stop</option>
                                    <option value="resume">Resume</option>
                                    
                                  </select>
                            </div>
                            
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label form-control-label">@lang('Surveyor')</label>
                            <div class="col-lg-5">
                               <div id="reportrangesurveyor" name="surveyordate" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                    <i class="fa fa-calendar"></i>&nbsp;
                                    <span></span> <i class="fa fa-caret-down"></i>
                                </div>
                                <input type="hidden" class="form-control form--control" id="surveyorstatedate" name="surveyorstatedate">
                                <input type="hidden" class="form-control form--control" id="surveyorenddate" name="surveyorenddate">
                            </div>
                            <div class="col-lg-5">
                                 <select class="custom-select" id="inputGroupSelect01" name="surveyor" required>
                                    <!--<option selected>Choose...</option>-->
                                    <option value="stop">Stop</option>
                                    <option value="resume">Resume</option>
                                    
                                  </select>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label form-control-label">@lang('User')</label>
                            <div class="col-lg-5">
                                <div id="reportrangeuser" name="userdate" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                    <i class="fa fa-calendar"></i>&nbsp;
                                    <span></span> <i class="fa fa-caret-down"></i>
                                </div>
                                <input type="hidden" class="form-control form--control" id="userstatedate" name="userstatedate">
                                <input type="hidden" class="form-control form--control" id="userenddate" name="userenddate">
                            </div>
                            <div class="col-lg-5">
                                 <select class="custom-select" id="inputGroupSelect01" name="user" required>
                                    <!--<option selected>Choose...</option>-->
                                    <option value="stop">Stop</option>
                                    <option value="resume">Resume</option>
                                    
                                  </select>
                            </div>
                        </div>

                        


                        <div class="form-group row">

                            <label class="col-lg-2 col-form-label form-control-label"></label>
                            <div class="col-lg-10">
                            <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Save Changes')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection




@push('style')
    <style>
        .list-left{
            width: 40%;
        }
        .list-right{
            width: calc(100% - 40%);
        }
    </style>
@endpush
@push('script')
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript">
$(function() {

var start = moment().subtract(29, 'days');
var end = moment();

function cb2(start, end) {
    $('#reportrangeagents span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    $('#agentstatedate').val(start.format('MMMM D, YYYY'));
    $('#agentenddate').val(end.format('MMMM D, YYYY'));
  
    
}

$('#reportrangeagents').daterangepicker({
    startDate: start,
    endDate: end,
    ranges: {
       'Today': [moment(), moment()],
       'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
       'Last 7 Days': [moment().subtract(6, 'days'), moment()],
       'Last 30 Days': [moment().subtract(29, 'days'), moment()],
       'This Month': [moment().startOf('month'), moment().endOf('month')],
       'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    }
}, cb2);
cb2(start, end);

function cb1(start, end) {
    $('#reportrangesurveyor span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
     $('#surveyorstatedate').val(start.format('MMMM D, YYYY'));
    $('#surveyorenddate').val(end.format('MMMM D, YYYY'));
    
}

$('#reportrangesurveyor').daterangepicker({
    startDate: start,
    endDate: end,
    ranges: {
       'Today': [moment(), moment()],
       'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
       'Last 7 Days': [moment().subtract(6, 'days'), moment()],
       'Last 30 Days': [moment().subtract(29, 'days'), moment()],
       'This Month': [moment().startOf('month'), moment().endOf('month')],
       'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    }
}, cb1);

cb1(start, end);



function cb(start, end) {
    $('#reportrangeuser span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    $('#userstatedate').val(start.format('MMMM D, YYYY'));
    $('#userenddate').val(end.format('MMMM D, YYYY'));
}

$('#reportrangeuser').daterangepicker({
    startDate: start,
    endDate: end,
    ranges: {
       'Today': [moment(), moment()],
       'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
       'Last 7 Days': [moment().subtract(6, 'days'), moment()],
       'Last 30 Days': [moment().subtract(29, 'days'), moment()],
       'This Month': [moment().startOf('month'), moment().endOf('month')],
       'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    }
}, cb);

cb(start, end);
    
});
</script>
@endpush
