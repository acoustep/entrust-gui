@extends('entrust-gui::app')

@section('heading', 'Create User')

@section('content')
<form action="{{ route('entrust-gui::users.store') }}" method="post" role="form">
  @include('entrust-gui::users.partials.form')
  <button type="submit" class="btn btn-labeled btn-primary"><span class="btn-label"><i class="fa fa-plus"></i></span>Create</button>
  <a class="btn btn-labeled btn-default" href="{{ route('entrust-gui::users.index') }}"><span class="btn-label"><i class="fa fa-chevron-left"></i></span>Cancel</a>
</form>
@endsection
