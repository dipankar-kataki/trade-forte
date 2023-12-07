<?php

namespace App\Http\Controllers\Exporters;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Exporter;

class ExporterController extends Controller {
    use ApiResponse;

    public function create(Request $request) {
        $validator = Validator::make($request->all(), Exporter::createRule());
        if($validator->fails()) {
            return $this->error('Oops!'.$validator->errors()->first(), null, null, 400);
        } else {
            try {
                DB::beginTransaction();
                $data = $validator->validated();
                $data["logo"] = $request->logo->store("logos");
                $data["account_created_by"] = Auth::id();
                Exporter::create($data);
                DB::commit();
                return $this->success("Exporter created Successfully!", null, null, 201);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
            }
        }
    }

    public function index(Request $request) {
        try {
            $exporter = Exporter::paginate(50);
            return $this->success("Exporter list.", $exporter, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
        }
    }

    public function show(Request $request) {
        try {
            $exporter = Exporter::where(function ($query) use ($request) {
                $query->where('id', $request->id)
                    ->orWhere('email', $request->id);
            })->get();
            if(!$exporter) {
                return $this->error("Exporter not found.", null, null, 404);
            }
            return $this->success("Exporter list.", $exporter, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
        }
    }
    public function update(Request $request) {
        $id = $request->exporterId;
        $data = array_merge(['exporterId' => $id], $request->all());
        $validator = Validator::make($data, Exporter::updateRule());
        if($validator->fails()) {
            return $this->error('Oops!'.$validator->errors()->first(), null, null, 400);
        } else {
            try {
                DB::beginTransaction();
                $exporter = Exporter::find($request->exporterId);
                $previousLogoPath = $exporter->logo;
                $exporter->fill($request->except('exporterId'));
                if($request->hasFile('logo')) {
                    $logo = $request->file('logo');
                    $logoPath = $logo->store('logos');
                    $exporter->logo = $logoPath;
                    if($previousLogoPath && Storage::disk('public')->exists($previousLogoPath)) {
                        Storage::disk('public')->delete($previousLogoPath);
                    }
                }
                $exporter->save();
                DB::commit();
                return $this->success("Exporter updated successfully.", null, null, 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
            }
        }
    }
    public function destroy(Request $request) {
        try {
            DB::beginTransaction();
            $exporter = Exporter::find($request->exporterId);
            if(!$exporter) {
                return $this->error("Exporter not found.", null, null, 404);
            }
            $exporter->delete();
            DB::commit();
            return $this->success("Exporter deleted successfully.", null, null, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
        }
    }
}
