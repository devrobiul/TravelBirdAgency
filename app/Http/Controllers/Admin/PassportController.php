<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PassportStore;
use App\Models\AgencyAccount;
use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PassportController extends Controller
{



    public function index()
    {
        if (request()->ajax()) {
            return $this->getData();
        }
        return view('backend.pages.passport.index', [
            'customers' => User::type('customer')->get()
        ]);
    }

    private function getData()
    {
        $passport = Product::query()->where('product_type', 'passport')->with(['sales.customer', 'vendor'])->where('status', 1);
        $filters = request()->all();
        if ($tracking_id = request()->tracking_id) {
            $passport->where('tracking_id', 'like', '%' . $tracking_id . '%');
        }
        if ($passport_type = request()->passport_type) {
            $passport->where('passport_type', $passport_type);
        }
        if ($sale_date = request()->sale_date) {
            $passport->where('sale_date', $sale_date);
        }
        if ($delivery_date = request()->delivery_date) {
            $passport->where('delivery_date', $delivery_date);
        }
        if ($invoice_no = request()->invoice_no) {
            $passport->where('invoice_no', 'like', '%' . $invoice_no . '%');
        }
        if (!empty($filters['sale_customer_id'])) {
            $passport->whereHas('sales', function ($query) use ($filters) {
                $query->where('sale_customer_id', $filters['sale_customer_id']);
            });
        }

        $passport = $passport->latest()->get();
        return DataTables::of($passport)
            ->addIndexColumn()

            ->editColumn('sale_data', function ($data) {
                if ($data->sales) { // Check if sales relationship exists
                    $sale_price = number_format($data->sales->sale_price);
                    $customer_name = $data->sales->customer ? $data->sales->customer->name : 'Unknown';
                    $customer_slug = $data->sales->customer ? $data->sales->customer->slug : ''; // Assuming slug exists on the customer model

                    // Add link to customer name
                    $customer_link = $customer_slug ? '<a href="">' . $customer_name . '</a>' : $customer_name;

                    return '<div class="text-dark">' .
                        '<span class="font-weight-bold">Client: </span>' . $customer_link . '<br>' .
                        '<span class="font-weight-bold">Sale: </span> ' . $sale_price . '/=' .
                        '</div>';
                }
                return '<div class="text-muted"><em>No sale data available</em></div>';
            })


            ->editColumn('loss_profit', function ($data) {
                if (auth()->user()->hasRole('admin') && $data->sales) { // Ensure sales relationship exists
                    if ($data->sales->sale_profit > 0) {
                        return '<div class="text-success">
                        <span class="font-weight-bold">Profit:</span> '
                            . number_format($data->sales->sale_profit) . '/=
                    </div>';
                    } elseif ($data->sales->sale_loss > 0) {
                        return '<div class="text-danger">
                        <span class="font-weight-bold">Loss:</span> '
                            . number_format($data->sales->sale_loss) . '/=
                    </div>';
                    } else {
                        return '<div class="text-muted"><del>Neither loss nor profit</del></div>';
                    }
                }

                return '<div class="text-muted"><del>000</del></div>';
            })


            ->editColumn('tracking_id', function ($data) {
                return 'Sale date : ' . $data->sale_date .
                    '<br> Method : ' . $data->purchase->account->account_name .
                    '<br> tnxid : <span style="color: blue;">' . $data->purchase->purchase_tnxid . '</span>';
            })

            ->editColumn('passport_type', function ($data) {
                return strtoupper(str_replace('_', ' ', $data->passport_type)) . ' <br> Sale date: <span class="text-dark">' . $data->sale_date . '</span>' . ' <br> Delivery: <span class="text-success">' . $data->delivery_date . '</span>';
            })
            ->editColumn('sale_by', function ($data) {
                return 'Tracking Id: <span class="text-info">' . $data->tracking_id . '</span><br> Invoice No : <span class="text-info">' . $data->invoice_no . '</span> <br> Sale by : ' . $data->user->name;
            })

            ->addColumn('action', function ($data) {
                $buttons = '';
                $buttons .= '<a class="btn btn-sm btn-dark" href="' . route('admin.passportSalePdf', $data->id) . '">
                    <i class="bi bi-download"></i>
                 </a>';
                if ($data->status == 'processing') {
                    $buttons .= ' <a class="btn btn-sm btn-warning show-modal" data-url="">
                        <i class="bi bi-eye-slash"></i>
                      </a>';
                }

                $buttons .= ' <a class="btn btn-sm btn-info" href="' . route('admin.inventory.passport.show', $data->id) . '">
                    <i class="bi bi-eye"></i>
                  </a>';
                $buttons .= ' <form action="' . route('admin.inventory.passport.destroy', $data->id) . '" 
                        method="POST" style="display:inline;" 
                        onsubmit="return confirmDelete(event);">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="bi bi-x-circle"></i> 
                        </button>
                  </form>';

                return $buttons;
            })



            ->rawColumns(['action', 'tracking_id', 'sale_by', 'passport_type', 'loss_profit', 'customer_data', 'sale_data', 'travel_status'])
            ->make(true);
    }
    public function create()
    {
        return view('backend.pages.passport.create', [
            'customers' => User::type('customer')->get(),
            'account' => AgencyAccount::all(),
            'passport' => null,
        ]);
    }

    public function store(PassportStore $request)
    {

        if ($request->purchase_account_id) {
            if ($request->purchase_account_id) {
                $account = AgencyAccount::find($request->purchase_account_id);
                if ($account->current_balance >= $request->purchase_price) {
                    $account->current_balance -= $request->purchase_price;
                } else {
                    return response()->json(['error' => 'Insufficient account balance for purchase'], 400);
                }
                $account->save();
            }
        }

        $paxData = [];

        foreach ($request->g_pax_name as $index => $name) {
            $paxData[] = [
                'name'       => $name,
                'type'       => $request->g_pax_type[$index] ?? null,
                'mobile_no'  => $request->g_pax_mobile_no[$index] ?? null,
            ];
        }
        $product = Product::create([
            'invoice_no' => mt_rand(100000, 999999),
            'user_id' => auth()->id(),
            'product_type' => 'passport',
            'passport_type' => $request->passport_type,
            'sale_date' => $request->sale_date,
            'tracking_id' => $request->tracking_id,
            'dath_of_birth' => $request->dath_of_birth,
            'delivery_date' => $request->delivery_date,
            'passport_price' => $request->purchase_price,
            'pax_data' => json_encode($paxData),
            'created_at' => Carbon::now(),
        ]);

        if ($request->sign_price) {
            Purchase::create([
                'product_id' => $product->id,
                'purchase_vendor_id' => $request->vendor_id,
                'purchase_account_id' => $request->purchase_account_id,
                'purchase_price' => $request->sign_price,
                'purchase_date' => $request->sale_date,
                'purchase_tnxid' => $request->purchase_tnxid,
                'created_at' => Carbon::now(),
            ]);
        } else {
            Purchase::create([
                'product_id' => $product->id,
                'purchase_vendor_id' => 0,
                'purchase_account_id' => $request->purchase_account_id,
                'purchase_price' => $request->purchase_price,
                'purchase_date' => $request->sale_date,
                'purchase_tnxid' => $request->purchase_tnxid,
                'purchase_note' => $request->purchase_note,
                'created_at' => Carbon::now(),
            ]);
        }

        Sale::create([
            'product_id' => $product->id,
            'sale_customer_id' => $request->sale_customer_id,
            'sale_date' => $request->sale_date,
            'sale_price' => $request->sale_price,
            'sale_profit' => $request->sale_profit,
            'sale_loss' => $request->sale_loss,
            'sale_note' => $request->sale_note,
            'created_at' => Carbon::now(),
        ]);
        transaction([
            'product_id' => $product->id,
            'from_account_id' => $account->id,
            'amount' => $request->purchase_price,
            'transaction_type' => $product->invoice_no,
            'transaction_date' => $request->sale_date,
            'note' => 'Passport payment for product',
        ]);
        session()->flash('success', 'Passport sale recorded successfully!');
        $route = route('admin.inventory.passport.index');
        return response()->json([
            'success' => true,
            'message' => session('success'),
            'route' => $route,
        ]);
    }



    public function show($id)
    {
        $passport = Product::findOrFail($id);
        return view('backend.pages.passport.show', [
            'passport' => $passport,
        ]);
    }


    public function destroy($id)
    {

        if (!auth()->user()->hasRole('admin')) {
            session()->flash('warning', 'You do not have permission to delete a payment.');
            return back();
        }
        $product = Product::findOrFail($id);
        if ($product->passport_price && $product->purchase) {
            if ($product->purchase->purchase_account_id) {
                $account = AgencyAccount::find($product->purchase->purchase_account_id);
                if ($account) {
                    $account->current_balance += $product->passport_price;
                    $account->save();
                }
            }
        }
        Transaction::where('product_id', $product->id)->delete();
        $product->sales()->delete();
        $product->purchase()->delete();
        $product->delete();
        session()->flash('success', 'Product deleted successfully!');
        return back();
    }
}
