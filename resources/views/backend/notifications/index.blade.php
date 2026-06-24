@extends('backend.master')

@section('title', 'Notification Center')

@section('content')
<section class="content">
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4><i class="fas fa-bell"></i> Notification Center
                @if($unreadCount > 0)
                    <span class="badge bg-danger">{{ $unreadCount }} unread</span>
                @endif
            </h4>
            @if($unreadCount > 0)
            <form action="{{ route('backend.admin.notifications.mark-all-read') }}" method="POST">
                @csrf
                <button class="btn btn-sm btn-secondary">
                    <i class="fas fa-check-double"></i> Mark All Read
                </button>
            </form>
            @endif
        </div>

        {{-- Filters --}}
        <div class="card card-outline card-secondary">
            <div class="card-body py-2">
                <form method="GET" class="form-inline">
                    <div class="form-group mr-2">
                        <select name="type" class="form-control form-control-sm">
                            <option value="">All Types</option>
                            @foreach($types as $type)
                            <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_',' ',$type)) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mr-2">
                        <select name="severity" class="form-control form-control-sm">
                            <option value="">All Severities</option>
                            <option value="info">Info</option>
                            <option value="warning">Warning</option>
                            <option value="danger">Danger</option>
                            <option value="success">Success</option>
                        </select>
                    </div>
                    <div class="form-group mr-2">
                        <select name="is_read" class="form-control form-control-sm">
                            <option value="">All</option>
                            <option value="0">Unread Only</option>
                            <option value="1">Read Only</option>
                        </select>
                    </div>
                    <button class="btn btn-primary btn-sm">Filter</button>
                    <a href="{{ route('backend.admin.notifications.index') }}" class="btn btn-secondary btn-sm ml-2">Clear</a>
                </form>
            </div>
        </div>

        @forelse($notifications as $notification)
        <div class="card mb-2 {{ !$notification->is_read ? 'card-warning card-outline' : '' }}">
            <div class="card-body py-2">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="d-flex align-items-center">
                        <i class="{{ $notification->severity_icon }} mr-2 fa-lg"></i>
                        <div>
                            <strong>{{ $notification->title }}</strong>
                            @if(!$notification->is_read)
                                <span class="badge bg-warning text-dark ml-1">New</span>
                            @endif
                            <br>
                            <small>{{ $notification->message }}</small>
                            <br>
                            <small class="text-muted">
                                <i class="fas fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                                &bull; <span class="badge bg-secondary">{{ ucfirst(str_replace('_',' ',$notification->type)) }}</span>
                            </small>
                        </div>
                    </div>
                    <div class="d-flex">
                        @if($notification->action_url)
                        <a href="{{ $notification->action_url }}" class="btn btn-sm btn-info mr-1">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                        @endif
                        @if(!$notification->is_read)
                        <form action="{{ route('backend.admin.notifications.mark-read', $notification->id) }}" method="POST" class="mr-1">
                            @csrf
                            <button class="btn btn-sm btn-success"><i class="fas fa-check"></i></button>
                        </form>
                        @endif
                        <form action="{{ route('backend.admin.notifications.delete', $notification->id) }}" method="POST"
                              onsubmit="return confirm('Delete this notification?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
            <p class="text-muted">No notifications found.</p>
        </div>
        @endforelse

        {{ $notifications->appends(request()->query())->links() }}

    </div>
</section>
@endsection
