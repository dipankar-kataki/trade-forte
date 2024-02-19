<?php

namespace App\Http\Controllers\BankAccounts;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Exporter;
use App\Traits\ApiResponse;
use App\Traits\CreateUserActivityLog;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BankAccountController extends Controller
{
    use ApiResponse;
    use CreateUserActivityLog;
    public function create(Request $request)
    {
        try {
            $user_id = Auth::id();
            $requestData = $request->banks;

            DB::beginTransaction();
            foreach ($requestData as $item) {
                $validator = Validator::make($item, BankAccount::createRule());
                if ($validator->fails()) {
                    return $this->error('Oops!' . $validator->errors()->first(), null, null, 400);
                }
                $data = $validator->validated();
                $data["users_id"] = $user_id;
                $bank = BankAccount::create($data);
                $this->createLog($user_id, "Bank account added.", "bankaccount", $bank->id);
            }
            DB::commit();
            return $this->success("Bank Accounts registered Successfully!", $request->banks, null, 201);
        } catch (QueryException $e) {
            DB::rollBack();
            if ($e->errorInfo[1] == 1062) {
                return $this->error("Bank account number already exists. Please provide another value", null, null, 422);
            }
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
        }
    }

    public function index(Request $request)
    {
        $accounts = BankAccount::latest()->paginate(10);
        return $this->success("Bank account list.", $accounts, null, 200);
    }

    public function show(Request $request)
    {
        try {
            $account = BankAccount::where(function ($query) use ($request) {
                $query->where('exporter_id', $request->id)
                    ->orWhere('account_no', $request->id);
            })->get();
            if (!$account) {
                return $this->error("Account not found.", null, null, 404);
            }
            return $this->success("Bank account.", $account, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $user_id = Auth::id();
            $data = $request->all();
            $bankId = $request->bank_id;
            DB::beginTransaction();
            $bank = BankAccount::where('id', $bankId)->first();
            if (!$bank) {
                return $this->error("Bank account not found.", $request->all(), null, 404);
            }
            $bank->bank_name = $data['bank_name'];
            $bank->branch_name = $data['branch_name'];
            $bank->account_name = $data['account_name'];
            $bank->account_no = $data['account_no'];
            $bank->ifsc_code = $data['ifsc_code'];
            $bank->swift_code = $data['swift_code'];
            $bank->status = $data['status'];
            $bank->auth_dealer_code = $data['auth_dealer_code'];
            $bank->save();
            $this->createLog($user_id, "Bank account updated.", "bankaccount", $bankId);
            DB::commit();
            return $this->success("Bank Account updated successfully.", null, null, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($e instanceof QueryException && $e->errorInfo[1] == 1062) {
                return $this->error("Bank account number already exists. Please provide another value", null, null, 422);
            }
            return $this->error('Oops! Something Went Wrong. ' . $e->getMessage(), null, null, 500);
        }
    }
    
    public function destroy(Request $request)
    {
        $account = BankAccount::where(function ($query) use ($request) {
            $query->where('id', $request->id)
                ->orWhere('account_no', $request->id);
        })->first();
        if (!$account) {
            return $this->error("Account not found.", null, null, 404);
        } else {
            try {
                $account = BankAccount::find($request->id);
                if (!$account) {
                    return $this->error("Bank account not found.", null, null, 404);
                }
                $user_id = Auth::id();
                DB::beginTransaction();
                $account->delete();
                $this->createLog($user_id, "Bank account deleted.", "bankaccount", $request->id);
                DB::commit();
                return $this->success("Bank account deleted successfully.", null, null, 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
            }
        }
    }
}
