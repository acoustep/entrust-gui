@extends('entrust-gui::app')

@section('heading', 'Users')

@section('content')
<table class="table table-striped">
  <tr>
    <th>Email</th>
    <th>Actions</th>
  </tr>
  @foreach($users as $user)
    <tr>
      <td>{{ $user->email }}</th>
      <td>
        <form action="{{ route('entrust-gui.users.destroy', $user->id) }}" method="post">
          <input type="hidden" name="_method" value="DELETE">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <a href="{{ route('entrust-gui.users.edit', $user->id) }}" class="btn btn-default">Edit</a>
          <button class="btn btn-danger" type="submit">Delete</button>
        </form>
      </td>
    </tr>
  @endforeach
</table>
<a href="{{ route('entrust-gui.users.create') }}" class="btn btn-primary">Create User</a>
@endsection
