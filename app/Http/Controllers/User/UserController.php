<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve paginated users with a custom page size (e.g., 10 items per page)
        $users = User::paginate(10);
        $users->each(function ($user) {
            $user->module_id = json_decode($user->module_id, true);
        });
        return response()->json(["data" => $users, "status" => 200]);
    }
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), User::createRules());
        if ($validator->fails()) {
            return response()->json([
                "message" => 'Oops!' . $validator->errors()->first(),
                "status" => 400
            ]);
        } else {
            try {
                User::create([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'password' => Hash::make($request->input('password')),
                    'role' => $request->input('role'),
                    // 'module_id' => json_encode($request->input('module_id')),
                    'module_id' => $request->input('module_id'),
                ]);
                return response()->json(["data" => "User created", "status" => 200]);
            } catch (\Exception $e) {
                return response()->json(["message" => 'Oops! Something Went Wrong.' . $e->getMessage(), "status" => 500]);
            }
        }
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|email",
            "password" => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => 'Oops! ' . $validator->errors()->first(), "status" => 400]);
        } else {
            try {
                $credentials = $request->only('email', 'password');

                if (!Auth::attempt($credentials)) {
                    return response()->json(['message' => 'Invalid credentials', 'status' => 401]);
                }
                $user = Auth::guard('sanctum')->user();
                $token = $user->createToken('api-token')->plainTextToken;
                return response()->json(['token' => $token, 'message' => 'Login successful', 'status' => 200]);
            } catch (\Exception $e) {
                return response()->json(["message" => 'Oops! Something Went Wrong.' . $e->getMessage(), "status" => 500]);
            }
        }
    }

    public function show(Request $request)
    {
        try {
            $user = User::where("email", $request->email)->first();
            if (!$user) {
                return response()->json(["message" => "User not found.", "status" => 404]);
            }
            $user->module_id = json_decode($user->module_id, true);
            return response()->json(["data" => $user, "status" => 200]);
        } catch (\Exception $e) {
            return response()->json(["message" => 'Oops! Something Went Wrong.' . $e->getMessage(), "status" => 500]);
        }
    }

    public function update(Request $request)
    {
        $user = User::find($request->id);
        // dump($user);
        if (!$user) {
            return response()->json([
                "message" => "User not found.",
                "status" => 404
            ]);
        }
        if ($request->has("name")) {
            $user->name = $request->name;
        }
        if ($request->has("module_id")) {
            $user->module_id = $request->module_id;
        }
        $user->save();
        return response()->json(["message" => "User updated successfully.", "status" => 200]);
    }


    public function destroy(Request $request)
    {        //
        $user = User::find($request->id);
        if (!$user) {
            return response()->json(["message" => "User not found.", "status" => 200]);
        }
        $user->delete();
        return response()->json(["message" => "User deleted.", "status" => 200]);
    }
}
