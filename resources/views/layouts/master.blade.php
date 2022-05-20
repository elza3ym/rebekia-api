@include('layouts.header')
@include('layouts.sidebar')

    <!--main content wrapper-->
    <div class="content-wrapper">

        <div class="container-fluid">
            @yield('content')
        </div>

        @include('layouts.footer')