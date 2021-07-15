<!DOCTYPE html>
<html>
<head>
  <title>@yield('title', 'Gao App') - MyLaravel </title>
  <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
  @include('layouts._header')

  <div class="container">
    @include('shared._messages')
    @yield('content')
    @include('layouts._footer')
  </div>
  <script src="/js/app.js"></script>
</body>
</html>
