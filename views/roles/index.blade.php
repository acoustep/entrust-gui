@extends('entrust-gui::app')

@section('heading', 'Roles')

@section('content')
<a class="btn btn-labeled btn-primary" href="{{ route('entrust-gui::roles.create') }}"><span class="btn-label"><i class="fa fa-plus"></i></span>Create Role</a>
<table class="table table-striped">
  <tr>
    <th>Name</th>
    <th>Actions</th>
  </tr>
  @foreach($roles as $role)
    <tr>
      <td>{{ $role->name }}</th>
      <td class="col-xs-3">
        <form action="{{ route('entrust-gui::roles.destroy', $role->id) }}" method="post">
          <input type="hidden" name="_method" value="DELETE">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <a class="btn btn-labeled btn-default" href="{{ route('entrust-gui::roles.edit', $role->id) }}"><span class="btn-label"><i class="fa fa-pencil"></i></span>Edit</a>
          <button type="submit" class="btn btn-labeled btn-danger"><span class="btn-label"><i class="fa fa-trash"></i></span>Delete</button>
        </form>
      </td>
    </tr>
  @endforeach
</table>
<div class="text-center">
  {!! $roles->render() !!}
</div>
@endsection
