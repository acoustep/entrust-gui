@if (count($errors->all()) > 0)
<div class="row">
  <div class="col-xs-12">
		<div class="alert alert-danger alert-dissmissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<h4>Error</h4>
			The following error has occured:
			<ul>
				{!! implode('', $errors->all('<li class="error">:message</li>')) !!}
			</ul>
		</div>
	</div>
</div>
@endif
@if ($message = Session::get('success'))
<div class="row">
  <div class="col-xs-12">
		<div class="alert alert-success alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<strong>Success:</strong> {{ $message }}
		</div>
		{{ Session::forget('success') }}
	</div>
</div>
@endif

@if ($message = Session::get('error'))
<div class="row">
  <div class="col-xs-12">
		<div class="alert alert-danger alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<strong>Error:</strong> {{ $message }}
		</div>
		{{ Session::forget('error') }}
	</div>
</div>
@endif

@if ($message = Session::get('warning'))
<div class="row">
  <div class="col-xs-12">
		<div class="alert alert-warning alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<strong>Warning:</strong> {{ $message }}
		</div>
		{{ Session::forget('warning') }}
	</div>
</div>
@endif

@if ($message = Session::get('info'))
<div class="row">
  <div class="col-xs-12">
		<div class="alert alert-info alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<strong>FYI:</strong> {{ $message }}
		</div>
		{{ Session::forget('info') }}
	</div>
</div>
@endif
