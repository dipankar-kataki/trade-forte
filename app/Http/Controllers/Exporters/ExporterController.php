<?php

namespace App\Http\Controllers\Exporters;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use App\Traits\CreateUserActivityLog;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Exporter;
use Illuminate\Support\Str;

class ExporterController extends Controller
{
    use ApiResponse;
    use CreateUserActivityLog;
    public function storeOrUpdate(Request $request)
    {
        $exporterId = $request->input('exporter_id');
    
        // Validation rules for both create and update
        $validator = Validator::make($request->all(), $exporterId ? Exporter::updateRule() : Exporter::createRule());
    
        if ($validator->fails()) {
            return $this->error('Oops!' . $validator->errors()->first(), null, null, 400);
        } else {
            try {
                $user_id = Auth::id();
                $data = $validator->validated();
    
                // Check if exporterId is present to determine if it's an update or create operation
                if ($exporterId) {
                    // Update operation
                    $exporter = Exporter::find($exporterId);
                    if (!$exporter) {
                        return $this->error('Exporter not found.', null, null, 404);
                    }
    
                    $exporter->fill($request->except('exporter_id'));
                    $exporter['addresses'] = json_encode($request->addresses);
                    // Set the logo attribute with the base64-encoded logo
                    $exporter->logo = $request->input('logo');
    
                    $exporter->save();
                    $message = "Exporter updated successfully.";
                } else {
                    // Create operation
                    $data["addresses"] = json_encode($request->addresses);
                    $data["users_id"] = Auth::id();
    
                    // Set the logo attribute with the base64-encoded logo
                    $data['logo'] = $request->input('logo');
    
                    DB::beginTransaction();
                    $exporter = Exporter::create($data);
                    $this->createLog($user_id, "Exporter details added.", "exporters", $exporter->id);
                    DB::commit();
    
                    $message = "Exporter created successfully.";
                }
    
                return $this->success($message, ["exporter_id" => $exporter->id], null, 200);
            } catch (QueryException $e) {
                DB::rollBack();
                if ($e->errorInfo[1] == 1062) {
                    return $this->error("Phone number already exists. Please provide another value", null, null, 422);
                }
                return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
            }
        }
    }
    
    
    public function index(Request $request)
    {
        try {
            $exporter = Exporter::select('id', 'name', 'organization_phone', "addresses")->get();
            return $this->success("Exporter list.", $exporter, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
        }
    }

    public function show(Request $request)
    {
        try {
            // Fetch exporter along with associated invoices (sorted by latest first)
            $exporter = Exporter::with('invoices')->where(function ($query) use ($request) {
                $query->where('id', $request->id)
                    ->orWhere('organization_email', $request->id);
            })->first();
    
            if (!$exporter) {
                return $this->error("Exporter not found.", null, null, 404);
            }
    
            return $this->success("Exporter details with invoices (latest first).", $exporter, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
        }
    }
     
    // public function update(Request $request)
    // {
    //     $id = $request->exporterId;
    //     $data = array_merge(['exporterId' => $id], $request->all());
    //     $validator = Validator::make($data, Exporter::updateRule());
    //     if ($validator->fails()) {
    //         return $this->error('Oops!' . $validator->errors()->first(), null, null, 400);
    //     } else {
    //         try {
    //             $user_id = Auth::id();
    //             $exporter = Exporter::find($request->exporterId);
    //             $previousLogoPath = $exporter->logo;
    //             $exporter->fill($request->except('exporterId'));
    //             if ($request->hasFile('logo')) {
    //                 $logo = $request->file('logo');
    //                 $logoPath = $logo->store('logos');
    //                 $exporter->logo = $logoPath;
    //                 if ($previousLogoPath && Storage::disk('public')->exists($previousLogoPath)) {
    //                     Storage::disk('public')->delete($previousLogoPath);
    //                 }
    //             }
    //             DB::beginTransaction();
    //             $exporter->save();
    //             $this->createLog($user_id, "Exporter details updated.", "exporters", $request->id);
    //             DB::commit();
    //             return $this->success("Exporter updated successfully.", null, null, 200);
    //         } catch (QueryException $e) {
    //             DB::rollBack();
    //             if ($e->errorInfo[1] == 1062) {
    //                 return $this->error("Phone number already exists. Please provide another value", null, null, 422);
    //             }
    //             return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
    //         }
    //     }
    // }
    public function destroy(Request $request)
    {
        try {
            $exporter = Exporter::find($request->exporterId);
            if (!$exporter) {
                return $this->error("Exporter not found.", null, null, 404);
            }
            $user_id = Auth::id();
            DB::beginTransaction();
            $exporter->relatedModel()->delete();
            $exporter->delete()->cascade();
            $this->createLog($user_id, "Exporter detaild deleted.", "exporters", $request->id);
            DB::commit();
            return $this->success("Exporter deleted successfully.", null, null, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
        }
    }
}
