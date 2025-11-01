<?php
namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\backend\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        return view('backend.modules.expense_categories.index');
    }

    public function listAjax(Request $request)
    {
        $columns   = ['id', 'name', 'slug', 'image', 'is_active'];
        $draw      = (int) $request->input('draw');
        $start     = (int) $request->input('start', 0);
        $length    = (int) $request->input('length', 10);
        $orderIdx  = (int) $request->input('order.0.column', 0);
        $orderDir  = strtolower($request->input('order.0.dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $searchVal = trim($request->input('search.value', ''));

        $base = ExpenseCategory::query()
            ->select(['id', 'name', 'slug', 'image', 'is_active']);

        $total = (clone $base)->count();

        if ($searchVal !== '') {
            $base->where(function ($q) use ($searchVal) {
                $q->where('name', 'like', "%{$searchVal}%")
                    ->orWhere('slug', 'like', "%{$searchVal}%");
            });
        }

        $filtered = (clone $base)->count();

        $orderCol = $columns[$orderIdx] ?? 'id';

        $rows = $base->orderBy($orderCol, $orderDir)
            ->skip($start)->take($length)->get();

        $data = [];
        foreach ($rows as $b) {
            $nameCol = '<strong>' . e($b->name) . '</strong>';

            $active = $b->is_active
                ? '<span class="badge text-sm fw-semibold bg-dark-success-gradient px-20 py-9 radius-4 text-white">Active</span>'
                : '<span class="badge text-sm fw-semibold bg-dark-warning-gradient px-20 py-9 radius-4 text-white">Inactive</span>';

            $actions = '<div class="d-inline-flex justify-content-end gap-1 w-100">
                <a href="#" class="w-32-px h-32-px rounded-circle d-inline-flex align-items-center justify-content-center
                    bg-success-focus text-success-main AjaxModal"
                    data-ajax-modal="' . route('expenseCategories.edit', $b->id) . '"
                    data-size="lg"
                    data-onload="PaymentTypeIndex.onLoad"
                    data-onsuccess="PaymentTypeIndex.onSaved"
                    title="Edit">
                    <iconify-icon icon="lucide:edit"></iconify-icon>
                </a>
                <a href="#" class="w-32-px h-32-px rounded-circle d-inline-flex align-items-center justify-content-center bg-danger-focus text-danger-main btn-branch-delete"
                    data-id="' . $b->id . '"
                    data-url="' . route('expenseCategories.destroy', $b->id) . '"
                    title="Delete">
                    <iconify-icon icon="mdi:delete"></iconify-icon>
                </a>
            </div>';

            $icon = '<div  style="width:70px"><img src="' . image($b->image) . '" alt="img"></div>';

          
            $data[] = [
                $b->id,
                $nameCol,
                e($b->slug),
                $icon,
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
       
        return view('backend.modules.expense_categories.create_modal'); // partial only
    }

    public function store(Request $req)
    {
        // dd($req->all());
        $data = $req->validate([
            'name'             => ['required', 'string', 'max:150', 'unique:expense_categories,name'],
            'slug'             => ['required', 'string', 'max:50', 'unique:expense_categories,slug'],
            'image'             => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active'        => ['required', 'integer'],

        ]);

        $iconPath      = uploadImage($req->file('image'), 'ExpenseCategory/icons');

        $expenseCategory = ExpenseCategory::create([
            'name'             => ucwords($data['name']),
            'slug'             => $data['slug'],
            'image'             => $iconPath,
            'is_active'        => $data['is_active'] ?? null,

        ]);

        return response()->json(['ok' => true, 'msg' => 'Expense created', 'id' => $expenseCategory->id]);
    }

    public function editModal(ExpenseCategory $expenseCategory)
    {

      
        return view('backend.modules.expense_categories.edit_modal', compact('expenseCategory'));
    }

    
   
    public function update(Request $req, ExpenseCategory $expenseCategory)
    {
        $data = $req->validate([
            'name'             => ['required', 'string', 'max:150'],
            'slug'             => ['required', 'string', 'max:50'],
            'image'             => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active'        => ['required', 'integer'],
        ]);

        $previousIcon      = $expenseCategory->image;

        if ($req->hasFile('image')) {
            $iconPath       = uploadImage($req->file('image'), 'PaymentType/icons');
            $expenseCategory->image = $iconPath;

            if ($previousIcon && file_exists($previousIcon)) {
                unlink($previousIcon);
            }
        }


        $expenseCategory->name             = ucwords($data['name']);
        $expenseCategory->slug             = $data['slug'];
        $expenseCategory->is_active        = $data['is_active'];

        $expenseCategory->save();

        return response()->json(['ok' => true, 'msg' => 'PaymentType updated']);
    }

    public function destroy(ExpenseCategory $expenseCategory)
    {
        // $inUse = DB::table('subpaymentTypes')->where('PaymentType_id', $PaymentType->id)->count();
        // if ($inUse > 0) {
        //     return response()->json([
        //         'ok'  => false,
        //         'msg' => "This PaymentType has {$inUse} subcategorie(s). Reassign them first.",
        //     ], 422);
        // }

        $iconPath      = $expenseCategory->image;

        $expenseCategory->delete();

        if (isset($iconPath) && file_exists($iconPath)) {
            unlink($iconPath);
        }
      

        return response()->json(['ok' => true, 'msg' => 'Expense category deleted']);
    }

    public function select2(Request $r)
    {
        
        $q = trim($r->input('q', ''));
        $type = $r->type;
        $base = ExpenseCategory::query()->where('is_active', 1);
      

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
