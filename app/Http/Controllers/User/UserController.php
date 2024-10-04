<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(){
        $products = Product::with('brand', 'category', 'product_images')->orderBy('id', 'desc')->limit(8)->get();

        return Inertia::render('User/Index', [
            'products' => $products,
        ]);
    }
}
