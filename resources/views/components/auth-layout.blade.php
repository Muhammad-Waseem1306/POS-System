@props([
    'pageTitle',
    'title',
    'subtitle' => '',
    'illustration' => 'register.svg',
    'brandTitle' => null,
    'brandSubtitle' => 'Manage sales, inventory, and customers from one powerful dashboard.',
])

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle }}</title>
    <x-favicon />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/auth-modern.css') }}">
    @isset($head)
        {{ $head }}
    @endisset
</head>

<body class="auth-page">
    <x-simple-alert />

    <div class="auth-shell">
        <aside class="auth-brand">
            <div class="auth-brand__content">
                <figure class="auth-brand__illustration">
                    <img src="{{ asset('assets/images/authentication/' . $illustration) }}"
                        alt="{{ $title }} illustration">
                </figure>
                <h2 class="auth-brand__title">{{ $brandTitle ?? readConfig('site_name') }}</h2>
                <p class="auth-brand__subtitle">{{ $brandSubtitle }}</p>
            </div>
        </aside>

        <main class="auth-main">
            <div class="auth-card">
                <div class="auth-mobile-brand">
                    <x-auth-brand-icon size="sm" />
                </div>

                <div class="auth-card__logo d-none d-lg-flex">
                    <x-auth-brand-icon />
                </div>

                <header class="auth-card__header">
                    <h1 class="auth-card__title">{{ $title }}</h1>
                    @if ($subtitle)
                        <p class="auth-card__subtitle">{{ $subtitle }}</p>
                    @endif
                </header>

                {{ $slot }}

                @isset($footer)
                    <div class="auth-footer">
                        {{ $footer }}
                    </div>
                @endisset
            </div>
        </main>
    </div>

    <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/validation/validation.js') }}"></script>
    <script src="{{ asset('assets/js/auth-modern.js') }}"></script>
    @isset($scripts)
        {{ $scripts }}
    @endisset
</body>

</html>
