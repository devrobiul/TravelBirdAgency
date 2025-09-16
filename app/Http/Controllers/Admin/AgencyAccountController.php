<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccountStore;
use App\Models\AgencyAccount;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AgencyAccountController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $accounts = AgencyAccount::latest();

            return DataTables::of($accounts)
                ->addIndexColumn()
                ->editColumn('current_balance', function ($row) {
                    return currencyBD($row->current_balance);
                })
                ->addColumn('action', function ($row) {
                    $edit = '<a data-url="' . route('admin.accounts.edit', $row->id) . '" class="btn btn-sm btn-info show-modal" title="Edit"><i class="bi bi-pencil-square"></i></a>';
                    $history = '<a href="' . route('admin.accounts.show', $row->id) . '" class="btn btn-sm btn-primary" title="History"><i class="bi bi-clock-history"></i></a>';
                    $delete = '          <form action="' . route('admin.accounts.destroy', $row->id) . '" method="POST" style="display:inline;" onsubmit="return confirmDelete(event);">
                ' . csrf_field() . '
                ' . method_field('DELETE') . '
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-x-circle"></i></button>
                           </form>';
                    return $edit . ' ' . $history . ' ' . $delete;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.pages.account.index');
    }

    public function create()
    {
        return view('backend.pages.account.create', [
            'account' => null,
        ]);
    }

    public function store(AccountStore $request)
    {

        $acc =  new AgencyAccount();
        $acc->account_type = $request->account_type;
        $acc->account_name = $request->account_name;
        $acc->account_number = $request->account_number;
        $acc->branch_name = $request->branch_name;
        $acc->opening_balance = $request->opening_balance;
        $acc->current_balance = $request->opening_balance;
        $acc->created_at = now();
        $acc->save();
        return response()->json([
            'route' => route('admin.accounts.index'),
            'success' => true,
            'msg' => 'Account created successfully!'
        ]);
    }


    public function edit($id)
    {
        return view('backend.pages.account.create', [
            'account' => AgencyAccount::findOrFail($id),
        ]);
    }
    public function show($id)
    {
        return view('backend.pages.account.show', [
            'data' => AgencyAccount::findOrFail($id),
        ]);
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'account_name' => 'required|unique:agency_accounts,account_name,' . $id,
            'account_number' => 'required',
        ]);
        $acc =   AgencyAccount::findOrFail($id);
        $acc->account_type = $request->account_type;
        $acc->account_name = $request->account_name;
        $acc->account_number = $request->account_number;
        $acc->branch_name = $request->branch_name;
        $acc->updated_at = now();
        $acc->save();
        return response()->json([
            'route' => route('admin.accounts.index'),
            'success' => true,
            'msg' => 'Account updated successfully!'
        ]);
    }

    public function destroy($id)
    {
        if (!auth()->user()->hasRole('admin')) {
            session()->flash('warning', 'You do not have permission to delete a payment.');
            return back();
        }
        $acc = AgencyAccount::findOrFail($id);
        Transaction::where('from_account_id',$acc->id)->delete();
        $acc->delete();

        session()->flash('success', 'Account deleted successfully');
        return back();
    }
}
