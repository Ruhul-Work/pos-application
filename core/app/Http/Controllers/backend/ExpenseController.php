<?php
namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\backend\Expense;
use App\Models\backend\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function index()
    {
        return view('backend.modules.expenses.index');
    }

    public function listAjax(Request $request)
    {
        $columns   = ['id', 'name','expense_category_id', 'reference','description','amount', 'status'];
        $draw      = (int) $request->input('draw');
        $start     = (int) $request->input('start', 0);
        $length    = (int) $request->input('length', 10);
        $orderIdx  = (int) $request->input('order.0.column', 0);
        $orderDir  = strtolower($request->input('order.0.dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $searchVal = trim($request->input('search.value', ''));

        $base = Expense::query()
            ->with('expense_category:id,name')
            ->select(['id', 'name','expense_category_id', 'reference','description','amount', 'status']);

        $total = (clone $base)->count();

        if ($searchVal !== '') {
            $base->where(function ($q) use ($searchVal) {
                $q->where('name', 'like', "%{$searchVal}%")
                    ->orWhere('reference', 'like', "%{$searchVal}%");
            });
        }

        $filtered = (clone $base)->count();

        $orderCol = $columns[$orderIdx] ?? 'id';

        $rows = $base->orderBy($orderCol, $orderDir)
            ->skip($start)->take($length)->get();

        $data = [];
        foreach ($rows as $b) {
            $nameCol = '<strong>' . e($b->name) . '</strong>';

            $active = $b->status
                ? '<span class="badge text-sm fw-semibold bg-dark-success-gradient px-20 py-9 radius-4 text-white">Approved</span>'
                : '<span class="badge text-sm fw-semibold bg-dark-warning-gradient px-20 py-9 radius-4 text-white">Pending</span>';

            $actions = '<div class="d-inline-flex justify-content-end gap-1 w-100">
                <a href="#" class="w-32-px h-32-px rounded-circle d-inline-flex align-items-center justify-content-center
                    bg-success-focus text-success-main AjaxModal"
                    data-ajax-modal="' . route('expenses.editModal', $b->id) . '"
                    data-size="lg"
                    data-onload="CategoryIndex.onLoad"
                    data-onsuccess="CategoryIndex.onSaved"
                    title="Edit">
                    <iconify-icon icon="lucide:edit"></iconify-icon>
                </a>
                <a href="#" class="w-32-px h-32-px rounded-circle d-inline-flex align-items-center justify-content-center bg-danger-focus text-danger-main btn-branch-delete"
                    data-id="' . $b->id . '"
                    data-url="' . route('expenses.destroy', $b->id) . '"
                    title="Delete">
                    <iconify-icon icon="mdi:delete"></iconify-icon>
                </a>
            </div>';

            $typeName = $b->expense_category->name ?? '—';

            $data[] = [
                $b->id,
                $nameCol,
                $b->reference,
                e($typeName),
                $b->description??'N/A',
                $b->amount,
                $active,
                $actions,
            ];
        }

        return response()->json([
            'draw'                 => $draw,
            'iTotalRecords'        => $total,
            'iTotalDisplayRecords' => $filtered,
            'aaData'               => $data,
        ]);
    }

    public function createModal()
    {
        $expense_categories = ExpenseCategory::select('id', 'name')->where('is_active', 1)->get();
        return view('backend.modules.expenses.create_modal', compact('expense_categories')); // partial only
    }

    public function store(Request $req)
    {
        // dd($req->all());
        $data = $req->validate([
            'name'             => ['required', 'string', 'max:255'],
            'reference'  => ['required','string','max:255'],
            'expense_category_id' => ['required', 'integer'],
            'description' => ['nullable', 'string', 'max:150'],
            'amount'  => ['required','numeric'],
            'status'        => ['required', 'integer'],

        ]);


        $expense = Expense::create([
            'name'             => ucwords($data['name']),
            'reference'        => ucwords($data['reference']),
            'expense_category_id' => $data['expense_category_id'],
            'description' => $data['description'],
            'amount'    => $data['amount'],
            'status'        => $data['status'],

        ]);

        return response()->json(['ok' => true, 'msg' => 'Expense created', 'id' => $expense->id]);
    }

    public function editModal(Expense $expense)
    {

        $expense_categories = ExpenseCategory::select('id', 'name')->where('is_active', 1)->get();
        return view('backend.modules.expenses.edit_modal', compact('expense', 'expense_categories'));
    }

   
    public function update(Request $req, Expense $expense)
    {
         $data = $req->validate([
            'name'             => ['required', 'string', 'max:255'],
            'reference'  => ['required','string','max:255'],
            'expense_category_id' => ['required', 'integer'],
            'description' => ['nullable', 'string', 'max:150'],
            'amount'  => ['required','numeric'],
            'status'        => ['required', 'integer'],

        ]);



        $expense->name             = ucwords($data['name']);
        $expense->reference             = ucwords($data['reference']);
        $expense->expense_category_id = $data['expense_category_id'];
        $expense->amount       = $data['amount'];
        $expense->description = $data['description'] ?? null;
        $expense->status        = $data['status'];

        $expense->save();

        return response()->json(['ok' => true, 'msg' => 'Expense updated']);
    }

    public function destroy(Expense $expense)
    {
        // $inUse = DB::table('subcategories')->where('category_id', $category->id)->count();
        // if ($inUse > 0) {
        //     return response()->json([
        //         'ok'  => false,
        //         'msg' => "This category has {$inUse} subcategorie(s). Reassign them first.",
        //     ], 422);
        // }


        $expense->delete();

        return response()->json(['ok' => true, 'msg' => 'Expense deleted']);
    }

    public function select2(Request $r)
    {
        
        $q = trim($r->input('q', ''));
        $type = $r->type;
        $base = Expense::query()->where('category_type_id',$type)->where('is_active', 1);
      

        if ($q !== '') {
            $base->where(function($x) use ($q){
                $x->where('name','like',"%{$q}%")
                ;
            });
        }

        $items = $base->orderBy('id')->orderBy('name')
                      ->limit(20)->get(['id','name']);


        return response()->json([
            'results' => $items->map(fn($t)=>[
                'id'   => $t->id,
                'text' => $t->name 
            ])
        ]);
    }

}
