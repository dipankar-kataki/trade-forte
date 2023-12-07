<?php

namespace App\Http\Controllers\Declarations;

use App\Http\Controllers\Controller;
use App\Models\Declaration;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DeclarationController extends Controller {
    use ApiResponse;

    public function create(Request $request) {
        $validator = Validator::make($request->all(), Declaration::createRule());
        if($validator->fails()) {
            return $this->error('Oops!'.$validator->errors()->first(), null, null, 400);
        } else {
            try {
                DB::beginTransaction();
                $data = $request->all();
                Declaration::create($data);
                DB::commit();
                return $this->success("Declaration created Successfully!", null, null, 201);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
            }
        }
    }

    public function index(Request $request) {
        try {
            $exporter = Declaration::paginate(50);
            return $this->success("Declaration list.", $exporter, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
        }
    }
    public function show(Request $request) {
        try {
            $exporter = Declaration::where(function ($query) use ($request) {
                $query->where('id', $request->id)
                    ->orWhere('email', $request->id);
            })->first();
            if(!$exporter) {
                return $this->error("Declaration not found.", null, null, 404);
            }
            return $this->success("Declaration info.", $exporter, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
        }
    }
    public function update(Request $request) {
        $validator = Validator::make($request->all(), Declaration::updateRule());
        if($validator->fails()) {
            return $this->error('Oops!'.$validator->errors()->first(), null, null, 400);
        } else {
            try {
                DB::beginTransaction();
                Declaration::where('id', $request->id)->update($request->all());
                DB::commit();
                return $this->success("Declaration updated successfully.", null, null, 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
            }
        }
    }
    public function delete(Request $request) {

        try {
            DB::beginTransaction();
            $exporter = Declaration::find($request->exporterId);
            if(!$exporter) {
                return $this->error("Declaration not found.", null, null, 404);
            }
            $exporter->delete();
            DB::commit();
            return $this->success("Declaration deleted successfully.", null, null, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
        }

    }
}
