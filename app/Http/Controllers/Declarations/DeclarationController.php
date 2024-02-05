<?php

namespace App\Http\Controllers\Declarations;

use App\Http\Controllers\Controller;
use App\Models\Declaration;
use App\Traits\ApiResponse;
use App\Traits\CreateUserActivityLog;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DeclarationController extends Controller
{
    use ApiResponse;
    use CreateUserActivityLog;
    public function create(Request $request)
    {
        $user_id = Auth::id();
        $declarations = $request->declarations;

        // Ensure that $invoiceItemsData is an array
        if (!is_array($declarations)) {
            return $this->error('Invalid data format. Expected an array of declarations.', null, null, 400);
        } else {
            try {
                $data["users_id"] = $user_id;

                DB::beginTransaction();
                // Check if payment already exists
                $declaration = Declaration::where("invoice_details_id", $request->invoice_details_id)->first();
                if (!$declaration) {
                    // Create a new payment if it doesn't exist
                    $data["invoice_details_id"] = $user_id;

                    $declaration = Declaration::create($data);
                    $this->createLog($user_id, "Declaration details added.", "declarationS", $declaration->id);
                } else {
                    // Update existing payment
                    $declaration->declarations = $request->declarations;
                    $declaration->save();
                    $this->createLog($user_id, "Tranportation details edited.", "payments", $declaration->id);
                }
                DB::commit();
                return $this->success("Transportation details added Successfully!", null, null, 201);
            } catch (QueryException $e) {
                DB::rollBack();
                return $this->error('Oops! Something Went Wrong. ' . $e->getMessage(), null, null, 500);
            }
        }
    }

    public function index(Request $request)
    {
        try {
            $exporter = Declaration::paginate(50);
            return $this->success("Declaration list.", $exporter, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
        }
    }
    public function show(Request $request)
    {
        try {
            $exporter = Declaration::where('id', $request->id)->first();
            if (!$exporter) {
                return $this->error("Declaration not found.", null, null, 404);
            }
            return $this->success("Declaration info.", $exporter, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
        }
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), Declaration::updateRule());
        if ($validator->fails()) {
            return $this->error('Oops!' . $validator->errors()->first(), null, null, 400);
        } else {
            try {
                $user_id = Auth::id();
                DB::beginTransaction();
                Declaration::where('id', $request->id)->update($request->all());
                $this->createLog($user_id, "Declaration updated.", "declarations", $request->id);
                DB::commit();
                return $this->success("Declaration updated successfully.", null, null, 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
            }
        }
    }
    public function delete(Request $request)
    {
        try {
            $user_id = Auth::id();
            $exporter = Declaration::find($request->exporterId);
            if (!$exporter) {
                return $this->error("Declaration not found.", null, null, 404);
            }
            DB::beginTransaction();
            $exporter->delete();
            $this->createLog($user_id, "Declaration deleted.", "declarations", $request->id);
            DB::commit();
            return $this->success("Declaration deleted successfully.", null, null, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
        }
    }
}
