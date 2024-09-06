<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(){

        $products = Product::with(['brand', 'category'])->get();
        $brands = Brand::all();
        $categories = Category::all();

        return Inertia::render('Admin/Product/Index', [
            'products' => $products,
            'brands' => $brands,
            'categories' => $categories
        ]);
    }

    public function store( Request $request){
        // dd($request);
        try{
            $product = Product::create([
                'title' => $request->title,
                'price' => $request->price,
                'quantity' => $request->quantity,
                'description' => $request->description,
                'brand_id' => $request->brand_id,
                'category_id' => $request->category_id,
            ]);
    
            if($request->hasFile('product_images')){
                $productImages = $request->file('product_images');
    
                foreach( $productImages as $image){
                    $uniqueName = time() . '-' . Str::random(10) . '.'. $image->getClientOriginalExtension();
                    $image->move('product_images', $uniqueName);
    
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => 'product_image/'. $uniqueName
                    ]);
                }
            }

            return redirect()->route('admin.products.index')->with('success', 'Product created successfully');

        }catch(\Exception $e){
            return redirect()->back()->withErrors(['error' => 'Failled to create Product: '. $e->getMessage()]);         
        }

    }
}
