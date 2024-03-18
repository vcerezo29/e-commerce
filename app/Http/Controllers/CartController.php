<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartStoreRequest;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Cart', [
            'status' => session('status'),
        ]);
    }

    /**
     * Store a new item in the cart.
     *
     * @param CartStoreRequest $request The incoming request containing cart item data
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CartStoreRequest $request)
    {
        DB::beginTransaction(); // Begin transaction to ensure data consistency
        try {
            $validated = $request->validated(); // Validate request data

            $cart = Cart::Create($validated); // Create a new cart item

            DB::commit(); // Commit transaction if successful

            return back()->with('status', 'Product ' . $cart->Product->name . ' was added to the cart.'); // Redirect with success message
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on error
            return redirect()->route('landing')->with('status', $e->getMessage()); // Redirect with error message
        }
    }
    /**
     * Remove an item from the cart.
     *
     * @param int $id The ID of the cart item to be removed
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        DB::beginTransaction(); // Begin transaction to ensure data consistency
        try {
            $cart = Cart::findOrFail($id); // Find the cart item
            $cart->delete(); // Delete the cart item

            DB::commit(); // Commit transaction if successful
            return back()->with('status', $cart->Product->name . ' removed from cart.'); // Redirect with success message
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on error
            return redirect()->back()->withErrors($e->getMessage())->withInput(); // Redirect with error message
        }
    }
}
