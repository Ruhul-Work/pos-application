<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\backend\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index()
    {
        return view('backend.modules.customers.index');
    }

    public function listAjax(Request $request)
    {
        $columns   = ['id', 'name', 'slug', 'email','birth_date', 'phone','postal_code', 'address', 'is_active'];
        $draw      = (int) $request->input('draw');
        $start     = (int) $request->input('start', 0);
        $length    = (int) $request->input('length', 10);
        $orderIdx  = (int) $request->input('order.0.column', 0);
        $orderDir  = strtolower($request->input('order.0.dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $searchVal = trim($request->input('search.value', ''));

        $base = customer::query()
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
                <a href="' . route('customer.edit', $b->id) . '" class="w-32-px h-32-px rounded-circle d-inline-flex align-items-center justify-content-center
                    bg-success-focus text-success-main "
                    data-size="lg"
                    data-onload="customerIndex.onLoad"
                    data-onsuccess="customerIndex.onSaved"
                    title="Edit">
                    <iconify-icon icon="lucide:edit"></iconify-icon>
                </a>
                <a href="#" class="w-32-px h-32-px rounded-circle d-inline-flex align-items-center justify-content-center bg-danger-focus text-danger-main btn-branch-delete"
                    data-id="' . $b->id . '"
                    data-url="' . route('customer.destroy', $b->id) . '"
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
                $b->birth_date??'N/A',
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
        return view('backend.modules.customers.create'); // partial only
    }

    public function store(Request $req)
    {
        // dd($req->all());
        $data = $req->validate([
            'name'             => ['required', 'string', 'max:150', 'unique:customers,name'],
            'slug'             => ['required', 'string', 'max:150', 'unique:customers,slug'],
            'email'             => ['required', 'string', 'max:150', 'unique:customers,email'],
            'phone'             => ['required', 'string', 'max:50', 'unique:customers,phone'],
            'alternate_phone'             => ['nullable', 'string', 'max:50', 'unique:customers,alternate_phone'],
            'birth_date'             => ['nullable', 'string', 'max:50', 'unique:customers,alternate_phone'],
            'postal_code'             => ['required', 'integer'],
            'address'             => ['required', 'string', 'max:255'],
            'image'             => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active'        => ['required', 'integer'],

        ]);

     
        $imagePath = null;
        if ($req->hasFile('image')) {
            $imagePath = uploadImage($req->file('image'), 'customer/images');
        }

        $customer = customer::create([
            'name'             => ucwords($data['name']),
            'slug'             => $data['slug'],
            'email'             => $data['email'],
            'phone'             => $data['phone'],
            'alternate_phone'             => $data['alternate_phone'],
            'birth_date'             => $data['birth_date'],
            'address'             => $data['address'],
            'postal_code' => $data['postal_code'],
            'image'             => $imagePath,
            'is_active'        => $data['is_active'] ?? null,

        ]);

        return response()->json(['ok' => true, 'msg' => 'customer created', 'id' => $customer->id]);
    }

    public function editModal(customer $customer)
    {
        return view('backend.modules.customers.edit', compact('customer'));
    }

    public function show(customer $customer)
    {

        return response()->json([
            'id'   => $customer->id,
            'name' => $customer->name,
            'slug' => $customer->slug,

        ]);
    }

   
    public function update(Request $req, customer $customer)
    {
       
        $data = $req->validate([
            'name'             => ['required', 'string', 'max:150'],
            'slug'             => ['required', 'string', 'max:150'],
            'email'             => ['required', 'string', 'max:150'],
            'phone'             => ['required', 'string', 'max:50'],
            'alternate_phone'             => ['nullable', 'string', 'max:50'],
            'birth_date'             => ['nullable', 'string', 'max:50'],
            'postal_code'             => ['required', 'integer'],
            'address'             => ['required', 'string', 'max:255'],
            'image'             => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active'        => ['required', 'integer'],

        ]);
        

        $previousImage = $customer->image;
        

        if ($req->hasFile('image')) {
            $imagePath       = uploadImage($req->file('image'), 'customer/images');
            $customer->image = $imagePath;
           

            if ($previousImage && file_exists($previousImage)) {
                unlink($previousImage);
            }
        }


        $customer->name             = ucwords($data['name']);
        $customer->slug             = $data['slug'];
        $customer->email             = $data['email'];
        $customer->phone             = $data['phone'];
        $customer->alternate_phone             = $data['alternate_phone']??null;
        $customer->birth_date             = $data['birth_date']??null;
        $customer->address             = $data['address'];
        $customer->postal_code             = $data['postal_code'];
        $customer->is_active        = $data['is_active'];

        $customer->save();

          return redirect()->route('customer.index')
                     ->with('success', 'customer updated successfully!');
    }

    public function destroy(customer $customer)
    {
        // $inUse = DB::table('customers')->where('customer_id', $customer->id)->count();
        // if ($inUse > 0) {
        //     return response()->json([
        //         'ok'  => false,
        //         'msg' => "This customer has {$inUse} subcategorie(s). Reassign them first.",
        //     ], 422);
        // }

        $image      = $customer->image;
        $metaImagePath = $customer->meta_image;

        $customer->delete();

        if (isset($image) && file_exists($image)) {
            unlink($image);
        }
       

        return response()->json(['ok' => true, 'msg' => 'customer deleted']);
    }

    public function select2(Request $r)
    {

        $q = trim($r->input('q', ''));
        $type = $r->type;
        $base = customer::query()->where('customer_type_id', $type)->where('is_active', 1);


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
