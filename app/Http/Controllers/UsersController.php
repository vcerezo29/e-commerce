<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    /**
     * Display the users page.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        // Retrieve all users with their roles
        $users = User::with('roles')->get();

        // Render the 'Admin/Users' page using Inertia.js
        return Inertia::render('Admin/Users', [
            'status' => session('status'), // Pass any status message to the view
            'users' => $users // Pass the retrieved users to the view
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserStoreRequest $request)
    {
        // Begin a database transaction
        DB::beginTransaction();
        try {
            // Validate the request data using the UserStoreRequest class
            $validated = $request->validated();

            // Create a new user with the validated data
            $newUser = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password'])
            ]);

            // Assign the 'editor' role to the new user
            $newUser->assignRole('editor');

            // Commit the transaction
            DB::commit();

            // Redirect back with a success message
            return back()->with('status', 'New User added');
        } catch (\Exception $e) {
            // If an exception occurs, rollback the transaction
            DB::rollBack();

            // Redirect back with an error message and input data
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Update the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // Begin a database transaction
        DB::beginTransaction();
        try {
            // Find the target user by its ID
            $target = User::findOrFail($request->id);

            // Validate the request data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($target->id)],
            ]);

            // Fill the target user's attributes with the validated data
            $target->fill($validated);

            // If the email has been changed, set email_verified_at to null
            if ($target->isDirty('email')) {
                $target->email_verified_at = null;
            }

            // Save the changes to the target user
            $target->save();

            // Commit the transaction
            DB::commit();

            // Redirect back with a success message
            return back()->with('status', 'User Updated');
        } catch (\Exception $e) {
            // If an exception occurs, rollback the transaction
            DB::rollBack();

            // Redirect back with an error message and input data
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }
    /**
     * Delete the user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Begin a database transaction
        DB::beginTransaction();
        try {
            // Find the target user by its ID
            $target = User::findOrFail($id);

            // Delete the target user
            $target->delete();

            // Commit the transaction
            DB::commit();

            // Redirect back with a success message
            return back()->with('status', 'User Deleted');
        } catch (\Exception $e) {
            // If an exception occurs, rollback the transaction
            DB::rollBack();

            // Redirect back with an error message and input data
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }
}
