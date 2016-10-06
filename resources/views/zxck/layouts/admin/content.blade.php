{{--
version: 1.0 后台模板
author: wuzhihui
date: 2016/9/30
description:
--}}

@extends('layouts.app')

@section('css')
@parent
<link href="//cdn.bootcss.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
@endsection

@section('app')
    <nav class="navbar navbar-default navbar-static-top admin-navar-top">
        <div class="container-fluid">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name') }}
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <a href="{{url('/')}}">@lang('common.index')</a>
                    </li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{ url('/logout') }}"
                                    onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                    @lang('common.logout')
                                </a>
                                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


	<div class="col-lg-2 admin-navar-left">
        <div class="list-group">
            <li class="list-group-item">后台管理</li>
            <a href="/admin" class="list-group-item" id="left-nav-index-index">@lang('common.index')</a>
        </div>
        <div class="list-group">
            <li class="list-group-item">系统管理</li>
            <a href="{{url('/admin/info')}}" class="list-group-item" id="left-nav-info-manager">
                <i class="fa fa-info" aria-hidden="true"></i>&nbsp;&nbsp;配置管理
            </a>
        </div>
    </div>
    <div class="col-lg-10">
    	@yield('content')
    </div>
    <div class="col-lg-12 admin-footer">
        <span> <i class="fa fa-copyright"></i>&nbsp;2016-2016 {{config('app.copyright')}}  All rights reserved.</span>
    </div>

@endsection



@section('script')
    @parent
    <script src="{{url(config('theme', 'zxck').'/js/common.js')}}"></script>
    <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.min.js"></script>

    @include('layouts.jquery_validate')
    @yield('jquery_validate')
@endsection