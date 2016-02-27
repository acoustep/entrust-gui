@extends(Config::get('entrust-gui.layout'))

@section('heading', 'Create Permission')

@section('content')
<form action="{{ route('entrust-gui::permissions.store') }}" method="post" role="form">
    @include('entrust-gui::permissions.partials.form')
    <button type="submit" class="btn btn-labeled btn-primary"><span class="btn-label"><i class="fa fa-plus"></i></span>{{ trans('entrust-gui::button.create') }}</button>
    <a class="btn btn-labeled btn-default" href="{{ route('entrust-gui::permissions.index') }}"><span class="btn-label"><i class="fa fa-chevron-left"></i></span>{{ trans('entrust-gui::button.cancel') }}</a>
</form>
@endsection
