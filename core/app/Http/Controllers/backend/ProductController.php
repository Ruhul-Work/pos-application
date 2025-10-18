<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\backend\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Contracts\Service\Attribute\Required;

class ProductController extends Controller
{
    public function index()
    {
        return view('backend.modules.products.index');
    }

    public function listAjax(Request $request)
    {
        $columns   = ['id', 'price', 'is_active', 'name', 'category_type_id', 'category_id', 'subcategory_id', 'product_type_id', 'brand_id', 'color_id', 'size_id', 'image'];
        $draw      = (int) $request->input('draw');
        $start     = (int) $request->input('start', 0);
        $length    = (int) $request->input('length', 10);
        $orderIdx  = (int) $request->input('order.0.column', 0);
        $orderDir  = strtolower($request->input('order.0.dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $searchVal = trim($request->input('search.value', ''));

        $base = Product::query()->select(['id', 'name', 'is_active', 'price', 'category_type_id', 'category_id', 'subcategory_id', 'product_type_id', 'brand_id', 'color_id', 'size_id', 'image'])->where('parent_id',null);

        $total = (clone $base)->count();

        if ($searchVal !== '') {
            $base->where(function ($q) use ($searchVal) {
                $q->where('name', 'like', "%{$searchVal}%")
                    ->orWhere('bn_name', 'like', "%{$searchVal}%")
                    ->orWhere('url', 'like', "%{$searchVal}%");
            });
        }

        $filtered = (clone $base)->count();

        $orderCol = $columns[$orderIdx] ?? 'id';

        $rows = $base->orderBy($orderCol, $orderDir)
            ->skip($start)->take($length)->get();

        $data = [];
        foreach ($rows as $b) {
            $nameCol = '<strong>' . e($b->name) . '</strong>';

            $actions = '<div class="d-inline-flex justify-content-end gap-1 w-100">
                <a href="' . route('product.products.editModal', $b->id) . '" class="w-32-px h-32-px rounded-circle d-inline-flex align-items-center justify-content-center
                    bg-success-focus text-success-main "
                    data-ajax-modal="' . route('product.products.editModal', $b->id) . '"
                    data-size="lg"
                    data-onsuccess="BranchesIndex.onSaved"
                    title="Edit">
                    <iconify-icon icon="lucide:edit"></iconify-icon>
                </a>
                <a href="#" class="w-32-px h-32-px rounded-circle d-inline-flex align-items-center justify-content-center bg-danger-focus text-danger-main btn-product-delete"
                    data-id="' . $b->id . '"
                    data-url="' . route('product.products.destroy', $b->id) . '"
                    title="Delete">
                    <iconify-icon icon="mdi:delete"></iconify-icon>
                </a>
            </div>';
            $image = '<div  style="width:70px"><img src="' . image($b->image) . '" alt="img"></div>';
            $status =  $b->is_active
                ? '<span class="badge text-sm fw-semibold bg-dark-success-gradient px-20 py-9 radius-4 text-white">Active</span>'
                : '<span class="badge text-sm fw-semibold bg-dark-warning-gradient px-20 py-9 radius-4 text-white">Inactive</span>';
            $size  = ($b->size && $b->size->name)  ? $b->size->name  : '';
            $color = ($b->color && $b->color->name) ? $b->color->name : '';

            $size_color = '<p>' . $size . '<br><span class="text-sm">' . $color . '</span></p>';
            $category = '<span class="text-sm">Category Type : ' . $b->category_type->name . '</span><br>
                        <span class="text-sm">Category: ' . $b->category->name . '</span><br>
                        <span class="text-sm">Sub-Category: ' . $b->subcategory->name . '</span>';

            $data[] = [
                $b->id,
                $nameCol,
                $category,
                $b->product_type->name,
                $b->brand->name,
                $b->price,
                $size_color,
                $image,
                $status,
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
        // @perm গার্ড চাইলে দিন
        return view('backend.modules.products.create'); // partial only
    }

    public function store(Request $req)
    {

        $data = $req->validate([
            'name'      => ['required', 'string', 'max:150', 'unique:products,name'],
            'slug'      => ['required', 'string', 'max:150', 'unique:products,slug'],
            'sku'      => ['required', 'string', 'max:150', 'unique:products,sku'],
            'has_variant' => ['required', 'integer'],
            'category_id' => ['required', 'integer'],
            'category_type_id' => ['required', 'integer'],
            'subcategory_id' => ['required', 'integer'],
            'product_type_id' => ['required', 'integer'],
            'brand_id' => ['required', 'integer'],
            'color_id.*' => 'nullable|integer',
            'unit_id' => ['required', 'integer'],
            'size_id.*' => ['nullable', 'integer'],
            'paper_id.*' => ['integer'],
            'cost_price' => ['required', 'numeric'],
            'mrp' => ['required', 'numeric'],
            'discount_type' => ['required', 'integer'],
            'discount_value' => ['required', 'numeric'],
            'price' => ['required', 'numeric'],
            'has_variant' => ['required', 'integer'],
            'is_active' => ['required', 'integer'],
            'material'     => ['nullable', 'string', 'max:150'],
            'description'     => ['nullable', 'string', 'max:350'],
            'short_description'     => ['nullable', 'string', 'max:250'],
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'thumbnail_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'size_chart_image' => 'image|mimes:jpeg,png,jpg|max:2048',
            'meta_title'     => ['nullable', 'string', 'max:150'],
            'meta_description'     => ['nullable', 'string', 'max:350'],
            'meta_keywords'     => ['nullable', 'string', 'max:250'],
            'meta_image ' => 'image|mimes:jpeg,png,jpg|max:2048',

        ]);
        $child_data = null;

        if ($data['has_variant'] == 1) {
            $child_data = $req->validate([
                'child_name' => 'required',
                'child_sku' => 'required',
                'child_color_id.*' => 'nullable|integer',
                'child_size_id.*' => 'nullable|integer',
                'child_paper_id.*' => 'nullable|integer',
                'child_price.*' => 'required|numeric',
            ]);
        }


        $final_price = 0;

        if ($data['discount_type'] === '1') {

            $final_price = $data['mrp'] - $data['discount_value'];
        } else {
            $final_price = $data['mrp'] - (($data['mrp'] * $data['discount_value']) / 100);
        }

        $imagePath = uploadImage($req->file('image'), 'product/images');
        $thumbnail_image = uploadImage($req->file('thumbnail_image'), 'product/thumbnail_images');
        $size_chart_image = null;
        if ($req->hasFile('meta_image')) {
            $size_chart_image = uploadImage($req->file('size_chart_image'), 'product/size_chart_images');
        }
        $metaImagePath = null;
        if ($req->hasFile('meta_image')) {
            $metaImagePath = uploadImage($req->file('meta_image'), 'product/meta_images');
        }

       

        $product = product::create([
            'name'      => ucwords($data['name']),
            'slug' => $data['slug'],
            'sku' => $data['sku'],
            'category_type_id' => $data['category_type_id'],
            'category_id'      => $data['category_id'],
            'subcategory_id'      => $data['subcategory_id'],
            'product_type_id'      => $data['product_type_id'],
            'brand_id'      => $data['brand_id'],
            'size_id'      => null,
            'color_id'      => null,
            'unit_id' => $data['unit_id'],
            'cost_price'      => $data['cost_price'],
            'mrp'      => $data['mrp'],
            'discount_type'      => $data['discount_type'],
            'discount_value'      => $data['discount_value'],
            'price'      =>  $final_price,
            'has_variants' => $data['has_variant'],
            'is_sellable' => ($data['has_variant'] == 0 ? 1 : 0),
            'material' => $data['material'],
            'description' => $data['description'],
            'short_description' => $data['short_description'],
            'is_active' => $data['is_active'],
            'meta_title' => $data['meta_title'],
            'meta_keywords' => $data['meta_keywords'],
            'meta_description' => $data['meta_description'],
            'image' => $imagePath,
            'thumbnail_image' => $thumbnail_image,
            'size_chart_image' => $size_chart_image,
            'meta_image' => $metaImagePath,



        ]);
        if ($data['has_variant'] == 1) {
            $colors = $child_data['child_color_id']??[];
            $sizes = $child_data['child_size_id'] ?? [];
            $papers = $child_data['child_paper_id']??[];
            // $variant = $data['variant_type'];
            $names = $child_data['child_name'];
            $skus = $child_data['child_sku'];
            $prices = $child_data['child_price'];



            if (sizeof($colors) > 0 && sizeof($sizes)> 0 ) {

                for ($i = 0; $i < sizeof($colors); $i++) {
                    $child_product = product::create([
                        'parent_id' => $product->id,
                        'name'      => ucwords($names[$i]),
                        'slug' => $data['slug'],
                        'sku' => $skus[$i],
                        'category_type_id' => $data['category_type_id'],
                        'category_id'      => $data['category_id'],
                        'subcategory_id'      => $data['subcategory_id'],
                        'product_type_id'      => $data['product_type_id'],
                        'brand_id'      => $data['brand_id'],
                        'size_id'      => $child_data['child_size_id'][$i],
                        'color_id'      => $child_data['child_color_id'][$i],
                        'unit_id' => $data['unit_id'],
                        'cost_price'      => $data['cost_price'],
                        'mrp'      => $data['mrp'],
                        'discount_type'      => $data['discount_type'],
                        'discount_value'      => $data['discount_value'],
                        'price'      =>  $prices[$i],
                        'has_variants' => 0,
                        'is_sellable' => 1,
                        'is_active' => $data['is_active'],
                        'image' => $imagePath,
                    ]);
                }
            } else {
                if (sizeof($colors) > 0 ) {

                    for ($i = 0; $i < sizeof($colors); $i++) {
                        $child_product = product::create([
                            'parent_id' => $product->id,
                            'name'      => ucwords($names[$i]),
                            'slug' => $data['slug'],
                            'sku' => $skus[$i],
                            'category_type_id' => $data['category_type_id'],
                            'category_id'      => $data['category_id'],
                            'subcategory_id'      => $data['subcategory_id'],
                            'product_type_id'      => $data['product_type_id'],
                            'brand_id'      => $data['brand_id'],
                            // 'size_id'      => $sizes[$i],
                            'color_id'      => $colors[$i],
                            'unit_id' => $data['unit_id'],
                            'cost_price'      => $data['cost_price'],
                            'mrp'      => $data['mrp'],
                            'discount_type'      => $data['discount_type'],
                            'discount_value'      => $data['discount_value'],
                            'price'      =>  $prices[$i],
                            'has_variants' => 0,
                            'is_sellable' => 1,
                            'is_active' => $data['is_active'],
                            'image' => $imagePath,
                        ]);
                    }
                } elseif (sizeof($sizes) > 0 ) {

                    for ($i = 0; $i < sizeof($sizes); $i++) {
                        $child_product = product::create([
                            'parent_id' => $product->id,
                            'name'      => ucwords($names[$i]),
                            'slug' => $data['slug'],
                            'sku' => $skus[$i],
                            'category_type_id' => $data['category_type_id'],
                            'category_id'      => $data['category_id'],
                            'subcategory_id'      => $data['subcategory_id'],
                            'product_type_id'      => $data['product_type_id'],
                            'brand_id'      => $data['brand_id'],
                            'size_id'      => $sizes[$i],
                            // 'color_id'      => $colors[$i],
                            'unit_id' => $data['unit_id'],
                            'cost_price'      => $data['cost_price'],
                            'mrp'      => $data['mrp'],
                            'discount_type'      => $data['discount_type'],
                            'discount_value'      => $data['discount_value'],
                            'price'      =>  $prices[$i],
                            'has_variants' => 0,
                            'is_sellable' => 1,
                            'is_active' => $data['is_active'],
                            'image' => $imagePath,
                        ]);
                    }
                }
                else{
                   
                   

                    for ($i = 0; $i < sizeof($papers); $i++) {
                        $child_product = product::create([
                            'parent_id' => $product->id,
                            'name'      => ucwords($names[$i]),
                            'sku' => $skus[$i],
                            'slug' => $data['slug'],
                            'category_type_id' => $data['category_type_id'],
                            'category_id'      => $data['category_id'],
                            'subcategory_id'      => $data['subcategory_id'],
                            'product_type_id'      => $data['product_type_id'],
                            'brand_id'      => $data['brand_id'],
                            'paper_id' => $papers[$i],
                            'unit_id' => $data['unit_id'],
                            'cost_price'      => $data['cost_price'],
                            'mrp'      => $data['mrp'],
                            'discount_type'      => $data['discount_type'],
                            'discount_value'      => $data['discount_value'],
                            'price'      =>  $prices[$i],
                            'has_variants' => 0,
                            'is_sellable' => 1,
                            'is_active' => $data['is_active'],
                            'image' => $imagePath,
                        ]);
                    
                }
                } 
            }
        }

        return response()->json(['ok' => true, 'msg' => 'product created', 'id' => $product->id]);
    }

    public function editModal(Product $product)
    {
        return view('backend.modules.products.edit', compact('product'));
    }

    public function show(Product $product)
    {

        return response()->json([
            'id'        => $product->id,
            'name'      => $product->name,
            'bn_name'      => $product->bn_mame,
            'url' => $product->url,

        ]);
    }

    public function update(Request $req, Product $product)
    {
        $data = $req->validate([
            'name'      => ['required', 'string', 'max:150'],
            'slug'      => ['required', 'string', 'max:150'],
            'category_type_id' => ['required', 'integer'],
            'category_id' => ['required', 'integer'],
            'subcategory_id' => ['required', 'integer'],
            'product_type_id' => ['required', 'integer'],
            'brand_id' => ['required', 'integer'],
            'color_id' => ['required', 'integer'],
            'unit_id' => ['required', 'integer'],
            'size_id' => ['required', 'integer'],
            'cost_price' => ['required', 'numeric'],
            'mrp' => ['required', 'numeric'],
            'discount_type' => ['required', 'integer'],
            'discount_value' => ['required', 'numeric'],
            'price' => ['required', 'numeric'],

            'is_active' => ['required', 'integer'],
            'material'     => ['nullable', 'string', 'max:150'],
            'description'     => ['nullable', 'string', 'max:350'],
            'short_description'     => ['nullable', 'string', 'max:250'],
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
            'thumbnail_image' => 'image|mimes:jpeg,png,jpg|max:2048',
            'size_chart_image' => 'image|mimes:jpeg,png,jpg|max:2048',
            'meta_title'     => ['nullable', 'string', 'max:150'],
            'meta_description'     => ['nullable', 'string', 'max:350'],
            'meta_keywords'     => ['nullable', 'string', 'max:250'],
            'meta_image ' => 'image|mimes:jpeg,png,jpg|max:2048',

        ]);

        $previousImage = $product->image;
        $previousThumbnailImage = $product->thumbnail_image;
        $previousSizeImage = $product->size_chart_image;
        $previousMetaImage = $product->meta_image;



        if ($req->hasFile('image')) {
            $imagePath = uploadImage($req->file('image'), 'product/images');
            $product->image = $imagePath;

            if ($previousImage && file_exists($previousImage)) {
                unlink($previousImage);
            }
        }

        if ($req->hasFile('meta_image')) {
            $metaImagePath = uploadImage($req->file('meta_image'), 'product/meta_images');
            $product->meta_image = $metaImagePath;

            if ($previousMetaImage && file_exists($previousMetaImage)) {
                unlink($previousMetaImage);
            }
        }

        if ($req->hasFile('thumbnail_image')) {
            $thumbnail_imagePath = uploadImage($req->file('thumbnail_image'), 'product/thumbnail_images');
            $product->image = $thumbnail_imagePath;

            if ($previousThumbnailImage && file_exists($previousThumbnailImage)) {
                unlink($previousThumbnailImage);
            }
        }

        if ($req->hasFile('size_chart_image')) {
            $sizeImagePath = uploadImage($req->file('size_chart_image'), 'product/size_chart_images');
            $product->size_chart_image = $sizeImagePath;

            if ($previousSizeImage && file_exists($previousSizeImage)) {
                unlink($previousSizeImage);
            }
        }
        $final_price = 0;

        if ($data['discount_type'] === '1') {

            $final_price = $data['mrp'] - $data['discount_value'];
        } else {
            $final_price = $data['mrp'] - (($data['mrp'] * $data['discount_value']) / 100);
        }

        $product->name      = ucwords($data['name']);
        $product->slug      = $data['slug'];
        $product->category_type_id = $data['category_type_id'];
        $product->category_id = $data['category_id'];
        $product->subcategory_id = $data['subcategory_id'];
        $product->size_id = $data['size_id'];
        $product->color_id = $data['color_id'];
        $product->brand_id = $data['brand_id'];
        $product->unit_id = $data['unit_id'];
        $product->product_type_id = $data['product_type_id'];
        $product->cost_price     = $data['cost_price'];
        $product->mrp     = $data['mrp'];
        $product->discount_type     = $data['discount_type'];
        $product->discount_value     = $data['discount_value'];
        $product->price     =  $final_price;
        $product->material     = $data['material'];
        $product->description     = $data['description'];
        $product->short_description     = $data['short_description'];
        $product->is_active     = $data['is_active'];
        $product->meta_description     = $data['meta_description'];
        $product->meta_title     = $data['meta_title'];
        $product->meta_keywords     = $data['meta_keywords'];

        $product->save();

        return response()->json(['ok' => true, 'msg' => 'product updated']);
    }

    public function destroy(Product $product)
    {
        // $inUse = DB::table('districts')->where('district_product_id', $product->id)->count();
        // if ($inUse > 0) {
        //     return response()->json([
        //         'ok'  => false,
        //         'msg' => "This product has {$inUse} district(s). Reassign them first.",
        //     ], 422);
        // }

        // DB::table('branch_business')->where('branch_id', $branch->id)->delete();

        $imagePath = $product->image;
        $metaImagePath = $product->meta_image;
        $thumbnailImagePath = $product->thumbnail_image;
        $sizeImagePath = $product->size_chart_image;


        $product->delete();


        if (isset($imagePath) && file_exists($imagePath)) {
            unlink($imagePath);
        }
        if (isset($metaImagePath) && file_exists($metaImagePath)) {
            unlink($metaImagePath);
        }
        if (isset($thumbnailImagePath) && file_exists($thumbnailImagePath)) {
            unlink($thumbnailImagePath);
        }
        if (isset($sizeImagePath) && file_exists($sizeImagePath)) {
            unlink($sizeImagePath);
        }

        return response()->json(['ok' => true, 'msg' => 'product deleted']);
    }
      public function select2(Request $r)
    {

        $q = trim($r->input('q', ''));
        $base = Product::query()->where('has_variants',0)->where('is_active', 1);


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
