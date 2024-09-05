<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(){

        $products = Product::with(['brand', 'category'])->get();
        return Inertia::render('Admin/Product/Index', ['products' => $products]);
    }
}
