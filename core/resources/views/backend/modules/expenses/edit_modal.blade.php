<div class="modal-header py-16 px-24 border-0" data-modal-key="branch-create">
  <h5 class="modal-title">Edit Expense</h5>
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body p-24" id="categoryCreateModal">
  <form id="categoryCreateForm" action="{{ route('expenses.update',$expense->id) }}" method="post" data-ajax="true" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- Row 1: Name --}}
    <div class="row">
      <div class="col-md-6 mb-20">
        <label class="form-label text-sm mb-8">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control radius-8" required id="nameInput" value="{{$expense->name}}">
        <div class="invalid-feedback d-block name-error" style="display:none"></div>
      </div>

      <div class="col-md-6 mb-20">
        <label class="form-label text-sm mb-8">Expense Type</label>
        <select name="expense_category_id" id="categoryTypeSelect" class="form-control js-category-type-select">
            <option value="{{$expense->expense_category_id}}">{{$expense->expense_category->name}}</option>
        </select>
        <div class="invalid-feedback d-block category_type_id-error" style="display:none"></div>
      </div>
    </div>

    {{-- Row 2: Amount + Reference --}}
    <div class="row">
      <div class="col-md-6 mb-16">
        <label class="form-label text-sm mb-8">Amount</label>
        <input type="number" name="amount" min="0" class="form-control radius-8" value="{{$expense->amount}}">
        <div class="invalid-feedback d-block amount-error" style="display:none"></div>
      </div>

      <div class="col-md-6 mb-20">
        <label class="form-label text-sm mb-8">Reference</label>
        <input type="text" name="reference" class="form-control radius-8" value="{{$expense->reference}}">
        <div class="invalid-feedback d-block reference-error" style="display:none"></div>
      </div>
    </div>

    {{-- Row 3: Description + Status --}}
    <div class="row">
      <div class="col-md-6 mb-20">
        <label class="form-label text-sm mb-8">Description</label>
        <textarea name="description" rows="5" class="form-control radius-8" placeholder="">{{$expense->description}}</textarea>
        <div class="invalid-feedback d-block description-error" style="display:none"></div>
      </div>

      <div class="col-md-6 mb-20">
        <label class="form-label text-sm mb-8">Status</label>
        <select class="form-control" name="status" id="">
          <option value="1" {{$expense->status?'selected':''}}>Approved</option>
          <option value="0"  {{$expense->status?'':'selected'}}>Pending</option>
        </select>
        <div class="invalid-feedback d-block status-error" style="display:none"></div>
      </div>
    </div>

    {{-- ✅ Buttons inside form --}}
    <div class="d-flex align-items-center justify-content-center gap-3 mt-16">
      <button type="button" class="btn border border-danger-600 text-danger-600 px-40 py-11 radius-8" data-bs-dismiss="modal">Cancel</button>
      <button type="submit" class="btn btn-primary px-48 py-12 radius-8">Save</button>
    </div>
  </form>
</div>

{{-- ✅ Script section --}}
<script>
  function initCategorySelect($el, $modal) {
    if ($el.hasClass('select2-hidden-accessible')) $el.select2('destroy');

    $el.select2({
      dropdownParent: $modal,
      placeholder: 'Select expense type',
      allowClear: true,
      width: '100%',
      ajax: {
        url: "{{ route('expenseCategories.select2') }}",
        dataType: 'json',
        delay: 200,
        data: params => ({ q: params.term || '' }),
        processResults: data => data
      }
    });
  }

  (function () {
    const $form = $('#categoryCreateForm');
    const $modal = $form.closest('.modal');
    const $sel = $form.find('#categoryTypeSelect');

    // init select2
    initCategorySelect($sel, $modal);

    // reset select if no preselected value
    const hasPreselected = !!$sel.find('option[selected]').length || !!$sel.val();
    if (!hasPreselected) {
      $sel.val(null).trigger('change');
    }

    // clear error messages
    $form.find('.name-error,.slug-error,.category_type_id-error').hide().text('');
  })();
</script>
