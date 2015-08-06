@extends('entrust-gui::app')


@section('content')
<form action="{{ route('entrust-gui.users.update', $user->id) }}" method="post" role="form">
<input type="hidden" name="_method" value="put">
  @include('entrust-gui::users.partials.form')
</form>
@endsection

