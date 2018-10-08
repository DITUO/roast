<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('public/css/app.css') }}" type="text/css"/>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <title>Roast</title>
    <script type="text/javascript">
        window.laravel = <?php echo json_encode(['csrfToken' => csrf_token()]);?>
    </script>
    <script src="https://webapi.amap.com/maps?v=1.4.8&key=591d65ede2c4b1ce0d80c8c1b18326fd"></script>
</head>
<body>
    <div id="app">
        <router-view></router-view>
    </div>
    <script type="text/javascript" src="{{ asset('public/js/app.js') }}">
    </script>
</body>
</html>