@props([
    'permissionGroups',
    'role' => null,
    'user' => null,
    'mode' => 'list',
    'disabled' => false,
])

@foreach ($permissionGroups as $groupIndex => $group)
@php
    $groupId = 'permission-group-' . $groupIndex;
    $groupCount = collect($group['permissions'] ?? [])->count()
        + collect($group['sections'] ?? [])->sum(fn ($section) => count($section['permissions'] ?? []));
@endphp
<div class="permission-group {{ $mode === 'matrix' ? 'permission-group--matrix' : 'permission-group--list' }}">
    <div class="content-card permission-group-card {{ $mode === 'list' ? 'permission-group-card--list' : '' }}" @if($mode === 'matrix') data-permission-group="{{ $groupId }}" @endif>
        <div class="permission-group-card__header">
            <div class="permission-group-card__heading">
                <h3 class="permission-group-card__title">{{ $group['label'] }}</h3>
                @if ($mode === 'list')
                <span class="permission-group-card__count">{{ $groupCount }} {{ Str::plural('permission', $groupCount) }}</span>
                @endif
            </div>
            @if ($mode === 'matrix' && ! $disabled)
            <div class="permission-group-card__actions">
                <button type="button" class="btn btn-modern btn-modern--ghost btn-modern--sm permission-group-select-all" data-target="{{ $groupId }}">
                    Select all
                </button>
                <button type="button" class="btn btn-modern btn-modern--ghost btn-modern--sm permission-group-clear-all" data-target="{{ $groupId }}">
                    Clear
                </button>
            </div>
            @endif
        </div>
        <div class="permission-group-card__body" @if($mode === 'matrix') id="{{ $groupId }}" @endif>
            @if (! empty($group['sections']))
                @foreach ($group['sections'] as $section)
                <div class="permission-group__section">
                    <h4 class="permission-group__subtitle">{{ $section['label'] }}</h4>
                    <div class="permission-group__grid">
                        @foreach ($section['permissions'] as $data)
                            @if ($mode === 'matrix')
                                @include('backend.settings.partials.permission-toggle', [
                                    'data' => $data,
                                    'role' => $role,
                                    'user' => $user,
                                    'disabled' => $disabled,
                                ])
                            @else
                                @include('backend.settings.partials.permission-item', ['data' => $data])
                            @endif
                        @endforeach
                    </div>
                </div>
                @endforeach
            @else
                <div class="permission-group__grid">
                    @foreach ($group['permissions'] as $data)
                        @if ($mode === 'matrix')
                            @include('backend.settings.partials.permission-toggle', [
                                'data' => $data,
                                'role' => $role,
                                'user' => $user,
                                'disabled' => $disabled,
                            ])
                        @else
                            @include('backend.settings.partials.permission-item', ['data' => $data])
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endforeach
