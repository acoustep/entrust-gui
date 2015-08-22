@extends(Config::get('entrust-gui.layout'))

@section('heading', 'Edit '.ucwords(str_singular($resource)))

@section('content')
<form action="{{ route('entrust-gui::'.$resource.'.update', $model->id) }}" method="post" role="form">
<input type="hidden" name="_method" value="put">
  @include('entrust-gui::roles.partials.form')
  <button type="submit" class="btn btn-labeled btn-primary"><span class="btn-label"><i class="fa fa-check"></i></span>{{ trans('entrust-gui::button.save') }}</button>
  <a class="btn btn-labeled btn-default" href="{{ route('entrust-gui::'.$resource.'.index') }}"><span class="btn-label"><i class="fa fa-chevron-left"></i></span>{{ trans('entrust-gui::button.cancel') }}</a>
</form>
@endsection
