<?php

namespace App\Http\Controllers\ShippingAddress;

use App\Http\Controllers\Controller;
use App\Models\ShippingAddress;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ShippingAddressController extends Controller {
    use ApiResponse;
    public function create(Request $request) {
        $validator = Validator::make($request->all(), ShippingAddress::createRule());
        if($validator->fails()) {
            return response()->json([
                "message" => 'Oops!'.$validator->errors()->first(),
                "status" => 400
            ]);
        } else {
            try {
                $data = $request->all();
                $data["details_created_by"] = Auth::id();
                DB::beginTransaction();
                ShippingAddress::create($data);
                DB::commit();
                return $this->success("Shipping details created Successfully!", null, null, 201);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
            }
        }
    }



    public function index(Request $request) {
        try {
            $shippingList = ShippingAddress::paginate(50);
            return $this->success("Shipping List.", $shippingList, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
        }
    }

    public function show(Request $request) {
        try {
            $shipping = ShippingAddress::where(function ($query) use ($request) {
                $query->where('id', $request->id)
                    ->orWhere('consignee_id', $request->id);
            })->get();
            if(!$shipping) {
                return $this->error("Shipping details not found.", null, null, 404);
            }
            return $this->success("Shipping info.", $shipping, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
        }
    }
    public function update(Request $request) {
        $validator = Validator::make($request->all(), ShippingAddress::updateRule());
        if($validator->fails()) {
            return $this->error('Oops!'.$validator->errors()->first(), null, null, 400);
        } else {
            try {
                DB::beginTransaction();
                ShippingAddress::where('id', $request->id)->update($request->all());
                DB::commit();
                return $this->success("Shipping updated successfully.", null, null, 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
            }
        }
    }
    public function destroy(Request $request) {
        try {
            DB::beginTransaction();
            $shipping = ShippingAddress::find($request->id);
            if(!$shipping) {
                return $this->error("Shipping not found.", null, null, 404);
            }
            $shipping->delete();
            DB::commit();
            return $this->success("Shipping deleted successfully.", null, null, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
        }

    }
}
