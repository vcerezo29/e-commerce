<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class LandingController extends Controller
{
    /**
     * Display the welcome page.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $products = Product::paginate(10); // Paginate products

        $cart = Cart::with('Product')->get()->groupBy('product'); // Group cart items by product

        $subtotal = 0; // Initialize subtotal price

        foreach ($cart as $productId => $cartItems) {
            $product = $cartItems->first()->Product; // Get product associated with the cart item
            $subtotal += $product->price * $cartItems->count(); // Calculate subtotal price
        }

        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'), // Check if login route exists
            'canRegister' => false, // Disable registration
            'laravelVersion' => Application::VERSION, // Laravel version
            'phpVersion' => PHP_VERSION, // PHP version
            'products' => $products, // Paginated products
            'cart' => $cart, // Grouped cart items
            'subtotal' => $subtotal, // Subtotal price
            'status' => session('status'), // Session status
        ]);
    }
}
