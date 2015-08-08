@extends('entrust-gui::app')

@section('heading', 'Edit User')

@section('content')
<form action="{{ route('entrust-gui.users.update', $user->id) }}" method="post" role="form">
<input type="hidden" name="_method" value="put">
  @include('entrust-gui::users.partials.form')
</form>
@endsection

