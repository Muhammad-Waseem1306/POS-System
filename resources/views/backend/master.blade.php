<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        @yield('title', 'Dashboard') | {{ readConfig('app_name') }}
    </title>

    <!-- FAVICON ICON -->
    <x-favicon />

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- dropzonejs -->
    <link rel="stylesheet" href="{{ asset('plugins/dropzone/min/dropzone.min.css') }}">
    {{-- datatable --}}
    <link rel="stylesheet" href="{{ asset('assets/css/datatable/datatable.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/datatable/buttons.dataTables.min.css') }}">
    {{-- custom style --}}
    <link rel="stylesheet" href="{{ asset('css/custom-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages-modern.css') }}?v=44">
    <link rel="stylesheet" href="{{ asset('css/dashboard-modern.css') }}?v=1">
    <link rel="stylesheet" href="{{ asset('css/sidebar-modern.css') }}?v=12">
    <link rel="stylesheet" href="{{ asset('css/header-modern.css') }}?v=7">
    <link rel="stylesheet" href="{{ asset('css/footer-modern.css') }}?v=1">
    @stack('style')
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed sidebar-modern-layout">

    <x-simple-alert />
    <x-confirm-dialog />

    <div class="wrapper">

        <!-- Preloader -->
        <!-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ assetImage(readconfig('site_logo')) }}" alt="Logo" height="60"
                width="60">
        </div> -->

        <!-- Navbar -->
        @include('backend.layouts.navbar')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-modern elevation-0">
            <div class="brand-link sidebar-brand">
                <div class="sidebar-brand__icon" aria-hidden="true">
                    <i class="fas fa-store"></i>
                </div>
                <span class="sidebar-brand__text">{{ readConfig('site_name') }}</span>
            </div>

            <!-- Sidebar -->
            @include('backend.layouts.sidebar')
            <!-- /.sidebar -->

        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div id="app-page-root">
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0">
                                    @yield('title')
                                </h1>
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div>
                <!-- /.content-header -->


                <!-- Main content -->
                <section class="content">
                    <!-- container-fluid -->
                    <div class="container-fluid">

                        <!-- content -->
                        <div class="page-modern @yield('page-class')">
                            @yield('content')
                        </div>
                        <!-- /.content -->

                    </div>
                    <!-- /.container-fluid -->
                </section>
                <!-- /.Main content -->
            </div>
            <!-- /.content-wrapper -->
        </div>

        @include('backend.layouts.footer')

        <!-- Control Sidebar — Help & Support -->
        <aside class="control-sidebar control-sidebar-dark">
            <div class="p-3" style="min-width:280px;">

                {{-- Header --}}
                <div class="text-center mb-3 pt-2">
                    <div style="width:56px;height:56px;background:linear-gradient(135deg,#e94560,#0f3460);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                        <i class="fas fa-headset" style="font-size:24px;color:#fff;"></i>
                    </div>
                    <h5 class="mb-0" style="color:#fff;font-weight:700;letter-spacing:1px;">Help & Support</h5>
                    <small style="color:rgba(255,255,255,.5);">{{ readConfig('studio_name') }}</small>
                </div>

                <hr style="border-color:rgba(255,255,255,.15);">

                {{-- Contact Channels --}}
                <h6 style="color:rgba(255,255,255,.6);font-size:10px;letter-spacing:1.5px;text-transform:uppercase;" class="mb-2">Contact Us</h6>

                <a href="https://wa.me/923306540300" target="_blank"
                   class="d-flex align-items-center mb-2 p-2 rounded"
                   style="background:rgba(37,211,102,.12);text-decoration:none;">
                    <i class="fab fa-whatsapp mr-2" style="color:#25d366;font-size:18px;"></i>
                    <div>
                        <div style="color:#fff;font-size:13px;font-weight:600;">WhatsApp</div>
                        <div style="color:rgba(255,255,255,.5);font-size:11px;">+92 330 654 0300</div>
                    </div>
                </a>

                <a href="mailto:info.waseem21@gmail.com"
                   class="d-flex align-items-center mb-3 p-2 rounded"
                   style="background:rgba(66,133,244,.12);text-decoration:none;">
                    <i class="fas fa-envelope mr-2" style="color:#4285f4;font-size:18px;"></i>
                    <div>
                        <div style="color:#fff;font-size:13px;font-weight:600;">Email</div>
                        <div style="color:rgba(255,255,255,.5);font-size:11px;">info.waseem21@gmail.com</div>
                    </div>
                </a>

                <hr style="border-color:rgba(255,255,255,.15);">

                {{-- Services --}}
                <h6 style="color:rgba(255,255,255,.6);font-size:10px;letter-spacing:1.5px;text-transform:uppercase;" class="mb-2">Our Services</h6>

                @foreach([
                    ['icon'=>'fas fa-globe','color'=>'#4285f4','label'=>'Website Development'],
                    ['icon'=>'fas fa-cloud','color'=>'#34a853','label'=>'SaaS Applications'],
                    ['icon'=>'fas fa-cash-register','color'=>'#f4b400','label'=>'POS Systems'],
                    ['icon'=>'fas fa-robot','color'=>'#e94560','label'=>'AI Solutions'],
                    ['icon'=>'fas fa-mobile-alt','color'=>'#00bcd4','label'=>'Mobile Apps'],
                    ['icon'=>'fas fa-code','color'=>'#9c27b0','label'=>'Custom Software'],
                ] as $svc)
                <div class="d-flex align-items-center mb-2">
                    <i class="{{ $svc['icon'] }} mr-2" style="color:{{ $svc['color'] }};width:16px;text-align:center;"></i>
                    <span style="color:rgba(255,255,255,.8);font-size:12px;">{{ $svc['label'] }}</span>
                </div>
                @endforeach

                <hr style="border-color:rgba(255,255,255,.15);">

                {{-- Footer note --}}
                <p style="color:rgba(255,255,255,.35);font-size:10px;text-align:center;margin:0;">
                    For any issue, bug, or new project<br>feel free to reach out anytime.
                </p>
                <p style="color:rgba(255,255,255,.2);font-size:9px;text-align:center;margin-top:6px;">
                    © {{ date('Y') }} {{ readConfig('studio_name') }}
                </p>

            </div>
        </aside>
        <!-- /.control-sidebar -->

    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ asset('plugins/sparklines/sparkline.js') }}"></script>
    <!-- JQVMap -->
    <script src="{{ asset('plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- Summernote -->
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    {{-- custom script --}}
    <script src="{{ asset('js/custom-script.js') }}?v=7"></script>
    <script src="{{ asset('js/app-confirm.js') }}?v=3"></script>
    <script src="{{ asset('js/app-navigation.js') }}?v=6"></script>
    <script src="{{ asset('js/form-modern.js') }}?v=2"></script>
    <!-- dropzonejs -->
    <script src="{{ asset('plugins/dropzone/min/dropzone.min.js') }}"></script>

    {{-- datatable --}}
    <script src="{{ asset('assets/js/datatable/datatable.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/datatable-modern.js') }}?v=3"></script>

    <script>
        window.__vitePosEntry = @json(\Illuminate\Support\Facades\Vite::asset('resources/js/app.jsx'));
    </script>
    <script src="{{ asset('js/pos-boot.js') }}?v=1"></script>

    <div id="page-scripts">
        @stack('script')
        @stack('scripts')
    </div>

    @viteReactRefresh
    @vite('resources/js/app.jsx')

    {{-- Notification Bell Live Update --}}
    <script>
    function loadNotifications() {
        $.ajax({
            url: '{{ route("backend.admin.notifications.unread") }}',
            success: function(data) {
                var count = data.count;
                var badge = $('#notifBadge');
                if (count > 0) {
                    badge.text(count).removeClass('d-none');
                } else {
                    badge.addClass('d-none');
                }
                $('#notifHeader').text(count + ' Notification' + (count !== 1 ? 's' : ''));
                var list = '';
                if (data.notifications.length === 0) {
                    list = '<span class="dropdown-item text-center text-muted">No new notifications</span>';
                } else {
                    data.notifications.forEach(function(n) {
                        list += \'<a href="\' + (n.action_url || \'#\') + \'" class="dropdown-item notification-dropdown-item">\';
                        list += \'<strong>\' + n.title + \'</strong>\';
                        list += \'<small class="text-muted d-block">\' + n.message.substring(0, 80) + (n.message.length > 80 ? \'...\' : \'\') + \'</small>\';
                        list += \'<small class="text-muted">\' + n.created_at + \'</small>\';
                        list += \'</a><div class="dropdown-divider"></div>\';
                    });
                }
                $('#notifList').html(list);
            }
        });
    }
    $(function() {
        loadNotifications();
        setInterval(loadNotifications, 60000); // Refresh every minute
    });
    </script>
</body>

</html>