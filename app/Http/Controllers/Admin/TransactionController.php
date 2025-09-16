<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgencyAccount;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index($type)
    {
        $transactions = Transaction::type($type)->get();
        return view('backend.pages.transactions.index', compact('type', 'transactions'));
    }
    public function create($type)
    {
        $accounts = AgencyAccount::all();
        return view('backend.pages.transactions.create', compact('type', 'accounts'));
    }
    public function edit($type, $id)
    {
        $transaction = Transaction::findOrFail($id);
        return view('backend.pages.transactions.edit', compact('transaction', 'type'));
    }

    public function store(Request $request, $type)
    {


        if (in_array($type, ['deposit', 'withdraw'])) {
            $request->validate([
                'account_id'       => 'required|exists:agency_accounts,id',
                'amount'           => 'required|numeric|min:1',
                'transaction_date' => 'required',
                'transaction_id'   => 'required',
            ]);

            $account = AgencyAccount::findOrFail($request->account_id);

            if ($type === 'withdraw' && $account->current_balance < $request->amount) {
                return back()->withErrors(['amount' => 'Insufficient balance for this withdrawal.'])
                    ->withInput();
            }

            $transaction = new Transaction();
            $transaction->user_id          = Auth::id();
            $transaction->from_account_id  = $request->account_id;
            $transaction->customer_id      = $request->customer_id ?? null;
            $transaction->amount           = $request->amount;
            $transaction->transaction_type = $type;
            $transaction->transaction_id   = $request->transaction_id;
            $transaction->transaction_date = $request->transaction_date;
            $transaction->note = $request->note;
            $transaction->created_at       = now();
            $transaction->save();

            if ($type === 'deposit') {
                $account->current_balance += $request->amount;
            } elseif ($type === 'withdraw') {
                $account->current_balance -= $request->amount;
            }
            $account->save();
        }

        if ($type === 'transfer') {
            $request->validate([
                'from_account_id'  => 'required|exists:agency_accounts,id',
                'to_account_id'    => 'required|exists:agency_accounts,id|different:from_account_id',
                'amount'           => 'required|numeric|min:1',
                'transaction_date' => 'required',
            ]);

            $fromAccount = AgencyAccount::findOrFail($request->from_account_id);
            $toAccount   = AgencyAccount::findOrFail($request->to_account_id);

            if ($fromAccount->current_balance < $request->amount) {
                return back()->withErrors(['amount' => 'Insufficient balance in sender account.'])
                    ->withInput();
            }

            DB::transaction(function () use ($fromAccount, $toAccount, $request, $type) {
                $fromAccount->current_balance -= $request->amount;
                $fromAccount->save();

                $toAccount->current_balance += $request->amount;
                $toAccount->save();

                Transaction::create([
                    'user_id'          => Auth::id(),
                    'from_account_id'  => $fromAccount->id,
                    'to_account_id'    => $toAccount->id,
                    'amount'           => $request->amount,
                    'transaction_type' => $type,
                    'transaction_date' => $request->transaction_date,
                    'note'             => $request->note,
                    'created_at'       => now(),
                ]);
            });
        }

        session()->flash('success', ucfirst($type) . ' created successfully!');
        return redirect()->route('admin.accounts.transaction.index', ['type' => $type]);
    }


    public function update(Request $request, $type, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $transaction = Transaction::findOrFail($id);

        if ($type === 'deposit') {
            $account = AgencyAccount::findOrFail($transaction->from_account_id);
            $account->current_balance -= $transaction->amount;
            $account->current_balance += $request->amount;
            $account->save();

            $transaction->update(['amount' => $request->amount]);
        } elseif ($type === 'withdraw') {
            $account = AgencyAccount::findOrFail($transaction->from_account_id);
            $account->current_balance += $transaction->amount;
            if ($account->current_balance < $request->amount) {
                session()->flash('warning', 'Insufficient balance after update!');
                return back()->withInput();
            }
            $account->current_balance -= $request->amount;
            $account->save();

            $transaction->update(['amount' => $request->amount]);
        } elseif ($type === 'transfer') {
            $fromAccount = AgencyAccount::findOrFail($transaction->from_account_id);
            $toAccount   = AgencyAccount::findOrFail($transaction->to_account_id);
            $fromAccount->current_balance += $transaction->amount;
            $toAccount->current_balance -= $transaction->amount;
            if ($fromAccount->current_balance < $request->amount) {
                session()->flash('warning', 'Insufficient balance for this transfer!');
                return back()->withInput();
            }
            $fromAccount->current_balance -= $request->amount;
            $toAccount->current_balance += $request->amount;

            $fromAccount->save();
            $toAccount->save();

            $transaction->update(['amount' => $request->amount]);
        }

        session()->flash('success', ucfirst($type) . ' amount updated successfully!');
        return redirect()->route('admin.accounts.transaction.index', ['type' => $type]);
    }


    public function destroy($type, $id)
    {
        if (!auth()->user()->hasRole('admin')) {
            session()->flash('warning', 'You do not have permission to delete a payment.');
            return back();
        }

        $transaction = Transaction::findOrFail($id);

        if ($type === 'deposit') {
            $account = AgencyAccount::findOrFail($transaction->from_account_id);
            $account->current_balance -= $transaction->amount;
            $account->save();
        } elseif ($type === 'withdraw') {
            $account = AgencyAccount::findOrFail($transaction->from_account_id);
            $account->current_balance += $transaction->amount;
            $account->save();
        } elseif ($type === 'transfer') {
            $fromAccount = AgencyAccount::findOrFail($transaction->from_account_id);
            $toAccount   = AgencyAccount::findOrFail($transaction->to_account_id);
            $fromAccount->current_balance += $transaction->amount;
            $toAccount->current_balance -= $transaction->amount;
            $fromAccount->save();
            $toAccount->save();
        }
        $transaction->delete();

        session()->flash('success', ucfirst($type) . ' deleted successfully!');
        return redirect()->route('admin.accounts.transaction.index', ['type' => $type]);
    }
}
