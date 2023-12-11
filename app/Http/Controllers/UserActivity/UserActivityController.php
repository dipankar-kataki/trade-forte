<?php

namespace App\Http\Controllers\UserActivity;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\userLog;

class UserActivityController extends Controller
{
    use ApiResponse;
    public function index(Request $request)
    {
        $users = userLog::paginate(50);
        return $this->success("User activity list.", $users, null, 200);
    }

    public function show(Request $request)
    {
        try {
            $user_activity = userLog::where('user_id', $request->id)->latest()->paginate(50)->get();
            if ($user_activity->isEmpty()) {
                return $this->error("No user activity found.", null, null, 404);
            }

            return $this->success("User Activity Info.", $user_activity, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
        }
    }
}
