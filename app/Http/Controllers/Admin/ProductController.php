<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['brand', 'category', 'product_images'])->get();
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
                return response()->json(base64_encode('/storage/images/products/'. $fileName));
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failled to upload Image: ' . $e->getMessage()]);
        }

    }

    public function revertImage($uniqueId)
    {

        $path= base64_decode($uniqueId);

        try {
            Storage::delete( str_replace("/storage","", $path ));
            ProductImage::where( 'image', $path)->delete();
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

            if( !empty($request->product_images)){
                foreach ($request->product_images as $image) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $image
                    ]);
                }    
            }
                
            return redirect()->route('admin.products.index')->with('success', 'Product created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failled to create Product: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $id){
        try{
            $product = Product::findOrFail($id);

            $product->update([
                'title' => $request->title,
                'price' => $request->price,
                'quantity' => $request->quantity,
                'description' => $request->description,
                'brand_id' => $request->brand_id,
                'category_id' => $request->category_id,
            ]);

            if( !empty($request->product_images)){
                foreach ($request->product_images as $image) {
                    ProductImage::create([
                        'product_id' => $id,
                        'image' => $image
                    ]);
                }    
            }

            return redirect()->back()->with('success', 'Product updated successfully');

        }catch(\Exception $e){
            return redirect()->back()->withErrors(['error' => 'Failled to Update Product: ' . $e->getMessage()]);
        }

    }
    public function destroy($id){
        try{
            Product::findOrFail($id)->delete();
            return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully');
    
        }catch (\Exception $e){
            return redirect()->back()->withErrors(['error' => 'Failled to Delete Product: ' . $e->getMessage()]);
        }
    }
}
