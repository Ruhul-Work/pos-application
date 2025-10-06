<div class="modal-header">
  <h5 class="modal-title">{{ $title }}</h5>
  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<form action="{{ $action }}" method="post" class="ajax-submit-form" data-ajax="true" data-onsuccess="UnitsIndex.onSaved">
  @csrf
  @if($method === 'PUT') @method('PUT') @endif
  <div class="modal-body">
    <div class="mb-3">
      <label class="form-label">Name</label>
      <input name="name" class="form-control" required maxlength="255" value="{{ $unit->name ?? '' }}">
    </div>
    <div class="mb-3">
      <label class="form-label">Code</label>
      <input name="code" class="form-control" required maxlength="16" value="{{ $unit->code ?? '' }}">
    </div>
    <div class="mb-3">
      <label class="form-label">Precision (0-6)</label>
      <input name="precision" type="number" min="0" max="6" value="{{ $unit->precision ?? 0 }}" class="form-control" required>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
        {{ ($unit->is_active ?? true) ? 'checked' : '' }}>
      <label class="form-check-label" for="is_active">Active</label>
    </div>
  </div>
  <div class="modal-footer">
    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
    <button class="btn btn-primary" type="submit">Save</button>
  </div>
</form>
