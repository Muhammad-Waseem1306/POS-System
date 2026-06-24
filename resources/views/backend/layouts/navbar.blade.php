<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        {{-- <li class="nav-item d-none d-sm-inline-block">
            <a href="index3.html" class="nav-link">Home</a>
        </li> --}}
        {{-- <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Contact</a>
        </li> --}}
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        @can('sale_create')
        <li class="nav-item dropdown">
            <a class="nav-link btn bg-gradient-primary text-white" href="{{route('backend.admin.cart.index')}}">
                <i class="fas fa-cart-plus"> POS</i>
            </a>
        </li>
        @endcan
        {{-- Live Notification Bell --}}
        <li class="nav-item dropdown" id="notifDropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" id="notifBell">
                <i class="far fa-bell"></i>
                @php $unreadNotif = \App\Models\SystemNotification::unreadCount(); @endphp
                @if($unreadNotif > 0)
                <span class="badge badge-danger navbar-badge" id="notifBadge">{{ $unreadNotif }}</span>
                @else
                <span class="badge badge-danger navbar-badge d-none" id="notifBadge">0</span>
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="notifMenu">
                <span class="dropdown-item dropdown-header" id="notifHeader">Loading...</span>
                <div class="dropdown-divider"></div>
                <div id="notifList"></div>
                <div class="dropdown-divider"></div>
                <a href="{{ route('backend.admin.notifications.index') }}" class="dropdown-item dropdown-footer">
                    See All Notifications
                </a>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button" title="Help & Support">
                <i class="fas fa-question-circle"></i>
            </a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fas fa-user-circle"></i>
                <i class="fas fa-angle-double-down"></i>
            </a>
            <div class="dropdown-menu ">
                <a href="{{ route('backend.admin.profile') }}" class="dropdown-item dropdown-footer">
                    <i class="fas fa-address-card"></i>
                    Profile
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('logout') }}" class="dropdown-item dropdown-footer">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </li>
    </ul>
</nav>