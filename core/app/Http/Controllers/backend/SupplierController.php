<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\backend\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function index()
    {
        return view('backend.modules.suppliers.index');
    }

    public function listAjax(Request $request)
    {
        $columns   = ['id', 'name', 'slug', 'email', 'phone','postal_code', 'address', 'is_active'];
        $draw      = (int) $request->input('draw');
        $start     = (int) $request->input('start', 0);
        $length    = (int) $request->input('length', 10);
        $orderIdx  = (int) $request->input('order.0.column', 0);
        $orderDir  = strtolower($request->input('order.0.dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $searchVal = trim($request->input('search.value', ''));

        $base = Supplier::query()
            ->select(['id', 'name', 'slug', 'email', 'phone','postal_code', 'address', 'is_active']);

        $total = (clone $base)->count();

        if ($searchVal !== '') {
            $base->where(function ($q) use ($searchVal) {
                $q->where('name', 'like', "%{$searchVal}%")
                    ->orWhere('slug', 'like', "%{$searchVal}%")
                    ->orWhere('email', 'like', "%{$searchVal}%");
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
                <a href="' . route('supplier.edit', $b->id) . '" class="w-32-px h-32-px rounded-circle d-inline-flex align-items-center justify-content-center
                    bg-success-focus text-success-main "
                    data-size="lg"
                    data-onload="supplierIndex.onLoad"
                    data-onsuccess="supplierIndex.onSaved"
                    title="Edit">
                    <iconify-icon icon="lucide:edit"></iconify-icon>
                </a>
                <a href="#" class="w-32-px h-32-px rounded-circle d-inline-flex align-items-center justify-content-center bg-danger-focus text-danger-main btn-branch-delete"
                    data-id="' . $b->id . '"
                    data-url="' . route('supplier.destroy', $b->id) . '"
                    title="Delete">
                    <iconify-icon icon="mdi:delete"></iconify-icon>
                </a>
            </div>';

            $icon = '<div  style="width:70px"><img src="' . image($b->image) . '" alt="img"></div>';

          

            $data[] = [
                $b->id,
                $nameCol,
                e($b->slug),
                $b->email,
                $b->phone,
                $b->address,
                $b->postal_code,
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
        return view('backend.modules.suppliers.create'); // partial only
    }

    public function store(Request $req)
    {
        // dd($req->all());
        $data = $req->validate([
            'name'             => ['required', 'string', 'max:150', 'unique:suppliers,name'],
            'slug'             => ['required', 'string', 'max:150', 'unique:suppliers,slug'],
            'email'             => ['required', 'string', 'max:150', 'unique:suppliers,email'],
            'phone'             => ['required', 'string', 'max:50', 'unique:suppliers,phone'],
            'postal_code'             => ['required', 'string'],
            'address'             => ['required', 'string', 'max:255'],
            'image'             => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active'        => ['required', 'integer'],

        ]);

     
        $imagePath = null;
        if ($req->hasFile('image')) {
            $imagePath = uploadImage($req->file('image'), 'supplier/images');
        }

        $supplier = Supplier::create([
            'name'             => ucwords($data['name']),
            'slug'             => $data['slug'],
            'email'             => $data['email'],
            'phone'             => $data['phone'],
            'address'             => $data['address'],
            'postal_code' => $data['postal_code'],
            'image'             => $imagePath,
            'is_active'        => $data['is_active'] ?? null,

        ]);

        return response()->json(['ok' => true, 'msg' => 'supplier created', 'id' => $supplier->id]);
    }

    public function editModal(Supplier $supplier)
    {
        return view('backend.modules.suppliers.edit', compact('supplier'));
    }

    public function show(Supplier $supplier)
    {

        return response()->json([
            'id'   => $supplier->id,
            'name' => $supplier->name,
            'slug' => $supplier->slug,

        ]);
    }

   
    public function update(Request $req, Supplier $supplier)
    {
       
        $data = $req->validate([
            'name'             => ['required', 'string', 'max:150'],
            'slug'             => ['required', 'string', 'max:150'],
            'email'             => ['required', 'string', 'max:150'],
            'phone'             => ['required', 'string', 'max:50'],
            'postal_code'             => ['required', 'string'],
            'address'             => ['required', 'string', 'max:255'],
            'image'             => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active'        => ['required', 'integer'],

        ]);
        

        $previousImage = $supplier->image;
        

        if ($req->hasFile('image')) {
            $imagePath       = uploadImage($req->file('image'), 'supplier/images');
            $supplier->image = $imagePath;
           

            if ($previousImage && file_exists($previousImage)) {
                unlink($previousImage);
            }
        }


        $supplier->name             = ucwords($data['name']);
        $supplier->slug             = $data['slug'];
        $supplier->email             = $data['email'];
        $supplier->phone             = $data['phone'];
        $supplier->address             = $data['address'];
        $supplier->postal_code             = $data['postal_code'];
        $supplier->is_active        = $data['is_active'];

        $supplier->save();

          return redirect()->route('supplier.index')
                     ->with('success', 'Supplier updated successfully!');
    }

    public function destroy(Supplier $supplier)
    {
        // $inUse = DB::table('suppliers')->where('supplier_id', $supplier->id)->count();
        // if ($inUse > 0) {
        //     return response()->json([
        //         'ok'  => false,
        //         'msg' => "This supplier has {$inUse} subcategorie(s). Reassign them first.",
        //     ], 422);
        // }

        $image      = $supplier->image;
        $metaImagePath = $supplier->meta_image;

        $supplier->delete();

        if (isset($image) && file_exists($image)) {
            unlink($image);
        }
       

        return response()->json(['ok' => true, 'msg' => 'supplier deleted']);
    }

    public function select2(Request $r)
    {

        $q = trim($r->input('q', ''));
        $type = $r->type;
        $base = Supplier::query()->where('supplier_type_id', $type)->where('is_active', 1);


        if ($q !== '') {
            $base->where(function ($x) use ($q) {
                $x->where('name', 'like', "%{$q}%");
            });
        }

        $items = $base->orderBy('id')->orderBy('name')
            ->limit(20)->get(['id', 'name']);


        return response()->json([
            'results' => $items->map(fn($t) => [
                'id'   => $t->id,
                'text' => $t->name
            ])
        ]);
    }
}
