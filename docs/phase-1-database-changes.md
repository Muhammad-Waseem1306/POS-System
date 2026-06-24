# Phase 1 Database Changes

This phase adds the database foundation for electronics installment financing while keeping the existing cash POS flow backward compatible.

## Existing Tables Extended

- `customers`
  - Added `cnic` for customer identity verification.
- `orders`
  - Added `sale_type`, defaulting to `cash`, so existing sales continue to behave as cash sales.
- `order_transactions`
  - Added `paid_at`, `reference_number`, and `notes` for collection metadata.
  - Existing `paid_by` remains the payment method field.
- `products`
  - Added `model` and `warranty_period_months`.

## New Tables

- `customer_guarantors`
  - Stores one or more guarantors per customer.
  - Includes name, CNIC, phone, address, relationship, and notes.
- `customer_documents`
  - Stores customer verification files and guarantor documents.
  - Supports document types such as customer photo, CNIC front, CNIC back, utility bill, and guarantor document.
- `installment_plans`
  - Stores one installment plan per installment sale.
  - Includes sale, customer, cash price, installment price, total amount, down payment, financed amount, months, monthly installment, start/end dates, and status.
- `installment_schedules`
  - Stores each monthly installment row.
  - Includes due date, amount, paid amount, remaining amount, and status.
- `installment_payment_allocations`
  - Links payment transactions to one or more installment schedule rows.
  - This allows automatic oldest-installment allocation and partial payments in later phases.
- `product_serials`
  - Tracks individual electronic item serial numbers and warranty dates.
  - Links serials to purchase items and sold order product rows.

## Compatibility Notes

- Existing cash sales are not migrated into installment records.
- Existing reports can keep using `orders` and `order_transactions`.
- Installment-specific reports should use `installment_plans`, `installment_schedules`, and `installment_payment_allocations`.
- Product brand support already existed; Phase 1 adds model, warranty period, and per-unit serial tracking.

## Existing Migration Correction

- Fixed the suppliers migration rollback to drop `suppliers` instead of `customers`.
