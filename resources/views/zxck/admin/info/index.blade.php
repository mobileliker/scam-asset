{{--
version: 1.0 配置管理index页
author: wuzhihui
date: 2016/9/30
description:
--}}

@extends(config('app.theme', 'zxck').'.layouts.admin.content')

@section('content')
<ol class="breadcrumb">
  <li><a href="{{url('admin')}}">后台</a></li>
  <li class="active">配置管理</li>
</ol>

<div class="panel panel-default">
  <div class="panel-body">
    <div class="col-lg-4 col-md-4 col-sm-10 col-xs-10">
    	<form action="{{url('admin/info')}}" method="GET">
		    <div class="input-group">
		      <input name="query_text" type="text" class="form-control">
		      <span class="input-group-btn">
		        <button class="btn btn-default" type="button submit">@lang('common.search')</button>
		      </span>
		    </div>
    	</form>
    </div>
    <div class="pull-right col-lg-1 col-md-1 col-sm-2 col-xs-2">
    	<a class="form-control btn btn-success" href="{{url('/admin/info/create')}}">@lang('common.add')</a>
    </div>
  </div>
</div>

<div class="panel panel-default">
  <div class="panel-body">
  	<table class="table table-striped">
  		<thead>
  			<tr>
  				<th>#</th>
  				<th>@lang('common.key')</th>
  				<th>@lang('common.value')</th>
          <th>@lang('common.operate')</th>
  			</tr>
  		</thead>
  		<tbody>
  			@foreach($infos as $info)
        <tr>
          <td><input class="checkbox-batch" name="checkbox[]" type="checkbox" data-group="infos" data-id="{{$info->id}}"></td>
          <td>{{$info->key}}</td>
          <td>{{$info->value}}</td>
          <td>
            <a class="btn btn-primary btn-xs" href="{{url('admin/info/'.$info->id.'/edit')}}">
              <i class="fa fa-pencil"></i>
            </a>
            <form class="form-operate-delete" action="{{url('admin/info/'.$info->id)}}" method="POST">
              {{ csrf_field() }}
              <input name="_method" type="hidden" value="DELETE">
              <button class="btn btn-danger btn-xs" id="delete" name="delete" type="submit">
                  <i class="fa fa-trash-o"></i>
              </button>
            </form>
          </td>
        </tr>
        @endforeach
  		</tbody>
  	</table>
  </div>
</div>

<div class="panel panel-default admin-toolbar">
  <div class="panel-body">
      <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
      <input class="checkbox-all" type="checkbox" data-group-name="infos">
      </div>
      <div class="col-lg-1 col-md-1 col-sm-2 col-xs-2">
        <button class="form-control btn btn-danger" id="batch_delete" type="button">@lang('common.delete')</button>
      </div>

      <div class="pull-right">
      {!! $infos->appends(['query_text' => old('query_text')])->links() !!}
      </div>
  </div>
</div>

@endsection


@section('script')
@parent
<script src="{{url(config('theme', 'zxck').'/js/admin/info.js')}}"></script>
<script>
  $().ready(function(){
    AdminInfo.init();
    AdminInfo.initIndex();
  });
</script>
@endsection
