<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ModuleController extends Controller {
    use ApiResponse;

    public function create(Request $request) {
        $validator = Validator::make($request->all(), Module::createRule());
        if($validator->fails()) {
            return $this->error('Oops!'.$validator->errors()->first(), null, null, 400);
        } else {
            try {
                DB::beginTransaction();
                $data = $request->all();
                Module::create($data);
                DB::commit();
                return $this->success("Module created Successfully!", null, null, 201);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
            }
        }
    }

    public function index(Request $request) {
        try {
            $module = Module::paginate(50);
            return $this->success("Module list.", $module, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
        }
    }


    public function show(Request $request) {
        try {
            $module = Module::where('id', $request->id)->first();
            if(!$module) {
                return $this->error("Module not found.", null, null, 404);
            }
            return $this->success("Module list.", $module, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
        }
    }
    public function update(Request $request) {
        $validator = Validator::make($request->all(), Module::updateRule());
        if($validator->fails()) {
            return $this->error('Oops!'.$validator->errors()->first(), null, null, 400);
        } else {
            try {
                DB::beginTransaction();
                Module::where('id', $request->id)->update($request->all());
                DB::commit();
                return $this->success("Module updated successfully.", null, null, 200);
            } catch (\Exception $e) {
                DB::rollback();
                return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
            }
        }
    }
    public function delete(Request $request) {

        try {
            $module = Module::find($request->moduleId);
            if(!$module) {
                return $this->error("Module not found.", null, null, 404);
            }
            DB::beginTransaction();
            $module->delete();
            DB::commit();
            return $this->success("Module deleted successfully.", null, null, 200);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
        }

    }
}
