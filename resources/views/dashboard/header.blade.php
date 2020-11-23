<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Homepage</title>
    <link rel="stylesheet" href="{{ asset('dashboard/css/bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('dashboard/css/bootstrap-rtl.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('dashboard/css/main.css') }}"/>
    <link rel="stylesheet" href="{{ asset('dashboard/css/table.css') }}"/>
{{--    <link rel="stylesheet"--}}
{{--          href="https://fonts.googleapis.com/css?family=Open+Sans|Public+Sans|Roboto|Source+Sans+Pro&amp;display=swap"/>--}}
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/printThis.js') }}" defer></script>

    <style>
        .group {
            margin: 200px;
            position:relative;
        }

        .myInput:focus::-webkit-input-placeholder {
            visibility:hidden; /*hide placeholder on focus*/
        }

        .myInput {
            padding: 5px;
        }

        .myInput:focus {
            border: 1px solid dodgerblue;
            border-radius:3px;
        }

        .myInput-label {
            display: none;
            font-size:11px;
            color: #5c5a5a;
            border-radius: 50px;
            font-family:sans-serif;
            background-color: #fff;
            padding: 0 3px;
        }



        /*style of label when input is focused*/
        .myInput:focus + .myInput-label  {
            display: inline-block;
            position:absolute;
            left: 12px;
            top: 0px;
        }
    </style>

</head>
<body class="rtl">
<div class="page home-page" id="app">
    @include('dashboard.sidebar')

    <div class="content-area">
        <div class="header"><i class="fa fa-exchange fa-lg toggle-sidebar"></i>
            <div class="dropdown notifications-container"><i class="fa fa-bell fa-lg header-icon toggle-notifications"
                                                             id="notifications" data-toggle="dropdown"
                                                             aria-haspopup="true" aria-expanded="false"></i>
                <div class="dropdown-menu" aria-labelledby="notifications">
                    <div class="noti-head">Notifications <span>50</span></div>
                    <div class="noti-content">
                        <ul class="list-unstyled">
                            <li><i class="fa fa-envelope-o fa-lg"> </i>You Got Email</li>
                            <li><i class="fa fa-money fa-lg"> </i>You Got Money</li>
                            <li><i class="fa fa-thumbs-o-up fa-lg"> </i>You Got Like</li>
                            <li><i class="fa fa-envelope-o fa-lg"> </i>You Got</li>
                            <li><i class="fa fa-money fa-lg"> </i>You Got Money</li>
                        </ul>
                    </div>
                    <div class="noti-footer text-center"><a href="#">View All Notifications</a></div>
                </div>
                <i class="fa fa-expand fa-lg toggle-fullscreen header-icon"></i>
            </div>
{{--            <div class="dropdown usermenu-container">--}}
{{--                <div class="username" id="usermenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
{{--                    <img class="avatar arounded-circle" src="{{ asset('dashboard/imgs/profile.png') }}"--}}
{{--                         alt="Avtar Img"/><span class="user">Ali Amer</span><i class="fa fa-angle-down fa-lg"></i></div>--}}
{{--                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="usermenu">--}}
{{--                    <div class="user-info">--}}
{{--                        <h3>Ali Amer</h3>--}}
{{--                        <p>Premuim Member</p>--}}
{{--                    </div>--}}
{{--                    <hr/>--}}
{{--                    <ul class="activities-list list-unstyled">--}}
{{--                        <li>Added Comments</li>--}}
{{--                        <li>Create Comments</li>--}}
{{--                        <li>Create Post</li>--}}
{{--                        <li>Added Images</li>--}}
{{--                        <hr/>--}}
{{--                        <div class="row usermenu-links">--}}
{{--                            <div class="col">--}}
{{--                                <div class="link-box"><i class="fa fa-gear fa-lg fa-fw"></i><span>Settings</span></div>--}}
{{--                            </div>--}}
{{--                            <div class="col">--}}
{{--                                <div class="link-box"><i--}}
{{--                                        class="fa fa-user fa-lg fa-fw"></i><span>Profile                    </span>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col">--}}
{{--                                <div class="link-box"><i class="fa fa-power-off fa-lg fa-fw"></i><span>Logout</span>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--            </div>--}}
            <a class="usermenu-container" href="{{ route('logout') }}"
               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>

