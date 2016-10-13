{{--
version: 1.0 用户管理create页
author: wuzhihui
date: 2016/9/30
description:
--}}

@extends(config('app.theme').'.layouts.admin.content')

@section('content')

  <ol class="breadcrumb">
    <li><a href="{{url('admin')}}">后台</a></li>
    <li><a href="{{url('admin/asset')}}">资产管理</a></li>
    <li class="active">@lang('common.add')</li>
  </ol>

  <div class="panel panel-default">
    <div class="panel-body">

      @include(config('app.theme').'.layouts.admin.error_alert')
      @yield('error_alert')

      <form class="form-horizontal" id="form-asset" role="form" action="{{url('admin/asset')}}" method="post">
        {{ csrf_field() }}
        <div class="page-header">
          <h4>基本信息</h4>
        </div>
        <div class="col-lg-6 col-sm-12">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="post_date">@lang('web.post-date')</label>
            <div class="col-sm-10">
              <input class="form-control" id="post_date" name="post_date" type="date" placeholder="@lang('web.post-date')">
            </div>
          </div>
          {{--
          <div class="form-group">
            <label class="col-sm-2 control-label" for="gallery">展厅</label>
            <div class="col-sm-10">
              <select class="form-control" id="gallery" name="gallery">
                <option value="">请选择...</option>
                @foreach(App\Category::categories('gallery') as $category)
                  <option value="{{$category->value}}">{{$category->name}}</option>
                @endforeach
              </select>
            </div>
          </div>
          --}}
          <div class="form-group">
            <label class="col-sm-2 control-label" for="number">@lang('web.bill-number')</label>
            <div class="col-sm-10">
              <input class="form-control" id="number" name="number" type="text" placeholder="@lang('web.bill-number')">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="name">@lang('web.name')</label>
            <div class="col-sm-10">
              <input class="form-control" id="name" name="name" type="text" placeholder="@lang('web.name')">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="serial">@lang('common.serial')</label>
            <div class="col-sm-10">
              <input class="form-control" id="serial" name="serial" type="text" placeholder="@lang('common.serial')">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="course">经费科目</label>
            <div class="col-sm-10">
              <select class="form-control" id="course" name="course">
                @foreach(App\Category::categories('course') as $category)
                  <option value="{{$category->value}}">{{$category->name}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="model">@lang('web.model')</label>
            <div class="col-sm-10">
              <input class="form-control" id="model" name="model" type="text" placeholder="@lang('web.model')">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="size">@lang('web.size')</label>
            <div class="col-sm-10">
              <input class="form-control" id="size" name="size" type="text" placeholder="@lang('web.size')">
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-sm-12">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="consumer_company">@lang('web.consumer-company')</label>
            <div class="col-sm-10">
              <input class="form-control" id="consumer_company" name="consumer_company" type="text" placeholder="@lang('web.consumer-company')" value="华南农业博物馆">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="factory">@lang('web.factory')</label>
            <div class="col-sm-10">
              <input class="form-control" id="factory" name="factory" type="text" placeholder="@lang('web.factory')">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="provider">@lang('web.provider')</label>
            <div class="col-sm-10">
              <input class="form-control" id="provider" name="provider" type="text" placeholder="@lang('web.provider')">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="country">@lang('web.country')</label>
            <div class="col-sm-10">
              <select class="form-control" id="country" name="country">
                <option value="中国">中国</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="storage_location">@lang('web.storage-location')</label>
            <div class="col-sm-10">
              <div class="input-group m-bot15">
                <div class="input-group-btn">
                  <button type="button" class="btn btn-white dropdown-toggle" data-toggle="dropdown" aria-expanded="false">@lang('web.storage-location') <span class="caret"></span></button>
                  <ul class="dropdown-menu">
                    @foreach(App\Category::categories('storage_location') as $category)
                      <li><a class="a-select" href="javascript:void(0);" data-id="storage_location">{{$category->value}}</a></li>
                    @endforeach
                  </ul>
                </div>
                <input class="form-control" id="storage_location" name="storage_location" type="text">
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="application">@lang('web.application')</label>
            <div class="col-sm-10">
              <select class="form-control" id="application" name="application">
                @foreach(App\Category::categories('application') as $category)
                  <option value="{{$category->value}}">{{$category->name}}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="page-header col-lg-12">
          <h4>财务信息</h4>
        </div>
        <div class="col-lg-6 col-sm-12">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="invoice">@lang('web.invoice')</label>
            <div class="col-sm-10">
              <input class="form-control" id="invoice" name="invoice" type="text" placeholder="@lang('web.invoice')">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="purchase_number">@lang('web.purchase-number')</label>
            <div class="col-sm-10">
              <input class="form-control" id="purchase_number" name="purchase_number" type="text" placeholder="@lang('web.purchase-number')">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="purchase_date">@lang('web.purchase-date')</label>
            <div class="col-sm-10">
              <input class="form-control" id="purchase_date" name="purchase_date" type="date" placeholder="@lang('web.purchase-date')">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="card">@lang('web.card')</label>
            <div class="col-sm-10">
              <div class="input-group m-bot15">
                <div class="input-group-btn">
                  <button type="button" class="btn btn-white dropdown-toggle" data-toggle="dropdown" aria-expanded="false">@lang('web.card') <span class="caret"></span></button>
                  <ul class="dropdown-menu">
                    @foreach(App\Category::categories('card') as $category)
                      <li><a class="a-select" href="javascript:void(0);" data-id="card">{{$category->value}}</a></li>
                    @endforeach
                  </ul>
                </div>
                <input class="form-control" id="card" name="card" type="text" value="9700-32010097">
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-sm-12">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="price">@lang('web.price')</label>
            <div class="col-sm-10">
              <input class="form-control sum-cal" id="price" name="price" type="text" placeholder="@lang('web.price')">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="amount">@lang('web.amount')</label>
            <div class="col-sm-10">
              <input class="form-control sum-cal" id="amount" name="amount" type="number" placeholder="@lang('web.amount')" min="0" value="1">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="sum">@lang('web.sum')</label>
            <div class="col-sm-10">
              <input class="form-control" id="sum" name="sum" type="text" placeholder="@lang('web.sum')">
            </div>
          </div>
        </div>
        <div class="page-header col-lg-12">
          <h4>操作信息</h4>
        </div>
        <div class="form-group col-lg-4 col-sm-12">
          <label class="col-sm-2 control-label" for="entry">@lang('web.entry')</label>
          <div class="col-sm-10">
            <input class="form-control" id="entry" name="entry" type="text" placeholder="{{trans('mannal.entry')}}" value="华南农业博物馆">
          </div>
        </div>
        <div class="form-group col-lg-4 col-sm-12">
          <label class="col-sm-2 control-label" for="consumer_id">@lang('web.consumer')</label>
          <div class="col-sm-10">
            <select class="form-control" id="consumer_id" name="consumer_id">
              <?php $users = App\User::all() ?>
              @foreach($users as $user)
                <option value="{{$user->id}}">{{$user->name}}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="form-group col-lg-4 col-sm-12">
          <label class="col-sm-2 control-label" for="handler_id">@lang('web.handler')</label>
          <div class="col-sm-10">
            <select class="form-control" id="handler_id" name="handler_id">
              @foreach($users as $user)
                <option value="{{$user->id}}">{{$user->name}}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="form-group col-lg-6 col-sm-12">
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
  <script src="{{url(config('app.theme').'/js/admin/asset.js')}}"></script>
  <script>
    AdminAsset.init();
    AdminAsset.initCreate();
  </script>
@endsection