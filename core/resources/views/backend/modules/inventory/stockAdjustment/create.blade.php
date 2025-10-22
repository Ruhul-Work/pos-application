@extends('backend.layouts.master')

@section('meta')
    <title>Advanced Stock Adjustment</title>
@endsection

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-0">Stock Adjustment </h6>
            <p class="text-muted m-0">Pick parents on left → edit variants on right</p>
        </div>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium"><a href="{{ route('backend.dashboard') }}"
                    class="d-flex align-items-center gap-1 hover-text-primary"><iconify-icon
                        icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon> Dashboard</a></li>
            <li>-</li>
            <li class="fw-medium"><a href="{{ route('inventory.adjustments.index') }}">Adjustments</a></li>
            <li>-</li>
            <li class="fw-medium">Create</li>
        </ul>
    </div>

    <form id="adjForm" action="{{ route('inventory.adjustments.store') }}" method="post" data-ajax="true">
        @csrf

        {{-- top controls --}}
        <div class="row g-16 mb-16">
            <div class="col-md-4">
                <label class="form-label text-sm mb-6">Warehouse <span class="text-danger">*</span></label>
                <select name="warehouse_id" class="form-control js-s2-ajax"
                    data-url="{{ route('inventory.warehouses.select2') }}" data-placeholder="Select warehouse"
                    required></select>
                <div class="invalid-feedback d-block warehouse_id-error" style="display:none"></div>
            </div>
            <div class="col-md-4">
                <label class="form-label text-sm mb-6">Date & Time</label>
                <input type="datetime-local" name="when" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}">
                <div class="invalid-feedback d-block when-error" style="display:none"></div>
            </div>
            <div class="col-md-4">
                <label class="form-label text-sm mb-6">Global Reason</label>
                <input type="text" name="global_reason" class="form-control" placeholder="(optional)">
            </div>
        </div>

        <div class="row">
            {{-- LEFT: Parents gallery --}}
            <div class="col-lg-5">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="w-100 d-flex gap-2">
                            <select id="parentFilter" class="form-control js-s2-ajax"
                                data-url="{{ route('product.parents.select2') }}"
                                data-placeholder="Search parents (name, sku, category)"></select>
                            <button type="button" id="btnClearParents" class="btn btn-light">Clear</button>
                        </div>
                    </div>
                    <div class="card-body p-12">
                        <div id="parentsGrid" class="grid grid-cols-2 gap-12"></div>
                        <div class="d-flex justify-content-center mt-12">
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="btnLoadMore">Load
                                more</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Variants editor --}}
            <div class="col-lg-7">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">Selected Variants</h6>
                        <div class="d-flex gap-2 align-items-center">
                            <input type="number" step="0.01" id="bulkCost" class="form-control form-control-sm w-110"
                                placeholder="Unit cost">
                            <input type="number" step="0.001" id="bulkQty" class="form-control form-control-sm w-110"
                                placeholder="Qty (+/−)">
                            <button type="button" class="btn btn-sm btn-outline-primary minw-120" id="btnApplyAll">Apply to
                                all</button>
                            <button type="button" class="btn btn-sm btn-outline-danger minw-100"
                                id="btnClearLines">Clear</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive table-scroll-wrap mb-8">
                            <table class="table table-bordered align-middle table-fixed-rows" id="linesTable">
                                <colgroup>
                                    <col style="width:36%;">
                                    <col style="width:18%;">
                                    <col style="width:18%;">
                                    <col style="width:20%;">
                                    <col style="width:8%;">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>Variant</th>
                                        <th class="text-center">Qty (+/−)</th>
                                        <th class="text-center">Unit Cost</th>
                                        <th>Reason</th>
                                        <th>action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <small class="text-muted">Tip: Qty পজিটিভ হলে IN, নেগেটিভ হলে OUT হিসেবে যাবে।</small>
                    </div>
                    <div class="card-footer d-flex justify-content-center gap-2">
                        <a href="{{ route('inventory.adjustments.index') }}"
                            class="btn border border-danger-600 text-danger-600">Cancel</a>
                        <button class="btn btn-primary" type="submit">Save Adjustment</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Hidden inputs (rows) dynamically append হবে --}}
    </form>

    {{-- Templates --}}
    <template id="parentCardTpl">
        <div class="wh-card card hover-shadow cursor-pointer" data-id="__ID__">
            <div class="card-body p-10">
                <div class="d-flex gap-10">
                    <img class="rounded" src="__IMG__" alt="" style="width:54px;height:54px;object-fit:cover;">
                    <div class="flex-1">
                        <div class="fw-semibold lh-sm mb-2">__NAME__</div>
                        <div class="text-muted small">__SKU__</div>
                    </div>
                </div>
                <div class="mt-8 d-flex gap-6 flex-wrap">
                    <!-- optional badges -->
                </div>
            </div>
        </div>
    </template>

    <template id="variantRowTpl">
        <tr data-vid="__VID__" data-parent="__PID__">
            <td>
                <div class="d-flex gap-10">
                    <img src="__IMG__" style="width:36px;height:36px;object-fit:cover;border-radius:6px">
                    <div>
                        <div class="fw-semibold">__VNAME__</div>
                        <small class="text-muted">__VSKU__</small>
                    </div>
                </div>
                <input type="hidden" name="rows[__IDX__][product_id]" value="__VID__">
            </td>
            <td><input type="number" step="0.001" class="form-control text-end" name="rows[__IDX__][qty]"
                    placeholder="+/- 0.000" required></td>
            <td><input type="number" step="0.01" class="form-control text-end" name="rows[__IDX__][unit_cost]"
                    placeholder="0.00"></td>
            <td><input type="text" class="form-control" name="rows[__IDX__][reason]" placeholder="(optional)"></td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger btnDel"><iconify-icon
                        icon="mdi:delete"></iconify-icon></button>
            </td>
        </tr>
    </template>

    {{-- minimal styles for grid/scroll --}}
    <style>
        .grid {
            display: grid;
        }

        .grid-cols-2 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .gap-12 {
            gap: 12px;
        }

        .table-scroll-wrap {
            max-height: 280px;
            overflow: auto;
        }

        .table-fixed-rows {
            width: 100%;
            table-layout: fixed;
            /* border-collapse: separate;   */
            border-spacing: 0;
        }

        .table-fixed-rows thead th {
            position: sticky;
            top: 0;
            z-index: 2;
            background: var(--bs-body-bg, #fff);
        }


        .table-fixed-rows th.col-action,
        .table-fixed-rows td.col-action {
            width: 90px;
            white-space: nowrap;
        }


        .wh-card.active {
            outline: 2px solid var(--bs-primary, #0d6efd);
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .w-110 {
            width: 126px;
        }

        .minw-120 {
            min-width: 120px;
        }

        .minw-100 {
            min-width: 100px;
        }
    </style>
@endsection

@section('script')
    <script>
        (function() {
            const $doc = $(document);
            const $grid = $('#parentsGrid');
            const $lines = $('#linesTable tbody');
            const $form = $('#adjForm');

            window.S2 && S2.auto($doc);

            // Selected parents + inserted variants tracking
            const selectedParents = new Set();
            const addedVariants = new Set(); // vid set

            // paging state for parents gallery
            let page = 1,
                lastQuery = '';

            function cardHTML(p) {
                let html = $('#parentCardTpl').html()
                    .replaceAll('__ID__', p.id)
                    .replaceAll('__IMG__', p.image || '{{ asset('images/placeholder.png') }}')
                    .replaceAll('__NAME__', p.name)
                    .replaceAll('__SKU__', p.sku || '');
                return $(html);
            }

            function loadParents(reset = false) {
                const $sel = $('#parentFilter');
                const q = ($sel.val() && $sel.find(':selected').text()) || lastQuery || '';
                if (reset) {
                    page = 1;
                    $grid.empty();
                    selectedParents.clear();
                }
                lastQuery = q;

                $.getJSON("{{ route('product.parents.index') }}", {
                    q,
                    page
                }, function(res) {
                    const items = res.data || res.results || [];
                    items.forEach(p => $grid.append(cardHTML(p)));
                    // simple next check
                    if (!res.next_page) {
                        $('#btnLoadMore').prop('disabled', true).text('No more');
                    } else {
                        $('#btnLoadMore').prop('disabled', false).text('Load more');
                        page++;
                    }
                });
            }

            // first load
            loadParents(true);

            $('#btnLoadMore').on('click', () => loadParents(false));
            $('#btnClearParents').on('click', function() {
                selectedParents.clear();
                $grid.find('.wh-card').removeClass('active');
                // lines clear শুধু ওই parent গুলোর—এখানে অল ক্লিয়ার করলাম চাইলে পারেন্ট-ওয়াইজ করবেন
                clearAllLines();
            });

            // Parent select/deselect
            $grid.on('click', '.wh-card', function() {
                const $c = $(this);
                const id = String($c.data('id'));
                const active = $c.toggleClass('active').hasClass('active');
                if (active) {
                    selectedParents.add(id);
                    fetchVariants(id);
                } else {
                    selectedParents.delete(id);
                    removeVariantsOfParent(id);
                }
            });

            function removeVariantsOfParent(pid) {
                $lines.find('tr[data-parent="' + pid + '"]').each(function() {
                    const vid = String($(this).data('vid'));
                    addedVariants.delete(vid);
                    $(this).remove();
                });
                renumberRows();
            }

            function clearAllLines() {
                addedVariants.clear();
                $lines.empty();
            }

            function fetchVariants(parentId) {
                $.getJSON("{{ url('/product/products') }}/" + parentId + "/variants", function(res) {
                    const list = res.data || res.variants || res || [];
                    list.forEach(v => addVariantRow(parentId, v));
                });
            }

            function addVariantRow(pid, v) {
                const vid = String(v.id);
                if (addedVariants.has(vid)) return;
                const idx = $lines.find('tr').length;

                let html = $('#variantRowTpl').html()
                    .replaceAll('__VID__', vid)
                    .replaceAll('__PID__', pid)
                    .replaceAll('__VNAME__', v.name)
                    .replaceAll('__VSKU__', v.sku || '')
                    .replaceAll('__IMG__', v.image || '{{ asset('images/placeholder.png') }}')
                    .replaceAll('__IDX__', idx);

                const $row = $(html);
                if (v.default_unit_cost) $row.find('input[name$="[unit_cost]"]').val(v.default_unit_cost);

                $lines.append($row);
                addedVariants.add(vid);
            }

            // delete single line
            $lines.on('click', '.btnDel', function() {
                const $tr = $(this).closest('tr');
                const vid = String($tr.data('vid'));
                addedVariants.delete(vid);
                $tr.remove();
                renumberRows();
            });

            // IDs standardize করি: bulkCost, bulkQty
            $('#btnApplyAll').on('click', function() {
                const costRaw = $('#bulkCost').val();
                const qtyRaw = $('#bulkQty').val();

                const hasCost = costRaw !== '' && !isNaN(parseFloat(costRaw));
                const hasQty = qtyRaw !== '' && !isNaN(parseFloat(qtyRaw));

                if (!hasCost && !hasQty) {
                    window.Swal && Swal.fire({
                        icon: 'info',
                        title: 'Nothing to apply without Unit or Quantity',
                        showConfirmButton: true
                    });
                    return;
                }

                if (hasCost) {
                    const cost = parseFloat(costRaw);
                    $('#linesTable tbody input[name$="[unit_cost]"]').each(function() {
                        this.value = cost.toFixed(2);
                    });
                }

                if (hasQty) {
                    // qty পজিটিভ/নেগেটিভ—যা দিবে তাই বসবে
                    const qty = parseFloat(qtyRaw);
                    $('#linesTable tbody input[name$="[qty]"]').each(function() {
                        this.value = qty.toFixed(3);
                    });
                }

            });
            $('#btnClearLines').on('click', clearAllLines);

            // global reason→ empty line reasons
            $form.find('input[name="global_reason"]').on('input', function() {
                const val = this.value;
                $lines.find('input[name$="[reason]"]').each(function() {
                    if (!this.value) this.value = val;
                });
            });

            function renumberRows() {
                $lines.find('tr').each(function(i) {
                    $(this).find('input').each(function() {
                        this.name = this.name
                            .replace(/rows\[\d+\]\[product_id\]/, 'rows[' + i + '][product_id]')
                            .replace(/rows\[\d+\]\[qty\]/, 'rows[' + i + '][qty]')
                            .replace(/rows\[\d+\]\[unit_cost\]/, 'rows[' + i + '][unit_cost]')
                            .replace(/rows\[\d+\]\[reason\]/, 'rows[' + i + '][reason]');
                    });
                });
            }

            // init select2 globally
            window.S2 && S2.auto($(document));

            // Optional: parentFilter change করলে reload
            $('#parentFilter').on('select2:select', () => loadParents(true));

            // ajax success → toast + redirect
            $form.on('ajax:success', function(_e, res) {
                window.Swal && Swal.fire({
                    icon: 'success',
                    title: res?.msg || 'Saved',
                    timer: 1000,
                    showConfirmButton: false
                });
                setTimeout(() => window.location = "{{ route('inventory.adjustments.index') }}", 800);
            });
        })();
    </script>
@endsection
