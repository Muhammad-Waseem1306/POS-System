<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>QPOS — Account Recovery</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Segoe UI', sans-serif; background: #0f1923; color: #e0e0e0; min-height: 100vh; padding: 40px 20px; }
  .container { max-width: 800px; margin: 0 auto; }
  .header { text-align: center; margin-bottom: 32px; }
  .header h1 { font-size: 26px; color: #fff; letter-spacing: 2px; }
  .header p { color: rgba(255,255,255,.4); font-size: 13px; margin-top: 6px; }
  .warning { background: rgba(233,69,96,.12); border: 1px solid rgba(233,69,96,.4); border-radius: 8px; padding: 12px 18px; margin-bottom: 24px; font-size: 13px; color: #e94560; }
  .success-msg { background: rgba(52,168,83,.12); border: 1px solid rgba(52,168,83,.4); border-radius: 8px; padding: 12px 18px; margin-bottom: 24px; font-size: 13px; color: #34a853; }
  .card { background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.1); border-radius: 10px; margin-bottom: 16px; overflow: hidden; }
  .card-header { background: rgba(255,255,255,.06); padding: 14px 20px; display: flex; align-items: center; justify-content: space-between; }
  .user-info { display: flex; align-items: center; gap: 14px; }
  .avatar { width: 40px; height: 40px; background: linear-gradient(135deg,#e94560,#0f3460); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 700; color: #fff; }
  .user-name { font-size: 15px; font-weight: 600; color: #fff; }
  .user-email { font-size: 12px; color: rgba(255,255,255,.45); margin-top: 2px; }
  .role-badge { padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; letter-spacing: .5px; }
  .role-Admin { background: rgba(233,69,96,.2); color: #e94560; border: 1px solid rgba(233,69,96,.4); }
  .role-cashier { background: rgba(66,133,244,.2); color: #4285f4; border: 1px solid rgba(66,133,244,.4); }
  .role-sales_associate { background: rgba(52,168,83,.2); color: #34a853; border: 1px solid rgba(52,168,83,.4); }
  .card-body { padding: 16px 20px; }
  form { display: flex; gap: 10px; align-items: center; }
  input[type=password] { flex: 1; background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.15); border-radius: 6px; padding: 8px 12px; color: #fff; font-size: 13px; outline: none; }
  input[type=password]:focus { border-color: #e94560; }
  input[type=password]::placeholder { color: rgba(255,255,255,.3); }
  button { background: #e94560; color: #fff; border: none; border-radius: 6px; padding: 8px 18px; font-size: 13px; font-weight: 600; cursor: pointer; white-space: nowrap; }
  button:hover { background: #c73652; }
  .footer-note { text-align: center; margin-top: 32px; font-size: 11px; color: rgba(255,255,255,.2); }
</style>
</head>
<body>
<div class="container">

  <div class="header">
    <h1>🔑 QPOS Account Recovery</h1>
    <p>Local access only &mdash; reset any account password without losing data</p>
  </div>

  <div class="warning">
    ⚠️ This page is only accessible from this computer. Close it after use.
  </div>

  @if(session('success'))
  <div class="success-msg">✅ {{ session('success') }}</div>
  @endif

  @foreach($users as $user)
  <div class="card">
    <div class="card-header">
      <div class="user-info">
        <div class="avatar">{{ strtoupper(substr($user->name ?? $user->email, 0, 1)) }}</div>
        <div>
          <div class="user-name">{{ $user->name ?? '(no name)' }}</div>
          <div class="user-email">{{ $user->email }}</div>
        </div>
      </div>
      <div style="display:flex;gap:6px;flex-wrap:wrap;">
        @forelse($user->roles as $role)
          <span class="role-badge role-{{ $role->name }}">{{ $role->name }}</span>
        @empty
          <span class="role-badge" style="background:rgba(255,255,255,.08);color:rgba(255,255,255,.4);border:1px solid rgba(255,255,255,.1);">no role</span>
        @endforelse
      </div>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ url('/recovery/'.$user->id.'/reset') }}">
        @csrf
        <input type="password" name="password" placeholder="Enter new password (min 6 chars)" required minlength="6">
        <button type="submit">Reset Password</button>
      </form>
    </div>
  </div>
  @endforeach

  <div class="footer-note">QPOS Recovery Tool &mdash; Alkyne Solutions</div>
</div>
</body>
</html>
