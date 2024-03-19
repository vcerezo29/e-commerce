<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    /**
     * Display the dashboard page.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $products = Product::withCount('cart')->get(); // Retrieve all products
        return Inertia::render('Dashboard', [
            'status' => session('status'), // Get session status
            'products' => $products // Pass products to the view
        ]);
    }
}
