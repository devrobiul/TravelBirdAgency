<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenseStoreRequest;
use App\Models\AgencyAccount;
use App\Models\Category;
use App\Models\Expense;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Nette\Schema\Expect;

class ExpenseController extends Controller
{
    public function index()
    {
        return view('backend.pages.expense.index', [
            'categories' => Category::all(),
            'expenses' => Expense::all(),
            'expense' => null,
            'accounts' => AgencyAccount::where('account_type','Bank')->get(),
        ]);
    }


    public function store(ExpenseStoreRequest $request)
    {
        
        $account = AgencyAccount::where('id', $request->account_id)->first();

        if (!$account) {
            session()->flash('warning', 'No account found.');
            return back();
        }
        if ($account->current_balance < $request->expense_amount) {
            session()->flash('error', 'Insufficient balance in Cash account!');
            return back();
        }

        $account->current_balance -= $request->expense_amount;
        $account->save();
        $expense = Expense::create([
            'category_id'    => $request->category_id,
            'account_id'     => $request->account_id,
            'expense_amount' => $request->expense_amount,
            'expense_date'   => $request->expense_date,
            'note'           => $request->note,
            'created_at'     => now(),
        ]);
             transaction([
            'expense_id' => $expense->id,
            'from_account_id' => $account->id,
            'amount' => $request->expense_amount,
            'transaction_type' => 'Expense'.$expense->category->name,
            'transaction_date' => $request->expense_date,
            'note' => 'Expense',
        ]);
        session()->flash('success', 'Expense created successfully!');
        return back();
    }




    public function edit($id)
    {
        $expense = Expense::findOrFail($id);
        return view('backend.pages.expense.index', [
            'categories' => Category::all(),
            'expenses' => Expense::all(),
            'expense' => $expense,
        ]);
    }

    public function update(ExpenseStoreRequest $request, $id)
    {
        $expense = Expense::findOrFail($id);
        $account = AgencyAccount::where('id', $expense->account_id)->first();

        if (!$account) {
            session()->flash('warning', 'No account found with the name "Cash". Please create an account named "Cash" to proceed.');
            return back();
        }
        $account->current_balance += $expense->expense_amount;
        if ($account->current_balance < $request->expense_amount) {
            session()->flash('error', 'Insufficient balance in CASH account for updated expense.');
            return back();
        }
        $account->current_balance -= $request->expense_amount;
        $account->save();
        Transaction::where('expense_id',$expense->id)->delete();
        $expense->update([
            'category_id'    => $request->category_id,
            'account_id'     => $request->account_id,
            'expense_amount' => $request->expense_amount,
            'expense_date'   => $request->expense_date,
            'note'           => $request->note,
            'updated_at'     => now(),
        ]);

             transaction([
            'expense_id' => $expense->id,
            'from_account_id' => $account->id,
            'amount' => $request->expense_amount,
            'transaction_type' => 'Expense',
            'transaction_date' => $request->expense_date,
            'note' => $expense->category->name,
        ]);
        session()->flash('success', 'Expense updated successfully.');
        return redirect()->route('admin.expense.index');
    }


   public function destroy($id)
{
    if (!auth()->user()->hasRole('admin')) {
        session()->flash('warning', 'You do not have permission to delete a payment.');
        return back();
    }
    $expense = Expense::findOrFail($id);
    $account = AgencyAccount::find($expense->account_id);

    if ($account) {
        $account->current_balance += $expense->expense_amount;
        $account->save();
    }
    $expense->delete();

    session()->flash('success', 'Expense deleted and account balance updated.');
    return back();
}

}
