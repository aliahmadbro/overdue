<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Handyman {{ !empty($title) ? '| ' . $title : '' }} </title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('/public/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- pace-progress -->
    <link rel="stylesheet" href="{{ asset('/public/plugins/pace-progress/themes/black/pace-theme-flat-top.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('/public/plugins/toastr/toastr.min.css') }}">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ asset('/public/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/public/plugins/select2/css/select2.min.css')}}">
    <!-- adminlte-->
    <link rel="stylesheet" href="{{ asset('/public/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/css/custom.css') }}">

    @stack('custom_head')
    <style>
        .select2-container--default .select2-selection--single {
            padding-bottom: 28px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding: 0px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 35px !important;
        }

    </style>
</head>
