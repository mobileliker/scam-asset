{{--
version: 1.0 前台模板
author: wuzhihui
date: 2016/11/9
description:
--}}

@extends('layouts.app')

@section('css')
    @parent
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
        </div>
    </nav>

    @yield('content')

    <div class="col-lg-12 admin-footer">
        <span> <i class="fa fa-copyright"></i>&nbsp;2016-2016 {{config('app.copyright')}}  All rights reserved.</span>
    </div>



@endsection



@section('script')
    @parent
    <script src="{{url(config('app.theme').'/js/common.js')}}"></script>
@endsection