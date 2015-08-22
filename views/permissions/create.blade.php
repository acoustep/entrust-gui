@extends(Config::get('entrust-gui.layout'))

@section('heading', 'Create '.ucwords(str_singular($resource)))

@section('content')
<form action="{{ route('entrust-gui::'.$resource.'.store') }}" method="post" role="form">
    @include('entrust-gui::'.$resource.'.partials.form')
    <button type="submit" class="btn btn-labeled btn-primary"><span class="btn-label"><i class="fa fa-plus"></i></span>{{ trans('entrust-gui::button.create') }}</button>
    <a class="btn btn-labeled btn-default" href="{{ route('entrust-gui::'.$resource.'.index') }}"><span class="btn-label"><i class="fa fa-chevron-left"></i></span>{{ trans('entrust-gui::button.cancel') }}</a>
</form>
@endsection
