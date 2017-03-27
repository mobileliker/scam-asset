{{--
version: 1.0 view 基础页
author: wuzhihui
date: 2016/9/30
description:
--}}

<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset='utf-8'>
    <!-- 优先使用 IE 最新版本和 Chrome -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <!-- 网页关键字 -->
    <meta name="keywords" content="惠林科技,惠林科技有限责任公司,林业互联网,惠林防火预警公司">
    <!-- 页面描述 -->
    <meta name="description" content="惠林科技,惠林科技有限责任公司,林业互联网,惠林防火预警公司"/>
    <!-- 网页作者 -->
    <meta name="author" content="zxck"/>
    <!-- 为移动设备添加 viewport -->
    <meta name="viewport" content="initial-scale=1, maximum-scale=3, minimum-scale=1, user-scalable=no">
    <!-- iOS 设备 begin -->
    <meta name="apple-mobile-web-app-title" content="惠林科技有限责任公司">
    <!-- 添加到主屏后的标题（iOS 6 新增） -->
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <!-- 是否启用 WebApp 全屏模式，删除苹果默认的工具栏和菜单栏 -->
    <meta name="apple-itunes-app" content="app-id=myAppStoreID, affiliate-data=myAffiliateData, app-argument=myURL">
    <!-- 启用360浏览器的极速模式(webkit) -->
    <meta name="renderer" content="webkit">
    <!-- 避免IE使用兼容模式 -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- 针对手持设备优化，主要是针对一些老的不识别viewport的浏览器，比如黑莓 -->
    <meta name="HandheldFriendly" content="true">
    <!-- 微软的老式浏览器 -->
    <meta name="MobileOptimized" content="320">
    <!-- uc强制竖屏 -->
    <meta name="screen-orientation" content="portrait">
    <!-- QQ强制竖屏 -->
    <meta name="x5-orientation" content="portrait">
    <!-- UC强制全屏 -->
    <meta name="full-screen" content="yes">
    <!-- QQ强制全屏 -->
    <meta name="x5-fullscreen" content="true">
    <!-- UC应用模式 -->
    <meta name="browsermode" content="application">
    <!-- QQ应用模式 -->
    <meta name="x5-page-mode" content="app">
    <!-- windows phone 点击无高光 -->
    <meta name="msapplication-tap-highlight" content="no">
    <!-- iOS 设备 end -->
    <meta name="msapplication-TileColor" content="#000"/>
    <!-- Windows 8 磁贴颜色 -->
    <meta name="msapplication-TileImage" content="icon.png"/>

    <!-- 网页icon -->
    <link rel="shortcut icon" href="{{asset('linxin/common/images/logo2.png')}}">
    <link rel="Bookmark" href="{{asset('linxin/common/images/logo2.png')}}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('css')

<!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
<div>
@yield('app')

<!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('script')
</div>
</body>
</html>
