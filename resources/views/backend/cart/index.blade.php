@extends('backend.master')
@section('title', 'Point of Sale')
@section('page-class', 'page-modern--pos')
@section('content')
<div id="cart" class="pos-shell">
    <div class="content-card p-4 text-center text-muted pos-shell__loading">
        <i class="fas fa-spinner fa-spin fa-2x mb-2" aria-hidden="true"></i>
        <p class="mb-0">Loading POS…</p>
    </div>
</div>
@endsection
@push('style')
<link rel="stylesheet" href="{{ asset('css/pos-modern.css') }}?v=3">
@endpush
@push('script')
<script>
    bootPosApp('cart');
</script>
@endpush
