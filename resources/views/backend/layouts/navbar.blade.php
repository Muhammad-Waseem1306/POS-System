<nav class="main-header navbar navbar-expand header-modern">
    <ul class="navbar-nav header-modern__left">
        <li class="nav-item">
            <a class="header-icon-btn" data-widget="pushmenu" data-enable-remember="true" data-no-transition-after-reload="true" href="#" role="button" aria-label="Toggle sidebar">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    <ul class="navbar-nav header-modern__actions ml-auto">
        @can('sale_create')
        <li class="nav-item">
            <a class="header-pos-btn" href="{{ route('backend.admin.cart.index') }}">
                <i class="fas fa-cart-plus"></i>
                <span>POS</span>
            </a>
        </li>
        @endcan

        <li class="nav-item dropdown" id="notifDropdown">
            <a class="header-icon-btn" data-toggle="dropdown" href="#" id="notifBell" aria-label="Notifications">
                <i class="far fa-bell"></i>
                @php $unreadNotif = \App\Models\SystemNotification::unreadCount(); @endphp
                <span class="header-icon-btn__badge {{ $unreadNotif > 0 ? '' : 'd-none' }}" id="notifBadge">{{ $unreadNotif }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right header-dropdown header-dropdown--wide" id="notifMenu">
                <span class="dropdown-item dropdown-header" id="notifHeader">Loading...</span>
                <div class="dropdown-divider"></div>
                <div id="notifList"></div>
                <div class="dropdown-divider"></div>
                <a href="{{ route('backend.admin.notifications.index') }}" class="dropdown-item dropdown-footer">
                    See all notifications
                </a>
            </div>
        </li>

        <li class="nav-item">
            <a class="header-icon-btn" data-widget="fullscreen" href="#" role="button" aria-label="Fullscreen">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

        <li class="nav-item">
            <a class="header-icon-btn" data-widget="control-sidebar" data-slide="true" href="#" role="button"
                title="Help & Support" aria-label="Help and support">
                <i class="fas fa-question-circle"></i>
            </a>
        </li>

        <li class="nav-item dropdown header-user-menu">
            <a class="header-user-btn" data-toggle="dropdown" href="#" aria-label="User menu">
                <span class="header-user-btn__avatar">
                    <i class="fas fa-user"></i>
                </span>
                <span class="header-user-btn__name d-none d-md-inline">{{ auth()->user()->name }}</span>
                <i class="fas fa-chevron-down header-user-btn__chevron"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right header-dropdown">
                <a href="{{ route('backend.admin.profile') }}" class="dropdown-item">
                    <i class="fas fa-user"></i>
                    Profile
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('logout') }}" class="dropdown-item dropdown-item--logout" data-partial-nav="false">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </li>
    </ul>
</nav>
