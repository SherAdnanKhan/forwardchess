<!DOCTYPE html>
<html class="fixed" lang="{{ app()->getLocale() }}">
<head>

    <!-- Basic -->
    <meta charset="UTF-8">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <meta name="keywords" content="HTML5 Admin Template"/>
    <meta name="description" content="Porto Admin - Responsive HTML5 Template">
    <meta name="author" content="okler.net">

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>

    <!-- Web Fonts  -->
    <link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet"
          type="text/css">

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{ realAsset('backend/vendor/bootstrap/css/bootstrap.css') }}"/>

    <link rel="stylesheet" href="{{ realAsset('backend/vendor/font-awesome/css/font-awesome.css') }}"/>
    <link rel="stylesheet" href="{{ realAsset('backend/vendor/magnific-popup/magnific-popup.css') }}"/>
    <link rel="stylesheet" href="{{ realAsset('backend/vendor/jquery-datatables-bs3/assets/css/datatables.css') }}"/>

    <!-- Theme CSS -->
    <link rel="stylesheet" href="{{ realAsset('backend/css/theme.css') }}"/>

    <!-- Skin CSS -->
    <link rel="stylesheet" href="{{ realAsset('backend/css/skins/default.css') }}"/>

    <!-- Theme Custom CSS -->
    <link rel="stylesheet" href="{{ realAsset('backend/css/theme-custom.css') }}">

    <link rel="stylesheet" href="{{ realAsset('backend/css/app.css?v=' . env('JS_VERSION')) }}">

@include('site.parts.favicon')

<!-- Head Libs -->
    <script src="{{ realAsset('backend/vendor/modernizr/modernizr.js') }}"></script>

</head>
<body>
<section class="body" id="app">
    <vue-snotify></vue-snotify>

    @include('backend.parts.header')
    <div class="inner-wrapper">
        <sidebar></sidebar>

        <section role="main" class="content-body">
            <router-view/>
        </section>
    </div>
</section>

@include('layout.js-vars')

<!-- Vendor -->
<script src="{{ realAsset('backend/vendor/jquery/jquery.js') }}"></script>
<script src="{{ realAsset('backend/vendor/jquery-browser-mobile/jquery.browser.mobile.js') }}"></script>
<script src="{{ realAsset('backend/vendor/bootstrap/js/bootstrap.js') }}"></script>
<script src="{{ realAsset('backend/vendor/magnific-popup/jquery.magnific-popup.js') }}"></script>
<script src="{{ realAsset('backend/vendor/jquery-placeholder/jquery-placeholder.js') }}"></script>
<script src="{{ realAsset('backend/vendor/nanoscroller/nanoscroller.js') }}"></script>
<script src="{{ realAsset('backend/vendor/jquery-datatables/media/js/jquery.dataTables.js') }}"></script>
<script src="{{ realAsset('backend/vendor/jquery-datatables-bs3/assets/js/datatables.js') }}"></script>

<script src="{{ realAsset('backend/js/app.js?v=' . env('JS_VERSION')) }}"></script>

</body>
</html>
