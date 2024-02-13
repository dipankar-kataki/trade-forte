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
    public function storeOrUpdate(Request $request)
    {
        $bankAccountId = $request->input('bank_account_id');

        // Validation rules for both create and update
        $validator = Validator::make($request->all(), $bankAccountId ? BankAccount::updateRule() : BankAccount::createRule());

        if ($validator->fails()) {
            return $this->error('Oops! ' . $validator->errors()->first(), null, null, 400);
        } else {
            try {
                $user_id = Auth::id();
                $data = $validator->validated();
                $data['users_id'] = $user_id;
                if ($bankAccountId) {
                    // Update operation
                    $bankAccount = BankAccount::find($bankAccountId);
                    if (!$bankAccount) {
                        return $this->error('Bank account not found.', null, null, 404);
                    }
                    $bankAccount->fill($data);
                    $bankAccount->save();
                    $this->createLog($user_id, "Bank account details updated.", "bank_accounts", $bankAccount->id);

                    $message = "Bank account updated successfully.";
                } else {
                    // Create operation

                    DB::beginTransaction();
                    $bankAccount = BankAccount::create($data);
                    $this->createLog($user_id, "Bank account details added.", "bank_accounts", $bankAccount->id);
                    DB::commit();
                    $message = "Bank account created successfully.";
                }

                return $this->success($message, ["bank_account_id" => $bankAccountId], null, 200);
            } catch (QueryException $e) {
                if (DB::transactionLevel() > 0) {
                    DB::rollBack();
                }

                if ($e->errorInfo[1] == 1062) {
                    return $this->error("Account number already exists. Please provide another value", null, null, 422);
                }

                return $this->error('Oops! Something Went Wrong. ' . $e->getMessage(), null, null, 500);
            }
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
        $account = BankAccount::where(function ($query) use ($request) {
            $query->where('id', $request->id)
                ->orWhere('account_no', $request->id);
        })->first();
        if (!$account) {
            return $this->error("Account not found.", null, null, 404);
        }
        $validator = Validator::make($request->all(), BankAccount::updateRule());
        if ($validator->fails()) {
            return $this->error('Oops!' . $validator->errors()->first(), null, null, 400);
        } else {
            try {
                $user_id = Auth::id();
                DB::beginTransaction();
                BankAccount::where('id', $request->bankAccountId)->update($request->all());
                $this->createLog($user_id, "Bank account updated.", "bankaccount", $request->id);
                DB::commit();
                return $this->success("Bank Account updated successfully.", null, null, 200);
            } catch (QueryException $e) {
                DB::rollBack();
                if ($e->errorInfo[1] == 1062) {
                    return $this->error("Bank account number already exists. Please provide another value", null, null, 422);
                }
                return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
            }
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
