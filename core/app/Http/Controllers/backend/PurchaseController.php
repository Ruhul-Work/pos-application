<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\backend\Category;
use App\Models\backend\Product;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $products = Product::with(['category'])
            ->where('parent_id', null)
            ->paginate(6);
        

        return view('backend.modules.purchase.index', compact('categories','products'));
    }
}
