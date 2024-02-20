<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Models\Declaration;
use App\Models\InvoiceDetail;
use App\Models\InvoiceItem;
use App\Models\Payments;
use App\Models\Transportation;
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
        $invoiceValidator = Validator::make($request->invoice, InvoiceDetail::createFirstRule());
        if ($invoiceValidator->fails()) {
            return $this->error('Oops!' . $invoiceValidator->errors()->first(), null, null, 400);
        }

        $paymentsValidator = Validator::make($request->payment, InvoiceDetail::createSecondRule());
        if ($paymentsValidator->fails()) {
            return $this->error('Oops!' . $paymentsValidator->errors()->first(), null, null, 400);
        }

        $transportValidator = Validator::make($request->transport, InvoiceDetail::createThirdRule());
        if ($transportValidator->fails()) {
            return $this->error('Oops!' . $transportValidator->errors()->first(), null, null, 400);
        }

        $invoiceItemsData = $request->items;

        if (!is_array($invoiceItemsData)) {
            return $this->error('Invalid data format. Expected an array of invoice items.', null, null, 400);
        }

        $declaration = $request->declaration;
        if (!is_array($declaration)) {
            return $this->error('Invalid data format. Expected an array of declaration.', null, null, 400);
        }
        try {
            $dataInvoice = $invoiceValidator->validated();
            $user_id = Auth::id();
            $dataInvoice["users_id"] = $user_id;
            $counter = InvoiceDetail::count() +1;
            $dataInvoice["invoice_number"] = 'INV-' .  $counter;
            $dataInvoice["invoice_date"] = Carbon::parse($dataInvoice['invoice_date']);
            $dataInvoice["po_contract_date"] = Carbon::parse($dataInvoice['po_contract_date']);

            $dataPayments = $paymentsValidator->validated();
            $dataPayments["users_id"] = $user_id;


            $dataTransport = $transportValidator->validated();
            $dataTransport["users_id"] = $user_id;
            $dataTransport["bl_awb_lr_date"] = Carbon::parse($dataTransport['bl_awb_lr_date']);
            $dataTransport["challan_date"] = Carbon::parse($dataTransport['challan_date']);
            $dataTransport["eway_bill_date"] = Carbon::parse($dataTransport['eway_bill_date']);

            $invoice_value = 0;
            $total_net_weight = 0;

            DB::beginTransaction();
            $invoice = InvoiceDetail::create($dataInvoice);
            $this->createLog($user_id, "Invoice details added.", "invoice", $request->id);
            $invoice_details_id =  $invoice->id;

            $dataPayments["invoice_details_id"] = $invoice_details_id;
            $payments = Payments::create($dataPayments);
            $this->createLog($user_id, "Payments details added.", "payments", $payments->id);

            $dataTransport["invoice_details_id"] = $invoice_details_id;
            $tranport = Transportation::create($dataTransport);
            $this->createLog($user_id, "Tranportation details added.", "transportation", $tranport->id);

            foreach ($invoiceItemsData as $itemData) {
                // Add user_id and calculate total_value for each item
                $itemData["invoice_details_id"] = $invoice_details_id;
                $itemData["users_id"] = $user_id;
                $itemData["net_value"] = $itemData["net_weight_of_each_unit"] * $itemData["quantity"];
                $itemData["net_weight"] = $itemData["unit_value"] * $itemData["quantity"];
                $invoice_value += $itemData["net_value"];
                $total_net_weight +=  $itemData["net_weight"];
                // Create the InvoiceItem record
                $item = InvoiceItem::create($itemData);
                // Log each item creation
                $this->createLog($user_id, "Invoice item added.", "invoice_items", $item->id);
            }

            $dataDeclaration["invoice_details_id"] = $invoice_details_id;
            $dataDeclaration["declaration"] = json_encode($request->declaration->declaration);
            $declaration = Declaration::create($dataDeclaration);
            $this->createLog($user_id, "Declaration details added.", "declarations", $declaration->id);

            $invoice->invoice_value = $invoice_value;
            $invoice->total_net_weight = $total_net_weight;
            
            DB::commit();
            return $this->success("Invoice created Successfully!", $invoice->id, null, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
        }
    }

    public function index(Request $request)
    {
        try {
            $invoices = InvoiceDetail::select(
                'id',
                'invoice_id',
                'exporter_id',
                "category",
                "invoice_date",
                "port_of_loading",
                "port_of_destination",
                'consignee_id',
                'created_at',
            )->with([
                        'exporters:id,name',
                        'consignees:id,name',
                    ])->paginate(10);

            return $this->success("Invoice list.", $invoices, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
        }
    }
    public function show(Request $request)
    {
        try {
            $invoice = InvoiceDetail::with(['exporters', 'items', 'consignees', 'payments', 'transport', 'declarations'])
                ->where('invoice_id', $request->id)
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
