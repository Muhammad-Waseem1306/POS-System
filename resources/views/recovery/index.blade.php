<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Account Recovery | {{ readConfig('site_name') ?? 'QPOS' }}</title>
    <x-favicon />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/auth-modern.css') }}">
    <style>
        body { background: #f8fafc; min-height: 100vh; padding: 2rem 1rem; font-family: "Plus Jakarta Sans", system-ui, sans-serif; }
        .recovery-shell { max-width: 720px; margin: 0 auto; }
        .recovery-header { text-align: center; margin-bottom: 2rem; }
        .recovery-header h1 { font-size: 1.5rem; font-weight: 700; color: #0f172a; margin-bottom: 0.35rem; }
        .recovery-header p { color: #64748b; font-size: 0.875rem; margin: 0; }
        .recovery-alert { padding: 0.875rem 1rem; border-radius: 10px; font-size: 0.875rem; margin-bottom: 1.25rem; }
        .recovery-alert--warning { background: #fffbeb; border: 1px solid #fde68a; color: #92400e; }
        .recovery-alert--success { background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; }
        .recovery-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; margin-bottom: 1rem; overflow: hidden; box-shadow: 0 1px 3px rgba(15,23,42,0.04); }
        .recovery-card__header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem; padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; }
        .recovery-user { display: flex; align-items: center; gap: 0.875rem; }
        .recovery-user__avatar { width: 2.5rem; height: 2.5rem; background: #eff6ff; color: #2563eb; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 700; }
        .recovery-user__name { font-weight: 600; color: #0f172a; font-size: 0.9375rem; }
        .recovery-user__email { font-size: 0.8125rem; color: #64748b; }
        .recovery-role { padding: 0.2rem 0.6rem; border-radius: 6px; font-size: 0.6875rem; font-weight: 600; background: #f1f5f9; color: #475569; }
        .recovery-card__body { padding: 1rem 1.25rem; }
        .recovery-form { display: flex; gap: 0.5rem; flex-wrap: wrap; }
        .recovery-form input[type=password] { flex: 1; min-width: 200px; border: 1px solid #e2e8f0; border-radius: 10px; padding: 0.5rem 0.75rem; font-size: 0.875rem; }
        .recovery-form input:focus { outline: none; border-color: #93c5fd; box-shadow: 0 0 0 3px rgba(37,99,235,0.12); }
        .recovery-form button { background: #2563eb; color: #fff; border: none; border-radius: 10px; padding: 0.5rem 1rem; font-weight: 600; font-size: 0.875rem; cursor: pointer; }
        .recovery-form button:hover { background: #1d4ed8; }
        .recovery-footer { text-align: center; margin-top: 2rem; font-size: 0.75rem; color: #94a3b8; }
    </style>
</head>
<body>
<div class="recovery-shell">
    <div class="recovery-header">
        <h1>Account Recovery</h1>
        <p>Local access only — reset account passwords without losing data</p>
    </div>

    <div class="recovery-alert recovery-alert--warning">
        This page is only accessible from this computer. Close it after use.
    </div>

    @if(session('success'))
    <div class="recovery-alert recovery-alert--success">{{ session('success') }}</div>
    @endif

    @foreach($users as $user)
    <div class="recovery-card">
        <div class="recovery-card__header">
            <div class="recovery-user">
                <div class="recovery-user__avatar">{{ strtoupper(substr($user->name ?? $user->email, 0, 1)) }}</div>
                <div>
                    <div class="recovery-user__name">{{ $user->name ?? '(no name)' }}</div>
                    <div class="recovery-user__email">{{ $user->email }}</div>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-1">
                @forelse($user->roles as $role)
                    <span class="recovery-role">{{ $role->name }}</span>
                @empty
                    <span class="recovery-role">no role</span>
                @endforelse
            </div>
        </div>
        <div class="recovery-card__body">
            <form method="POST" action="{{ url('/recovery/'.$user->id.'/reset') }}" class="recovery-form">
                @csrf
                <input type="password" name="password" placeholder="New password (min 6 chars)" required minlength="6">
                <button type="submit">Reset Password</button>
            </form>
        </div>
    </div>
    @endforeach

    <div class="recovery-footer">{{ readConfig('studio_name') ?? 'QPOS' }} Recovery Tool</div>
</div>
</body>
</html>
