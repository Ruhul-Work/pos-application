@extends('backend.layouts.master')

@section('meta')
<title>Adjust Parent - {{ $parent->name }}</title>
@endsection

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-0">Stock Adjustment Edit </h6>
            <p class="text-muted m-0">All variants product → <strong>{{ $parent->name }}</strong></p>
        </div>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium"><a href="{{ route('backend.dashboard') }}"
                    class="d-flex align-items-center gap-1 hover-text-primary"><iconify-icon
                        icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon> Dashboard</a></li>
            <li>-</li>
            <li class="fw-medium"><a href="{{ route('inventory.adjustments.index') }}">Adjustments</a></li>
            <li>-</li>
            <li class="fw-medium">Edit</li>
        </ul>
    </div>
<form id="parentForm" action="{{ route('inventory.adjustments.parent.update',$parent->id) }}" method="post" data-ajax-form="true">
  @csrf
  @method('PUT')

  <div class="row g-16 mb-16">
    <div class="col-md-4">
      <label class="form-label text-sm mb-6">Warehouse <span class="text-danger">*</span></label>
      <select id="warehouse_id" name="warehouse_id" class="form-control js-s2-ajax"
        data-url="{{ route('inventory.warehouses.select2') }}" data-placeholder="Select warehouse" required></select>
      <div class="invalid-feedback d-block warehouse_id-error" style="display:none"></div>
    </div>
    <div class="col-md-4">
      <label class="form-label text-sm mb-6">Date & Time</label>
      <input type="datetime-local" name="when" id="when" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}">
    </div>
  </div>

  <div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
      <h6 class="mb-0">Product: {{ $parent->name }} <small class="text-muted">{{ $parent->sku }}</small></h6>
      <div class="d-flex gap-2">
        <input type="number" step="0.001" id="bulkQty" class="form-control form-control-sm w-110" placeholder="Qty (+/-)">
        <input type="number" step="0.01" id="bulkCost" class="form-control form-control-sm w-110" placeholder="Unit cost">
        <button type="button" id="btnApplyAll" class="btn btn-sm btn-outline-primary minw-120">Apply to all</button>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive table-scroll-wrap mb-8">
        <table class="table table-bordered align-middle" id="linesTable">
          <thead>
            <tr>
              <th>Variant</th>
              <th class="text-end">Current</th>
              <th class="text-end">Qty (+/−)</th>
              <th class="text-end">Unit Cost</th>
              <th>Reason</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <small class="text-muted">Qty পজিটিভ হলে IN, নেগেটিভ হলে OUT। Save করলে **overwrite** হবে।</small>
    </div>
    <div class="card-footer d-flex justify-content-center gap-2">
      <a href="{{ route('inventory.adjustments.index') }}" class="btn border border-danger-600 text-danger-600">Back</a>
      <button class="btn btn-primary" type="submit" id="btnSave" disabled>Save</button>
    </div>
  </div>
</form>
@endsection

@section('script')
<script>
(function(){
  const $doc   = $(document);
  const $lines = $('#linesTable tbody');
  const $form  = $('#parentForm');

  window.S2 && S2.auto($doc);

  function loadVariants() {
    const wid = $('#warehouse_id').val();
    if(!wid) return;
    $('#btnSave').prop('disabled', true);
    $lines.empty();

    $.getJSON("{{ route('inventory.adjustments.parent.variants') }}", {
      parent_id: {{ $parent->id }},
      warehouse_id: wid
    }, function(res){
      (res.variants || []).forEach((v, i) => addRow(i, v));
      $('#btnSave').prop('disabled', false);
    });
  }

  // function addRow(idx, v){
  //   const row = `<tr>
  //     <td>
  //       <div class="d-flex gap-10">
  //         <img src="${v.image || '{{ asset('images/placeholder.png') }}'}" style="width:36px;height:36px;object-fit:cover;border-radius:6px">
  //         <div>
  //           <div class="fw-semibold">${_.escape(v.name)}</div>
  //           <small class="text-muted">${_.escape(v.sku || '')}</small>
  //         </div>
  //       </div>
  //       <input type="hidden" name="rows[${idx}][product_id]" value="${v.product_id}">
  //       <input type="hidden" name="rows[${idx}][ledger_id]" value="${v.ledger_id || ''}">
  //     </td>
  //     <td class="text-end">${(v.current_qty ?? 0).toFixed(3)}</td>
  //     <td><input type="number" step="0.001" class="form-control text-end" name="rows[${idx}][qty_signed]" value="${v.qty_signed ?? 0}"></td>
  //     <td><input type="number" step="0.01" class="form-control text-end" name="rows[${idx}][unit_cost]" value="${v.unit_cost ?? ''}"></td>
  //     <td><input type="text" class="form-control" name="rows[${idx}][reason]" value="${_.escape(v.reason ?? '')}"></td>
  //   </tr>`;
  //   $lines.append(row);
  // }


  function addRow(idx, v) {
  // fallback / sanitize
  const img   = v.image || '{{ asset('images/placeholder.png') }}';
  const name  = (v.name  ?? '').toString();
  const sku   = (v.sku   ?? '').toString();
  const curr  = Number(v.current_qty ?? 0).toFixed(3);
  const qty   = (v.qty_signed ?? 0);
  const ucost = (v.unit_cost  ?? '');
  const note  = (v.reason     ?? '');

  // jQuery DOM build (XSS-safe: text() দিয়ে বসাচ্ছি)
  const $tr = $('<tr/>');

  const $col1 = $('<td/>');
  const $wrap = $('<div class="d-flex gap-10"/>');
  const $img  = $('<img/>', { src: img, css:{width:36,height:36,objectFit:'cover',borderRadius:'6px'} });
  const $txt  = $('<div/>');
  $('<div class="fw-semibold"/>').text(name).appendTo($txt);
  $('<small class="text-muted"/>').text(sku).appendTo($txt);
  $wrap.append($img, $txt);
  $col1.append($wrap);
  // hidden inputs
  $col1.append($('<input>', { type:'hidden', name:`rows[${idx}][product_id]`, value:v.product_id }));
  $col1.append($('<input>', { type:'hidden', name:`rows[${idx}][ledger_id]`,  value:(v.ledger_id || '') }));

  const $col2 = $('<td class="text-end"/>').text(curr);
  const $col3 = $('<td/>').append(
    $('<input>', { type:'number', step:'0.001', class:'form-control text-end', name:`rows[${idx}][qty_signed]`, value:qty })
  );
  const $col4 = $('<td/>').append(
    $('<input>', { type:'number', step:'0.01',  class:'form-control text-end', name:`rows[${idx}][unit_cost]`, value:ucost })
  );
  const $col5 = $('<td/>').append(
    $('<input>', { type:'text', class:'form-control', name:`rows[${idx}][reason]`, value:note })
  );

  $tr.append($col1, $col2, $col3, $col4, $col5);
  $lines.append($tr);
}

  // Warehouse নির্বাচন করলে variants লোড
  $('#warehouse_id').on('change', loadVariants);

  // Apply to all
  $('#btnApplyAll').on('click', function(){
    const q = $('#bulkQty').val();
    const c = $('#bulkCost').val();
    if (q !== '') $('#linesTable tbody input[name$="[qty_signed]"]').val(parseFloat(q).toFixed(3));
    if (c !== '') $('#linesTable tbody input[name$="[unit_cost]"]').val(parseFloat(c).toFixed(2));
  });

  // submit ajax
  $form.on('ajax:success', function(_e, res){
    window.Swal && Swal.fire({icon:'success', title: res?.msg || 'Saved', timer:1200, showConfirmButton:false});
  });

})();
</script>
@endsection
