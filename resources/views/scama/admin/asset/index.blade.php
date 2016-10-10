{{--
version: 1.0 资产管理index页
author: wuzhihui
date: 2016/10/9
description:
--}}

@extends(config('app.theme').'.layouts.admin.content')

@section('content')
  <ol class="breadcrumb">
    <li><a href="{{url('admin')}}">后台</a></li>
    <li class="active">资产管理</li>
  </ol>

  <div class="panel panel-default">
    <div class="panel-body">
      <form class="form-admin-search"action="{{url('admin/asset')}}" method="GET">
        <div class="col-lg-2 col-md-2 col-sm-12">
          <div class="form-group">
             <label class="control-label col-lg-2" for="post_date_start">从</label>
             <div class="col-lg-10">
               <input name="post_date_start" type="date" class="form-control" value="{{old('post_date_start')}}" placeholder="入账日期开始日期">
             </div>
          </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-12">
          <div class="form-group">
             <label class="control-label col-lg-2" for="post_date_end">到</label>
             <div class="col-lg-10">
               <input name="post_date_end" type="date" class="form-control" value="{{old('post_date_end')}}" placeholder="入账日期结束日期">
             </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-10 col-xs-10">
          <div class="input-group">
            <input name="query_text" type="text" class="form-control" value="{{old('query_text')}}" placeholder="请输入名字进行查询">
            <input id="_sort" name="_sort" type="hidden">
            <input id="_order" name="_order" type="hidden">
  		      <span class="input-group-btn">
  		        <button class="btn btn-default" type="button submit">@lang('common.search')</button>
  		      </span>
          </div>
        </div>
      </form>
      <div class="pull-right col-lg-1 col-md-1 col-sm-2 col-xs-2">
        <a class="form-control btn btn-success" href="{{url('/admin/asset/create')}}">@lang('common.add')</a>
      </div>
    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-body">
      <table class="table table-striped">
        <thead>
        <tr>
          <th>#</th>
          <th><a class="admin-order-group" data-sort="post_date" data-order='asc' href="javascript:void(0)">@lang('web.post-date')</a></th>
          <th><a class="admin-order-group" data-sort="number" data-order='asc' href="javascript:void(0)">@lang('web.bill-number')</a></th>
          <th>@lang('web.name')</th>
          <th>@lang('web.model')</th>
          <th>@lang('web.size')</th>
          <th>@lang('web.factory')</th>
          <th>@lang('web.provider')</th>
          <th>@lang('web.invoice')</th>
          <th><a class="admin-order-group" data-sort="purchase_number" data-order='asc' href="javascript:void(0)">@lang('web.purchase-number')</a></th>
          <th><a class="admin-order-group" data-sort="price" data-order='asc' href="javascript:void(0)">@lang('web.price')(@lang('common.yuan'))</a></th>
          <th><a class="admin-order-group" data-sort="amount" data-order='asc' href="javascript:void(0)">@lang('web.amount')</a></th>
          <th><a class="admin-order-group" data-sort="sum" data-order='asc' href="javascript:void(0)">@lang('web.sum')(@lang('common.yuan'))</a></th>
          <th>@lang('common.operate')</th>
        </tr>
        </thead>
        <tbody>
        @foreach($assets as $asset)
          <tr>
            <td><input class="checkbox-batch" name="checkbox[]" type="checkbox" data-group="assets" data-id="{{$asset->id}}"></td>
            <td>{{$asset->post_date}}</td>
            <td>{{$asset->number}}</td>
            <td>{{$asset->name}}</td>
            <td>{{$asset->model}}</td>
            <td>{{$asset->size}}</td>
            <td>{{$asset->factory}}</td>
            <td>{{$asset->provider}}</td>
            <td>{{$asset->invoice}}</td>
            <td>{{$asset->purchase_number}}</td>
            <td>{{$asset->price}}</td>
            <td>{{$asset->amount}}</td>
            <td>{{$asset->sum}}</td>
            <td>
              <a class="btn btn-primary btn-xs" href="{{url('admin/asset/'.$asset->id.'/edit')}}">
                <i class="fa fa-pencil"></i>
              </a>
              <form class="form-operate-delete" action="{{url('admin/asset/'.$asset->id)}}" method="POST">
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
        <input class="checkbox-all" type="checkbox" data-group-name="assets">
      </div>
      <div class="col-lg-1 col-md-1 col-sm-2 col-xs-2">
        <button class="form-control btn btn-danger" id="batch_delete" type="button">@lang('common.delete')</button>
      </div>

      <div class="pull-right">
        {!! $assets->appends(['query_text' => old('query_text'),'_sort' => old('_sort'), '_order' => old('_order')])->links() !!}
      </div>
    </div>
  </div>

@endsection


@section('script')
  @parent
  <script src="{{url(config('app.theme').'/js/admin/asset.js')}}"></script>
  <script src="{{url(config('app.theme').'/js/util.js')}}"></script>
  <script>
    $().ready(function(){
      AdminAsset.init();
      AdminAsset.initIndex();
      Util.initOrder("{{old('_sort')}}", "{{old('_order')}}");
    });
  </script>
@endsection
