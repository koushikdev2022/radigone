<section class="call-to-action-section ptb-80 section--bg bg-overlay-black bg_img" data-background="{{asset($activeTemplateTrue.'images/banner.svg')}}">
    <div class="container">
        <div class="call-to-action-area">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-12 text-center">
                    <div class="call-to-action-content">
                        <form class="call-to-action-form">
                            <div class="row justify-content-center align-items-center mb-20-none">
                                <div class="col-lg-10 mb-20">
                                    <input type="email" name="email" id="subscriber" class="form-control" placeholder="@lang('Enter Email Address')" required>
                                </div>
                                <div class="col-lg-2 mb-20">
                                    <button type="button" class="submit-btn mt-0 subs">@lang('Submit')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('script')
<script>
    "use strict";

    $('.subs').on('click',function () {

        var email = $('#subscriber').val();
        var csrf = '{{csrf_token()}}'

        var url = "{{ route('subscriber.store') }}";
        var data = {email:email, _token:csrf};

        $.post(url, data,function(response){

            if(response.email){
                $.each(response.email, function (i, val) {
                iziToast.error({
                message: val,
                position: "topRight"
                });
            });
            } else{
                iziToast.success({
                message: response.success,
                position: "topRight"
                });
            }
        });
    });

</script>

@endpush
