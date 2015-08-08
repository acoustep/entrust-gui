@extends('entrust-gui::app')

@section('heading', 'Edit Role')

@section('content')
<form action="{{ route('entrust-gui::roles.update', $role->id) }}" method="post" role="form">
<input type="hidden" name="_method" value="put">
  @include('entrust-gui::roles.partials.form')
  <button type="submit" class="btn btn-labeled btn-primary"><span class="btn-label"><i class="fa fa-check"></i></span>Save</button>
  <a class="btn btn-labeled btn-default" href="{{ route('entrust-gui::roles.index') }}"><span class="btn-label"><i class="fa fa-chevron-left"></i></span>Cancel</a>
</form>
@endsection

