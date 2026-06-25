@extends('backend.master')

@section('title', 'Customer Details')

@section('content')
<div class="content-card">
  <div class="card-body">
    <div class="page-header mb-4">
      <div class="page-header__info">
        <h2 class="page-header__title">{{ $customer->name }}</h2>
        <p class="page-header__subtitle">Customer profile and documents</p>
      </div>
      <div class="page-header__actions">
        <a href="{{ route('backend.admin.customers.index') }}" class="btn btn-modern btn-modern--ghost">
          <i class="fas fa-arrow-left"></i> Back
        </a>
        @can('customer_update')
        <a href="{{ route('backend.admin.customers.edit', $customer->id) }}" class="btn btn-modern btn-modern--primary">
          <i class="fas fa-edit"></i> Edit
        </a>
        @endcan
        <button class="btn btn-modern btn-modern--secondary" onclick="window.print()">
          <i class="fas fa-print"></i> Print
        </button>
      </div>
    </div>

    <div class="detail-grid mb-4">
      <div class="detail-item">
        <span class="detail-item__label">Phone</span>
        <span class="detail-item__value">{{ $customer->phone }}</span>
      </div>
      <div class="detail-item">
        <span class="detail-item__label">CNIC</span>
        <span class="detail-item__value">{{ $customer->cnic ?? 'N/A' }}</span>
      </div>
      <div class="detail-item">
        <span class="detail-item__label">Address</span>
        <span class="detail-item__value">{{ $customer->address ?? 'N/A' }}</span>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <div class="content-card">
          <div class="card-body">
            <h5 class="form-section-title">Customer Documents</h5>
            <div class="list-group list-group-flush">
              @forelse($customer->documents as $doc)
              <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                <span><strong>{{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}</strong></span>
                <span class="btn-group-responsive">
                  <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="btn btn-modern btn-modern--primary btn-modern--sm">View</a>
                  <a href="{{ asset('storage/' . $doc->file_path) }}" download class="btn btn-modern btn-modern--ghost btn-modern--sm">Download</a>
                </span>
              </div>
              @empty
              <div class="list-group-item px-0 text-muted">No customer documents uploaded.</div>
              @endforelse
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 mb-3">
        <div class="content-card">
          <div class="card-body">
            <h5 class="form-section-title">Guarantors</h5>
            @forelse($customer->guarantors as $guarantor)
            <div class="content-card mb-3">
              <div class="card-body">
                <div class="detail-grid mb-3">
                  <div class="detail-item">
                    <span class="detail-item__label">Name</span>
                    <span class="detail-item__value">{{ $guarantor->name }}</span>
                  </div>
                  <div class="detail-item">
                    <span class="detail-item__label">Phone</span>
                    <span class="detail-item__value">{{ $guarantor->phone }}</span>
                  </div>
                  <div class="detail-item">
                    <span class="detail-item__label">CNIC</span>
                    <span class="detail-item__value">{{ $guarantor->cnic }}</span>
                  </div>
                  <div class="detail-item">
                    <span class="detail-item__label">Address</span>
                    <span class="detail-item__value">{{ $guarantor->address ?? 'N/A' }}</span>
                  </div>
                  <div class="detail-item">
                    <span class="detail-item__label">Relationship</span>
                    <span class="detail-item__value">{{ $guarantor->relationship ?? 'N/A' }}</span>
                  </div>
                  <div class="detail-item">
                    <span class="detail-item__label">Notes</span>
                    <span class="detail-item__value">{{ $guarantor->notes ?? 'N/A' }}</span>
                  </div>
                </div>
                <h6 class="text-muted mb-2">Guarantor Documents</h6>
                <div class="list-group list-group-flush">
                  @forelse($guarantor->documents as $doc)
                  <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                    <span><strong>{{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}</strong></span>
                    <span class="btn-group-responsive">
                      <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="btn btn-modern btn-modern--primary btn-modern--sm">View</a>
                      <a href="{{ asset('storage/' . $doc->file_path) }}" download class="btn btn-modern btn-modern--ghost btn-modern--sm">Download</a>
                    </span>
                  </div>
                  @empty
                  <div class="list-group-item px-0 text-muted">No guarantor documents uploaded.</div>
                  @endforelse
                </div>
              </div>
            </div>
            @empty
            <p class="text-muted mb-0">No guarantors registered for this customer.</p>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
