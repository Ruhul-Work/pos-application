<?php
namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\backend\Account;
use App\Models\backend\Branch;
use App\Models\backend\BranchAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BranchAccountController extends Controller
{
    public function index()
    {
        $branches = Branch::orderBy('name')->get();
        $accounts = Account::where('is_active', 1)
            ->with('type:id,name')
            ->orderBy('name')
            ->get();

        return view(
            'backend.modules.accountBranch.index',
            compact('branches', 'accounts')
        );
    }

    public function assignedAccounts($branchId)
    {
        $accountIds = BranchAccount::where('branch_id', $branchId)
            ->pluck('account_id');

        return response()->json([
            'account_ids' => $accountIds,
        ]);
    }

    public function assign(Request $request)
    {
        $request->validate([
            'branch_id'     => 'required|exists:branches,id',
            'account_ids'   => 'nullable|array',
            'account_ids.*' => 'exists:accounts,id',
        ]);

        DB::transaction(function () use ($request) {

            // 1️⃣ Clear previous assignment
            BranchAccount::where('branch_id', $request->branch_id)->delete();

            // 2️⃣ Insert new assignment (if any)
            if (! empty($request->account_ids)) {
                foreach ($request->account_ids as $accountId) {
                    BranchAccount::create([
                        'branch_id'  => $request->branch_id,
                        'account_id' => $accountId,
                    ]);
                }
            }
        });

        return redirect()
            ->back()
            ->with('success', 'Branch accounts updated successfully.');
    }

}
