<?php

namespace App\Http\Controllers\BankAccounts;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BankAccountController extends Controller {
    use ApiResponse;
    public function create(Request $request) {
        $validator = Validator::make($request->all(), BankAccount::createRule());
        if($validator->fails()) {
            return $this->error('Oops!'.$validator->errors()->first(), null, null, 400);
        } else {
            try {
                DB::beginTransaction();
                $data = $validator->validated();
                $data["account_created_by"] = Auth::id();
                BankAccount::create($data);
                DB::commit();
                return $this->success("Bank Account registered Successfully!", null, null, 201);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
            }
        }
    }
    public function index(Request $request) {
        $accounts = BankAccount::latest()->paginate(10);
        return $this->success("Bank account list.", $accounts, null, 200);
    }

    public function show(Request $request) {
        try {
            $account = BankAccount::where(function ($query) use ($request) {
                $query->where('id', $request->id)
                    ->orWhere('account_no', $request->id);
            })->get();
            if(!$account) {
                return $this->error("Account not found.", null, null, 404);
            }
            return $this->success("Bank account.", $account, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
        }
    }

    public function update(Request $request) {
        $account = BankAccount::where(function ($query) use ($request) {
            $query->where('id', $request->id)
                ->orWhere('account_no', $request->id);
        })->first();
        if(!$account) {
            return $this->error("Account not found.", null, null, 404);
        }
        $validator = Validator::make($request->all(), BankAccount::updateRule());
        if($validator->fails()) {
            return $this->error('Oops!'.$validator->errors()->first(), null, null, 400);
        } else {
            try {
                DB::beginTransaction();
                BankAccount::where('id', $request->bankAccountId)->update($request->all());
                DB::commit();
                return $this->success("Bank Account updated successfully.", null, null, 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
            }
        }
    }

    public function delete(Request $request) {
        $validator = Validator::make($request->all(), [
            'exporterId' => 'required|exists:bank_accounts,id',
        ]);
        if($validator->fails()) {
            return $this->error('Oops!'.$validator->errors()->first(), null, null, 400);
        } else {
            try {
                DB::beginTransaction();
                $account = BankAccount::find($request->id);
                if(!$account) {
                    return $this->error("Bank account not found.", null, null, 404);
                }
                $account->delete();
                DB::commit();
                return $this->success("Bank account deleted successfully.", null, null, 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
            }
        }
    }
}
