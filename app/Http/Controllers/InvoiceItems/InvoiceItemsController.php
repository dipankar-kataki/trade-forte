<?php

namespace App\Http\Controllers\InvoiceItems;

use App\Http\Controllers\Controller;
use App\Models\InvoiceItem;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InvoiceItemsController extends Controller {
    use ApiResponse;

    public function create(Request $request) {
        $validator = Validator::make($request->all(), InvoiceItem::createRule());
        if($validator->fails()) {
            return $this->error('Oops!'.$validator->errors()->first(), null, null, 400);
        } else {
            try {
                $data = $request->all();
                $data["items_added_by"] = Auth::id();
                $data["total_value"] = $request->quantity * $request->unit_value;
                // dump($request->quantity * $request->unit_value);
                DB::beginTransaction();
                InvoiceItem::create($data);
                DB::commit();
                return $this->success("Invoice item added Successfully!", null, null, 201);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
            }
        }
    }



    public function index(Request $request) {
        try {
            $invoice = InvoiceItem::paginate(50);
            return $this->success("Invoice item list.", $invoice, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
        }
    }


    public function show(Request $request) {
        try {
            $invoice = InvoiceItem::where(function ($query) use ($request) {
                $query->where('id', $request->id)
                    ->orWhere('email', $request->id);
            })->first();
            if(!$invoice) {
                return $this->error("Invoice not found.", null, null, 404);
            }
            return $this->success("Invoice info.", $invoice, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
        }
    }
    // public function update(Request $request) {
    //     $validator = Validator::make($request->all(), InvoiceItem::updateRule());
    //     if($validator->fails()) {
    //         return $this->error('Oops!'.$validator->errors()->first(), null, null, 400);
    //     } else {
    //         try {
    //             DB::beginTransaction();
    //             InvoiceItem::where('id', $request->id)->update($request->all());
    //             DB::commit();
    //             return $this->success("Consignee updated successfully.", null, null, 200);
    //         } catch (\Exception $e) {
    //             DB::rollBack();
    //             return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
    //         }
    //     }
    // }
    public function delete(Request $request) {

        try {
            DB::beginTransaction();
            $invoice = InvoiceItem::find($request->id);
            if(!$invoice) {
                return $this->error("Invoice not found.", null, null, 404);
            }
            $invoice->delete();
            DB::commit();
            return $this->success("Invoice deleted successfully.", null, null, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
        }

    }
}
