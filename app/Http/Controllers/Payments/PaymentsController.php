<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\Exporter;
use App\Models\Payments;
use App\Traits\ApiResponse;
use App\Traits\CreateUserActivityLog;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaymentsController extends Controller
{
    use ApiResponse;
    use CreateUserActivityLog;
    //add payment details for invoice
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), Payments::createRule());

        if ($validator->fails()) {
            return $this->error('Oops! ' . $validator->errors()->first(), null, null, 400);
        } else {
            try {
                $data = $validator->validated();
                $user_id = Auth::id();
                $data["users_id"] = $user_id;
                DB::beginTransaction();
                $payments = Payments::create($data);
                $this->createLog($user_id, "Payments details added.", "payments", $payments->id);
                DB::commit();
                return $this->success("Payments details added Successfully!", null, null, 201);
            } catch (QueryException $e) {
                DB::rollBack();
                return $this->error('Oops! Something Went Wrong. ' . $e->getMessage(), null, null, 500);
            }
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), Payments::createRule());

        if ($validator->fails()) {
            return $this->error('Oops! ' . $validator->errors()->first(), null, null, 400);
        } else {
            try {
                $data = $validator->validated();
                $user_id = Auth::id();
                $data["users_id"] = $user_id;
                $payments = Payments::where("invoice_details_id", $request->invoice_details_id)->first();
                if (!$payments) {
                    return $this->error('Oops! payment details not found ', null, null, 400);
                }
                DB::beginTransaction();
                $payments->bank_accounts_id = $request->bank_accounts_id;
                $payments->invoice_currency = $request->invoice_currency;
                $payments->terms_of_payment = $request->terms_of_payment;
                $payments->save();
                $this->createLog($user_id, "Payments details updated.", "payments", $payments->id);
                DB::commit();
                return $this->success("Payments details added Successfully!", null, null, 201);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->error('Oops! Something Went Wrong. ' . $e->getMessage(), null, null, 500);
            }
        }
    }

}
