<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VisaSaleUpdate;
use App\Models\AgencyAccount;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\User;
use App\Models\Visa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class VisaSaleController extends Controller
{


    public function index()
    {
        if (request()->ajax()) {
            return $this->getData();
        }
        return view('backend.pages.visa_sale.index', [
            'customers' => User::type('customer')->get(),
            'visa' => Visa::all(),
        ]);
    }

    private function getData()
    {
        $visasale = Product::query()->where('product_type', 'visa_sale')->with(['sales.customer', 'vendor']);
        $filters = request()->all();
        if ($invoice_no = request()->invoice_no) {
            $visasale->where('invoice_no', 'like', '%' . $invoice_no . '%');
        }

        if ($visa_type = request()->visa_type) {
            $visasale->where('visa_type', $visa_type);
        }
        if ($sale_date = request()->sale_date) {
            $visasale->where('sale_date', $sale_date);
        }

        if (!empty($filters['sale_customer_id'])) {
            $visasale->whereHas('sales', function ($query) use ($filters) {
                $query->where('sale_customer_id', $filters['sale_customer_id']);
            });
        }

        if (!empty($filters['visa_id'])) {
            $visasale->whereHas('visa', function ($query) use ($filters) {
                $query->where('visa_id', $filters['visa_id']);
            });
        }
        $visasale = $visasale->orderBy('sale_date', 'DESC')->get();
        return DataTables::of($visasale)
            ->addIndexColumn()
            ->editColumn('purchase_vendor', function ($data) {
                $purchase = $data->purchase;

                $vendor = ''; // Default empty vendor

                if ($purchase) {
                    if ($purchase->purchase_vendor_id == 0) {
                        // When purchase_vendor_id is 0 (myself)
                        $vendor = '<span class="font-weight-bold">' . setting('app_name') . ' (MYSELF)</span> <br> 
                       <span class="font-weight-bold">Method:</span> ' . $purchase->account->account_name .
                            '<br> <span class="font-weight-bold">tnxid:</span>  ' . $purchase->purchase_tnxid .
                            '<br> <span class="font-weight-bold">Purchase:</span> ' . currencyBD($purchase->purchase_price) . '/= </div>';
                    } else {
                        // When vendor is available
                        $vendor = '<span class="font-weight-bold">' . $purchase->vendor->name . '</span>' .
                            '  (<span class="text-muted">' . $purchase->vendor->phone . '</span>)<br> 
                      <span class="font-weight-bold">Purchase:</span> ' . currencyBD($purchase->purchase_price) . '/= ';
                    }
                } else {
                    // If no purchase data, return a default message
                    $vendor = '<div>No purchase data available</div>';
                }

                return $vendor; // Return the appropriate content
            })

            ->editColumn('sale_data', function ($data) {
                if ($data->sales) { // Check if sales relationship exists
                    $sale_price = number_format($data->sales->sale_price);
                    $customer_name = $data->sales->customer ? $data->sales->customer->name : 'Unknown';
                    return '<div class="text-dark">' .
                        '<span class="font-weight-bold">Client: </span>' . $customer_name . '<br>' .
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
                            <span class="fw-bold">Profit:</span> ' . number_format($data->sales->sale_profit) . '/=
                        </div>';
                        } elseif (!empty($data->sales->sale_loss)) {
                            return '<div class="text-danger">
                            <span class="fw-bold">Loss:</span> ' . number_format($data->sales->sale_loss) . '/=
                        </div>';
                        }
                    }
                    return '<div class="text-muted"><del>No Sales Data</del></div>';
                }

                return '<div class="text-muted"><del>000</del></div>';
            })


            ->editColumn('visa_type', function ($data) {
                $visaName = $data->visa ? '<span class="text-success">' . $data->visa->visa_name . '</span>' : '<span class="text-danger">No Visa</span>';
                return 'Visa : ' . $visaName . '<br>' .
                    'VisaType : ' . ($data->visa_type ?? 'N/A') . '<br>' .
                    'Invoice No : ' . ($data->invoice_no ?? 'N/A') . '<br>' .
                    'Sale by : ' . ($data->user->name ?? 'Unknown') . '<br> Date: ' . $data->sale_date;
            })



            ->addColumn('action', function ($data) {
                $buttons = '';
                $buttons .= '<a class="btn btn-sm btn-dark" href="' . route('admin.visaSalePdf', $data->id) . '">
                    <i class="bi bi-download"></i>
                 </a>';

     
                $buttons .= ' <a class="btn btn-sm btn-primary" href="' . route('admin.inventory.visasale.edit', $data->id) . '">
                    <i class="bi bi-pencil-square"></i>
                 </a>';
                $buttons .= ' <form action="' . route('admin.inventory.visasale.destroy', $data->id) . '" method="POST" style="display:inline;" 
                    onsubmit="return confirmDelete(event);">
                    ' . csrf_field() . '
                    ' . method_field('DELETE') . '
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fas fa-times"></i> 
                    </button>
                 </form>';
                return $buttons;
            })


            ->rawColumns(['action', 'visa_type',  'passport_type', 'loss_profit', 'customer_data', 'sale_data', 'purchase_vendor'])
            ->make(true);
    }
    public function create()
    {
        return view('backend.pages.visa_sale.create', [
            'customers' =>User::type('customer')->get(),
            'visa' => Visa::all(),
            'account' => AgencyAccount::where('current_balance', '>', 0)->get(),
            'visa_sale' => null,
        ]);
    }
   public function edit($id)
{
    $visa_sale = Product::findOrFail($id);
    $pax_data = json_decode($visa_sale->pax_data, true);
    if (!auth()->user()->hasRole('admin') && $visa_sale->user_id != auth()->id()) {
        session()->flash('warning', 'Only admins or the user who created this visa sale can edit it.');
        return back();
    }

    return view('backend.pages.visa_sale.create', [
        'customers' => User::all(),
        'visa' => Visa::all(),
        'account' => AgencyAccount::where('current_balance', '>', 0)->get(),
        'visa_sale' => $visa_sale,
        'pax_data' => $pax_data,
    ]);
}



    public function update(VisaSaleUpdate $request, $id)
    {

        $product = Product::findOrFail($id);
        DB::beginTransaction();

        try {

            $product->update([
                'user_id' => auth()->id(),
                'visa_id' => $request->visa_id,
                'visa_type' => $request->visa_type,
                'visit_country' => $request->visit_country,
                'visa_issue_date' => $request->visa_issue_date,
                'visa_exp_date' => $request->visa_exp_date,
                'updated_at' => Carbon::now(),
            ]);

            $purchase = Purchase::where('product_id', $product->id)->first();

            if ($purchase) {

                if ($purchase->purchase_account_id) {
                    $account = AgencyAccount::find($purchase->purchase_account_id);

                    if ($account) {
                        $account->current_balance += $purchase->purchase_price;
                        $account->save();
                    }
                }


                if ($request->purchase_account_id) {
                    $account = AgencyAccount::find($request->purchase_account_id);

                    if ($account) {
                        if ($account->current_balance >= $request->purchase_price) {
                            $account->current_balance -= $request->purchase_price;
                            $account->save();
                        } else {
                            return response()->json(['error' => 'Insufficient account balance for purchase'], 400);
                        }
                    }
                }


                $purchase->update([
                    'purchase_vendor_id' => $request->purchase_vendor_id ?? 0,
                    'purchase_account_id' => $request->purchase_account_id,
                    'purchase_price' => $request->purchase_price,
                    'purchase_date' => $request->sale_date,
                    'purchase_tnxid' => $request->purchase_tnxid,
                ]);
            }


            $sale = Sale::where('product_id', $product->id)->first();
            if ($sale) {
                $sale->update([
                    'sale_customer_id' => $request->sale_customer_id,
                    'sale_account_id' => $request->sale_account_id,
                    'sale_date' => $request->sale_date,
                    'sale_price' => $request->sale_price,
                    'sale_profit' => $request->sale_profit ?? 0,
                    'sale_loss' => $request->sale_loss ?? 0,
                    'sale_note' => $request->sale_note,
                ]);
            }


            DB::commit();
            session()->flash('success', 'Visa updated successfully!');
            $route = route('admin.inventory.visasale.index');

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
        session()->flash('warning', 'You do not have permission to delete this product.');
        return back();
    }
    $product = Product::with(['purchase', 'sales'])->findOrFail($id);
    DB::transaction(function() use ($product) {
        if ($product->purchase?->purchase_account_id) {
            $account = AgencyAccount::find($product->purchase->purchase_account_id);
            if ($account) {
                $account->current_balance += $product->purchase->purchase_price ?? 0;
                $account->save();
            }
        }
        $product->sales()->delete();
        $product->purchase()->delete();
        $product->delete();
    });

    session()->flash('success', 'Visa deleted successfully!');
    return back();
}


  
}
