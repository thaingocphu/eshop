<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['brand', 'category'])->get();
        $brands = Brand::all();
        $categories = Category::all();

        return Inertia::render('Admin/Product/Index', [
            'products' => $products,
            'brands' => $brands,
            'categories' => $categories
        ]);
    }

    public function uploadImage(Request $request)
    {
        try {
            if ($request->hasFile('image')) {

                $image = $request->file('image');
                $fileName = uniqid('product-', true) . '.' . $image->getClientOriginalExtension();
                $image->storeAs('/images/products', $fileName);
    
                $setImageSession = $request->session()->get('upload_images', []);
                $setImageSession[] = $fileName;
                session()->put('upload_images', $setImageSession);
            }
            return response()->json($fileName);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failled to upload Image: ' . $e->getMessage()]);
        }

    }

    public function revertImage($fileName)
    {
        try {
            $getImageSession = session()->get('upload_images', []);

            if (($key = array_search($fileName, $getImageSession)) !== false) {
                unset($getImageSession[$key]);
                session()->put('upload_images', $getImageSession);
            }
            Storage::delete('images/products/' . $fileName);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'fail to revert image: ' . $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $product = Product::create([
                'title' => $request->title,
                'price' => $request->price,
                'quantity' => $request->quantity,
                'description' => $request->description,
                'brand_id' => $request->brand_id,
                'category_id' => $request->category_id,
            ]);

            $getImageSession = session()->get('upload_images', []);
            foreach ($getImageSession as $image) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $image
                ]);
            }
                
            session()->forget('upload_images');

            return redirect()->route('admin.products.index')->with('success', 'Product created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failled to create Product: ' . $e->getMessage()]);
        }
    }
}
