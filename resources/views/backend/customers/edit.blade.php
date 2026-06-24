@extends('backend.master')

@section('title', 'Create Customer')

@section('content')
<div class="card">
  <div class="card-body">
    <form action="{{ route('backend.admin.customers.update',$customer->id) }}" method="post" class="accountForm"
      enctype="multipart/form-data">
      @method('PUT')
      @csrf
      <div class="card-body">
        <div class="row">
          <div class="mb-3 col-md-6">
            <label for="title" class="form-label">
              Name
              <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control" placeholder="Enter title" name="name"
              value="{{ $customer->name }}" required>
          </div>
          <div class="mb-3 col-md-6">
            <label for="title" class="form-label">
              Phone
              <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control" placeholder="Enter phone" name="phone"
              value="{{ $customer->phone }}" required>
          </div>
          <div class="mb-3 col-md-6">
            <label for="cnic" class="form-label">
              CNIC
            </label>
            <input type="text" class="form-control" placeholder="Enter CNIC" name="cnic"
              value="{{ $customer->cnic }}">
          </div>
          <div class="mb-3 col-md-6">
            <label for="title" class="form-label">
              Address
            </label>
            <input type="text" class="form-control" placeholder="Enter Address" name="address"
              value="{{ $customer->address }}">
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-12">
            <h5>Verification Documents</h5>
          </div>
          <div class="mb-3 col-md-3">
            <label class="form-label">Customer Photo</label>
            <input type="file" class="form-control" name="photo" accept="image/*">
          </div>
          <div class="mb-3 col-md-3">
            <label class="form-label">CNIC Front</label>
            <input type="file" class="form-control" name="cnic_front" accept="image/*,.pdf">
          </div>
          <div class="mb-3 col-md-3">
            <label class="form-label">CNIC Back</label>
            <input type="file" class="form-control" name="cnic_back" accept="image/*,.pdf">
          </div>
          <div class="mb-3 col-md-3">
            <label class="form-label">Utility Bill</label>
            <input type="file" class="form-control" name="utility_bill" accept="image/*,.pdf">
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-12">
            <h5>Guarantor Details</h5>
          </div>
          <div class="col-md-12">
            @foreach($customer->guarantors as $index => $existingGuarantor)
            <div class="border rounded p-3 mb-3">
              <p><strong>Name:</strong> {{ $existingGuarantor->name }}</p>
              <p><strong>Phone:</strong> {{ $existingGuarantor->phone }}</p>
              <p><strong>CNIC:</strong> {{ $existingGuarantor->cnic }}</p>
              <p><strong>Relationship:</strong> {{ $existingGuarantor->relationship ?? 'N/A' }}</p>
              <div class="mb-2">
                @foreach($existingGuarantor->documents as $doc)
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span>{{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}</span>
                  <span>
                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="btn btn-sm btn-info">View</a>
                    <a href="{{ asset('storage/' . $doc->file_path) }}" download class="btn btn-sm btn-secondary">Download</a>
                  </span>
                </div>
                @endforeach
                @if($existingGuarantor->documents->isEmpty())
                <p>No guarantor documents available.</p>
                @endif
              </div>
            </div>
            @endforeach
          </div>
          <div id="guarantorRows">
            <div class="row guarantor-row">
              <div class="mb-3 col-md-4">
                <label class="form-label">Guarantor Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="guarantor_name[]" value="{{ old('guarantor_name.0') }}" required>
              </div>
              <div class="mb-3 col-md-4">
                <label class="form-label">Guarantor CNIC <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="guarantor_cnic[]" value="{{ old('guarantor_cnic.0') }}" required>
              </div>
              <div class="mb-3 col-md-4">
                <label class="form-label">Guarantor Phone <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="guarantor_phone[]" value="{{ old('guarantor_phone.0') }}" required>
              </div>
              <div class="mb-3 col-md-4">
                <label class="form-label">Guarantor Address</label>
                <input type="text" class="form-control" name="guarantor_address[]" value="{{ old('guarantor_address.0') }}">
              </div>
              <div class="mb-3 col-md-4">
                <label class="form-label">Relationship</label>
                <input type="text" class="form-control" name="guarantor_relationship[]" value="{{ old('guarantor_relationship.0') }}">
              </div>
              <div class="mb-3 col-md-4">
                <label class="form-label">Document</label>
                <input type="file" class="form-control" name="guarantor_document[]" accept="image/*,.pdf">
              </div>
              <div class="mb-3 col-md-12">
                <label class="form-label">Notes</label>
                <textarea class="form-control" name="guarantor_notes[]">{{ old('guarantor_notes.0') }}</textarea>
              </div>
            </div>
          </div>
          <div class="mb-3 col-md-12">
            <button type="button" id="addGuarantor" class="btn btn-secondary">Add Guarantor</button>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <button type="submit" class="btn bg-gradient-primary">Update</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
@push('script')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const addButton = document.getElementById('addGuarantor');
    const guarantorRows = document.getElementById('guarantorRows');

    addButton.addEventListener('click', function () {
      const row = document.createElement('div');
      row.classList.add('row', 'guarantor-row');
      row.innerHTML = `
        <div class="mb-3 col-md-4">
          <label class="form-label">Guarantor Name</label>
          <input type="text" class="form-control" name="guarantor_name[]">
        </div>
        <div class="mb-3 col-md-4">
          <label class="form-label">Guarantor CNIC</label>
          <input type="text" class="form-control" name="guarantor_cnic[]">
        </div>
        <div class="mb-3 col-md-4">
          <label class="form-label">Guarantor Phone</label>
          <input type="text" class="form-control" name="guarantor_phone[]">
        </div>
        <div class="mb-3 col-md-4">
          <label class="form-label">Guarantor Address</label>
          <input type="text" class="form-control" name="guarantor_address[]">
        </div>
        <div class="mb-3 col-md-4">
          <label class="form-label">Relationship</label>
          <input type="text" class="form-control" name="guarantor_relationship[]">
        </div>
        <div class="mb-3 col-md-4">
          <label class="form-label">Document</label>
          <input type="file" class="form-control" name="guarantor_document[]" accept="image/*,.pdf">
        </div>
        <div class="mb-3 col-md-12">
          <label class="form-label">Notes</label>
          <textarea class="form-control" name="guarantor_notes[]"></textarea>
        </div>
      `;
      guarantorRows.appendChild(row);
    });
  });
</script>
@endpush