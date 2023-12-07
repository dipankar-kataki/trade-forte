<?php

namespace App\Http\Controllers\Consignees;

use App\Http\Controllers\Controller;
use App\Models\Consignee;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ConsigneeController extends Controller {
    use ApiResponse;

    public function create(Request $request) {
        $validator = Validator::make($request->all(), Consignee::createRule());
        if($validator->fails()) {
            return $this->error('Oops!'.$validator->errors()->first(), null, null, 400);
        } else {
            try {
                DB::beginTransaction();
                $data = $validator->validated();
                $data["account_created_by"] = Auth::id();
                Consignee::create($data);
                DB::commit();
                return $this->success("Consignee created Successfully!", null, null, 201);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
            }
        }
    }



    public function index(Request $request) {
        try {
            $exporter = Consignee::paginate(50);
            return $this->success("Consignee list.", $exporter, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
        }
    }


    public function show(Request $request) {
        try {
            $consignee = Consignee::where(function ($query) use ($request) {
                $query->where('id', $request->id)
                    ->orWhere('name', $request->id);
            })->get();
            if(!$consignee) {
                return $this->error("Consignee not found.", null, null, 404);
            }
            return $this->success("Consignee list.", $consignee, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
        }
    }
    public function update(Request $request) {
        $consignee = Consignee::where('id', $request->id)->first();
        if(!$consignee) {
            return $this->error("Consignee not found.", null, null, 404);
        }
        $validator = Validator::make($request->all(), Consignee::updateRule());
        if($validator->fails()) {
            return $this->error('Oops!'.$validator->errors()->first(), null, null, 400);
        } else {
            try {
                dump($request->all());
                DB::beginTransaction();
                Consignee::where('id', $request->id)->update($request->all());
                DB::commit();
                return $this->success("Consignee updated successfully.", null, null, 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
            }
        }
    }
    public function destroy(Request $request) {

        try {
            DB::beginTransaction();
            $consignee = Consignee::find($request->id);
            if(!$consignee) {
                return $this->error("Consignee not found.", null, null, 404);
            }
            $consignee->delete();
            DB::commit();
            return $this->success("Consignee deleted successfully.", null, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
        }

    }
}
