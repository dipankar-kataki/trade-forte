<?php

namespace App\Http\Controllers\Packaging;

use App\Http\Controllers\Controller;
use App\Models\InvoiceDetail;
use App\Models\InvoiceItem;
use App\Models\PackagingDetail;
use App\Traits\CreateUserActivityLog;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PackagingController extends Controller
{
    use ApiResponse;
    use CreateUserActivityLog;

    public function create(Request $request)
    {
        try {
            $user_id = Auth::id();
            $packagingDetailsData = $request->final_packaging_list;

            if (!is_array($packagingDetailsData)) {
                return $this->error('Invalid data format. Expected an array of packaging details.', null, null, 400);
            }

            DB::beginTransaction();
            $invoice = InvoiceDetail::where("id", $request->id)->first();
            $invoice["with_letter_head"] = $request->with_letter_head;
            $invoice->save();
            foreach ($packagingDetailsData as $packagingData) {
                $packagingData["users_id"] = $user_id;
                $packagingData["total_gross_weight"] = $packagingData['quantity'] * $packagingData['each_box_weight'];

                $packaging = PackagingDetail::create($packagingData);

                $this->createLog($user_id, "Packaging details added.", "packaging", $packaging->id);
            }
            DB::commit();
            return $this->success("Packaging details added successfully!", null, null, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
        }
    }

    public function index(Request $request)
    {
        try {
            $exporter = PackagingDetail::latest()->paginate(50);
            return $this->success("Exporter list.", $exporter, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
        }
    }
    public function show(Request $request)
    {
        try {
            $invoiceId = $request->id;
            $invoice = InvoiceDetail::with(["consignees", "exporter", "exporter_address", "items", "packagingDetails"])->where("id", $invoiceId)->first();

            $packagingItems = ($invoice && $invoice->id) ? PackagingDetail::where('invoice_id', $invoice->id)->get() : PackagingDetail::where('invoice_id', $invoiceId)->get();

            if ($packagingItems->isEmpty()) {
                return $this->error("Packaging not found.", null, null, 404);
            }
            // Query for Invoice Items
            $invoiceItems = ($invoice && $invoice->id) ? InvoiceItem::where('invoice_id', $invoice->id)->get() : InvoiceItem::where('invoice_id', $invoiceId)->get();
            // Organize the result
            $result = [
                'invoice_items' => $invoiceItems->toArray(),
                'packaging_items' => $packagingItems->toArray(),
            ];
            return $this->success("Packaging Info.", $result, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
        }
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), PackagingDetail::updateRule());
        if ($validator->fails()) {
            return $this->error('Oops!' . $validator->errors()->first(), null, null, 400);
        } else {
            try {
                $user_id = Auth::id();
                DB::beginTransaction();
                PackagingDetail::where('invoice_item_id', $request->id)->update($request->all());
                $this->createLog($user_id, "Packaging details updated.", "packaging", $request->id);
                DB::commit();
                return $this->success("Packaging updated successfully.", null, null, 200);
            } catch (\Exception $e) {
                DB::rollback();
                return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
            }
        }
    }
    public function delete(Request $request)
    {
        try {
            DB::beginTransaction();
            $Packaging = PackagingDetail::find($request->exporterId);
            if (!$Packaging) {
                return $this->error("Packaging not found.", null, null, 404);
            }
            $Packaging->delete();
            $user_id = Auth::id();
            $this->createLog($user_id, "Packaging details deleted.", "packaging", $request->id);
            DB::commit();
            return $this->success("Packaging deleted successfully.", null, null, 200);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
        }
    }

}
