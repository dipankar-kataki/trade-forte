<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\Exporter;
use App\Models\Payments;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaymentsController extends Controller
{
    //add payment details for invoice
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), Payments::createRule());
        if ($validator->fails()) {
            return $this->error('Oops!' . $validator->errors()->first(), null, null, 400);
        } else {
            try {
                $data = $validator->validated();
                $user_id = Auth::id();
                $data["users_id"] = $user_id;
                DB::beginTransaction();
                Payments::create($data);
                $this->createLog($user_id, "Payments details added.", "exporters", $request->id);
                DB::commit();
                return $this->success("Payments details added Successfully!", null, null, 201);
            } catch (QueryException $e) {
                DB::rollBack();
                return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
            }
        }
    }

}
