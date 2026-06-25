@php
$route = request()->route()->getName();
@endphp
<div class="sidebar">
    <!-- Sidebar user panel (optional) -->

    <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="{{ auth()->user()->pro_pic }}" class="img-circle elevation-2" style="width: 2.5rem; height: 2.5rem;"
                alt="User Image">
        </div>
        <div class="info">
            <a href="{{ route('backend.admin.profile') }}" class="d-block">
                {{ auth()->user()->name }}
            </a>
        </div>
    </div> -->


    <!-- Sidebar Menu -->
    <nav class="mt-1 px-1">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">
            @can('dashboard_view')
            <li class="nav-item">
                <a href="{{ route('backend.admin.dashboard') }}"
                    class="nav-link {{ $route === 'backend.admin.dashboard' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
            </li>
            @endcan
            @can('sale_create')
            <li class="nav-item">
                <a href="{{ route('backend.admin.cart.index') }}"
                    class="nav-link {{ $route === 'backend.admin.cart.index' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-cart-plus"></i>
                    <span class="nav-label">POS</span>
                </a>
            </li>
            @endcan
            @if (auth()->user()->hasAnyPermission([
            //customer
            'customer_create',
            'customer_view',
            'customer_update',
            'customer_delete',
            'customer_sales',
            //supplier
            'supplier_create',
            'supplier_view',
            'supplier_update',
            'supplier_delete',
            ]))
            <li class="nav-item {{ request()->routeIs(['backend.admin.customers.index', 'backend.admin.customers.create', 'backend.admin.customers.edit','backend.admin.suppliers.index', 'backend.admin.suppliers.create', 'backend.admin.suppliers.edit']) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link nav-link--parent">
                    <i class="fas fa-user-circle nav-icon"></i>
                    <span class="nav-label">People</span>
                    <x-sidebar-chevron />
                </a>
                <ul class="nav nav-treeview">
                    @if (auth()->user()->hasAnyPermission(['customer_create','customer_view','customer_update','customer_delete']))
                    <li class="nav-item">
                        <a href="{{route('backend.admin.customers.index')}}"
                            class="nav-link nav-link--sub {{ request()->routeIs(['backend.admin.customers.index','backend.admin.customers.edit','backend.admin.customers.create']) ? 'active' : '' }}">
                            <span class="nav-sublabel">Customer</span>
                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->hasAnyPermission(['supplier_create','supplier_view','supplier_update','supplier_delete']))
                    <li class="nav-item">
                        <a href="{{route('backend.admin.suppliers.index')}}"
                            class="nav-link nav-link--sub {{ request()->routeIs(['backend.admin.suppliers.index','backend.admin.suppliers.edit','backend.admin.suppliers.create']) ? 'active' : '' }}">
                            <span class="nav-sublabel">Supplier</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif
            @if (auth()->user()->hasAnyPermission([
            'product_create',
            'product_view',
            'product_update',
            'product_delete',
            'product_import',
            ]))
            @php
                $productMenuRoutes = [
                    'backend.admin.products.index',
                    'backend.admin.products.create',
                    'backend.admin.products.edit',
                    'backend.admin.products.import',
                ];
            @endphp
            <li class="nav-item {{ request()->routeIs($productMenuRoutes) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link nav-link--parent {{ request()->routeIs($productMenuRoutes) ? 'active' : '' }}">

                    <i class="fas fa-box nav-icon"></i>
                    <span class="nav-label">Product</span>
                    <x-sidebar-chevron />
                </a>
                <ul class="nav nav-treeview">
                    @if (auth()->user()->hasAnyPermission(['product_view','product_update','product_delete']))
                    <li class="nav-item">
                        <a href="{{route('backend.admin.products.index')}}"
                            class="nav-link nav-link--sub {{ request()->routeIs(['backend.admin.products.index', 'backend.admin.products.edit', 'backend.admin.products.create']) ? 'active' : '' }}">
                            <span class="nav-sublabel">Product List</span>
                        </a>
                    </li>
                    @endif

                    @can('product_import')
                    <li class="nav-item">
                        <a href="{{route('backend.admin.products.import')}}"
                            class="nav-link nav-link--sub {{ request()->routeIs(['backend.admin.products.import']) ? 'active' : '' }}">
                            <span class="nav-sublabel">Product Import</span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endif

            @if (auth()->user()->hasAnyPermission(['brand_create', 'brand_view', 'brand_update', 'brand_delete']))
            <li class="nav-item">
                <a href="{{ route('backend.admin.brands.index') }}"
                    class="nav-link {{ request()->routeIs(['backend.admin.brands.index', 'backend.admin.brands.create', 'backend.admin.brands.edit']) ? 'active' : '' }}">
                    <i class="fas fa-copyright nav-icon"></i>
                    <span class="nav-label">Brands</span>
                </a>
            </li>
            @endif
            @if (auth()->user()->hasAnyPermission(['category_create', 'category_view', 'category_update', 'category_delete']))
            <li class="nav-item">
                <a href="{{ route('backend.admin.categories.index') }}"
                    class="nav-link {{ request()->routeIs(['backend.admin.categories.index', 'backend.admin.categories.create', 'backend.admin.categories.edit']) ? 'active' : '' }}">
                    <i class="fas fa-layer-group nav-icon"></i>
                    <span class="nav-label">Categories</span>
                </a>
            </li>
            @endif
            @if (auth()->user()->hasAnyPermission(['unit_create', 'unit_view', 'unit_update', 'unit_delete']))
            <li class="nav-item">
                <a href="{{ route('backend.admin.units.index') }}"
                    class="nav-link {{ request()->routeIs(['backend.admin.units.index', 'backend.admin.units.create', 'backend.admin.units.edit']) ? 'active' : '' }}">
                    <i class="fas fa-balance-scale nav-icon"></i>
                    <span class="nav-label">Units</span>
                </a>
            </li>
            @endif

            @if (auth()->user()->hasAnyPermission([
            'sale_view'
            ]))
            <li class="nav-item">
                <a href="#" class="nav-link nav-link--parent {{ request()->routeIs(['backend.admin.orders.index', 'backend.admin.orders.create', 'backend.admin.orders.edit']) ? 'menu-open' : '' }}">
                    <i class="fas fa-tags nav-icon"></i>
                    <span class="nav-label">Sale</span>
                    <x-sidebar-chevron />
                </a>
                <ul class="nav nav-treeview">
                    @can('sale_view')
                    <li class="nav-item">
                        <a href="{{route('backend.admin.orders.index')}}"
                            class="nav-link nav-link--sub {{ request()->routeIs(['backend.admin.orders.index']) ? 'active' : '' }}">
                            <span class="nav-sublabel">Sale List</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('backend.admin.installments.index')}}"
                            class="nav-link nav-link--sub {{ request()->routeIs(['backend.admin.installments.index', 'backend.admin.installments.show']) ? 'active' : '' }}">
                            <span class="nav-sublabel">Installments</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('backend.admin.installment-dashboard.index') }}"
                            class="nav-link nav-link--sub {{ request()->routeIs('backend.admin.installment-dashboard.*') ? 'active' : '' }}">
                            <span class="nav-sublabel">Installment Monitor
                                @php $overdue = \App\Models\InstallmentSchedule::where('status','overdue')->count(); @endphp
                                @if($overdue > 0)<span class="badge badge-danger ml-2">{{ $overdue }}</span>@endif
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('backend.admin.cash-register.index') }}"
                            class="nav-link nav-link--sub {{ request()->routeIs('backend.admin.cash-register.*') ? 'active' : '' }}">
                            <span class="nav-sublabel">Cash Register</span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endif
            @can('purchase_view')
            <li class="nav-item">
                <a href="{{ route('backend.admin.purchase.index') }}"
                    class="nav-link {{ request()->routeIs(['backend.admin.purchase.index', 'backend.admin.purchase.create', 'backend.admin.purchase.edit']) ? 'active' : '' }}">
                    <i class="fas fa-shopping-bag nav-icon"></i>
                    <span class="nav-label">Purchase</span>
                </a>
            </li>
            @endcan
            @if (auth()->user()->hasAnyPermission([
            'reports_summary',
            'reports_sales',
            'reports_inventory',
            ]))
            <li class="nav-item">
                <a href="#" class="nav-link nav-link--parent {{ request()->routeIs(['backend.admin.sale.report','backend.admin.sale.summery']) ? 'menu-open' : '' }}">
                    <i class="fas fa-chart-bar nav-icon"></i>
                    <span class="nav-label">Reports</span>
                    <x-sidebar-chevron />
                </a>
                <ul class="nav nav-treeview">
                    @can('reports_summary')
                    <li class="nav-item">
                        <a href="{{route('backend.admin.sale.summery')}}"
                            class="nav-link nav-link--sub {{ request()->routeIs(['backend.admin.sale.summery']) ? 'active' : '' }}">
                            <span class="nav-sublabel">Sales Summary</span>
                        </a>
                    </li>
                    @endcan
                    @can('reports_sales')
                    <li class="nav-item">
                        <a href="{{route('backend.admin.sale.report')}}"
                            class="nav-link nav-link--sub {{ request()->routeIs(['backend.admin.sale.report']) ? 'active' : '' }}">
                            <span class="nav-sublabel">Sales</span>
                        </a>
                    </li>
                    @endcan
                    @can('reports_inventory')
                    <li class="nav-item">
                        <a href="{{route('backend.admin.inventory.report')}}"
                            class="nav-link nav-link--sub {{ request()->routeIs(['backend.admin.inventory.report']) ? 'active' : '' }}">
                            <span class="nav-sublabel">Inventory</span>
                        </a>
                    </li>
                    @endcan
                    {{-- Advanced Reports --}}
                    <li class="nav-item">
                        <a href="{{ route('backend.admin.reports.advanced.sales-by-day') }}"
                            class="nav-link nav-link--sub {{ request()->routeIs('backend.admin.reports.advanced.*') ? 'active' : '' }}">
                            <span class="nav-sublabel">Advanced Reports</span>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Inventory Safety --}}
            <li class="nav-item">
                <a href="{{ route('backend.admin.stock-movements.index') }}"
                    class="nav-link {{ request()->routeIs('backend.admin.stock-movements.*') ? 'active' : '' }}">
                    <i class="fas fa-boxes nav-icon"></i>
                    <span class="nav-label">Stock Movements</span>
                </a>
            </li>
            @endif
            {{-- settings --}}
            {{-- Notification Center --}}
            <li class="nav-item">
                <a href="{{ route('backend.admin.notifications.index') }}"
                    class="nav-link {{ request()->routeIs('backend.admin.notifications.*') ? 'active' : '' }}">
                    <i class="fas fa-bell nav-icon"></i>
                    <span class="nav-label">Notifications
                        @php $notifCount = \App\Models\SystemNotification::unreadCount(); @endphp
                        @if($notifCount > 0)<span class="badge badge-danger ml-2">{{ $notifCount }}</span>@endif
                    </span>
                </a>
            </li>

            @if (auth()->user()->hasAnyPermission([
            //currency
            'currency_create',
            'currency_view',
            'currency_update',
            'currency_delete',
            'currency_set_default',
            //role
            'role_create',
            'role_view',
            'role_update',
            'role_delete',
            'permission_view',
            //user
            'user_create',
            'user_view',
            'user_update',
            'user_delete',
            'user_suspend',
            //setting
            'website_settings',
            'contact_settings',
            'socials_settings',
            'style_settings',
            'custom_settings',
            'notification_settings',
            'website_status_settings',
            'invoice_settings',
            ]))
            <li class="nav-header">SETTINGS</li>

            <li class="nav-item">
                <a href="#" class="nav-link nav-link--parent">
                    <i class="fas fa-cog nav-icon"></i>
                    <span class="nav-label">Website Settings</span>
                    <x-sidebar-chevron />
                </a>
                <ul class="nav nav-treeview">
                    @if (auth()->user()->hasAnyPermission([
                    'website_settings',
                    'contact_settings',
                    'socials_settings',
                    'style_settings',
                    'custom_settings',
                    'notification_settings',
                    'website_status_settings',
                    'invoice_settings',
                    ]))
                    <li class="nav-item">
                        <a href="{{ route('backend.admin.settings.website.general') }}?active-tab=website-info"
                            class="nav-link nav-link--sub {{ $route === 'backend.admin.settings.website.general' ? 'active' : '' }}">
                            <span class="nav-sublabel">General Settings</span>
                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->hasAnyPermission(['currency_create','currency_view','currency_update','currency_delete']))
                    <li class="nav-item">
                        <a href="{{ route('backend.admin.currencies.index') }}"
                            class="nav-link nav-link--sub {{ request()->routeIs([ 'backend.admin.currencies.index', 'backend.admin.currencies.create', 'backend.admin.currencies.edit']) ? 'active' : '' }}">
                            <span class="nav-sublabel">Currency</span>
                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->hasAnyPermission([
                    'role_create',
                    'role_view',
                    'role_update',
                    'role_delete',
                    'permission_view',
                    ]))
                    @can('role_view')
                    <li class="nav-item">
                        <a href="{{ route('backend.admin.roles') }}"
                            class="nav-link nav-link--sub {{ request()->routeIs(['backend.admin.roles', 'backend.admin.roles.show']) ? 'active' : '' }}">
                            <span class="nav-sublabel">Roles</span>
                        </a>
                    </li>
                    @endcan
                    @can('permission_view')
                    <li class="nav-item">
                        <a href="{{ route('backend.admin.permissions') }}"
                            class="nav-link nav-link--sub {{ request()->routeIs(['backend.admin.permissions']) ? 'active' : '' }}">
                            <span class="nav-sublabel">Permissions</span>
                        </a>
                    </li>
                    @endcan
                    @endif
                    @if (auth()->user()->hasAnyPermission([
                    //user
                    'user_create',
                    'user_view',
                    'user_update',
                    'user_delete',
                    'user_suspend',
                    ]))
                    <li class="nav-item">
                        <a href="{{ route('backend.admin.users') }}"
                            class="nav-link nav-link--sub {{ $route === 'backend.admin.users' ? 'active' : '' }}">
                            <span class="nav-sublabel">User Management</span>
                        </a>
                    </li>
                    @endif

                    {{-- Enterprise Modules --}}
                    <li class="nav-item">
                        <a href="{{ route('backend.admin.backup.index') }}"
                            class="nav-link nav-link--sub {{ request()->routeIs('backend.admin.backup.*') ? 'active' : '' }}">
                            <span class="nav-sublabel">Backup & Restore</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('backend.admin.audit-logs.index') }}"
                            class="nav-link nav-link--sub {{ request()->routeIs('backend.admin.audit-logs.*') ? 'active' : '' }}">
                            <span class="nav-sublabel">Audit Logs</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('backend.admin.system-health.index') }}"
                            class="nav-link nav-link--sub {{ request()->routeIs('backend.admin.system-health.*') ? 'active' : '' }}">
                            <span class="nav-sublabel">System Health</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('backend.admin.license.index') }}"
                            class="nav-link nav-link--sub {{ request()->routeIs('backend.admin.license.*') ? 'active' : '' }}">
                            <span class="nav-sublabel">License</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>

<script>
    // Get all elements with the nav-treeview class
    const treeviewElements = document.querySelectorAll('.nav-treeview');

    // Iterate over each treeview element
    treeviewElements.forEach(treeviewElement => {
        // Check if it has the nav-link and active classes
        const navLinkElements = treeviewElement.querySelectorAll('.nav-link.active');

        // If there are nav-link elements with the active class, log the treeview element
        if (navLinkElements.length > 0) {
            // Add the menu-open class to the parent nav-item
            const parentNavItem = treeviewElement.closest('.nav-item');
            if (parentNavItem) {
                parentNavItem.classList.add('menu-open');
            }

            // Add the active class to the immediate child nav-link
            const childNavLink = parentNavItem.querySelector('.nav-link');
            if (childNavLink) {
                childNavLink.classList.add('active');
            }
        }
    });
</script>