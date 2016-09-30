{{--
v1.0
功能：可关闭的错误提示
作者：吴志辉
日期：2016/8/5
--}}

@section('error_alert')
    @if (count($errors) > 0)
        <div class="alert alert-warning fade in">
            <button data-dismiss="alert" class="close close-sm" type="button">
                <i class="fa fa-times"></i>
            </button>
            <strong>{{trans('common.Whoops!')}}</strong>{{trans('common.error-tip')}}<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endsection
