@extends('entrust-gui::app')

@section('heading', 'Create User')

@section('content')
<form action="{{ route('entrust-gui.users.store') }}" method="post" role="form">
  @include('entrust-gui::users.partials.form')
</form>
@endsection
