<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>AdminLTE 3 | Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <x-css-style />
    @stack('styles')

</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTELogo" height="60"
                width="60" />
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Navbar Search -->
                <li class="nav-item">
                    <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                        <i class="fas fa-search"></i>
                    </a>
                    <div class="navbar-search-block">
                        <form class="form-inline">
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-navbar" type="search" placeholder="Search"
                                    aria-label="Search" />
                                <div class="input-group-append">
                                    <button class="btn btn-navbar" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <li class="nav-item">
                    @auth
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        @method('delete')
                        <button type="submit"
                            class=" mx-2 mt-1 text-sm font-bold uppercase btn btn-secondary">Logout</button>
                    </form>
                    @endauth
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->
        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="/dashboard" class="brand-link">
                <img src=" {{ asset('dist/img/AdminLTELogo.png') }} " alt="AdminLTE Logo"
                    class="brand-image img-circle elevation-3" style="opacity: 0.8" />
                <span class="brand-text font-weight-light">
                    {{ Str::ucfirst(Auth::user()->roles->pluck('name')[0]??'Guest') }}
                </span>
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                            alt="User Image" />
                    </div>
                    @auth
                    <div class="info">
                        <a href="#" class="d-block font-bold">{{ Str::ucfirst(Auth::user()->fname) }}</a>
                    </div>
                    @endauth
                </div>
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <x-nav-item href="/dashboard" navname="Dashboard" class="fa-tachometer-alt"
                            active="{{ request()->is('dashboard')?'active':''}} " />
                        @canany(['create_user','edit_user','delete_user'])
                        <x-nav-item href="{{ route('users.index') }}" navname="Users Table" class="fa-table"
                            active="{{ request()->is('users/*')?'active':''}} {{ request()->is('users')?'active':'' }} " />
                        @endcanany
                        @hasrole('admin')
                        <x-nav-item href="{{ route('roles.index')}}" navname="Role" class="fa-tachometer-alt"
                            active="{{ request()->is('roles/*')?'active':''}} {{ request()->is('roles')?'active':'' }} " />
                        <x-nav-item href="{{ route('modules.index')}}" navname="Module" class="fa-tachometer-alt"
                            active="{{ request()->is('modules/*')?'active':''}} {{ request()->is('modules')?'active':'' }} " />
                        @endhasrole
                        @canany(['create_category','edit_category','delete_category'])
                        <x-nav-item href="{{ route('categories.index')}}" navname="Categories" class="fa-th"
                            active="{{ request()->is('categories/*')?'active':''}} {{ request()->is('categories')?'active':'' }} " />
                        @endcanany
                        @canany(['create_post','edit_post','delete_post'])
                        <x-nav-item href="{{ route('posts.index')}}" navname="Posts" class="fa-book"
                            active="{{ request()->is('posts/*')?'active':''}} {{ request()->is('posts')?'active':'' }} " />
                        @endcan
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">{{ $header }}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/">Home</a></li>
                                <li class="breadcrumb-item active">{{ $header }} </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            {{ $slot }}
        </div>
    </div>
    <!-- ./wrapper -->

    <x-js-scripts />
    @stack('script')


</body>

</html>