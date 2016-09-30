{{--
version: 1.0 jquery.validate location
author: wuzhihui
date: 2016/9/30
description:
--}}

@section('jquery_validate')
@if(App::getLocale() != "en")
<script>
  jQuery.extend(jQuery.validator.messages, {
    required: "{{trans('jquery_validate.required')}}",
    remote: "{{trans('jquery_validate.remote')}}",
    email: "{{trans('jquery_validate.email')}}",
    url: "{{trans('jquery_validate.url')}}",
    date: "{{trans('jquery_validate.date')}}",
    dateISO: "{{trans('jquery_validate.dateISO')}}",
    number: "{{trans('jquery_validate.number')}}",
    digits: "{{trans('jquery_validate.digits')}}",
    creditcard: "{{trans('jquery_validate.creditcard')}}",
    equalTo: "{{trans('jquery_validate.equalTo')}}",
    accept: "{{trans('jquery_validate.accept')}}",
    maxlength: jQuery.validator.format("{{trans('jquery_validate.maxlength')}}"),
    minlength: jQuery.validator.format("{{trans('jquery_validate.minlength')}}"),
    rangelength: jQuery.validator.format("{{trans('jquery_validate.rangelength')}}"),
    range: jQuery.validator.format("{{trans('jquery_validate.range')}}"),
    max: jQuery.validator.format("{{trans('jquery_validate.max')}}"),
    min: jQuery.validator.format("{{trans('jquery_validate.min')}}"),
  });
</script>
@endif


<script type="text/javascript">
  $(function(){
    //matching mobile
    jQuery.validator.addMethod("mobile", function(value, element) {
      var length = value.length;
      return this.optional(element) || (length == 11 && /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/.test(value));
    }, "{{trans('jquery_validate.mobile')}}");
    // matching QQ
    jQuery.validator.addMethod("qq", function(value, element) {
      return this.optional(element) || /^[1-9]\d{4,12}$/;
    }, "{{trans('jquery_validate.qq')}}");
    // matching Wechat
    jQuery.validator.addMethod("wechat", function(value, element) {
      return this.optional(element) || /^[a-zA-Z\d_]{5,}$/;
    }, "{{trans('jquery_validate.wechat')}}");
  });
</script>



@endsection