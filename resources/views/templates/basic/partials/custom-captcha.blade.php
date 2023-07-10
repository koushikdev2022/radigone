@if(\App\Extension::where('act', 'custom-captcha')->where('status', 1)->first())
    @php echo  getCustomCaptcha() @endphp

    <div class="mt-2">
        <label for="">@lang('Enter The Code')</label>
        <input type="text" name="captcha" class="form-control form--control" id="pass" required>
    </div>
@endif
