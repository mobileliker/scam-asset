{{--
version: 1.0 配置管理create页
author: wuzhihui
date: 2016/9/30
description:
--}}

@extends(config('theme', 'zxck').'.layouts.admin.content')

@section('content')

<ol class="breadcrumb">
  <li><a href="{{url('admin')}}">后台</a></li>
  <li><a href="{{url('admin/info')}}">配置管理</a></li>
  <li class="active">@lang('common.add')</li>
</ol>

<div class="panel panel-default">
  <div class="panel-body">

      @include(config('theme', 'zxck').'.layouts.admin.error_alert')
      @yield('error_alert')

		<form class="form-horizontal" id="form-info" role="form" action="{{url('admin/info')}}" method="post">
			{{ csrf_field() }}
		  <div class="form-group">
		    <label for="key" class="col-sm-2 control-label">@lang('common.key')</label>
		    <div class="col-sm-10">
		      <input class="form-control" id="key" name="key" type="text" placeholder="@lang('common.key')">
		    </div>
		  </div>
		  <div class="form-group">
		    <label for="value" class="col-sm-2 control-label">@lang('common.value')</label>
		    <div class="col-sm-10">
		      <input class="form-control" id="value" name="value" type="text" placeholder="@lang('common.value')">
		    </div>
		  </div>
		  <div class="form-group">
		    <div class="col-sm-offset-2 col-sm-10">
		      <button type="submit" class="btn btn-default">@lang('common.save')</button>
		    </div>
		  </div>
		</form>
  </div>
</div>

@endsection


@section('script')
@parent
<script src="{{url(config('theme', 'zxck').'/js/admin/info.js')}}"></script>
<script>
	AdminInfo.init();
	AdminInfo.initCreate();
</script>
@endsection