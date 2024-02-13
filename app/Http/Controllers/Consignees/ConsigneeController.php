<?php

namespace App\Http\Controllers\Consignees;

use App\Http\Controllers\Controller;
use App\Models\Consignee;
use App\Traits\ApiResponse;
use App\Traits\CreateUserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class ConsigneeController extends Controller
{
    use ApiResponse;
    use CreateUserActivityLog;
    public function storeOrUpdate(Request $request)
    {
        $consigneeId = $request->input('consignee_id');
    
        // Validation rules for both create and update
        $validator = Validator::make($request->all(), $consigneeId ? Consignee::updateRule() : Consignee::createRule());
    
        if ($validator->fails()) {
            return $this->error('Oops!' . $validator->errors()->first(), null, null, 400);
        } else {
            try {
                $user_id = Auth::id();
                $data = $validator->validated();
    
                if ($consigneeId) {
                    // Update operation
                    $consignee = Consignee::find($consigneeId);
                    if (!$consignee) {
                        return $this->error('Exporter not found.', null, null, 404);
                    }
                    $consignee->fill($request->except('exporter_id'));
                    $consignee->save();
                    $this->createLog($user_id, "Consignee details updated.", "consignees", $consignee->id);

                    $message = "Consignee updated successfully.";
                } else {
                    // Create operation
                    $data["addresses"] = json_encode($request->addresses);
                    $data["users_id"] = Auth::id();
        
                    DB::beginTransaction();
                    $consignee = Consignee::create($data);
                    $this->createLog($user_id, "Consignee details added.", "consignees", $consignee->id);
                    DB::commit();
                    $message = "Consignee created successfully.";
                }
    
                return $this->success($message, ["consignee_id" => $consignee->id], null, 200);
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
            $consigneeData = Consignee::select('id', 'name', 'organization_phone', "address")->get();
            return $this->success("Consignee list.", $consigneeData, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
        }
    }

    public function show(Request $request)
    {
        try {
            $consignee = Consignee::where(function ($query) use ($request) {
                $query->where('id', $request->id)
                    ->orWhere('name', $request->id);
            })->get();
            if (!$consignee) {
                return $this->error("Consignee not found.", null, null, 404);
            }
            return $this->success("Consignee list.", $consignee, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
        }
    }
    public function update(Request $request)
    {
        $consignee = Consignee::where('id', $request->id)->first();
        if (!$consignee) {
            return $this->error("Consignee not found.", null, null, 404);
        }
        $validator = Validator::make($request->all(), Consignee::updateRule());
        if ($validator->fails()) {
            return $this->error('Oops!' . $validator->errors()->first(), null, null, 400);
        } else {
            try {
                $user_id = Auth::id();
                DB::beginTransaction();
                Consignee::where('id', $request->id)->update($request->all());
                $this->createLog($user_id, "Consignee updated.", "consignees", $request->id);
                DB::commit();
                return $this->success("Consignee updated successfully.", null, null, 200);
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
            $consignee = Consignee::find($request->id);
            if (!$consignee) {
                return $this->error("Consignee not found.", null, null, 404);
            }
            $user_id = Auth::id();
            DB::beginTransaction();
            $consignee->delete();
            $this->createLog($user_id, "Consignee deleted.", "consignees", $request->id);
            DB::commit();
            return $this->success("Consignee deleted successfully.", null, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
        }

    }
}
