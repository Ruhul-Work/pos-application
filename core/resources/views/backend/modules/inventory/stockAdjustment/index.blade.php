@extends('backend.layouts.master')
@section('meta')
    <title>Stock Adjustments</title>
@endsection

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-0">Stock Adjustments</h6>
            <p class="text-muted m-0">Increase/Decrease stock with reason</p>
        </div>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium"><a href="{{ route('backend.dashboard') }}"
                    class="d-flex align-items-center gap-1 hover-text-primary"><iconify-icon
                        icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon> Dashboard</a></li>
            <li>-</li>
            <li class="fw-medium">Adjustments</li>
        </ul>
    </div>

    <div class="card basic-data-table">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0">Datatables</h5>
            <div class="actions-bar d-flex align-items-center gap-2 flex-wrap">
                <div class="search-set me-2">
                    <div id="tableSearch" class="search-input"></div>
                </div>
                <ul class="table-top-head list-unstyled d-flex align-items-center gap-2 mb-0">
                    @include('backend.include.buttons')
                </ul>
                   <button class="d-flex btn btn-primary btn-sm px-12 py-8 radius-8" data-size="lg">
                        <iconify-icon icon="ic:baseline-plus" class="text-xl"></iconify-icon><a
                            href="{{ route('inventory.adjustments.create') }}">New Adjustment</a>
                    </button>
            </div>
        </div>

        <div class="card-body">
            <table class="table bordered-table table-scroll mb-0 AjaxDataTable" id="adjustTable" style="width:100%">
                <thead>
                    <tr>
                        <th style="width:50px">SL</th>
                        <th style="width:150px">Date</th>
                        <th>Warehouse</th>
                        <th>Product</th>
                        <th class="text-end">Qty (+/-)</th>
                        <th>By</th>
                        <th>Reason</th>
                        <th style="width:90px">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var DATATABLE_URL = "{{ route('inventory.adjustments.list') }}";
        // $(function(){
        //   const t = $('#adjustTable').DataTable({

        //   });
        //   $('#tableSearch input, #tableSearch').on('keyup change', function(){ t.search($(this).val()||'').draw(); });
        // });
        window.AdjustmentsIndex = {
            onSaved(res) {
                $('.AjaxDataTable').DataTable().ajax.reload(null, false);
                window.Swal && Swal.fire({
                    icon: 'success',
                    title: res?.msg || 'Saved',
                    timer: 1000,
                    showConfirmButton: false
                });
            }
        };
    </script>
@endsection
