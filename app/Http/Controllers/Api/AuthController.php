<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Login user and create token.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password'
            ], 401);
        }

        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();
            return response()->json([
                'success' => false,
                'message' => 'Your account is inactive. Please contact administrator.'
            ], 403);
        }

        // Create token (using Sanctum)
        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $user->load(['role', 'department', 'branch', 'position']),
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ]);
    }

    /**
     * Get authenticated user.
     */
    public function user(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()->load(['role', 'department', 'branch', 'position', 'supervisor'])
        ]);
    }

    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'phone_number' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Determine role based on position
        $position = \App\Models\Position::find($request->position_id);
        if (!$position) {
            return response()->json([
                'success' => false,
                'message' => 'Selected position not found.'
            ], 404);
        }

        $roleId = $this->determineRoleId($position->name);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'department_id' => $request->department_id,
            'position_id' => $request->position_id,
            'role_id' => $roleId,
            'phone_number' => $request->phone_number,
            'is_active' => true,
        ]);

        // Auto login after registration
        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'data' => [
                'user' => $user->load(['role', 'department', 'branch', 'position']),
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ], 201);
    }

    /**
     * Determine user role ID based on position name.
     */
    protected function determineRoleId($positionName)
    {
        $positionLower = strtolower($positionName);

        if (str_contains($positionLower, 'ceo') || str_contains($positionLower, 'chief executive') || str_contains($positionLower, 'md')) {
            $role = \App\Models\Role::where('name', 'admin')->first();
        } elseif (str_contains($positionLower, 'manager') || str_contains($positionLower, 'director')) {
            $role = \App\Models\Role::where('name', 'manager')->first();
        } elseif (str_contains($positionLower, 'supervisor')) {
            $role = \App\Models\Role::where('name', 'supervisor')->first();
        } else {
            $role = \App\Models\Role::where('name', 'employee')->first();
        }

        // Fallback to employee role if no role is found
        if (!$role) {
            $role = \App\Models\Role::where('name', 'employee')->first();
        }

        // If still no role, throw an exception
        if (!$role) {
            throw new \Exception('Employee role not found. Please seed the roles table.');
        }

        return $role->id;
    }

    /**
     * Logout user (revoke token).
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}

