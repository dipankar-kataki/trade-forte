<?php

namespace App\Http\Controllers\Packaging;

use App\Http\Controllers\Controller;
use App\Models\InvoiceDetail;
use App\Models\InvoiceItem;
use App\Models\PackagingDetail;
use App\Models\PackagingItems;
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
    private function getExporterInitials($exporterName)
    {
        $words = explode(' ', $exporterName);
        $initials = '';
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        return $initials;
    }

    private function getCurrentFinancialYear()
    {
        $currentYear = date('Y');
        $currentMonth = date('n');

        $financialYearStartMonth = 4;

        if ($currentMonth >= $financialYearStartMonth) {
            $startYear = $currentYear;
        } else {
            $startYear = $currentYear - 1;
        }
        $endYear = ($startYear % 100) + 1;

        $financialYear = $startYear . '-' . str_pad($endYear, 2, '0', STR_PAD_LEFT);

        return $financialYear;
    }


    public function create(Request $request)
    {
        try {
            $user_id = Auth::id();
            $packagingDetailsData = $request->final_packaging_list;
            $packagingListData = $request->packaging_list;
            $packagingDetails = $request->packaging_details;

            if (!is_array($packagingDetailsData) || !is_array($packagingListData) || !is_array($packagingDetails)) {
                return $this->error('Invalid data format. Expected arrays of packaging details, packaging list, and packaging details.', null, null, 400);
            }
            $packagingDetails['users_id'] = $user_id;
            DB::beginTransaction();

            // Create packaging details
            $packaging = PackagingDetail::create($packagingDetails);

            // Update invoice details with additional information
            // dd($packagingDetails);
            $invoice = InvoiceDetail::where("id", $request->packaging_details["invoice_details_id"])->first();
            $invoice->with_letter_head = $request->packaging_details['with_letter_head'];
            $invoice->save();
            $invoice->lazy;

            // Calculate reference number
            $exporterInitials = $this->getExporterInitials($invoice->exporters->name); // Add your logic to get exporter initials
            $currentFinancialYear = $this->getCurrentFinancialYear(); // Add your logic to get current financial year
            $reference_no = $exporterInitials . "/EXIM/" . $currentFinancialYear . "/" . $packaging->id;

            // Create packaging details
            $packagingDetails["reference_no"] = $reference_no;
            $packagingDetails["eway_bill_no"] = $invoice->transportation->eway_bill_no;
            $packaging = PackagingDetail::create($packagingDetails);

            // Update total values
            $total_gross_weight_in_kgs = 0;
            $total_packages = 0;
            $total_net_weight_in_kgs = 0;

            foreach ($packagingDetailsData as $packagingData) {
                $packagingData["users_id"] = $user_id;
                $packagingData["packaging_id"] = $packaging->id;

                $packagingData["total_gross_weight"] = floatval($packagingData['quantity']) * floatval($packagingData['each_box_weight']);

                $packagingItems = PackagingItems::create($packagingData);

                // Update total values
                $total_gross_weight_in_kgs += floatval($packagingData["total_gross_weight"]);
                $total_packages += intval($packagingData['quantity']);
                $total_net_weight_in_kgs += floatval($packagingData['net_weight']);
            }

            // Update packaging details with calculated values
            $packaging->total_gross_weight = $total_gross_weight_in_kgs;
            $packaging->total_packages = $total_packages;
            $packaging->net_weight_in_kgs = $total_net_weight_in_kgs;
            $packaging->save();

            // Update packaging list descriptions in each invoice item
            // Update packaging list descriptions in each invoice item
            foreach ($packagingListData as $packagingListItem) {
                // dd($packagingListItem);
                $invoiceItem = InvoiceItem::where('id', $packagingListItem['invoice_item_id'])->first();

                // Update the correct property name here
                $invoiceItem->packaging_description = $packagingListItem['packaging_description'];
                $invoiceItem->save();
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

            // Use find to retrieve a single record by its primary key
            $invoice = PackagingDetail::with(["packaging_items", "invoice", "invoice.items", "invoice.exporters", "invoice.consignees"])
                ->find($invoiceId);

            // Check if the record exists
            if (!$invoice) {
                return $this->error('Packaging detail not found.', null, null, 404);
            }

            return $this->success("Packaging Info.", $invoice, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something went wrong. ' . $e->getMessage(), null, null, 500);
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
