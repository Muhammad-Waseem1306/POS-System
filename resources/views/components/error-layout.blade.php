@props(['code' => '404', 'title' => 'Page not found', 'message' => 'The page you are looking for does not exist or has been moved.'])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $code }} | {{ readConfig('app_name') ?? 'QPOS' }}</title>
    <x-favicon />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/auth-modern.css') }}">
    <style>
        .error-shell {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            background: #f8fafc;
        }
        .error-card {
            max-width: 480px;
            width: 100%;
            text-align: center;
            padding: 2.5rem 2rem;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(15, 23, 42, 0.08);
        }
        .error-card__code {
            font-size: 4rem;
            font-weight: 800;
            color: #2563eb;
            letter-spacing: -0.04em;
            line-height: 1;
            margin-bottom: 0.5rem;
        }
        .error-card__title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.5rem;
        }
        .error-card__message {
            color: #64748b;
            font-size: 0.9375rem;
            margin-bottom: 1.5rem;
        }
        .error-card__actions {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        .error-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.625rem 1.25rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.875rem;
            text-decoration: none;
        }
        .error-btn--primary {
            background: #2563eb;
            color: #fff;
        }
        .error-btn--ghost {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="error-shell">
        <div class="error-card">
            <div class="error-card__code">{{ $code }}</div>
            <h1 class="error-card__title">{{ $title }}</h1>
            <p class="error-card__message">{{ $message }}</p>
            <div class="error-card__actions">
                <a href="{{ url('/') }}" class="error-btn error-btn--primary">Go to Login</a>
                <a href="javascript:history.back()" class="error-btn error-btn--ghost">Go Back</a>
            </div>
        </div>
    </div>
</body>
</html>
