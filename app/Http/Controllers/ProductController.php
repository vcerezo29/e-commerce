<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Product;
use App\Models\UsersProducts;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;
use PhpParser\Node\Expr\Throw_;

class ProductController extends Controller
{
    /**
     * Store a new product and its image path.
     *
     * @param ProductStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ProductStoreRequest $request)
    {
        DB::beginTransaction(); // Begin transaction to ensure data consistency
        try {
            $validated = $request->validated(); // Validate request data
            $imagePath = $request->hasFile('image') ? $request->file('image')->store('image', 'public') : null; // Store image if present
            $product = Product::create($validated + ['image_path' => $imagePath]); // Create new product with image path

            // link the product to the user who created the product
            $productOwner = UsersProducts::create([
                'user' => Auth::user()->id,
                'product' => $product->id
            ]);

            DB::commit(); // Commit transaction if successful
            return back()->with('status', 'New Product Added: ' . $product->name); // Redirect with success message
        } catch (\Exception $e) {
            DB::rollback(); // Rollback transaction on error
            return redirect()->route('dashboard')->with('status', $e->getMessage()); // Redirect with error message
        }
    }

    /**
     * Update an existing product.
     *
     * @param int $id The ID of the product to be updated
     * @param ProductUpdateRequest $request The incoming request containing updated product data
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, ProductUpdateRequest $request)
    {
        DB::beginTransaction(); // Begin transaction to ensure data consistency
        try {
            $validated = $request->validated(); // Validate request data
            $target = Product::findOrFail($id); // Find the target product

            //check if the auth user has the role of admin nor editor
            if (Auth::user()->hasRole('admin')) {
                $target->fill($validated); // Update product with validated data
                $target->save();
            } else if (Auth::user()->hasRole('editor')) {
                //check if the auth user is same the owner of the product
                if (Auth::user()->id === $target->Owner[0]->user) {
                    $target->fill($validated); // Update product with validated data
                    $target->save(); // Save the updated product
                } else
                    throw new  Exception("Not Owner"); // return if Not

            } else
                throw new  Exception("Unable to do this action."); // return if not admin nor editor

            DB::commit(); // Commit transaction if successful
            return back()->with('status', 'Saved Product: ' . $target->name); // Redirect with success message
        } catch (\Exception $e) {
            DB::rollback(); // Rollback transaction on error
            return redirect()->route('dashboard')->with('status', $e->getMessage()); // Redirect with error message
        }
    }

    /**
     * Upload a new image for a product.
     *
     * @param int $id The ID of the product to upload image for
     * @param Request $request The incoming request containing the image file
     * @return \Illuminate\Http\RedirectResponse
     */
    public function upload($id, Request $request)
    {
        DB::beginTransaction(); // Begin transaction to ensure data consistency
        try {
            $validator = Validator::make($request->all(), [
                'image' => ['image', 'mimes:png,jpg', 'required', 'max:8000']
            ]); // Validate the incoming image file

            if ($validator->fails()) { // Check if validation fails
                throw new \Exception('Validation failed');
            }

            $target = Product::findOrFail($id); // Find the target product

            //check if the auth user has the role of admin nor editor
            if (Auth::user()->hasRole('admin')) {
                $imagePath = $request->file('image')->store('image', 'public'); // Store the uploaded image
            } else if (Auth::user()->hasRole('editor')) {
                //check if the auth user is same the owner of the product
                if (Auth::user()->id === $target->Owner[0]->user) {
                    $imagePath = $request->file('image')->store('image', 'public');
                } else
                    throw new  Exception("Not Owner"); // return if Not
            } else
                throw new  Exception("Unable to do this action."); // return if not admin nor editor

            $target->image_path = $imagePath; // Update the product's image path
            $target->save(); // Save the changes

            DB::commit(); // Commit transaction if successful
            return back()->with('status', 'Saved Product: ' . $target->name); // Redirect with success message
        } catch (\Exception $e) {
            DB::rollback(); // Rollback transaction on error
            return redirect()->back()->withErrors($validator)->withInput(); // Redirect with validation errors
        }
    }

    /**
     * Delete a product.
     *
     * @param int $id The ID of the product to be deleted
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        DB::beginTransaction(); // Begin transaction to ensure data consistency
        try {
            $target = Product::findOrFail($id); // Find the target product

            //check if the auth user has the role of admin nor editor
            if (Auth::user()->hasRole('admin')) {
                $target->delete(); // Delete the product
            } else if (Auth::user()->hasRole('editor')) {
                //check if the auth user is same the owner of the product
                if (Auth::user()->id === $target->Owner[0]->user) {
                    $target->delete(); // Delete the product
                } else
                    throw new  Exception("Not Owner"); // return if Not
            } else
                throw new  Exception("Unable to do this action."); // return if not admin nor editor


            // Check if the product has an associated image and delete it if it exists
            if ($target->image_path !== null && Storage::exists('public/' . $target->image_path)) {
                Storage::delete('public/' . $target->image_path);
            }

            DB::commit(); // Commit transaction if successful
            return back()->with('status', 'Deleted Product: ' . $target->name); // Redirect with success message
        } catch (\Exception $e) {
            DB::rollback(); // Rollback transaction on error
            return redirect()->back()->withErrors($e->getMessage())->withInput(); // Redirect with error message
        }
    }
}
