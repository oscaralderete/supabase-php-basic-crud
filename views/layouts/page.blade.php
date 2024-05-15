<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page_title')</title>

    <link rel="stylesheet" href="public/css/pure.3.0.0.css">
    <link rel="stylesheet" href="public/css/OA.1.0.2.css">
    <link rel="stylesheet" href="public/css/styles.css">
    @yield('css')

    <script src="public/js/alpine.3.13.10.js" defer></script>
    <script src="public/js/axios.1.6.8.js"></script>
    <script src="public/js/OA.1.0.2.js" defer></script>
</head>

<body>
    <main>@yield('body')</main>

    <oa-loader></oa-loader>
    <oa-dialogs></oa-dialogs>
    <oa-toast></oa-toast>

    @stack('scripts')
    <script src="public/js/scripts.js"></script>
</body>

</html>
