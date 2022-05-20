<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">

    <!--favicon icon-->
    <link rel="icon" type="image/png" href="/assets/img/favicon.png">

    <title>Re-Bekia</title>

    <!--web fonts-->
    <link href="//fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700,800" rel="stylesheet">
    <link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">

    <!--bootstrap styles-->
    <link href="/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!--icon font-->
    <link href="/assets/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="/assets/vendor/dashlab-icon/dashlab-icon.css" rel="stylesheet">
    <link href="/assets/vendor/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">
    <link href="/assets/vendor/themify-icons/css/themify-icons.css" rel="stylesheet">
    <link href="/assets/vendor/weather-icons/css/weather-icons.min.css" rel="stylesheet">

    <!--custom scrollbar-->
    <link href="/assets/vendor/m-custom-scrollbar/jquery.mCustomScrollbar.css" rel="stylesheet">

    <!--jquery dropdown-->
    <link href="/assets/vendor/jquery-dropdown-master/jquery.dropdown.css" rel="stylesheet">

    <!--jquery ui-->
    <link href="/assets/vendor/jquery-ui/jquery-ui.min.css" rel="stylesheet">

    <!--iCheck-->
    <link href="/assets/vendor/icheck/skins/all.css" rel="stylesheet">
    @yield('styles')
    <!--custom styles-->
    <link href="/assets/css/main.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="/assets/vendor/html5shiv.js"></script>
    <script src="/assets/vendor/respond.min.js"></script>
    <![endif]-->
</head>

<body class="fixed-nav">

    <!--navigation : sidebar & header-->
    <nav class="navbar navbar-expand-lg fixed-top navbar-light" id="mainNav">

        <!--brand name-->
        <a class="navbar-brand" href="#">
            <img class="pr-3 float-left" src="/assets/img/logo-icon.png" srcset="/assets/img/logo-icon@2x.png 2x"  alt=""/>
            Re-Bekia
        </a>
        <!--/brand name-->
        <!--responsive nav toggle-->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!--/responsive nav toggle-->

        <!--responsive rightside toogle-->
        <a href="javascript:;" class="nav-link right_side_toggle responsive-right-side-toggle">
            <i class="icon-options-vertical"> </i>
        </a>
        <!--/responsive rightside toogle-->
