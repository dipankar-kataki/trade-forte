<?php

namespace App\Http\Controllers\LorryItems;

use App\Http\Controllers\Controller;
use App\Models\InvoiceDetail;
use App\Models\LorryItems;
use App\Traits\ApiResponse;
use App\Traits\CreateUserActivityLog;
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
            if (!is_array($data)) {
                return $this->error('Invalid data format. Expected an array of invoice items.', null, null, 400);
            }
            $user_id = Auth::id();
            DB::beginTransaction();
            foreach ($data as $itemData) {
                $validator = Validator::make($itemData, LorryItems::createRule());
                if ($validator->fails()) {
                    return $this->error('Oops!' . $validator->errors()->first(), null, null, 400);
                }
                $validData = $validator->validated();
                $data["users_id"] = $user_id;
                LorryItems::create($validData);
                $this->createLog($user_id, "Lorry items added.", "lorryitems", null);
            }
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
            $account = InvoiceDetail::with(["exporter", "consignee","lorry_items", "shipping_address", "exporter_address"])->where(function ($query) use ($request) {
                $query->where('id', $request->id)
                    ->orWhere('invoice_id', $request->id);
            })->get()->first();
            if (!$account) {
                return $this->error("Lorry items not found.", null, null, 404);
            }
            return $this->success("Lorry items info.", $account, null, 200);
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
