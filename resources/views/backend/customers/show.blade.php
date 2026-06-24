@extends('backend.master')

@section('title', 'Customer Details')

@section('content')
<div class="card">
  <div class="card-body">
    <div class="row mb-3">
      <div class="col-md-8">
        <h4>{{ $customer->name }}</h4>
        <p><strong>Phone:</strong> {{ $customer->phone }}</p>
        <p><strong>CNIC:</strong> {{ $customer->cnic ?? 'N/A' }}</p>
        <p><strong>Address:</strong> {{ $customer->address ?? 'N/A' }}</p>
      </div>
      <div class="col-md-4 text-right">
        <button class="btn btn-primary" onclick="window.print()">Print</button>
      </div>
    </div>

    <div class="row mb-4">
      <div class="col-md-6">
        <div class="card mb-3">
          <div class="card-body">
            <h5 class="card-title">Customer Documents</h5>
            <div class="list-group">
              @forelse($customer->documents as $doc)
              <div class="list-group-item">
                <div class="d-flex justify-content-between align-items-center">
                  <span><strong>{{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}</strong></span>
                  <span>
                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="btn btn-sm btn-info">View</a>
                    <a href="{{ asset('storage/' . $doc->file_path) }}" download class="btn btn-sm btn-secondary">Download</a>
                  </span>
                </div>
              </div>
              @empty
              <div class="list-group-item">
                <span>No customer documents uploaded.</span>
              </div>
              @endforelse
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card mb-3">
          <div class="card-body">
            <h5 class="card-title">Guarantors</h5>
            @forelse($customer->guarantors as $guarantor)
            <div class="border rounded p-3 mb-3">
              <p><strong>Name:</strong> {{ $guarantor->name }}</p>
              <p><strong>Phone:</strong> {{ $guarantor->phone }}</p>
              <p><strong>CNIC:</strong> {{ $guarantor->cnic }}</p>
              <p><strong>Address:</strong> {{ $guarantor->address ?? 'N/A' }}</p>
              <p><strong>Relationship:</strong> {{ $guarantor->relationship ?? 'N/A' }}</p>
              <p><strong>Notes:</strong> {{ $guarantor->notes ?? 'N/A' }}</p>
              
              <h6 class="mt-3 mb-2">Guarantor Documents:</h6>
              <div class="list-group">
                @forelse($guarantor->documents as $doc)
                <div class="list-group-item">
                  <div class="d-flex justify-content-between align-items-center">
                    <span><strong>{{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}</strong></span>
                    <span>
                      <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="btn btn-sm btn-info">View</a>
                      <a href="{{ asset('storage/' . $doc->file_path) }}" download class="btn btn-sm btn-secondary">Download</a>
                    </span>
                  </div>
                </div>
                @empty
                <div class="list-group-item">
                  <span>No guarantor documents uploaded.</span>
                </div>
                @endforelse
              </div>
            </div>
            @empty
            <p>No guarantors registered for this customer.</p>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
