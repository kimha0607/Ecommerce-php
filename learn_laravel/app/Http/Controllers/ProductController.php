<?php

// app/Http/Controllers/ProductController.php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
  public function index()
  {
    $products = Product::paginate(10);
    return view('products.index', compact('products'));
  }

  public function create()
  {
    return view('products.create');
  }

  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'price' => 'required|numeric',
      'description' => 'nullable|string',
    ]);

    Product::create($request->all());
    return redirect()->route('products.index')->with('success', 'Sản phẩm đã được thêm thành công.');
  }

  public function edit(Product $product)
  {
    return view('products.edit', compact('product'));
  }

  public function update(Request $request, Product $product)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'price' => 'required|numeric',
      'description' => 'nullable|string',
    ]);

    $product->update($request->all());
    return redirect()->route('products.index')->with('success', 'Sản phẩm đã được cập nhật thành công.');
  }

  public function destroy(Product $product)
  {
    $product->delete();
    return redirect()->route('products.index')->with('success', 'Sản phẩm đã được xóa thành công.');
  }

  public function addToCart(Request $request, Product $product)
  {
    $product_id = $product->id;

    $cart = Session::get('cart', []);

    if (isset($cart[$product_id])) {
      $cart[$product_id]['quantity']++;
    } else {
      $cart[$product_id] = [
        'product_name' => $product->product_name,
        'price' => $product->price,
        'quantity' => 1
      ];
    }
    Session::put('cart', $cart);
    return redirect()->route('products.index')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
  }
}
