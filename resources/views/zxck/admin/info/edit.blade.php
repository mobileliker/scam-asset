{{--
version: 1.0 配置管理edit页
author: wuzhihui
date: 2016/9/30
description:
--}}

@extends(config('app.theme').'.layouts.admin.content')

@section('content')

<ol class="breadcrumb">
  <li><a href="{{url('admin')}}">后台</a></li>
  <li><a href="{{url('admin/info')}}">配置管理</a></li>
  <li class="active">@lang('common.edit')</li>
</ol>

<div class="panel panel-default">
  <div class="panel-body">

      @include(config('app.theme').'.layouts.admin.error_alert')
      @yield('error_alert')

		<form class="form-horizontal" id="form-info" role="form" action="{{url('admin/info/'.$info->id)}}" method="post">
			{{ csrf_field() }}
			<input name="_method" type="hidden" value="PUT">
			<input type="hidden" id="id" name="id" value="{{$info->id}}">
		  <div class="form-group">
		    <label for="key" class="col-sm-2 control-label">@lang('common.key')</label>
		    <div class="col-sm-10">
		      <input class="form-control" id="key" name="key" type="text" placeholder="@lang('common.key')" value="{{$info->key}}">
		    </div>
		  </div>
		  <div class="form-group">
		    <label for="value" class="col-sm-2 control-label">@lang('common.value')</label>
		    <div class="col-sm-10">
		      <input class="form-control" id="value" name="value" type="text" placeholder="@lang('common.value')" value="{{$info->value}}">
		    </div>
		  </div>
		  <div class="form-group">
		    <label for="type" class="col-sm-2 control-label">@lang('common.type')</label>
		    <div class="col-sm-10">
		    	<select class="form-control" id="type" name="type">
		    		@foreach(App\User::TYPE as $key=>$type)
		    		<option value="{{$key}}">{{$type}}</option>
		    		@endforeach
		    	</select>
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
<script src="{{url(config('app.theme').'/js/admin/info.js')}}"></script>
<script>
  $().ready(function(){
    AdminInfo.init("{{$user->type}}");
    AdminInfo.initEdit();
  });
</script>
@endsection
