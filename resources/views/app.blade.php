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
</head>
<body>
    <div id="app">
        <router-view></router-view>
    </div>
    <script type="text/javascript" src="{{ asset('public/js/app.js') }}">
    </script>
</body>
</html>