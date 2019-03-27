
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>{{ config('app.name', 'Laravel') }}</title>


<!-- Font Awesome Icons -->
  {{-- <link rel="stylesheet" href="{{ asset('assets/plugins/font-awesome/css/font-awesome.min.css')}}"> --}}

  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css')}}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@8.5.0/dist/sweetalert2.min.css">


  {{-- <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}


    @yield('styles')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="index3.html" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>
    </ul>

    <!-- SEARCH FORM -->
    <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fa fa-search"></i>
          </button>
        </div>
      </div>
    </form>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->

      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="fas fa-user mr-2"></i> {{ Auth::user()->name }}
        </a>
        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <i class="fas fa-user-cog mr-2"></i> My Account
          </a>
          <a href="{{ route('change-password') }}" class="dropdown-item">
            <i class="fas fa-fingerprint mr-2"></i> Change Password
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="{{ route('logout') }}"
             onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();"> <i class="fas fa-power-off mr-2"></i>
              {{ __('Logout') }}
          </a>

          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
          </form>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#"><i
            class="fa fa-th-large"></i></a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link">
      <img src="{{ asset('/img/magazine.png') }}" alt="{{ config('app.name', 'School Mag') }}" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">{{ config('app.name', 'School Mag') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <li class="nav-item">
            <a href="{{ route('home') }}" class="nav-link {{Request::route()->getName() == 'student' || Request::route()->getName() == 'admin' || Request::route()->getName() == 'coordinator' || Request::route()->getName() == 'manager' || Request::route()->getName() == 'faculty' ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>

          @if(Auth::user()->role == 2)
          <li class="nav-item">
            <a href="{{ route('stdcontributions') }}" class="nav-link {{Request::route()->getName() == 'stdcontributions' ? 'active' : '' }}">
              <i class="nav-icon fas fa-file-contract"></i>
              <p>
                Contributions
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('stdacademic-years') }}" class="nav-link {{Request::route()->getName() == 'stdacademic-years' ? 'active' : '' }}">
              <i class="nav-icon far fa-calendar-alt"> </i>
              <p>
                Academic Years
              </p>
            </a>
          </li>

          @endif


          @if(Auth::user()->role == 5)

          <li class="nav-item">
            <a href="{{ route('contributions') }}" class="nav-link {{Request::route()->getName() == 'contributions' || Request::route()->getName() == 'approved-contributions' || Request::route()->getName() == 'commented-contributions' || Request::route()->getName() == 'pending-contributions' || Request::route()->getName() == 'single-contribution'? 'active' : '' }}">
              <i class="nav-icon fas fa-file-contract"></i>
              <p>
                Contributions
              </p>
            </a>
          </li>
          

          
          <li class="nav-item">
            <a href="{{ route('academic-years') }}" class="nav-link {{Request::route()->getName() == 'academic-years' ? 'active' : '' }}">
              <i class="nav-icon far fa-calendar-alt"> </i>
              <p>
                Academic Years
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('departments') }}" class="nav-link {{Request::route()->getName() == 'departments' ? 'active' : '' }}">
              <i class="fas fa-landmark"></i>
              <p>
                Faculties
                {{-- <span class="right badge badge-danger">New</span> --}}
              </p>
            </a>
          </li>


          <li class="nav-item has-treeview">
            <a href="#" class="nav-link {{Request::route()->getName() == 'con-report' || Request::route()->getName() == 'con-percentage' || Request::route()->getName() == 'contributor-number' || Request::route()->getName() == 'contributor-without-comment' ? 'active' : '' }}">
              <i class="fas fa-chart-area"></i>
              <p>
                 Reports
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('con-report') }}" class="nav-link {{Request::route()->getName() == 'con-report' ? 'active' : '' }}">
                  <i class="fas fa-chart-bar"></i>
                  <p>Number of contributions</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('con-percentage') }}" class="nav-link {{Request::route()->getName() == 'con-percentage' ? 'active' : '' }}">
                  <i class="fas fa-chart-pie"></i>
                  <p>Percentage of contributions </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('contributor-number') }}" class="nav-link {{Request::route()->getName() == 'contributor-number' ? 'active' : '' }}">
                  <i class="far fa-chart-bar"></i>
                  <p>Number of contributors </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('contributor-without-comment') }}" class="nav-link {{Request::route()->getName() == 'contributor-without-comment' ? 'active' : '' }}">
                  <i class="fas fa-comments"></i>
                  <p>Comment status</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link {{Request::route()->getName() == 'users' || Request::route()->getName() == 'add-user' ? 'active' : '' }}">
              <i class="nav-icon fas fa-users"></i>
              <p>
                 Users
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('add-user') }}" class="nav-link {{Request::route()->getName() == 'add-user' ? 'active' : '' }}">
                  <i class="fas fa-user-plus"></i>
                  <p> Add User</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('users') }}" class="nav-link {{Request::route()->getName() == 'users' ? 'active' : '' }}">
                  <i class="fas fa-clipboard-list"></i>
                  <p> All Users</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-th"></i>
              <p>
                Settings
                {{-- <span class="right badge badge-danger">New</span> --}}
              </p>
            </a>
          </li>

          @endif
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{ $title }}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">

         <div class="row">
    <div class="col-lg-12">

        <!--  ==================================SESSION MESSAGES==================================  -->
        @if (session()->has('message'))
            <div class="alert alert-{!! session()->get('type')  !!} alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {!! session()->get('message')  !!}
            </div>
        @endif
    <!--  ==================================SESSION MESSAGES==================================  -->


    <!--  ==================================VALIDATION ERRORS==================================  -->
        @if($errors->any())
            @foreach ($errors->all() as $error)

                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {!!  $error !!}
                </div>

        @endforeach
     @endif
    <!--  ==================================SESSION MESSAGES==================================  -->

  </div></div>


        @yield('content')
        

        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
      <h5>Title</h5>
      <p>Sidebar content</p>
    </div>
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2019 <a href="#">Mominul Islam</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
    {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}

    <!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8.5.0/dist/sweetalert2.all.min.js"></script>

<script>
    @if (session()->has('message'))
        Swal.fire({
        title: "{!! session()->get('title')  !!}",
        text: "{!! session()->get('message')  !!}",
        type: "{!! session()->get('type')  !!}",
        confirmButtonText: "OK"
    });
        {{ Session::forget('message')}}
    @endif

</script>

@yield('scripts')
</body>
</html>
