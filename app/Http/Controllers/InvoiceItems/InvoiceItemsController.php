<?php

namespace App\Http\Controllers\InvoiceItems;

use App\Http\Controllers\Controller;
use App\Models\InvoiceItem;
use App\Traits\ApiResponse;
use App\Traits\CreateUserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InvoiceItemsController extends Controller
{
    use ApiResponse;
    use CreateUserActivityLog;
    public function create(Request $request)
    {
        try {
            $user_id = Auth::id();
            $invoiceItemsData = $request->all();

            // Ensure that $invoiceItemsData is an array
            if (!is_array($invoiceItemsData)) {
                return $this->error('Invalid data format. Expected an array of invoice items.', null, null, 400);
            }

            DB::beginTransaction();

            // Loop through each invoice item in the array and create a record
            foreach ($invoiceItemsData as $itemData) {
                // Add user_id and calculate total_value for each item
                $itemData["items_added_by"] = $user_id;
                $itemData["total_value"] = $itemData['quantity'] * $itemData['unit_value'];

                // Create the InvoiceItem record
                $item = InvoiceItem::create($itemData);

                // Log each item creation
                $this->createLog($user_id, "Invoice item added.", "invoiceitems", $item->id);
            }

            DB::commit();

            return $this->success("Invoice items added Successfully!", null, null, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
        }
    }




    public function show(Request $request)
    {
        try {
            $invoiceId = $request->id;

            // Retrieve all invoice items for the specified invoice_id
            $invoiceItems = InvoiceItem::where('invoice_id', $invoiceId)->get();

            if ($invoiceItems->isEmpty()) {
                return $this->error("No invoice items found for the given invoice_id.", null, null, 404);
            }

            return $this->success("Invoice items for invoice_id: $invoiceId", $invoiceItems, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), InvoiceItem::updateRule());
        if ($validator->fails()) {
            return $this->error('Oops!' . $validator->errors()->first(), null, null, 400);
        } else {
            try {
                DB::beginTransaction();
                InvoiceItem::where('id', $request->id)->update($request->all());
                $user_id = Auth::id();
                $this->createLog($user_id, "Invoice items updated.", "invoiceitems", $request->id);
                DB::commit();
                return $this->success("Consignee updated successfully.", null, null, 200);
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
            $invoice = InvoiceItem::find($request->id);
            if (!$invoice) {
                return $this->error("Invoice not found.", null, null, 404);
            }
            $invoice->delete();
            $user_id = Auth::id();
            $this->createLog($user_id, "Invoice items deleted.", "invoiceitems", $request->id);
            DB::commit();
            return $this->success("Invoice deleted successfully.", null, null, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
        }
    }
}
