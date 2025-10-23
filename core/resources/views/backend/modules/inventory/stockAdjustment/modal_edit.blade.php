<div class="modal-header py-16 px-24 border-0">
  <h5 class="modal-title">Edit Adjustment</h5>
  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body p-24">
  <form action="{{ route('inventory.adjustments.update', $ledger->id) }}" method="post" data-ajax="true">
    @csrf @method('PUT')

    <div class="mb-12">
      <div class="fw-semibold">{{ $ledger->product->name ?? 'Product' }}</div>
      <small class="text-muted">{{ $ledger->product->sku ?? '' }}</small><br>
      <small class="text-muted">Warehouse: {{ $ledger->warehouse->name ?? '—' }}</small>
    </div>

    <div class="row g-16">
      <div class="col-md-4">
        <label class="form-label text-sm mb-6">Qty (+/−)</label>
        @php
          $signed = $ledger->direction === 'IN' ? (float)$ledger->quantity : -(float)$ledger->quantity;
        @endphp
        <input type="number" step="0.001" name="qty" class="form-control text-end" value="{{ $signed }}" required>
        <div class="invalid-feedback d-block qty-error" style="display:none"></div>
      </div>
      <div class="col-md-4">
        <label class="form-label text-sm mb-6">Unit Cost</label>
        <input type="number" step="0.01" name="unit_cost" class="form-control text-end" value="{{ $ledger->unit_cost }}">
        <div class="invalid-feedback d-block unit_cost-error" style="display:none"></div>
      </div>
      <div class="col-md-12">
        <label class="form-label text-sm mb-6">Reason</label>
        <input type="text" name="note" class="form-control" value="{{ $ledger->note }}">
        <div class="invalid-feedback d-block note-error" style="display:none"></div>
      </div>
    </div>

    <div class="d-flex align-items-center justify-content-center gap-3 mt-16">
      <button type="button" class="btn border border-danger-600 text-danger-600 px-40 py-11 radius-8" data-bs-dismiss="modal">Cancel</button>
      <button class="btn btn-primary px-48 py-12 radius-8" type="submit">Update</button>
    </div>
  </form>
</div>
