<?php

namespace App\Http\Controllers\Transportation;

use App\Http\Controllers\Controller;
use App\Models\Transportation;
use App\Traits\ApiResponse;
use App\Traits\CreateUserActivityLog;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
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
                $data["bl_awb_lr_date"] = Carbon::parse($data['bl_awb_lr_date']);
                $data["challan_date"] = Carbon::parse($data['challan_date']);
                $data["eway_bill_date"] = Carbon::parse($data['eway_bill_date']);

                DB::beginTransaction();
                $tranport = Transportation::create($data);
                $this->createLog($user_id, "Tranportation details added.", "payments", $tranport->id);

                DB::commit();
                return $this->success("Transportation details added Successfully!", null, null, 201);
            } catch (QueryException $e) {
                DB::rollBack();
                return $this->error('Oops! Something Went Wrong. ' . $e->getMessage(), null, null, 500);
            }
        }
    }

    public function update(Request $request)
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
                $data["bl_awb_lr_date"] = Carbon::parse($data['bl_awb_lr_date']);
                $data["challan_date"] = Carbon::parse($data['challan_date']);
                $data["eway_bill_date"] = Carbon::parse($data['eway_bill_date']);

                DB::beginTransaction();
                // Check if payment already exists
                $tranport = Transportation::where("invoice_details_id", $request->invoice_details_id)->first();

                // Update existing payment
                $tranport->mode_of_transport = $request->mode_of_transport;
                $tranport->bl_awb_lr_no = $request->bl_awb_lr_no;
                $tranport->bl_awb_lr_date = $request->bl_awb_lr_date;
                $tranport->transporter_name = $request->transporter_name;
                $tranport->save();                
                $this->createLog($user_id, "Tranportation details edited.", "payments", $tranport->id);
                DB::commit();
                return $this->success("Transportation details added Successfully!", null, null, 201);
            } catch (QueryException $e) {
                DB::rollBack();
                return $this->error('Oops! Something Went Wrong. ' . $e->getMessage(), null, null, 500);
            }
        }
    }
}
