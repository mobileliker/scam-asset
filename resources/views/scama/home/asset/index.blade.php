@extends(config('app.theme').'.layouts.home.content')

@section('content')
    <div class="page-header">
        <h3 style="text-align:center;">华南农业博物馆固定资产</h3>
    </div>
    <div class="panel panel-default" id="home-asset-info">
        <div class="panel-heading">
            <h3 class="panel-title">基本信息</h3>
        </div>
        @if(isset($asset))
            <table class="table table-striped table-bordered">
                <tbody>
                    <tr>
                        <td>@lang('web.post-date')</td>
                        <td>{{$asset->post_date}}</td>
                    </tr>
                    <tr>
                        <td>@lang('common.type')</td>
                        <td>{{App\Asset::TYPE[$asset->type]}}</td>
                    </tr>
                    <tr>
                        <td>@lang('common.category')</td>
                        @if(isset(App\Category::where('serial', 'like', 'category-%')->where('value','=',$asset->category_number)->first()->name))
                            <td>{{App\Category::where('serial', 'like', 'category-%')->where('value','=',$asset->category_number)->first()->name}}</td>
                        @else
                            <td></td>
                        @endif
                    </tr>
                    <tr>
                        <td>@lang('web.name')</td>
                        <td>{{$asset->name}}</td>
                    </tr>
                    <tr>
                        <td>@lang('common.serial')</td>
                        <td>{{$asset->serial}}</td>
                    </tr>
                    <tr>
                        <td>@lang('web.storage-location')</td>
                        <td>{{$asset->storage_location}}</td>
                    </tr>
                    <tr>
                        <td>@lang('web.model')</td>
                        <td>{{$asset->model}}</td>
                    </tr>
                    <tr>
                        <td>@lang('web.size')</td>
                        <td>{{$asset->size}}</td>
                    </tr>
                </tbody>
            </table>
        @else
            该固定资产不存在
        @endif
    </div>

    @if(isset($asset->image) && $asset->image != "")
    <div class="panel panel-default" id="home-asset-info">
        <div class="panel-heading">
            <h3 class="panel-title">图片</h3>
        </div>
        <div class="panel-body">
            <img src="{{$asset->image}}" width="100%">
        </div>
    </div>
    @endif
@endsection