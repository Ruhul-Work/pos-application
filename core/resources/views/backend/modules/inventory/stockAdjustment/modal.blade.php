<div class="modal-header py-16 px-24 border-0">
  <h5 class="modal-title">New Adjustment</h5>
  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body p-24">
  <form id="adjustForm" action="{{ route('inventory.adjustments.store') }}" method="post" data-ajax="true">
    @csrf

    <div class="row g-16">
      <div class="col-md-6">
        <label class="form-label text-sm mb-8">Warehouse <span class="text-danger">*</span></label>
        <select name="warehouse_id" class="form-control js-s2-ajax"
          data-url="{{ route('inventory.warehouses.select2') }}"
          data-placeholder="Select warehouse" required></select>
        <div class="invalid-feedback d-block warehouse_id-error" style="display:none"></div>
      </div>
      <div class="col-md-6">
        <label class="form-label text-sm mb-8">Global Reason</label>
        <input type="text" name="global_reason" class="form-control radius-8" placeholder="(optional)">
      </div>
    </div>

    <hr class="my-16">

    <div class="d-flex align-items-center justify-content-between mb-12">
      <h6 class="mb-0">Lines (use + for IN, − for OUT)</h6>
      <button type="button" class="btn btn-sm btn-primary" id="btnAddRow">
        + Add Row
      </button>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered align-middle mb-0" id="adjustLines">
        <thead>
          <tr class="bg-base-200">
            <th style="width:45%">Product</th>
            <th class="text-end" style="width:20%">Qty (+/-)</th>
            <th style="width:25%">Reason</th>
            <th style="width:10%"></th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>

    <div class="d-flex align-items-center justify-content-center gap-3 mt-16">
      <button type="button" class="btn border border-danger-600 text-danger-600 px-40 py-11 radius-8" data-bs-dismiss="modal">Cancel</button>
      <button type="submit" class="btn btn-primary px-48 py-12 radius-8">Save</button>
    </div>
  </form>
</div>

<template id="adjRowTpl">
  <tr>
    <td>
      <select class="form-control js-s2-ajax"
        name="rows[__IDX__][product_id]"
        {{-- data-url="{{ route('products.select2') }}?sellable=1" --}}
        data-placeholder="Select product" required></select>
      <div class="invalid-feedback d-block rows.__IDX__.product_id-error" style="display:none"></div>
    </td>
    <td>
      <input type="number" step="0.001" name="rows[__IDX__][qty]" class="form-control text-end" placeholder="+/- 0.000" required>
      <div class="invalid-feedback d-block rows.__IDX__.qty-error" style="display:none"></div>
    </td>
    <td>
      <input type="text" name="rows[__IDX__][reason]" class="form-control" placeholder="(optional)">
    </td>
    <td class="text-center">
      <button type="button" class="btn btn-sm btn-outline-danger btnDelRow"><iconify-icon icon="mdi:delete"></iconify-icon></button>
    </td>
  </tr>
</template>

<script>
(function(){
  const $form  = $('#adjustForm');
  const $modal = $form.closest('.modal');

  function addRow(){
    const $tb = $('#adjustLines tbody');
    const idx = $tb.find('tr').length;
    const html = $('#adjRowTpl').html().replaceAll('__IDX__', idx);
    const $row = $(html);
    $tb.append($row);
    window.S2 && S2.auto($row);
  }

  $('#btnAddRow').on('click', addRow);
  addRow();

  $(document).on('click', '.btnDelRow', function(){
    const $tb = $('#adjustLines tbody');
    if ($tb.find('tr').length <= 1) {
      $(this).closest('tr').find('input').val('');
      $(this).closest('tr').find('select').val(null).trigger('change');
    } else {
      $(this).closest('tr').remove();
    }
  });

  window.S2 && S2.auto($modal);

  // Global reason → empty line reason-এ প্রয়োগ (optional nice touch)
  $form.find('input[name="global_reason"]').on('change keyup', function(){
    const val = this.value;
    $('#adjustLines tbody input[name$="[reason]"]').each(function(){
      if (!this.value) this.value = val;
    });
  });
})();
</script>
