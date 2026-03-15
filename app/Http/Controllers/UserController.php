<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Get all customers (users with role 'customer')
     */
    public function getCustomers()
    {
        try {
            $customers = User::where('role', 'customer')
                ->select('id', 'name', 'email')
                ->orderBy('name')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $customers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch customers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search customers by name or email
     */
    public function searchCustomers(Request $request)
    {
        try {
            $search = $request->get('q', '');
            
            $customers = User::where('role', 'customer')
                ->where(function($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                })
                ->select('id', 'name', 'email')
                ->orderBy('name')
                ->limit(20)
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $customers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to search customers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a single customer by ID
     */
    public function getCustomer($id)
    {
        try {
            $customer = User::where('role', 'customer')
                ->where('id', $id)
                ->select('id', 'name', 'email')
                ->first();
            
            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $customer
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch customer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // =========================================================
    //  Admin User Management
    // =========================================================

    /**
     * List all users (admin only)
     */
    public function adminIndex()
    {
        try {
            $users = User::select('id', 'name', 'email', 'role', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json(['success' => true, 'data' => $users]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Create a new user with any role (admin only)
     */
    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role'     => 'required|in:admin,cashier,customer',
        ]);

        try {
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
                'role'     => $validated['role'],
            ]);

            return response()->json(['success' => true, 'data' => $user], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update a user (admin only)
     */
    public function adminUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'sometimes|required|string|max:255',
            'email'    => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
            'role'     => 'sometimes|required|in:admin,cashier,customer',
            'password' => 'sometimes|nullable|string|min:8',
        ]);

        try {
            if (isset($validated['name']))     $user->name  = $validated['name'];
            if (isset($validated['email']))    $user->email = $validated['email'];
            if (isset($validated['role']))     $user->role  = $validated['role'];
            if (!empty($validated['password'])) {
                $user->password = \Illuminate\Support\Facades\Hash::make($validated['password']);
            }
            $user->save();

            return response()->json(['success' => true, 'data' => $user]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a user (admin only, cannot delete self)
     */
    public function adminDestroy(Request $request, $id)
    {
        if ((int) $id === $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'You cannot delete your own account.'], 403);
        }

        try {
            User::findOrFail($id)->delete();
            return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}

