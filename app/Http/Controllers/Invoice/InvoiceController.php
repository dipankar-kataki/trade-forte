<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Models\InvoiceDetail;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller {
    use ApiResponse;

    public function create(Request $request) {
        $validator = Validator::make($request->all(), InvoiceDetail::createRule());
        if($validator->fails()) {
            return $this->error('Oops!'.$validator->errors()->first(), null, null, 400);
        } else {
            try {
                DB::beginTransaction();
                $data = $request->all();
                $data["details_added_by"] = Auth::id();
                $data["invoice_id"] = Date::now();
                InvoiceDetail::create($data);
                DB::commit();
                return $this->success("Invoice created Successfully!", null, null, 201);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
            }
        }
    }



    public function index(Request $request) {
        try {
            $invoice = InvoiceDetail::paginate(50);
            return $this->success("Invoice list.", $invoice, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
        }
    }


    public function show(Request $request) {
        try {
            $invoice = InvoiceDetail::where(function ($query) use ($request) {
                $query->where('id', $request->id)
                    ->orWhere('invoice_id', $request->id);
            })->first();
            if(!$invoice) {
                return $this->error("Invoice not found.", null, null, 404);
            }
            return $this->success("Invoice list.", $invoice, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
        }
    }
    public function update(Request $request) {
        $validator = Validator::make($request->all(), InvoiceDetail::updateRule());
        if($validator->fails()) {
            return $this->error('Oops!'.$validator->errors()->first(), null, null, 400);
        } else {
            try {
                DB::beginTransaction();
                InvoiceDetail::where('id', $request->id)->update($request->all());
                DB::commit();
                return $this->success("Invoice updated successfully.", null, null, 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
            }
        }
    }
    public function delete(Request $request) {

        try {
            DB::beginTransaction();
            $invoice = InvoiceDetail::find($request->id);
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
