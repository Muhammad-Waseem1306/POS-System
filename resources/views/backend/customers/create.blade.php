@extends('backend.master')

@section('title', 'Create Customer')

@section('content')
<x-form-page
    :action="route('backend.admin.customers.store')"
    :cancel-url="route('backend.admin.customers.index')"
    submit-label="Create Customer"
    enctype="multipart/form-data"
>
    <x-form-section title="Basic Information">
        <x-form-field label="Name" name="name" required>
            <input type="text" class="form-control" id="name" placeholder="Enter customer name" name="name"
                value="{{ old('name') }}" required>
        </x-form-field>
        <x-form-field label="Phone" name="phone" required>
            <input type="text" class="form-control" id="phone" placeholder="Enter phone number" name="phone"
                value="{{ old('phone') }}" required>
        </x-form-field>
        <x-form-field label="CNIC" name="cnic">
            <input type="text" class="form-control" id="cnic" placeholder="Enter CNIC" name="cnic"
                value="{{ old('cnic') }}">
        </x-form-field>
        <x-form-field label="Address" name="address">
            <input type="text" class="form-control" id="address" placeholder="Enter address" name="address"
                value="{{ old('address') }}">
        </x-form-field>
    </x-form-section>

    <x-form-section title="Verification Documents" description="Upload customer identity and address documents.">
        <x-form-field label="Customer Photo" col="md-3">
            <x-form-file name="photo" accept="image/*" />
        </x-form-field>
        <x-form-field label="CNIC Front" col="md-3">
            <x-form-file name="cnic_front" accept="image/*,.pdf" />
        </x-form-field>
        <x-form-field label="CNIC Back" col="md-3">
            <x-form-file name="cnic_back" accept="image/*,.pdf" />
        </x-form-field>
        <x-form-field label="Utility Bill" col="md-3">
            <x-form-file name="utility_bill" accept="image/*,.pdf" />
        </x-form-field>
    </x-form-section>

    <x-form-section title="Guarantor Details">
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
            <button type="button" id="addGuarantor" class="btn btn-modern btn-modern--secondary btn-modern--sm">
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
