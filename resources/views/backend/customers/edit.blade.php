@extends('backend.master')

@section('title', 'Edit Customer')

@section('content')
<x-form-page
    :action="route('backend.admin.customers.update', $customer->id)"
    method="PUT"
    :cancel-url="route('backend.admin.customers.index')"
    submit-label="Update Customer"
    enctype="multipart/form-data"
>
    <x-form-section title="Basic Information">
        <x-form-field label="Name" name="name" required>
            <input type="text" class="form-control" id="name" placeholder="Enter customer name" name="name"
                value="{{ old('name', $customer->name) }}" required>
        </x-form-field>
        <x-form-field label="Phone" name="phone" required>
            <input type="text" class="form-control" id="phone" placeholder="Enter phone number" name="phone"
                value="{{ old('phone', $customer->phone) }}" required>
        </x-form-field>
        <x-form-field label="CNIC" name="cnic">
            <input type="text" class="form-control" id="cnic" placeholder="Enter CNIC" name="cnic"
                value="{{ old('cnic', $customer->cnic) }}">
        </x-form-field>
        <x-form-field label="Address" name="address">
            <input type="text" class="form-control" id="address" placeholder="Enter address" name="address"
                value="{{ old('address', $customer->address) }}">
        </x-form-field>
    </x-form-section>

    <x-form-section title="Verification Documents">
        @php
            $documents = $customer->documents->keyBy('document_type');
            $doc = fn (string $type) => $documents->get($type);
        @endphp
        <x-form-field label="Customer Photo" col="md-3">
            <x-form-file
                name="photo"
                accept="image/*"
                :current-name="$doc(\App\Models\CustomerDocument::TYPE_CUSTOMER_PHOTO)?->original_name"
                :current-url="$doc(\App\Models\CustomerDocument::TYPE_CUSTOMER_PHOTO) ? asset('storage/' . $doc(\App\Models\CustomerDocument::TYPE_CUSTOMER_PHOTO)->file_path) : null"
            />
        </x-form-field>
        <x-form-field label="CNIC Front" col="md-3">
            <x-form-file
                name="cnic_front"
                accept="image/*,.pdf"
                :current-name="$doc(\App\Models\CustomerDocument::TYPE_CNIC_FRONT)?->original_name"
                :current-url="$doc(\App\Models\CustomerDocument::TYPE_CNIC_FRONT) ? asset('storage/' . $doc(\App\Models\CustomerDocument::TYPE_CNIC_FRONT)->file_path) : null"
            />
        </x-form-field>
        <x-form-field label="CNIC Back" col="md-3">
            <x-form-file
                name="cnic_back"
                accept="image/*,.pdf"
                :current-name="$doc(\App\Models\CustomerDocument::TYPE_CNIC_BACK)?->original_name"
                :current-url="$doc(\App\Models\CustomerDocument::TYPE_CNIC_BACK) ? asset('storage/' . $doc(\App\Models\CustomerDocument::TYPE_CNIC_BACK)->file_path) : null"
            />
        </x-form-field>
        <x-form-field label="Utility Bill" col="md-3">
            <x-form-file
                name="utility_bill"
                accept="image/*,.pdf"
                :current-name="$doc(\App\Models\CustomerDocument::TYPE_UTILITY_BILL)?->original_name"
                :current-url="$doc(\App\Models\CustomerDocument::TYPE_UTILITY_BILL) ? asset('storage/' . $doc(\App\Models\CustomerDocument::TYPE_UTILITY_BILL)->file_path) : null"
            />
        </x-form-field>
    </x-form-section>

    @if($customer->guarantors->isNotEmpty())
    <x-form-section title="Existing Guarantors">
        <div class="col-12">
            @foreach($customer->guarantors as $existingGuarantor)
            <div class="content-card mb-3 p-3">
                <p class="mb-1"><strong>Name:</strong> {{ $existingGuarantor->name }}</p>
                <p class="mb-1"><strong>Phone:</strong> {{ $existingGuarantor->phone }}</p>
                <p class="mb-1"><strong>CNIC:</strong> {{ $existingGuarantor->cnic }}</p>
                <p class="mb-2"><strong>Relationship:</strong> {{ $existingGuarantor->relationship ?? 'N/A' }}</p>
                @forelse($existingGuarantor->documents as $doc)
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span>{{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}</span>
                    <span class="table-actions-inline">
                        <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="table-actions-btn table-actions-btn--primary" title="View" aria-label="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ asset('storage/' . $doc->file_path) }}" download class="table-actions-btn table-actions-btn--ghost" title="Download" aria-label="Download">
                            <i class="fas fa-download"></i>
                        </a>
                    </span>
                </div>
                @empty
                <p class="text-muted mb-0">No guarantor documents available.</p>
                @endforelse
            </div>
            @endforeach
        </div>
    </x-form-section>
    @endif

    <x-form-section title="Add Guarantor">
        <div class="col-12" id="guarantorRows">
            <div class="row guarantor-row">
                <x-form-field label="Guarantor Name" required col="md-4">
                    <input type="text" class="form-control" name="guarantor_name[]" value="{{ old('guarantor_name.0') }}" required>
                </x-form-field>
                <x-form-field label="Guarantor CNIC" required col="md-4">
                    <input type="text" class="form-control" name="guarantor_cnic[]" value="{{ old('guarantor_cnic.0') }}" required>
                </x-form-field>
                <x-form-field label="Guarantor Phone" required col="md-4">
                    <input type="text" class="form-control" name="guarantor_phone[]" value="{{ old('guarantor_phone.0') }}" required>
                </x-form-field>
                <x-form-field label="Guarantor Address" col="md-4">
                    <input type="text" class="form-control" name="guarantor_address[]" value="{{ old('guarantor_address.0') }}">
                </x-form-field>
                <x-form-field label="Relationship" col="md-4">
                    <input type="text" class="form-control" name="guarantor_relationship[]" value="{{ old('guarantor_relationship.0') }}">
                </x-form-field>
                <x-form-field label="Document" col="md-4">
                    <x-form-file name="guarantor_document[]" accept="image/*,.pdf" />
                </x-form-field>
                <x-form-field label="Notes" col="md-12">
                    <textarea class="form-control" name="guarantor_notes[]" rows="3">{{ old('guarantor_notes.0') }}</textarea>
                </x-form-field>
            </div>
        </div>
        <div class="col-12 mb-2">
            <button type="button" id="addGuarantor" class="btn btn-modern btn-modern--ghost btn-modern--sm">
                <i class="fas fa-plus"></i> Add Guarantor
            </button>
        </div>
    </x-form-section>
</x-form-page>
@endsection

@push('script')
<script>
  (function () {
    function initCustomerGuarantorRows() {
    const addButton = document.getElementById('addGuarantor');
    const guarantorRows = document.getElementById('guarantorRows');
    if (!addButton || !guarantorRows || addButton.dataset.bound === 'true') {
      return;
    }

    addButton.dataset.bound = 'true';
    addButton.addEventListener('click', function () {
      const row = document.createElement('div');
      row.classList.add('row', 'guarantor-row', 'mt-2', 'pt-3', 'border-top');
      row.innerHTML = `
        <div class="form-field col-12 col-md-4">
          <label class="form-field__label">Guarantor Name</label>
          <div class="form-field__control"><input type="text" class="form-control" name="guarantor_name[]"></div>
        </div>
        <div class="form-field col-12 col-md-4">
          <label class="form-field__label">Guarantor CNIC</label>
          <div class="form-field__control"><input type="text" class="form-control" name="guarantor_cnic[]"></div>
        </div>
        <div class="form-field col-12 col-md-4">
          <label class="form-field__label">Guarantor Phone</label>
          <div class="form-field__control"><input type="text" class="form-control" name="guarantor_phone[]"></div>
        </div>
        <div class="form-field col-12 col-md-4">
          <label class="form-field__label">Guarantor Address</label>
          <div class="form-field__control"><input type="text" class="form-control" name="guarantor_address[]"></div>
        </div>
        <div class="form-field col-12 col-md-4">
          <label class="form-field__label">Relationship</label>
          <div class="form-field__control"><input type="text" class="form-control" name="guarantor_relationship[]"></div>
        </div>
        <div class="form-field col-12 col-md-4">
          <label class="form-field__label">Document</label>
          <div class="form-field__control">
            <div class="form-file" data-form-file>
              <div class="form-file__picker">
                <input type="file" name="guarantor_document[]" class="form-file__input" accept="image/*,.pdf">
                <label class="form-file__label">
                  <span class="form-file__icon" aria-hidden="true"><i class="fas fa-paperclip"></i></span>
                  <span class="form-file__text">Choose file</span>
                  <span class="form-file__name" data-file-name data-default-name="No file chosen">No file chosen</span>
                </label>
              </div>
            </div>
          </div>
        </div>
        <div class="form-field col-12 col-md-12">
          <label class="form-field__label">Notes</label>
          <div class="form-field__control"><textarea class="form-control" name="guarantor_notes[]" rows="3"></textarea></div>
        </div>
      `;
      guarantorRows.appendChild(row);
    });
    }

    window.initCustomerGuarantorRows = initCustomerGuarantorRows;

    if (window.__customerGuarantorRowsRegistered) {
      initCustomerGuarantorRows();
      return;
    }

    window.__customerGuarantorRowsRegistered = true;
    document.addEventListener('DOMContentLoaded', initCustomerGuarantorRows);
    document.addEventListener('app:page-loaded', initCustomerGuarantorRows);
  })();
</script>
@endpush
