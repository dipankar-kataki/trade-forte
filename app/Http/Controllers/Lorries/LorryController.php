<?php

namespace App\Http\Controllers\Lorries;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\InvoiceDetail;
use App\Models\Lorry;
use App\Models\LorryInvoices;
use App\Models\LorryItems;
use App\Traits\ApiResponse;
use App\Traits\CreateUserActivityLog;
use Carbon\Carbon;
use Illuminate\Contracts\Support\ValidatedData;
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
        try {
            $lorryData = $request->lorryDetails;
            $lorryItems = $request->lorryItems;
            $lorryInvoices = $request->lorryInvoices;

            if (!is_array($lorryItems)) {
                return $this->error('Invalid data format. Expected an array of lorry items.', null, null, 400);
            }
            if (!is_array($lorryInvoices)) {
                return $this->error('Invalid data format. Expected an array of lorry invoices.', null, null, 400);
            }
            $user_id = Auth::id();
            $lorryData["total_quantity"] = 0;
            $lorryData["date"] = Carbon::parse($lorryData['date']);
            $lorryData["total_trips"] = 0;
            $validator = Validator::make($lorryData, Lorry::createRule());
            if ($validator->fails()) {
                return $this->error('Oops! 43' . $validator->errors()->first(), null, null, 400);
            }
            $lorryData["users_id"] = $user_id;

            DB::beginTransaction();

            $lorry = Lorry::create($lorryData);

            $total_quantity = 0;
            $lorry["total_trips"] = 0;
            foreach ($lorryInvoices as $item) {
                // dd($item);
                $lorryInvoicesValidator = Validator::make($item, LorryInvoices::createRule());

                if ($lorryInvoicesValidator->fails()) {
                    DB::rollBack();
                    return $this->error('Oops! 67' . $lorryInvoicesValidator->errors()->first(), null, null, 400);
                }
                $item["lorry_id"] = $lorry->id;
                LorryInvoices::create($item);
            }
            $total_trips = 0;
            $total_quantity = 0;

            foreach ($lorryItems as $itemData) {

                $lorry["total_trips"] += $itemData["trip"];

                $itemValidator = Validator::make($itemData, LorryItems::createRule());

                if ($validator->fails()) {
                    DB::rollBack();
                    return $this->error('Oops! 78' . $itemValidator->errors()->first(), null, null, 400);
                }
                $validData = $itemValidator->validated();
                $validData["lorry_id"] = $lorry->id;
                $validData["total_quantity_to_deliver"] = $validData["trip"] * $validData["quantity"];

                LorryItems::create($validData);

                $total_quantity += $validData["total_quantity_to_deliver"];
                $total_trips += $validData["trip"];

            }

            $lorry->total_quantity = $total_quantity;
            $lorry->save();

            DB::commit();
            $this->createLog($user_id, "Lorry created.", "lorry", $lorry->id);

            return $this->success("Lorry items registered Successfully!", $lorry->id, null, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
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
            $lorry = LorryInvoices::with('lorry', 'lorry_items')
                ->where('id', $request->id)
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
