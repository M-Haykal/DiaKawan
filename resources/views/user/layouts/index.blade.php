<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    @stack('style')
</head>

<body>
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg bg-success">
        <div class="container px-5">
            <a class="navbar-brand text-white" href="#!"><img src="{{ asset('img/DiaKawan.png') }}" alt=""
                    srcset="" width="35" height="35" class="d-inline-block align-text-top">DiaKawan</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link text-white active" aria-current="page"
                            href="{{ route('user.home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="#!">About</a></li>
                    <li class="nav-item"><a class="nav-link text-white"
                            href="{{ route('user.products.index') }}">Product</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="{{ route('user.blogs.index') }}">Blog</a>
                    </li>
                    <li class="nav-item"><a class="nav-link text-white"
                            href="{{ route('user.seminars.index') }}">Seminar</a></li>
                    <li class="nav-item">
                        <div class="dropdown">
                            <button class="btn btn-outline-light dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Menu
                            </button>
                            <ul class="dropdown-menu dropdown-menu-lg-end">
                                <li><a class="dropdown-item" href="{{ route('user.order-history.index') }}">Order</a></li>
                                <li><a class="dropdown-item" href="{{ route('cart.index') }}">Cart</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Section-->
    <section class="py-5">
        @yield('content')
    </section>
    <!-- Footer-->
    <footer class="py-5 bg-success">
        <div class="container">
            <p class="m-0 text-center text-white">Copyright © Your Website 2023</p>
        </div>
    </footer>
</body>
<!--Start of Tawk.to Script-->
<script type="text/javascript">
    var Tawk_API = Tawk_API || {},
        Tawk_LoadStart = new Date();
    (function() {
        var s1 = document.createElement("script"),
            s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = 'https://embed.tawk.to/68df7c018627511951406014/1j6kfoq9t';
        s1.charset = 'UTF-8';
        s1.setAttribute('crossorigin', '*');
        s0.parentNode.insertBefore(s1, s0);
    })();
</script>
<!--End of Tawk.to Script-->
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('services.midtrans.client_key') }}"></script>
{{-- <script src="{{ asset('js/bootstrap.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.js') }}"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
@stack('script')


</html>
