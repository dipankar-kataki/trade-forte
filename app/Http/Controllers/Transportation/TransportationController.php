<?php

namespace App\Http\Controllers\Transportation;

use App\Http\Controllers\Controller;
use App\Models\Transportation;
use App\Traits\ApiResponse;
use App\Traits\CreateUserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransportationController extends Controller
{
    use ApiResponse;
    use CreateUserActivityLog;
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), Transportation::createRule());

        if ($validator->fails()) {
            return response()->json([
                "message" => 'Oops!' . $validator->errors()->first(),
                "status" => 400
            ]);
        } else {
            try {
                $data = $validator->validated();
                $user_id = Auth::id();
                $data["users_id"] = $user_id;
                DB::beginTransaction();
                $transport = Transportation::create($data);
                $this->createLog($user_id, "transport added.", "shipping", $transport->id);
                DB::commit();
                return $this->success("Transport details added Successfully!", null, null, 201);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
            }
        }
    }
}
