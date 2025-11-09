<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\backend\Category;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
     public function index()
    {
        $categories = Category::all();
        return view('backend.modules.purchase.index',compact('categories'));
    }
}
