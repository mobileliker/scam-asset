@extends('layouts.app')

@section('app')
    <div class="container-fluid" id="auth-login">
        <div class="row">
            <div class="col-lg-6 col-lg-offset-3 h-middle" id="auth-login-panel">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-7" id="auth-login-panel-show">
                            <img src="{{asset(config('app.theme').'/images/login-show.jpg')}}" width="100%">
                        </div>
                        <div class="col-lg-5" id="auth-login-panel-input">
                            <div id="auth-login-panel-input-logo">
                                <img src="{{asset(config('app.theme').'/images/login-logo.png')}}" width="100%">
                            </div>
                            <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <input type="text" class="form-control" id="email" name="email" placeholder="邮箱"> 
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="密码">
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember"> 记住我
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-success form-control" type="submit">登录</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection