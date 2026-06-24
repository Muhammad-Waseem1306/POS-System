<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        @yield('title', 'Dashboard') | {{ readConfig('app_name') }}
    </title>

    <!-- FAVICON ICON -->
    <link rel="shortcut icon" href="{{ assetImage(readconfig('favicon_icon')) }}" type="image/svg+xml">

    <!-- FAVICON ICON APPLE -->
    <link href="{{ assetImage(readconfig('favicon_icon_apple')) }}" rel="apple-touch-icon">
    <link href="{{ assetImage(readconfig('favicon_icon_apple')) }}" rel="apple-touch-icon" sizes="72x72">
    <link href="{{ assetImage(readconfig('favicon_icon_apple')) }}" rel="apple-touch-icon" sizes="114x114">
    <link href="{{ assetImage(readconfig('favicon_icon_apple')) }}" rel="apple-touch-icon" sizes="144x144">

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

    <style>
        .image-upload-container {
            border: 2px dashed #8b9ee9;
            /* Dashed border color */
            border-radius: 8px;
            background-color: #f8f9fa;
            /* Light background color */
            display: flex;
            justify-content: center;
            /* Center the content */
            align-items: center;
            /* Center the content vertically */
            width: 100%;
            /* Make the container full width of its parent */
            height: 200px;
            /* Fixed height */
            cursor: pointer;
            /* Indicate clickability */
        }

        .thumb-preview {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            /* Prevents overflow */
        }

        #thumbnailPreview {
            max-width: 100%;
            max-height: 100%;
            /* Ensure it fits within the container */
            object-fit: cover;
            /* Maintain aspect ratio while covering the box */
        }

        .upload-text {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #8b9ee9;
            /* Text color */
            text-align: center;
        }

        .upload-text i {
            font-size: 24px;
            /* Icon size */
            margin-bottom: 5px;
            /* Space between icon and text */
        }
    </style>
    @stack('style')
    @viteReactRefresh
    @vite('resources/js/app.jsx')
</head>

<body class="hold-transition sidebar-mini layout-fixed">

    <x-simple-alert />

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
        <aside class="main-sidebar elevation-4 sidebar-light-lightblue">
            <!-- Brand Logo -->
            <a href="{{ route('frontend.home') }}" class="brand-link">
                <img src="{{ assetImage(readconfig('site_logo')) }}" alt="Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">{{ readConfig('site_name') }}</span>
            </a>

            <!-- Sidebar -->
            @include('backend.layouts.sidebar')
            <!-- /.sidebar -->

        </aside>

        <!-- Content Wrapper. Contains page content -->
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
                    @yield('content')
                    <!-- /.content -->

                </div>
                <!-- /.container-fluid -->
            </section>
            <!-- /.Main content -->
        </div>
        <!-- /.content-wrapper -->

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
                    <small style="color:rgba(255,255,255,.5);">Alkyne Solutions</small>
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
                    © {{ date('Y') }} Alkyne Solutions
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
    <script src="{{ asset('js/custom-script.js') }}"></script>
    <!-- dropzonejs -->
    <script src="{{ asset('plugins/dropzone/min/dropzone.min.js') }}"></script>

    {{-- datatable --}}
    <script src="{{ asset('assets/js/datatable/datatable.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/dataTables.buttons.min.js') }}"></script>

    @stack('script')
    @stack('scripts')

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
                        list += '<a href="' + (n.action_url || '#') + '" class="dropdown-item">';
                        list += '<i class="fas fa-circle mr-2 text-' + n.severity + '"></i>';
                        list += '<strong>' + n.title + '</strong>';
                        list += '<span class="float-right text-muted text-sm">' + n.created_at + '</span>';
                        list += '<br><small>' + n.message.substring(0, 60) + '...</small>';
                        list += '</a><div class="dropdown-divider"></div>';
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