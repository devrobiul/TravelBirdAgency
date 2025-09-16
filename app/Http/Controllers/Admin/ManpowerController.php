<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ManpowerRequest;
use App\Models\Account;
use App\Models\AgencyAccount;
use App\Models\Customer;
use App\Models\Country;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Transaction;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Js;
use Yajra\DataTables\Facades\DataTables;

class ManpowerController extends Controller
{
    public function index()
    {

        if (request()->ajax()) {
            return $this->getTicket();
        }
        return view('backend.pages.manpower.index', [
            'customers' => User::type('customer')->get(),
            'country' => Country::all(),
        ]);
    }

    public function create()
    {
        return view('backend.pages.manpower.create', [
            'customers' => User::type('customer')->get(),
            'country' => Country::all(),
            'account' => AgencyAccount::all(),
            'manpower' => null,
        ]);
    }


    private function getTicket()
    {
        $manpower = Product::query()->where('product_type', 'manpower')->with(['sales.customer', 'vendor']);
        $filters = request()->all();
        if ($invoice_no = request()->invoice_no) {
            $manpower->where('invoice_no', 'like', '%' . $invoice_no . '%');
        }
        if ($tracking_id = request()->tracking_id) {
            $manpower->where('tracking_id', 'like', '%' . $tracking_id . '%');
        }

        if ($visit_country = request()->visit_country) {
            $manpower->where('visit_country', $visit_country);
        }
        if ($sale_date = request()->sale_date) {
            $manpower->where('sale_date', $sale_date);
        }
        if ($delivery_date = request()->delivery_date) {
            $manpower->where('delivery_date', $delivery_date);
        }

        if (!empty($filters['sale_customer_id'])) {
            $manpower->whereHas('sales', function ($query) use ($filters) {
                $query->where('sale_customer_id', $filters['sale_customer_id']);
            });
        }


        $manpower = $manpower->latest()->get();
        return DataTables::of($manpower)
            ->addIndexColumn()
            ->editColumn('purchase_vendor', function ($data) {
                $purchase = $data->purchase;

                $vendor = '';
                if ($purchase) {
                    if ($purchase->purchase_vendor_id == 0) {
                        $vendor = '<span class="font-weight-bold">'.(setting('app_name')).' (MY SELF)</span>';
                    } else {
                        $vendor = '<span class="font-weight-bold">' . 
                        ($purchase->vendor->slug ? '<a href="'.(route('admin.customer.details',$purchase->vendor->slug)).'">' . $purchase->vendor->name . '</a>' : $purchase->vendor->name) . 
                        '</span>' .
                        '  (<span class="text-muted">' . ($purchase->vendor->phone ?? 'N/A') . '</span>)';

                    }
                    $purchase_price = currencyBD($purchase->purchase_price);

                    return '<div class="text-dark">' .
                        $vendor . '<br>' .
                        '<span class="font-weight-bold">Purchase:</span> ' . $purchase_price . '/= <br>';
                }

                // If no purchase data, return a default message
                return '<div>No purchase data available</div>';
            })


->editColumn('sale_data', function ($data) {
    if ($data->sales) { // Check if sales relationship exists
        $sale_price = currencyBD($data->sales->sale_price);
        $customer_name = $data->sales->customer ? $data->sales->customer->name ?? null : 'Unknown';
        return '<div class="text-dark">' .
            '<span class="font-weight-bold">Passport: </span>' . $data->tracking_id . '<br>' .
            '<span class="font-weight-bold">Client: </span> <a href="'.(route('admin.customer.details', $data->sales->customer->slug)).'">' . $customer_name . '</a>'.'<br>' .
            '<span class="font-weight-bold">Sale: </span> ' . $sale_price . '/=' .
            '</div>';
    }
    return '<div class="text-muted"><em>No sale data available</em></div>';
})

->editColumn('loss_profit', function ($data) {
    if (auth()->user()->hasRole('admin')) {
        if ($data->sales) {
            if (!empty($data->sales->sale_profit)) {
                return '<div class="text-success">
                            <span class="fw-bold">Profit:</span> ' . currencyBD($data->sales->sale_profit) . '/=
                        </div>';
            } elseif (!empty($data->sales->sale_loss)) {
                return '<div class="text-danger">
                            <span class="fw-bold">Loss:</span> ' . currencyBD($data->sales->sale_loss) . '/=
                        </div>';
            }
        }
        return '<div class="text-muted"><del>No Sales Data</del></div>';
    }

    return '<div class="text-muted"><del>000</del></div>';
})
            ->editColumn('manpower', function ($data) {

                return
                    'Country : ' . ($data->visit_country ?? 'N/A') . '<br>' .
                    'Invoice No : ' . ($data->invoice_no ?? 'N/A') . '<br>' .
                    'Sale by : ' . ($data->user->name ?? 'Unknown');
            })


->addColumn('action', function ($data) {
    $buttons = '';
    $buttons .= '<a class="btn btn-sm btn-dark" href="' . route('admin.manpowerSalePdf', $data->id) . '" title="Download PDF">
                    <i class="bi bi-download"></i>
                 </a>';

    $buttons .= ' <a class="btn btn-sm btn-primary" href="' . route('admin.inventory.manpower.edit', $data->id) . '" title="Edit">
                    <i class="bi bi-pencil-square"></i>
                  </a>';
    $buttons .= ' <form action="' . route('admin.inventory.manpower.destroy', $data->id) . '" 
                        method="POST" style="display:inline;" 
                        onsubmit="return confirmDelete(event);">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                            <i class="bi bi-x-circle"></i>
                        </button>
                  </form>';

    return $buttons;
})


            ->rawColumns(['action', 'manpower', 'loss_profit', 'customer_data', 'sale_data', 'purchase_vendor'])
            ->make(true);
    }




    public function edit($id)
    {
        $manpower = Product::findOrFail($id);
        $pax_data = json_decode($manpower->pax_data,true);
        if (!auth()->user()->hasRole('admin') && $manpower->user_id != auth()->id()) {
            session()->flash('warning', 'Only admins or the user who created this manpower sale can edit it.');
            return back();
        }
        return view('backend.pages.manpower.create', [
            'customers' => User::type('customer')->get(),
            'country' => Country::all(),
            'account' => AgencyAccount::all(),
            'manpower' => $manpower,
            'pax_data' => $pax_data,
        ]);
    }

    public function update(ManpowerRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        DB::beginTransaction();

        try {

            $product->update([
                'user_id' => auth()->id(),
                'delivery_date' => $request->delivery_date,
                'visit_country' => $request->visit_country,
                'tracking_id' => $request->tracking_id,
                'updated_at' => Carbon::now(),
            ]);

            $purchase = Purchase::where('product_id', $product->id)->first();
            $purchase->update([
                'purchase_vendor_id' => $request->purchase_vendor_id ?? 0,
                'purchase_account_id' => $request->purchase_account_id,
                'purchase_price' => $request->purchase_price,
                'purchase_date' => $request->sale_date,
                'purchase_tnxid' => $request->purchase_tnxid,
            ]);



            $sale = Sale::where('product_id', $product->id)->first();
            if ($sale) {
                $sale->update([
                    'sale_customer_id' => $request->sale_customer_id,
                    'sale_account_id' => $request->sale_account_id,
                    'sale_date' => $request->sale_date,
                    'sale_price' => $request->sale_price,
                    'sale_profit' => $request->sale_profit ?? 0,
                    'sale_loss' => $request->sale_loss ?? 0,
                ]);
            }



            DB::commit();
            session()->flash('success', 'Product, purchase, sale, and transaction updated successfully!');
            $route = route('admin.inventory.manpower.index');

            return response()->json([
                'success' => true,
                'message' => session('success'),
                'route' => $route,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Something went wrong, please try again.'], 500);
        }
    }




    public function destroy($id)
    {
        if (!auth()->user()->hasRole('admin')) {
            session()->flash('warning', 'You do not have permission to delete a payment.');
            return back();
        }
        $product = Product::findOrFail($id);
        if ($product->purchase) {
            if ($product->purchase->purchase_account_id) {
                $account = AgencyAccount::find($product->purchase->purchase_account_id);
                if ($account && $account->current_balance >= $product->purchase->purchase_price) {
                    $account->current_balance += $product->purchase->purchase_price;
                    $account->save();
                }
            }
        }
        Transaction::where('product_id',$product->id)->delete();
        $product->sales()->delete();
        $product->purchase()->delete();
        $product->delete();
        session()->flash('success', 'Passport deleted successfully!');
        return back();
    }
}
