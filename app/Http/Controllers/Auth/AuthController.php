<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\Wallet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PhpParser\Node\Stmt\Catch_;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $registerUserData = $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|min:5',
            'role_name' => 'required',
        ]);

        try {

            DB::beginTransaction();

            $roleName = $registerUserData['role_name'] ?? 'user';
            $role = Role::whereRaw('LOWER(name) = ?', [strtolower($roleName)])->first();

            if (!$role) {
                $role = Role::create(['name' => $roleName, 'description' => '']);
            }

            $user = User::create([
                'firstname' => $registerUserData['firstname'],
                'lastname' => $registerUserData['lastname'],
                'email' => $registerUserData['email'],
                'password' => Hash::make($registerUserData['password']),
                'role_id' => $role->id,
                'status' => 'active'
            ]);

            Wallet::create([
                'serial' => strtoupper(Str::random(10)),
                'balance' => 0.00,
                'status' => 'active',
                'user_id' => $user->id,
            ]);

            DB::commit();

            return response()->json(['message' => 'User Created with wallet and role.']);
        } catch (Exception $e) {

            return redirect()->back()->with('error', 'Failed to create user with wallet: ' . $e->getMessage());
            DB::rollBack();
        }
    }

    public function login(Request $request)
    {
        $loginUserData = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|min:5'
        ]);

        $user = User::where('email', $loginUserData['email'])->first();
        if (!$user || !Hash::check($loginUserData['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid Credentials'
            ], 401);
        }

        $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;

        return response()->json([
            'access_token' => $token,
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            "message" => "logged out"
        ]);
    }
}
