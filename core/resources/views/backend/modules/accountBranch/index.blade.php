@extends('backend.layouts.master')

@section('meta')
<title>Branch Account Assignment</title>
@endsection

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <div>
        <h6 class="fw-semibold mb-0">Branch Accounts</h6>
        <p class="text-muted m-0">Assign accounts to branches</p>
    </div>

    <ul class="d-flex align-items-center gap-2">
        <li class="fw-medium">
            <a href="{{ route('backend.dashboard') }}" class="hover-text-primary d-flex align-items-center gap-1">
                <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                Dashboard
            </a>
        </li>
        <li>-</li>
        <li class="fw-medium">Branch Accounts</li>
    </ul>
</div>

<div class="card">
    <div class="card-body">

        <form method="POST" action="{{ route('branch-accounts.assign') }}">
            @csrf

            {{-- Branch Select --}}
            <div class="mb-4 col-md-4">
                <label class="form-label fw-semibold">Select Branch</label>
                <select name="branch_id" id="branchSelect" class="form-control" required>
                    <option value="">-- Select Branch --</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Accounts List --}}
            <div class="mb-4">
                <label class="form-label fw-semibold mb-3">Accounts</label>

                <div class="row">
                    @foreach($accounts as $account)
                        <div class="col-md-4 mb-2">
                            <div class="">
                                <input class="form-check-input text-lg account-checkbox"
                                       type="checkbox"
                                       name="account_ids[]"
                                       value="{{ $account->id }}"
                                       id="acc{{ $account->id }}">
                                <label class="form-check-label" for="acc{{ $account->id }}">
                                    {{ $account->name }}
                                    <span class="text-muted">({{ $account->type?->name }})</span>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Action --}}
            <div class="text-end">
                <button class="btn btn-success">
                    Save Assignment
                </button>
            </div>

        </form>

    </div>
</div>
@endsection

@section('script')
<script>
    const loadAssignedAccountsUrl = "{{ route('branch-accounts.assigned', ':id') }}";

    $('#branchSelect').on('change', function () {

        let branchId = $(this).val();

        // reset all
        $('.account-checkbox').prop('checked', false);

        if (!branchId) return;

        let url = loadAssignedAccountsUrl.replace(':id', branchId);

        $.get(url, function (res) {
            if (res.account_ids) {
                res.account_ids.forEach(function (id) {
                    $('#acc' + id).prop('checked', true);
                });
            }
        });
    });

    @if(session('success'))

    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: "{{ session('success') }}",
        timer: 2000,
        showConfirmButton: false
    });
    @endif

</script>
@endsection

