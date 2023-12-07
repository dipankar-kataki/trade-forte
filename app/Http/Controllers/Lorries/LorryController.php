<?php

namespace App\Http\Controllers\Lorries;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Lorry;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LorryController extends Controller {
    use ApiResponse;
    public function create(Request $request) {
        $validator = Validator::make($request->all(), Lorry::createRule());
        if($validator->fails()) {
            return $this->error('Oops!'.$validator->errors()->first(), null, null, 400);
        } else {
            try {
                DB::beginTransaction();
                $data = $validator->validated();
                Lorry::create($data);
                DB::commit();
                return $this->success("Lorry details registered Successfully!", null, null, 201);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
            }
        }
    }
    public function index(Request $request) {
        $lorry = Lorry::latest()->paginate(10);
        return $this->success("Lorry list.", $lorry, null, 200);
    }

    public function show(Request $request) {
        try {
            $lorry = Lorry::where(function ($query) use ($request) {
                $query->where('id', $request->id)
                    ->orWhere('invoice_id', $request->id);
            })->get();
            if(!$lorry) {
                return $this->error("Lorry not found.", null, null, 404);
            }
            return $this->success("Lorry info.", $lorry, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
        }
    }

    public function update(Request $request) {
        $lorry = Lorry::where(function ($query) use ($request) {
            $query->where('id', $request->id)
                ->orWhere('account_no', $request->id);
        })->first();
        if(!$lorry) {
            return $this->error("Lorry not found.", null, null, 404);
        }
        $validator = Validator::make($request->all(), BankAccount::updateRule());
        if($validator->fails()) {
            return $this->error('Oops!'.$validator->errors()->first(), null, null, 400);
        } else {
            try {
                DB::beginTransaction();
                Lorry::where('id', $request->id)->update($request->all());
                DB::commit();
                return $this->success("Lorry updated successfully.", null, null, 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
            }
        }
    }

    public function delete(Request $request) {
        try {
            DB::beginTransaction();
            $lorry = BankAccount::find($request->id);
            if(!$lorry) {
                return $this->error("Lorry not found.", null, null, 404);
            }
            $lorry->delete();
            DB::commit();
            return $this->success("Lorry deleted successfully.", null, null, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
        }
    }

}
