@extends('backend.master')

@section('title', 'Product Purchase')
@section('page-class', 'page-modern--pos')

@section('content')
<div id="purchase" class="pos-shell">
    <div class="content-card p-4 text-center text-muted pos-shell__loading">
        <i class="fas fa-spinner fa-spin fa-2x mb-2" aria-hidden="true"></i>
        <p class="mb-0">Loading purchase form…</p>
    </div>
</div>
@endsection

@push('style')
<link rel="stylesheet" href="{{ asset('css/pos-modern.css') }}?v=3">
<style>
  .react-datepicker-wrapper {
    width: 100%;
    box-sizing: border-box;
  }
</style>
@endpush
@push('script')
<script>
    bootPosApp('purchase');
</script>
@endpush
