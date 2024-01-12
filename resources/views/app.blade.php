<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>XL Shop</title>

        <!-- unpkg -->
        <script src="https://unpkg.com/@egjs/flicking/dist/flicking.pkgd.min.js" crossorigin="anonymous"></script>
        <!-- cdnjs -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/egjs-flicking/4.11.2/flicking.pkgd.min.js" crossorigin="anonymous"></script>

        <!-- unpkg -->
        <link rel="stylesheet" href="https://unpkg.com/@egjs/flicking/dist/flicking.css" />
        <link rel="stylesheet" href="https://unpkg.com/@egjs/flicking/dist/flicking-inline.css" />
        <!-- cdnjs -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/egjs-flicking/4.11.2/flicking.css" integrity="sha512-rysMP9d5q6qpqIgVFlzKteQD1hj1h2+tlMvmTazmxVs/wCOjeRCA1JATqSoZoj1i74pTm2BvRYJ7j78aYBhUBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/egjs-flicking/4.11.2/flicking-inline.css" />
        <link rel="stylesheet" href="https://naver.github.io/egjs-flicking-plugins/release/latest/dist/flicking-plugins.css">
        @vite('resources/css/app.css')
    </head>
    <body>
        <div id="app"></div>
        @vite('resources/js/app.js')
    </body>
</html>