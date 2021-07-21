<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Chat Project</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
        <!-- Styles -->
        <script>
            // 로그인 인증정보
            let userAuth = <?php echo $user;?>;
        </script>
        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <body>
        <div id="app"></div>
    </body>
</html>
