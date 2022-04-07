@extends(Config::get('entrust-gui.layout'))

@section('heading', 'Roles')

@section('content')
<div class="models--actions">
  <div class="col-lg-4">
      <a class="btn btn-labeled btn-primary" href="{{ route('entrust-gui::roles.create') }}"><span class="btn-label"><i class="fa fa-plus"></i></span>{{ trans('entrust-gui::button.create-role') }}</a>
  </div>
  <div class="col-md-4 col-md-offset-4">
    <form action="" class="input-group" method="get">
      <input type="search" name="search" value="{{request('search', '')}}" placeholder="Search" type="text" class="form-control" placeholder="Search for...">
      <span class="input-group-btn">
        <button class="btn btn-default" type="button">Go!</button>
      </span>
    </form>
  </div><!-- /.col-lg-6 -->
</div>
<table class="table table-striped">
    <tr>
        <th>Name</th>
        <th>Actions</th>
    </tr>
    @foreach($models as $model)
        <tr>
            <td>{{ $model->name }}</th>
            <td class="col-xs-3">
                <form action="{{ route('entrust-gui::roles.destroy', $model->id) }}" method="post">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <a class="btn btn-labeled btn-default" href="{{ route('entrust-gui::roles.edit', $model->id) }}"><span class="btn-label"><i class="fa fa-pencil"></i></span>{{ trans('entrust-gui::button.edit') }}</a>
                    <button type="submit" class="btn btn-labeled btn-danger"><span class="btn-label"><i class="fa fa-trash"></i></span>{{ trans('entrust-gui::button.delete') }}</button>
                </form>
            </td>
        </tr>
    @endforeach
</table>
<div class="text-center">
    {!! $models->render() !!}
</div>
@endsection
