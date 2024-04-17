<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>{{ config('app.name', 'Laravel') }}</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  {{-- <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon"> --}}

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/quill/quill.snow.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/quill/quill.bubble.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/remixicon/remixicon.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/simple-datatables/style.css')}}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">

  <style>
    .footer{
        position: absolute;
        bottom: 0;
        /* height: 60px; */
        width: 100%;
        background-color: white;
    }
  </style>
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">

        <img src="{{asset('assets/img/splurge_logo.png')}}" height="60px" width="240px" alt="logo">
      {{-- <i class="bi bi-list toggle-sidebar-btn"></i> --}}

    </div><!-- End Logo -->

    <!-- End Icons Navigation -->

  </header><!-- End Header -->


     {{-- <!-- Sidebar -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link " href="{{route('home')}}">
              <i class="bi bi-person-gear"></i>
              <span>Users</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link " href="{{url('clients')}}">
              <i class="bi bi-person-fill"></i>
              <span>Clients</span>
            </a>
        </li>
    </ul>
  </aside> --}}

  <!-- End of sidebar -->
    <main id = "main" class = "main container" style="width: 120vh; margin-top: 10%;">
        @yield('content')
    </main>



    {{-- <div class="container" style="width:100% ;margin-bottom:0%; padding-bottom:0%; background-color:white; margin-top:7%"> --}}
        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 border-top footer">
            <div class="col-md-4 d-flex align-items-center px-5">
              <a class="mb-3 me-2 mb-md-0 text-muted text-decoration-none lh-1">
                <img src="{{asset('assets/img/creator.jpeg')}}" height="40" width="40" alt="Mukesh" style="border-radius:50%">
              </a>
              <span class="mb-3 mb-md-0 text-muted">Mukesh Prakash</span>
            </div>

            <ul class="nav col-md-4 justify-content-end list-unstyled d-flex px-5">
              <li class="ms-3"><a class="text-muted" target="_blank" href="https://github.com/intr0vert-55"><i class="bi bi-github"></i></a></li>
              <li class="ms-3"><a class="text-muted" target="_blank" href="https://www.linkedin.com/in/mukesh-prakash-343635233/"><i class="bi bi-linkedin"></i></a></li>
            </ul>
        </footer>

  <!-- Vendor JS Files -->
  <script src="{{asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
  <script src="{{asset('assets/vendor/echarts/echarts.min.js') }}"></script>
  <script src="{{asset('assets/vendor/quill/quill.min.js') }}"></script>
  <script src="{{asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
  <script src="{{asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
  <script src="{{asset('assets/vendor/php-email-form/validate.js') }}"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>
