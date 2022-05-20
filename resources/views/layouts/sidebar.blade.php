@if (\Auth::user()->access_level == 2)
    <div class="collapse navbar-collapse" id="navbarResponsive">

        <!--left side nav-->
        <ul class="navbar-nav left-side-nav" id="accordion">
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
                <a class="nav-link" href="{{ route('home') }}">
                    <i class="vl_dashboard"></i>
                    <span class="nav-link-text">Dashboard </span>
                </a>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Collector Requests">
                <a class="nav-link" href="{{ route('collectionAllImport') }}">
                    <i class="vl_clip-board"></i>
                    <span class="nav-link-text">Collectors Import</span>
                </a>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Factory Requests">
                <a class="nav-link" href="{{ route('exports') }}">
                    <i class="vl_clip-board"></i>
                    <span class="nav-link-text">Factories Export</span>
                </a>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Inventory">
                <a class="nav-link" href="{{ route('collectionInventory') }}">
                    <i class="vl_inbox"></i>
                    <span class="nav-link-text">Inventory</span>
                </a>
            </li>
        </ul>
        <!--/left side nav-->

        <!--nav push link-->
        <ul class="navbar-nav sidenav-toggler">
            <li class="nav-item">
                <a class="nav-link text-center" id="left-nav-toggler">
                    <i class="fa fa-angle-left"></i>
                </a>
            </li>
        </ul>
        <!--/nav push link-->



        <!--header rightside links-->
        <ul class="navbar-nav header-links ml-auto hide-arrow">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle mr-lg-3" id="userNav" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="user-thumb">
                        <img class="rounded-circle" src="{{ \Auth::user()->profile_pic }}" alt=""/>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userNav">
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button type="submit" class="dropdown-item" href="login.html">Sign Out</button>
                    </form>
                </div>
            </li>
        </ul>
        <!--/header rightside links-->

    </div>
    </nav>
@elseif (\Auth::user()->access_level == 3)
    <div class="collapse navbar-collapse" id="navbarResponsive">

        <!--left side nav-->
        <ul class="navbar-nav left-side-nav" id="accordion">
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
                <a class="nav-link" href="{{ route('home') }}">
                    <i class="vl_dashboard"></i>
                    <span class="nav-link-text">Dashboard </span>
                </a>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Collector Requests">
                <a class="nav-link" href="{{ route('factoryAllImport') }}">
                    <i class="vl_clip-board"></i>
                    <span class="nav-link-text">Collections Import</span>
                </a>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Inventory">
                <a class="nav-link" href="{{route('factoryInventory')}}">
                    <i class="vl_inbox"></i>
                    <span class="nav-link-text">Inventory</span>
                </a>
            </li>
        </ul>
        <!--/left side nav-->

        <!--nav push link-->
        <ul class="navbar-nav sidenav-toggler">
            <li class="nav-item">
                <a class="nav-link text-center" id="left-nav-toggler">
                    <i class="fa fa-angle-left"></i>
                </a>
            </li>
        </ul>
        <!--/nav push link-->



        <!--header rightside links-->
        <ul class="navbar-nav header-links ml-auto hide-arrow">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle mr-lg-3" id="userNav" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="user-thumb">
                        <img class="rounded-circle" src="{{ \Auth::user()->profile_pic }}" alt=""/>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userNav">
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button type="submit" class="dropdown-item" href="login.html">Sign Out</button>
                    </form>
                </div>
            </li>
        </ul>
        <!--/header rightside links-->

    </div>
    </nav>
@elseif(\Auth::user()->access_level == 4)
    <div class="collapse navbar-collapse" id="navbarResponsive">
        <!--left side nav-->
        <ul class="navbar-nav left-side-nav" id="accordion">
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
                <a class="nav-link" href="{{ route('home') }}">
                    <i class="vl_dashboard"></i>
                    <span class="nav-link-text">Dashboard </span>
                </a>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Users">
                <a class="nav-link" href="{{ route('admin.users.citizen') }}">
                    <i class="vl_user-male"></i>
                    <span class="nav-link-text">Citizens</span>
                </a>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Users">
                <a class="nav-link" href="{{ route('admin.users.collector') }}">
                    <i class="vl_user-male"></i>
                    <span class="nav-link-text">Collectors</span>
                </a>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Users">
                <a class="nav-link" href="{{ route('admin.users.collection') }}">
                    <i class="vl_user-male"></i>
                    <span class="nav-link-text">Collections</span>
                </a>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Users">
                <a class="nav-link" href="{{ route('admin.users.factory') }}">
                    <i class="vl_user-male"></i>
                    <span class="nav-link-text">Factories</span>
                </a>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Users">
                <a class="nav-link" href="{{ route('admin.reports') }}">
                    <i class="vl_bug"></i>
                    <span class="nav-link-text">Reports</span>
                </a>
            </li>


            <li class="nav-item bg-dark text-white" data-toggle="tooltip" data-placement="right" title="Users">
                <a class="nav-link text-white" href="{{ route('admin.payment') }}">
                    <i class="vl_money"></i>
                    <span class="nav-link-text">Release Payments</span>
                </a>
            </li>
        </ul>
        <!--/left side nav-->

        <!--nav push link-->
        <ul class="navbar-nav sidenav-toggler">
            <li class="nav-item">
                <a class="nav-link text-center" id="left-nav-toggler">
                    <i class="fa fa-angle-left"></i>
                </a>
            </li>
        </ul>
        <!--/nav push link-->



        <!--header rightside links-->
        <ul class="navbar-nav header-links ml-auto hide-arrow">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle mr-lg-3" id="userNav" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="user-thumb">
                        <img class="rounded-circle" src="{{ \Auth::user()->profile_pic }}" alt=""/>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userNav">
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button type="submit" class="dropdown-item" href="login.html">Sign Out</button>
                    </form>
                </div>
            </li>
        </ul>
        <!--/header rightside links-->

    </div>
    </nav>
@endif
<!--/navigation : sidebar & header-->