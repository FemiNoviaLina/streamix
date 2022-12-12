<!DOCTYPE html>
<html lang="en">
<head>
    @vite('resources/css/app.css')
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StreaMix</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/sm.ico') }}">
</head>
<body>
    {{ $slot }}
</body>
</html>