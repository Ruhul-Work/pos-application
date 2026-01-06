<?php
namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\backend\Account;
use App\Models\backend\Branch;
use App\Models\backend\FiscalYear;
use App\Models\backend\OpeningBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OpeningBalanceController extends Controller
{
    public function index()
    {
        // UI only for now
        $branches    = Branch::orderBy('name')->get();
        $fiscalYears = FiscalYear::orderBy('start_date', 'desc')->get();

        return view(
            'backend.modules.accountOpenBalance.index', compact('branches', 'fiscalYears')
        );
    }

    // public function save(Request $request)
    // {
    //     $request->validate([
    //         'branch_id'      => 'required|exists:branches,id',
    //         'fiscal_year_id' => 'required|exists:fiscal_years,id',
    //         'balances'       => 'nullable|array',
    //         'balances.*'     => 'nullable|numeric',
    //     ]);

    //     DB::transaction(function () use ($request) {

    //         $branchId = $request->branch_id;
    //         $fyId     = $request->fiscal_year_id;
    //         $balances = $request->balances ?? [];

    //         foreach ($balances as $accountId => $amount) {

    //             // 🧠 Rule:
    //             // amount = null or 0 → delete (keep table clean)
    //             // amount ≠ 0 → updateOrCreate

    //             if ($amount === null || (float) $amount == 0) {
    //                 OpeningBalance::where([
    //                     'branch_id'      => $branchId,
    //                     'fiscal_year_id' => $fyId,
    //                     'account_id'     => $accountId,
    //                 ])->delete();
    //             } else {
    //                 OpeningBalance::updateOrCreate(
    //                     [
    //                         'branch_id'      => $branchId,
    //                         'fiscal_year_id' => $fyId,
    //                         'account_id'     => $accountId,
    //                     ],
    //                     [
    //                         'amount' => $amount,
    //                     ]
    //                 );
    //             }
    //         }
    //     });

    //     return redirect()
    //         ->back()
    //         ->with('success', 'Opening balances saved successfully.');
    // }

    public function save(Request $request)
    {
        $request->validate([
            'branch_id'      => 'required|exists:branches,id',
            'fiscal_year_id' => 'required|exists:fiscal_years,id',
            'balances'       => 'nullable|array',
            'balances.*'     => 'nullable|numeric',
        ]);

        // ✅ HARD GUARD (helper use)
        $currentFY = requireFiscalYear();

        // 🔒 extra safety: user-selected FY ≠ active FY → block
        if ((int) $request->fiscal_year_id !== (int) $currentFY->id) {
            return redirect()->back()
                ->withErrors('Selected fiscal year is not the active fiscal year.');
        }

        DB::transaction(function () use ($request) {

            $branchId = $request->branch_id;
            $fyId     = $request->fiscal_year_id;
            $balances = $request->balances ?? [];

            foreach ($balances as $accountId => $amount) {

                if ($amount === null || (float) $amount == 0) {
                    OpeningBalance::where([
                        'branch_id'      => $branchId,
                        'fiscal_year_id' => $fyId,
                        'account_id'     => $accountId,
                    ])->delete();
                } else {
                    OpeningBalance::updateOrCreate(
                        [
                            'branch_id'      => $branchId,
                            'fiscal_year_id' => $fyId,
                            'account_id'     => $accountId,
                        ],
                        [
                            'amount' => $amount,
                        ]
                    );
                }
            }
        });

        return redirect()
            ->back()
            ->with('success', 'Opening balances saved successfully.');
    }

    public function loadAccounts($branchId, $fiscalYearId)
    {
        // only accounts assigned to branch
        $accounts = Account::where('is_active', 1)
            ->whereHas('branchAccounts', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->with(['openingBalances' => function ($q) use ($branchId, $fiscalYearId) {
                $q->where('branch_id', $branchId)
                    ->where('fiscal_year_id', $fiscalYearId);
            }])
            ->orderBy('name')
            ->get();

        $data = $accounts->map(function ($acc) {
            return [
                'id'     => $acc->id,
                'name'   => $acc->name,
                'amount' => $acc->openingBalances->first()->amount ?? 0,
            ];
        });

        return response()->json($data);
    }

}
