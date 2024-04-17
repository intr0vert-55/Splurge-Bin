<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <meta name="csrf-token" content="{{ csrf_token() }}">

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
        position: fixed;
        bottom: 0;
        /* height: 60px; */
        width: 100%;
        background-color: white;
    }
    body{
        background-image: url({{asset('assets/img/background.png')}});
    }
    .mainbody{
        background-image: url({{asset('assets/img/background.png')}});
        background-size: 75%;
    }
    .card .items{
        transition: transform 0.2s ease;
        box-shadow: 0 4px 6px 0 rgba(22, 22, 26, 0.18);
        border-radius: 0;
        border: 0;
        margin-bottom: 1.5em;
    }
    .card .items:hover {
        transform: scale(1.1);
    }
    .icons{
        border-radius: 8px 8px 8px 8px;
    }
    .icons:hover{
        background-color: #4154f1;
        color: white;
    }
    .delete:hover{
        background-color: #dc3545;
    }

    .productName{
        color:rgb(0, 70, 144);
    }

    .productPrice{
        color:rgb(0, 126, 86);
    }

    .sellerName{
        color:rgb(246, 38, 145);
    }

    .bin{
        color:#4154f1;
    }

    .buy{
        margin-top: 5px;
        height: 40px;
    }

    .purchaseHistory{
        margin-left : 2%;
    }

    .buttons{
        margin : 1%;
        margin-bottom : 3%;
    }
    .purchaseForm{
        display: inline;
    }
    .btn-ad{
        margin: 0%;
        padding: 0%;
        margin-right: 3%;
    }
    .btn-ad :hover{
        color: white;
        background-color : black;
    }
    .btn-buy{
        margin: 1%;
    }
    .sidebar-nav .nav-link-danger{
        background: #ffbaba;
    }
    .sidebar-nav .nav-link-danger:hover{
        background: #ffbaba;
    }
    .sidebar-nav .nav-link-active{
        background: #4154f1;
        color: white;
    }
    .sidebar-nav .nav-link-active:hover{
        background: #4154f1;
        color: white;
    }
    .sidebar-nav .nav-link-active i{
        color: white;
    }
    .review-stars{
        background-color : #388e3c;
        padding : 5px;
        padding-left : 8px;
        padding-right : 8px;
        border-radius : 8px 8px 8px 8px;
    }
    .profile-cards-2{
        width: 36rem;
        height : 12rem;
    }
    .profile-cards-3{
        width: 24rem;
        height : 15rem;
    }
    .card .wishlist{
        background-color: #f45dac;
        color: white;
    }
    .card .cover{
        background-color: #4154f1;
        color: white;
    }
    .card .orders{
        background-color: #1b6cab;
        color: white;
    }
    .card .rating{
        background-color: #fdcd11;
    }
    .card .revenue{
        background-color: #00B974;
    }
    .profile-cards-3 .card-title{
        color: white;
    }
    .profile-cards-2 .card-title{
        color: black;
    }

    /* For rollable image in product.blade.php */

    #zoom-image {
        width: 100%; /* Make the image fill the container */
        height: 100%;
        transition: transform 0.5s ease; /* Add smooth transition effect */
    }

  </style>

  {{-- Icon --}}

  <link rel="icon" href="{{asset('assets/img/logo_small.png')}}" type="image/x-icon">

</head>

<body class="toggle-sidebar">

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">

        <i class="bi bi-list toggle-sidebar-btn"></i>
        <a href = {{route('home')}}>
            <img src="{{asset('assets/img/splurge_logo.png')}}" height="60px" width="240px" alt="logo">
        </a>

    </div><!-- End Logo -->

    {{-- <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    {{ Auth::user()->name }}
                </a>

                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </div> --}}

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">

          <li class="nav-item dropdown pe-3">

            {{-- <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
              {{ Auth::user()->name }}
            </a> --}}

            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="{{ route('logout') }}"
                 onclick="event.preventDefault();
                               document.getElementById('logout-form').submit();">
                  {{ __('Logout') }}
              </a>

              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                  @csrf
              </form>
          </div>
              </li>

            </ul><!-- End Profile Dropdown Items -->
          </li><!-- End Profile Nav -->

        </ul>
      </nav>
    <!-- End Icons Navigation -->

  </header><!-- End Header -->


     <!-- Sidebar -->
  <aside id="sidebar" class="sidebar">
    <h4 style="font-weight:20">Welcome</h4>
    <h3>{{Auth::user() -> name}}</h3>
    <ul class="sidebar-nav" id="sidebar-nav">
        <div class="d-flex flex-column justify-content-between">
            <div class="w-100">
                <li class="nav-item">
                    <a class = "nav-link {{ (Route::current()->getName() == ('home')) ? 'nav-link-active' : '' }}" href="{{route('home')}}">
                        <i class="bi bi-house-fill"></i>
                        <span>Home</span>
                    </a>
                </li>
                @if(Auth::user()->is_seller == 0)
                    <li class="nav-item">
                        <a class = "nav-link {{ Route::current()->getName() == ('wishlist') ? 'nav-link-active' : '' }}" href="{{route('wishlist')}}">
                            <i class="bi bi-bag-heart-fill"></i>
                            <span>WishList</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class = "nav-link {{ Route::current()->getName() == ('bin') ? 'nav-link-active' : '' }}" href="{{route('bin')}}">
                            <i class="bi bi-trash2-fill"></i>
                            <span>Bin</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class = "nav-link {{ Route::current()->getName() == ('orders') ? 'nav-link-active' : '' }}" href="{{route('orders')}}">
                            <i class="bi bi-cart-fill"></i>
                            <span>Orders</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class = "nav-link {{ Route::current()->getName() == ('user.profile') ? 'nav-link-active' : '' }}" href="{{route('user.profile')}}">
                            <i class="bi bi-person-fill"></i>
                            <span>Profile</span>
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class = "nav-link {{ Route::current()->getName() == ('seller.orders') ? 'nav-link-active' : '' }}" href="{{route('seller.orders')}}">
                            <i class="bi bi-cart-fill"></i>
                            <span>Orders</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class = "nav-link {{ Route::current()->getName() == ('seller.profile') ? 'nav-link-active' : '' }}" href="{{route('seller.profile')}}">
                            <i class="bi bi-person-fill"></i>
                            <span>Profile</span>
                        </a>
                    </li>
                @endif
                    <li class="nav-item">
                        <a class="nav-link nav-link-danger link-danger" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-left text-danger"></i>
                            <span>Log Out</span>
                        </a>
                    </li>
            </div>
        </div>
    @if(Auth::user() -> is_seller == 1)
        {{-- Seller --}}
    @else
        {{-- Consumer --}}
    @endif
        {{-- <li class="nav-item">
            <a class="nav-link" href="{{route('home')}}">
              <i class="bi bi-person-gear"></i>
              <span>Users</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link " href="{{url('clients')}}">
              <i class="bi bi-person-fill"></i>
              <span>Clients</span>
            </a>
        </li> --}}
    </ul>
  </aside>

  <!-- End of sidebar -->
    <main id = "main">
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
  <script src="{{asset('assets/js/main.js')  }}"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
  <script>

  </script>

  @stack('script')

</body>

</html>
