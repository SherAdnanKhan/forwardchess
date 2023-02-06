<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="description" content="Combine the book and the board! Hundreds of interactive chess ebooks from leading publishers. Browse samples to find what will improve your game.">
    <meta name="author" content="">
    <meta name="keywords" content="eCommerce">
    <meta name="robots" content="all">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Forward Chess | interactive Chess eBooks</title>

    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="{{ realAsset('theme/bootstrap.min.css') }}">

    <!-- Customizable CSS -->
    <link rel="stylesheet" href="{{ realAsset('css/app.css?v=' . env('JS_VERSION')) }}">
    <link rel="stylesheet" href="{{ realAsset('theme/slick.css?v=' . env('JS_VERSION')) }}">
    <link rel="stylesheet" href="{{ realAsset('theme/slick-theme.css') }}">

    <!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
          integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

    {{--<!-- Icons/Glyphs -->--}}
    <link rel="stylesheet" href="{{ realAsset('theme/font-awesome.min.css') }}">

    <!-- Favicon -->
    @include('site.parts.favicon')

<!-- HTML5 elements and media queries Support for IE8 : HTML5 shim and Respond.js -->
    <!--[if lt IE 9]>
    <script src="{{ realAsset('js/html5shiv.js') }}"></script>
    <script src="{{ realAsset('js/respond.min.js') }}"></script>
    <![endif]-->

    <style type="text/css">
        [v-cloak] > * {
            display: none;
        }
    </style>

    <!-- Google Analytics -->
    <script>
        window.ga = window.ga || function () {
            (ga.q = ga.q || []).push(arguments)
        };
        ga.l = +new Date;
        // ga('create', 'UA-46105122-1', 'auto');
        ga('create', 'UA-222694251-1', 'auto');
        ga('require', 'ec');
    </script>
    @include('site.parts.analytics')
    <script>
        ga('send', 'pageview');
    </script>
    <script async src='https://www.google-analytics.com/analytics.js'></script>
    <!-- End Google Analytics -->

    <script>
        var shareasaleSSCID = shareasaleGetParameterByName("sscid");

        function shareasaleSetCookie(e, a, r, s, t) {
            if (e && a) {
                var o, n = s ? "; path=" + s : "", i = t ? "; domain=" + t : "", l = "";
                r && ((o = new Date).setTime(o.getTime() + r), l = "; expires=" + o.toUTCString()), document.cookie = e + "=" + a + l + n + i
            }
        }

        function shareasaleGetParameterByName(e, a) {
            a || (a = window.location.href), e = e.replace(/[\[\]]/g, "\\$&");
            var r = new RegExp("[?&]" + e + "(=([^&#]*)|&|#|$)").exec(a);
            return r ? r[2] ? decodeURIComponent(r[2].replace(/\+/g, "")) : "" : null
        }

        shareasaleSSCID && shareasaleSetCookie("shareasaleSSCID", shareasaleSSCID, 94670778e4, "/");
    </script>

    @yield('head-scripts')
</head>
<body>

<!-- App -->

<div class="wrapper d-flex flex-column h-100vh" id="app" v-cloak>

    <vue-snotify></vue-snotify>
    @include('site.parts.navigation.top-menu-bar')
    @include('site.parts.navigation.low-menu-bar')


    <main id="main" class="flex-grow-1">
        @yield('content')
    </main>

    @include('site.parts.section.footer')
</div>

<!-- / App -->

@include('layout.js-vars')

<!-- JavaScripts placed at the end of the document so the pages load faster -->
<script src="{{ realAsset('js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ realAsset('js/jquery-migrate-1.2.1.js') }}"></script>
<script src="{{ realAsset('js/bootstrap.min.js') }}"></script>
<script src="{{ realAsset('js/app.js?v=' . env('JS_VERSION')) }}"></script>
<script src="{{ realAsset('js/slick-carousel/slick.min.js') }}"></script>
<script src="{{ realAsset('js/slick-carousel/main-slick.js') }}"></script>
{{--<script src="//maps.google.com/maps/api/js?key=AIzaSyDDZJO4F0d17RnFoi1F2qtw4wn6Wcaqxao&sensor=false&amp;language=en"></script>--}}
{{--<script src="{{ realAsset('js/gmap3.min.js') }}"></script>--}}

<script src="{{ realAsset('js/bootstrap-hover-dropdown.min.js') }}"></script>
<script src="{{ realAsset('js/css_browser_selector.min.js') }}"></script>
<script src="{{ realAsset('js/echo.min.js') }}"></script>
<script src="{{ realAsset('js/jquery.easing-1.3.min.js') }}"></script>
<script src="{{ realAsset('js/bootstrap-slider.min.js') }}"></script>
<script src="{{ realAsset('js/jquery.raty.min.js') }}"></script>
<script src="{{ realAsset('js/jquery.prettyPhoto.min.js') }}"></script>
<script src="{{ realAsset('js/jquery.customSelect.min.js') }}"></script>
<script src="{{ realAsset('js/jquery.sticky.js') }}"></script>
<script src="{{ realAsset('js/scripts.js?v=' . env('JS_VERSION')) }}"></script>

@yield('scripts')

</body>
</html>
