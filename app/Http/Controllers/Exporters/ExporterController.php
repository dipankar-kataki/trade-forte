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

class ExporterController extends Controller
{
    use ApiResponse;
    use CreateUserActivityLog;
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), Exporter::createRule());
        if ($validator->fails()) {
            return $this->error('Oops!' . $validator->errors()->first(), null, null, 400);
        } else {
            try {
                $data = $validator->validated();
                $data["logo"] = $request->file("logo")->store("logos");
                $user_id = Auth::id();
                $data["addresses"] = json_decode($request->addresses);
                $data["account_created_by"] = Auth::id();
                DB::beginTransaction();
                Exporter::create($data);
                $this->createLog($user_id, "Exporter details added.", "exporters", $request->id);
                DB::commit();
                return $this->success("Exporter created Successfully!", null, null, 201);
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
            $exporter = Exporter::select('id', 'name', 'phone', "address")->get();
            return $this->success("Exporter list.", $exporter, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
        }
    }


    public function show(Request $request)
    {
        try {
            $exporter = Exporter::where(function ($query) use ($request) {
                $query->where('id', $request->id)
                    ->orWhere('email', $request->id);
            })->get();
            if (!$exporter) {
                return $this->error("Exporter not found.", null, null, 404);
            }
            return $this->success("Exporter list.", $exporter, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
        }
    }
    public function update(Request $request)
    {
        $id = $request->exporterId;
        $data = array_merge(['exporterId' => $id], $request->all());
        $validator = Validator::make($data, Exporter::updateRule());
        if ($validator->fails()) {
            return $this->error('Oops!' . $validator->errors()->first(), null, null, 400);
        } else {
            try {
                $user_id = Auth::id();
                $exporter = Exporter::find($request->exporterId);
                $previousLogoPath = $exporter->logo;
                $exporter->fill($request->except('exporterId'));
                if ($request->hasFile('logo')) {
                    $logo = $request->file('logo');
                    $logoPath = $logo->store('logos');
                    $exporter->logo = $logoPath;
                    if ($previousLogoPath && Storage::disk('public')->exists($previousLogoPath)) {
                        Storage::disk('public')->delete($previousLogoPath);
                    }
                }
                DB::beginTransaction();
                $exporter->save();
                $this->createLog($user_id, "Exporter details updated.", "exporters", $request->id);
                DB::commit();
                return $this->success("Exporter updated successfully.", null, null, 200);
            } catch (QueryException $e) {
                DB::rollBack();
                if ($e->errorInfo[1] == 1062) {
                    return $this->error("Phone number already exists. Please provide another value", null, null, 422);
                }
                return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
            }
        }
    }
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
