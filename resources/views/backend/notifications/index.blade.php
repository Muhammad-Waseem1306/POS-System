@extends('backend.master')

@section('title', 'Notification Center')
@section('page-class', 'page-modern--no-page-title')

@section('content')
<div class="content-card">
    @if($unreadCount > 0)
    <div class="content-card__toolbar">
        <span class="text-muted small">{{ $unreadCount }} unread notification{{ $unreadCount !== 1 ? 's' : '' }}</span>
        <form action="{{ route('backend.admin.notifications.mark-all-read') }}" method="POST">
            @csrf
            <button class="btn btn-modern btn-modern--secondary btn-modern--sm">
                <i class="fas fa-check-double"></i> Mark All Read
            </button>
        </form>
    </div>
    @endif

    <div class="filter-bar">
        <form method="GET" class="filter-bar__form form-modern">
            <div class="filter-bar__grid filter-bar__grid--notifications">
                <div class="filter-bar__field">
                    <label class="form-label" for="filterType">Type</label>
                    <select name="type" id="filterType" class="form-control form-control-sm">
                        <option value="">All Types</option>
                        @foreach($types as $type)
                        <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_',' ',$type)) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-bar__field">
                    <label class="form-label" for="filterSeverity">Severity</label>
                    <select name="severity" id="filterSeverity" class="form-control form-control-sm">
                        <option value="">All Severities</option>
                        <option value="info" {{ request('severity') === 'info' ? 'selected' : '' }}>Info</option>
                        <option value="warning" {{ request('severity') === 'warning' ? 'selected' : '' }}>Warning</option>
                        <option value="danger" {{ request('severity') === 'danger' ? 'selected' : '' }}>Danger</option>
                        <option value="success" {{ request('severity') === 'success' ? 'selected' : '' }}>Success</option>
                    </select>
                </div>
                <div class="filter-bar__field">
                    <label class="form-label" for="filterStatus">Status</label>
                    <select name="is_read" id="filterStatus" class="form-control form-control-sm">
                        <option value="">All Statuses</option>
                        <option value="0" {{ request('is_read') === '0' ? 'selected' : '' }}>Unread Only</option>
                        <option value="1" {{ request('is_read') === '1' ? 'selected' : '' }}>Read Only</option>
                    </select>
                </div>
                <x-filter-actions :clear-url="route('backend.admin.notifications.index')" />
            </div>
        </form>
    </div>

    <div class="content-card__body p-3">
        @forelse($notifications as $notification)
        <div class="notification-item {{ !$notification->is_read ? 'notification-item--unread' : '' }}">
            <div class="notification-item__icon">
                <i class="{{ $notification->severity_icon }}"></i>
            </div>
            <div class="notification-item__body">
                <div class="notification-item__title">
                    {{ $notification->title }}
                    @if(!$notification->is_read)
                        <span class="badge bg-warning text-dark ml-1">New</span>
                    @endif
                </div>
                <p class="notification-item__message">{{ $notification->message }}</p>
                <div class="notification-item__meta">
                    <i class="fas fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                    <span class="badge bg-secondary ml-1">{{ ucfirst(str_replace('_',' ',$notification->type)) }}</span>
                </div>
            </div>
            <div class="notification-item__actions">
                @if($notification->action_url)
                <a href="{{ $notification->action_url }}" class="btn btn-modern btn-modern--primary btn-modern--sm btn-modern--icon" title="Open">
                    <i class="fas fa-external-link-alt"></i>
                </a>
                @endif
                @if(!$notification->is_read)
                <form action="{{ route('backend.admin.notifications.mark-read', $notification->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-modern btn-modern--success btn-modern--sm btn-modern--icon" title="Mark read">
                        <i class="fas fa-check"></i>
                    </button>
                </form>
                @endif
                <form action="{{ route('backend.admin.notifications.delete', $notification->id) }}" method="POST"
                      data-confirm="Delete this notification?"
                      data-confirm-title="Delete notification"
                      data-confirm-ok="Delete"
                      data-confirm-variant="danger">
                    @csrf @method('DELETE')
                    <button class="btn btn-modern btn-modern--danger btn-modern--sm btn-modern--icon" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <x-empty-state
            icon="fas fa-bell-slash"
            title="No notifications found"
            message="You're all caught up, or try changing the filters above."
        />
        @endforelse

        @if($notifications->hasPages())
        <div class="datatable-footer">
            {{ $notifications->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
