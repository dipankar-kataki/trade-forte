<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Models\InvoiceDetail;
use App\Traits\ApiResponse;
use App\Traits\CreateUserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    use ApiResponse;
    use CreateUserActivityLog;

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), InvoiceDetail::createRule());
        if ($validator->fails()) {
            return $this->error('Oops!' . $validator->errors()->first(), null, null, 400);
        } else {
            try {
                $data = $request->all();
                $user_id = Auth::id();
                $data["details_added_by"] = $user_id;
                $uuid = Str::uuid()->toString();
                $uniqueIdentifier = substr($uuid, -8);
                $data["invoice_id"] = 'INV-' . now()->format('dmy') . '-' . $uniqueIdentifier;

                DB::beginTransaction();
                $invoice = InvoiceDetail::create($data);
                $this->createLog($user_id, "Invoice details added.", "invoice", $request->id);
                DB::commit();
                return $this->success("Invoice created Successfully!", $invoice->id, null, 201);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
            }
        }
    }

    public function index(Request $request)
    {
        try {
            $invoices = InvoiceDetail::select(
                'id',
                'created_at',
                'updated_at'
            )->with([
                        'exporters:id,name',
                        'consignees:id,name',
                    ])->paginate(50);

            return $this->success("Invoice list.", $invoices, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
        }
    }



    public function show(Request $request)
    {
        try {
            $invoice = InvoiceDetail::with('items', 'declarations')->where(function ($query) use ($request) {
                $query->where('id', $request->id)
                    ->orWhere('invoice_id', $request->id);
            })->get();
            if (!$invoice) {
                return $this->error("Invoice not found.", null, null, 404);
            }
            return $this->success("Invoice list.", $invoice, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
        }
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), InvoiceDetail::updateRule());
        if ($validator->fails()) {
            return $this->error('Oops!' . $validator->errors()->first(), null, null, 400);
        } else {
            try {
                $user_id = Auth::id();
                DB::beginTransaction();
                InvoiceDetail::where('id', $request->id)->update($request->all());
                $this->createLog($user_id, "Invoice details updated.", "invoice", $request->id);
                DB::commit();
                return $this->success("Invoice updated successfully.", null, null, 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
            }
        }
    }
    public function delete(Request $request)
    {

        try {
            $invoice = InvoiceDetail::find($request->id);
            if (!$invoice) {
                return $this->error("Invoice not found.", null, null, 404);
            }
            $user_id = Auth::id();
            DB::beginTransaction();
            $invoice->delete();
            $this->createLog($user_id, "Invoice deleted.", "invoice", $request->id);
            DB::commit();
            return $this->success("Invoice deleted successfully.", null, null, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
        }

    }
}
