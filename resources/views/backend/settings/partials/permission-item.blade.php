@props(['data'])

<div class="permission-item">
    <span class="permission-item__label">{{ snakeToTitle($data->name) }}</span>
    <code class="permission-item__slug">{{ $data->name }}</code>
</div>
