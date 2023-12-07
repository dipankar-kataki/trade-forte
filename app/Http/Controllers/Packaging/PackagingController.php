<?php

namespace App\Http\Controllers\Packaging;

use App\Http\Controllers\Controller;
use App\Models\PackagingDetail;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PackagingController extends Controller {
    use ApiResponse;

    public function create(Request $request) {
        // dump($request->all());
        $validator = Validator::make($request->all(), PackagingDetail::createRule());
        if($validator->fails()) {
            return $this->error('Oops!'.$validator->errors()->first(), null, null, 400);
        } else {
            try {
                $data = $request->all();
                $data["details_added_by"] = Auth::id();
                $data["total_gross_weight"] = $request->input("quantity") * $request->input("each_box_weight");
                // dump($data);
                DB::beginTransaction();
                PackagingDetail::create($data);
                DB::commit();
                return $this->success("Exporter created Successfully!", null, null, 201);
            } catch (\Exception $e) {
                DB::rollback();
                return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
            }
        }
    }
    public function index(Request $request) {
        try {
            $exporter = PackagingDetail::paginate(50);
            return $this->success("Exporter list.", $exporter, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
        }
    }
    public function show(Request $request) {
        try {
            $packaging = PackagingDetail::where('id', $request->id)->first();
            if(!$packaging) {
                return $this->error("Packaging not found.", null, null, 404);
            }
            return $this->success("Packaging Info.", $packaging, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
        }
    }
    public function update(Request $request) {
        $validator = Validator::make($request->all(), PackagingDetail::updateRule());
        if($validator->fails()) {
            return $this->error('Oops!'.$validator->errors()->first(), null, null, 400);
        } else {
            try {
                DB::beginTransaction();
                PackagingDetail::where('id', $request->id)->update($request->all());
                DB::commit();
                return $this->success("Packaging updated successfully.", null, null, 200);
            } catch (\Exception $e) {
                DB::rollback();
                return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
            }
        }
    }
    public function delete(Request $request) {
        try {
            DB::beginTransaction();
            $Packaging = PackagingDetail::find($request->exporterId);
            if(!$Packaging) {
                return $this->error("Packaging not found.", null, null, 404);
            }
            $Packaging->delete();
            DB::commit();
            return $this->success("Packaging deleted successfully.", null, null, 200);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
        }
    }

}
