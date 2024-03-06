<?php

namespace App\Http\Controllers\Lorries;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\InvoiceDetail;
use App\Models\Lorry;
use App\Models\LorryItems;
use App\Traits\ApiResponse;
use App\Traits\CreateUserActivityLog;
use Carbon\Carbon;
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
            $data = $request->lorryItems;
            $lorryData = $request->lorryDetails;
            $lorryInvoices = $request->lorryInvoices;

            if (!is_array($data)) {
                return $this->error('Invalid data format. Expected an array of lorry items.', null, null, 400);
            }

            $user_id = Auth::id();
            $lorryData["total_quantity"] = 0;

            $validator = Validator::make($lorryData, Lorry::createRule());
            if ($validator->fails()) {
                return $this->error('Oops! ' . $validator->errors()->first(), null, null, 400);
            }
            foreach ($data as $item) {
                $validator = Validator::make($item, LorryItems::createRule());
                if ($validator->fails()) {
                    return $this->error('Oops! ' . $validator->errors()->first(), null, null, 400);
                }
            }
            
            DB::beginTransaction();

            $lorryData["users_id"] = $user_id;
            $lorryData["date"] = Carbon::parse($lorryData['date']);
            $lorryData["total_trips"] = 0;
            $lorry = Lorry::create($lorryData);

            $total_quantity = 0;
            $lorry["total_trips"] = 0;
            foreach($item as $lorryInvoices){
                $validator = Validator::make($itemData, LorryInvoices::createRule());

                if ($validator->fails()) {
                    DB::rollBack();
                    return $this->error('Oops! ' . $validator->errors()->first(), null, null, 400);
                }
                $item["lorry_id"] = $lorry->id;
                LorryInvoices::create($item)
            }
            foreach ($data as $itemData) {

                $validator = Validator::make($itemData, LorryItems::createRule());

                if ($validator->fails()) {
                    DB::rollBack();
                    return $this->error('Oops! ' . $validator->errors()->first(), null, null, 400);
                }

                $validData = $validator->validated();
                $validData["users_id"] = $user_id;
                $lorry["total_trips"] += $validData["trip"];
                LorryItems::create($validData);

                $total_quantity += $validData["total_quantity_to_deliver"];
                $this->createLog($user_id, "Lorry items added.", "lorryitems", null);
            }

            $lorry->total_quantity = $total_quantity;
            $lorry->save();

            DB::commit();

            return $this->success("Lorry items registered Successfully!", null, null, 201);
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
            $lorry = Lorry::with('lorry_items')
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
