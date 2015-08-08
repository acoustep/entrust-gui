<html>
<head>
  <meta charset="UTF-8">
  <title>title</title>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <style>
    body {
      margin-top: 60px;
    }
  </style>
</head>
<body>
  @include('entrust-gui::partials.navigation')
  <div class="container">
    <div class="row">
      <div class="col-xs-12">
        <h1>@yield('heading')</h1>
        @include('entrust-gui::partials.notifications')
        @yield('content')
      </div>
    </div>
  </div>
</body>
</html>
