<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\Module;
use App\Models\ResetPassword;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Traits\CreateUserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    use ApiResponse;
    use CreateUserActivityLog;
    public function index(Request $request)
    {
        // Retrieve paginated users with a custom page size (e.g., 10 items per page)
        $users = User::where('status', 1)->paginate(10);
        $users->each(function ($user) {
            $user->module_id = json_decode($user->module_id);
        });
        return $this->success("User list.", $users, null, 200);
    }
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), User::createRules());
        if ($validator->fails()) {
            return $this->error('Oops!' . $validator->errors()->first(), null, null, 400);
        } else {
            try {
                DB::beginTransaction();
                $user = User::create([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'password' => Hash::make($request->input('password')),
                    'role' => $request->input('role'),
                    'module_id' => $request->input('module_id'),
                ]);
                $user_id = $user->id;
                $created_by = Auth::id();
                $this->createLog($created_by, "Created user account", "users", $user_id);
                DB::commit();
                return $this->success("User created.", null, null, 201);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
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
            return $this->error('Oops! ' . $validator->errors()->first(), null, null, 400);
        } else {
            try {
                $credentials = $request->only('email', 'password');
                if (!Auth::attempt($credentials)) {
                    return $this->error('Invalid credentials', null, null, 401);
                }
                $user = Auth::guard('sanctum')->user();
                $token = $user->createToken('api-token')->plainTextToken;
                $moduleIds = json_decode($user->module_id);
                // $modulesData = Module::whereIn("id", $moduleIds)->get()->toArray();

                $this->createLog($user->id, "User logged in.", "users", null);
                return $this->success("Login successful.", [
                    'modules' => $moduleIds,
                ], $token, 200);
            } catch (\Exception $e) {
                return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
            }
        }
    }
    public function show(Request $request)
    {
        try {
            $user = User::where(function ($query) use ($request) {
                $query->where('id', $request->id)
                    ->orWhere('email', $request->id);
            })
                ->where('status', 1)
                ->first();
            if (!$user) {
                return $this->error("User not found.", null, null, 404);
            }
            $user->module_id = json_decode($user->module_id, true);
            return $this->success("User data.", $user, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
        }
    }

    public function update(Request $request)
    {
        DB::beginTransaction();

        $user = User::find($request->id);
        if (!$user) {
            return $this->error("User not found.", null, null, 404);
        } else {
            try {
                if ($request->has("name")) {
                    $user->name = $request->name;
                }
                if ($request->has("module_id")) {
                    $user->module_id = $request->module_id;
                }
                $user->save();
                DB::commit();
                $this->createLog($user->id, "User update details.", "users", null);
                return $this->success("User updated successfully.", null, null, 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
            }
        }
    }

    public function destroy(Request $request)
    {        //
        $user = User::find($request->id);
        if (!$user) {
            return $this->error("User not found.", null, null, 404);
        } else {
            try {
                $user->status = 0;
                $user->save();
                $deleted_by = Auth::id();
                $this->createLog($deleted_by, "User deleted.", "users", null);
                return $this->success("User deleted.", null, null, 200);
            } catch (\Exception $e) {
                return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
            }
        }
    }

    public function forgotPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
            ]);
            $email = $request->email;
            $user = User::where('email', $email)->first();
            if (!$user) {
                return $this->error("User Email not found. Please register.", null, null, 404);
            }
            $token = Str::random(20);
            DB::beginTransaction();
            $expiresAt = Carbon::now()->addMinutes(15);

            ResetPassword::create([
                'email' => $email,
                'token' => $token,
                'expires_at' => $expiresAt,
            ]);

            DB::commit();
            $mail = new ResetPasswordMail($email, "Otp Password reset", $token);
            Mail::send($mail);
            return $this->success("Please reset password using the sent OTP code on mail.", null, null, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'token' => 'required',
                'password' => 'required|min:8|confirmed',
            ]);
            if ($validator->fails()) {
                return $this->error('Oops! Something Went Wrong.' . $validator->errors(), null, null, 500);
            }
            $email = $request->email;
            $token = $request->token;
            $password = $request->password;
            // Check if the reset token exists
            $resetToken = ResetPassword::where('email', $email)->where('token', $token)->first();
            if (!$resetToken) {
                return $this->error('Invalid reset token', null, null, 400);
            }
            // Check if the token has expired
            if ($resetToken->expires_at < now()) {
                return $this->error('Reset token has expired. Please make request again.', null, null, 400);
            }
            // Find the user by email
            $user = User::where('email', $email)->first();
            if (!$user) {
                return $this->error('User Not found..', null, null, 404);
            }
            // Update user's password
            $user->password = Hash::make($password);
            $user->save();
            $this->createLog($user->id, "Password reset.", "users", null);
            // Delete the used reset token
            $resetToken->delete();
            return $this->success("Password reset successfully. Please login to continue.", null, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
        }
    }
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), User::changePasswordRule());

        if ($validator->fails()) {
            return $this->error("Validation failed" . $validator->errors(), null, null, 400);
        } else {
            try {
                DB::beginTransaction();
                $user = User::where('email', $request->email)->first();
                if (!$user) {
                    return $this->error("User not found", null, null, 404);
                }
                $user->password = Hash::make($request->password);
                $user->save();
                $this->createLog($user->id, "Password changed.", "users", null);
                DB::commit();
                return $this->success("Password reset successfull.", null, null, 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
            }
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = Auth::user();
            $user->tokens()->where('id', $user->currentAccessToken()->id)->delete(); // Revoke the current user's token
            $this->createLog($user->id, "User logged out.", "user", null);
            return $this->success("User logged out successfully.", null, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.', null, null, 500);
        }
    }
}
