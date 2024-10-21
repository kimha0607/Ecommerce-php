<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;

class OrderController extends Controller
{
  public function index()
  {
    $cart = session()->get('cart', []);
    $totalAmount = 0;

    foreach ($cart as $item) {
      $totalAmount += $item['price'] * $item['quantity'];
    }

    $id = Cookie::get('user_id');
    $user = User::find($id);
    return view('order.index', compact('user', 'totalAmount'));
  }

  public function placeOrder(Request $request)
  {
    $id = Cookie::get('user_id');
    $cart = session()->get('cart', []);
    if (empty($cart)) {
      return redirect()->back()->withErrors('Your cart is empty. Please add items to your cart before checking out.');
    }
    $totalAmount = 0;
    foreach ($cart as $item) {
      $totalAmount += $item['price'] * $item['quantity'];
    }

    $order = Order::create([
      'user_id' => $id,
      'total_amount' => $totalAmount,
    ]);
    foreach ($cart as $productId => $item) {
      $product = Product::find($productId);
      if ($product) {
        $quantity = $item['quantity'] ?? 0;
        OrderDetail::create([
          'order_id' => $order->id,
          'product_id' => $productId,
          'quantity' => $quantity,
          'price' => $product->price,
          'total_amount' => $product->price * (int) $quantity,
        ]);
      }
    }

    session()->forget('cart');

    return redirect()->route('order.index')->with('success', 'Your order has been placed successfully!');
  }

}
