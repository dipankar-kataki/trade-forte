<?php

namespace App\Http\Controllers\Lorries;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\InvoiceDetail;
use App\Models\Lorry;
use App\Traits\ApiResponse;
use App\Traits\CreateUserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LorryController extends Controller
{
    use ApiResponse;
    use CreateUserActivityLog;

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), Lorry::createRule());

        if ($validator->fails()) {
            return $this->error('Oops!' . $validator->errors()->first(), null, null, 400);
        } else {
            try {
                $user_id = Auth::id();
                $data = $validator->validated();
                $invoice = InvoiceDetail::find($request->invoice_id);
                $data["details_added_by"] = $user_id;

                $data["exporter_id"] = $invoice->exporter_id;

                $data["consignee_id"] = $invoice->consignee_id;

                $data["bank_id"] = BankAccount::find($invoice->exporter_id)->first()->id;

                DB::beginTransaction();
                Lorry::create($data);
                $this->createLog($user_id, "Lorry details added.", "lorry_details", null);
                DB::commit();

                return $this->success("Lorry details registered Successfully!", null, null, 201);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
            }
        }
    }


    public function index(Request $request)
    {
        $lorry = Lorry::latest()->paginate(10);
        return $this->success("Lorry list.", $lorry, null, 200);
    }

    public function show(Request $request)
    {
        try {
            $lorry = Lorry::with('exporter', 'consignee', 'invoice', 'lorry_items')
                ->where(function ($query) use ($request) {
                    $query->where('id', $request->id)
                        ->orWhere('invoice_details_id', $request->id);
                })
                ->first();
            if (!$lorry) {
                return $this->error("Lorry not found.", null, null, 404);
            }
            return $this->success("Lorry info.", $lorry, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
        }
    }

    public function update(Request $request)
    {
        $lorry = Lorry::where(function ($query) use ($request) {
            $query->where('id', $request->id)
                ->orWhere('account_no', $request->id);
        })->first();
        if (!$lorry) {
            return $this->error("Lorry not found.", null, null, 404);
        }
        $validator = Validator::make($request->all(), BankAccount::updateRule());
        if ($validator->fails()) {
            return $this->error('Oops!' . $validator->errors()->first(), null, null, 400);
        } else {
            try {
                DB::beginTransaction();
                Lorry::where('id', $request->id)->update($request->all());
                $user_id = Auth::id();
                $this->createLog($user_id, "Lorry details updated.", "lorry_details", null);
                DB::commit();
                return $this->success("Lorry updated successfully.", null, null, 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
            }
        }
    }

    public function delete(Request $request)
    {
        try {
            DB::beginTransaction();
            $lorry = BankAccount::find($request->id);
            if (!$lorry) {
                return $this->error("Lorry not found.", null, null, 404);
            }
            $lorry->delete();
            $user_id = Auth::id();
            $this->createLog($user_id, "Lorry details deleted.", "lorry_details", null);
            DB::commit();
            return $this->success("Lorry deleted successfully.", null, null, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
        }
    }

}
