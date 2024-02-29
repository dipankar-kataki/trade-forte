<?php

namespace App\Http\Controllers\LorryItems;

use App\Http\Controllers\Controller;
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

class LorryItemsController extends Controller
{
    //
    use ApiResponse;
    use CreateUserActivityLog;
    public function create(Request $request)
    {
        try {
            $data = $request->lorryItems;
            $lorryData = $request->lorryDetails;
    
            if (!is_array($data)) {
                return $this->error('Invalid data format. Expected an array of lorry items.', null, null, 400);
            }
    
            $user_id = Auth::id();
            $lorryData["total_quantity"] = 0;
    
            $validator = Validator::make($lorryData, Lorry::createRule());
            if ($validator->fails()) {
                return $this->error('Oops! ' . $validator->errors()->first(), null, null, 400);
            }
    
            DB::beginTransaction();
    
            $lorryData["users_id"] = $user_id;
            $lorryData["date"] = Carbon::parse($lorryData['date']);
            $lorry = Lorry::create($lorryData);
    
            $total_quantity = 0;
    
            foreach ($data as $itemData) {
                $itemData["invoice_details_id"] = $lorryData["invoice_details_id"];
    
                $validator = Validator::make($itemData, LorryItems::createRule());
    
                if ($validator->fails()) {
                    DB::rollBack();
                    return $this->error('Oops! ' . $validator->errors()->first(), null, null, 400);
                }
    
                $validData = $validator->validated();
                $validData["users_id"] = $user_id;
    
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
        $lorry = LorryItems::latest()->paginate(10);
        return $this->success("Lorry Items list.", $lorry, null, 200);
    }

    public function show(Request $request)
    {
        try {
            $invoice = InvoiceDetail::with(['exporters', 'items', 'consignees', 'payments', 'transport',"lorry_details", 'declarations',"exporter_address", "shipping_address","lorry_items"])
                ->where('invoice_number', $request->id)
                ->orWhere("id", $request->id)
                ->get()->first();

            if (!$invoice) {
                return $this->error("Invoice not found.", null, null, 404);
            }
            $invoice->declarations->declaration = json_decode($invoice->declarations->declaration);
            return $this->success("Invoice details.", $invoice, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
        }
    }

    public function update(Request $request)
    {
        $account = LorryItems::where(function ($query) use ($request) {
            $query->where('id', $request->id)
                ->orWhere('lorry_id', $request->id);
        })->first();
        if (!$account) {
            return $this->error("Lorry items not found.", null, null, 404);
        }
        $validator = Validator::make($request->all(), LorryItems::updateRule());
        if ($validator->fails()) {
            return $this->error('Oops!' . $validator->errors()->first(), null, null, 400);
        } else {
            try {
                DB::beginTransaction();
                LorryItems::where('id', $request->bankAccountId)->update($request->all());
                $user_id = Auth::id();
                $this->createLog($user_id, "Lorry items updated.", "lorryitems", $request->id);
                DB::commit();
                return $this->success("lorry item updated successfully.", null, null, 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
            }
        }
    }

    public function delete(Request $request)
    {
        try {
            $user_id = Auth::id();
            DB::beginTransaction();
            $lorry = LorryItems::find($request->id);
            if (!$lorry) {
                return $this->error("Lorry Item not found.", null, null, 404);
            }
            $lorry->delete();
            $this->createLog($user_id, "Lorry items deleted.", "lorryitems", $request->id);
            DB::commit();
            return $this->success("Lorry Item deleted successfully.", null, null, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
        }
    }
}
