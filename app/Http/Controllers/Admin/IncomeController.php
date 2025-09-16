<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenseStoreRequest;
use App\Http\Requests\IncomeRequest;
use App\Models\AgencyAccount;
use App\Models\Income;
use App\Models\IncomeCategory;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncomeController extends Controller
{
    public function index()
    {
        return view('backend.pages.income.index', [
            'categories' => IncomeCategory::all(),
            'incomes' => Income::all(),
            'income' => null,
            'accounts' => AgencyAccount::where('account_type','Bank')->get(),
        ]);
    }


    public function store(IncomeRequest $request)
    {
        $account = AgencyAccount::where('id', $request->from_account_id)->first();
        if (!$account) {
            session()->flash('warning', 'No account found.');
            return back();
        }
        $account->current_balance += $request->income_amount;
        $account->save();
        $income = Income::create([
            'income_category_id'    => $request->income_category_id,
            'from_account_id'     => $request->from_account_id,
            'income_amount' => $request->income_amount,
            'income_date'   => $request->income_date,
            'note'           => $request->note,
            'created_at'     => now(),
        ]);
             transaction([
            'income_id' => $income->id,
            'from_account_id' => $account->id,
            'amount' => $request->income_amount,
            'transaction_type' => 'Extra Income-' . ($income->category->name ?? 'N/A'),
            'transaction_date' => $request->income_date,
            'note' => 'Extra Income-' . ($income->category->name ?? 'N/A'),

        ]);
        session()->flash('success', 'Expense created successfully!');
        return back();
    }




    public function edit($id)
    {
        $income = Income::findOrFail($id);
        return view('backend.pages.income.index', [
           'categories' => IncomeCategory::all(),
            'incomes' => Income::all(),
            'income' => $income,
            'accounts' => AgencyAccount::where('account_type','Bank')->get(),
        ]);
    }

public function update(Request $request, $id)
{
    DB::beginTransaction();

    try {
        $income  = Income::findOrFail($id);
        $account = AgencyAccount::findOrFail($income->from_account_id);
        $account->current_balance -= $income->income_amount;
        if ($income->from_account_id != $request->from_account_id) {
            $newAccount = AgencyAccount::findOrFail($request->from_account_id);
            $account->save();
            $newAccount->current_balance += $request->income_amount;
            $newAccount->save();

            $account = $newAccount; 
        } else {
            $account->current_balance += $request->income_amount;
            $account->save();
        }
        Transaction::where('income_id', $income->id)->delete();
        $income->update([
            'income_category_id' => $request->income_category_id,
            'from_account_id'    => $request->from_account_id,
            'income_amount'      => $request->income_amount,
            'income_date'        => $request->income_date,
            'note'               => $request->note,
        ]);
        transaction([
            'income_id'        => $income->id,
            'from_account_id'  => $account->id,
            'amount'           => $request->income_amount,
            'transaction_type' => 'Extra Income-' . ($income->category->name ?? 'N/A'),
            'transaction_date' => $request->income_date,
            'note'             => 'Extra Income-' . ($income->category->name ?? 'N/A'),
        ]);

        DB::commit();

        session()->flash('success', 'Income updated successfully.');
        return redirect()->route('admin.income.index');
    } catch (\Exception $e) {
        DB::rollBack();
        session()->flash('error', 'Something went wrong: ' . $e->getMessage());
        return back();
    }
}



   public function destroy($id)
{
    if (!auth()->user()->hasRole('admin')) {
        session()->flash('warning', 'You do not have permission to delete a payment.');
        return back();
    }
    $income = Income::findOrFail($id);
    $account = AgencyAccount::find($income->from_account_id);

    if ($account) {
        $account->current_balance -= $income->income_amount;
        $account->save();
    }
    Transaction::where('income_id',$income->id)->delete();
    $income->delete();

    session()->flash('success', 'Expense deleted and account balance updated.');
    return back();
}

}
