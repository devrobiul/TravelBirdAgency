<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HotelBookingUpdate;
use App\Models\Account;
use App\Models\AgencyAccount;
use App\Models\Country;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Visa;
use App\Models\VisaSale;
use App\Services\VisaSaleService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class HotelbookingController extends Controller
{




    public function index()
    {
        if (request()->ajax()) {
            return $this->getData();
        }
        return view('backend.pages.booking.index', [
            'customers' => User::type('customer')->get(),
        ]);
    }

    private function getData()
    {
        $hotel = Product::query()->where('product_type', 'hotel_booking')->with(['sales.customer', 'vendor']);
        $filters = request()->all();
        if ($invoice_no = request()->invoice_no) {
            $hotel->where('invoice_no', 'like', '%' . $invoice_no . '%');
        }
        if ($hotel_name = request()->hotel_name) {
            $hotel->where('hotel_name', 'like', '%' . $hotel_name . '%');
        }
        if ($hotel_location = request()->hotel_location) {
            $hotel->where('hotel_location', 'like', '%' . $hotel_location . '%');
        }

        if ($sale_date = request()->sale_date) {
            $hotel->where('sale_date', $sale_date);
        }

        if (!empty($filters['sale_customer_id'])) {
            $hotel->whereHas('sales', function ($query) use ($filters) {
                $query->where('sale_customer_id', $filters['sale_customer_id']);
            });
        }


        $hotel = $hotel->orderBy('sale_date', 'DESC')->get();
        return DataTables::of($hotel)
            ->addIndexColumn()
            ->editColumn('purchase_vendor', function ($data) {
                $purchase = $data->purchase;
                $vendor = '';

                if ($purchase) {
                    if ($purchase->purchase_vendor_id == 0) {
                        $accountName = $purchase->account ? $purchase->account->account_name : 'N/A';
                        $vendor = '<span class="font-weight-bold">' . setting('app_name') . ' (MYSELF)</span> <br> 
                       <span class="font-weight-bold">Method:</span> ' . $accountName . '
                       <br> <span class="font-weight-bold">tnxid:</span> ' . ($purchase->purchase_tnxid ?? 'N/A') . '
                       <br> <span class="font-weight-bold">Purchase:</span> ' . currencyBD($purchase->purchase_price) . '/=';
                    } else {
                        $vendorName = $purchase->vendor->name ?? 'N/A';
                        $vendorPhone = $purchase->vendor->phone ?? 'N/A';
                        $vendor = '<span class="font-weight-bold">' . $vendorName . '</span>
                       (<span class="text-muted">' . $vendorPhone . '</span>)<br>
                       <span class="font-weight-bold">Purchase:</span> ' . currencyBD($purchase->purchase_price) . '/=';
                    }
                } else {
                    $vendor = '<div>No purchase data available</div>';
                }

                return $vendor;
            })



            ->editColumn('sale_data', function ($data) {
                if ($data->sales) { // Check if sales relationship exists
                    $sale_price = number_format($data->sales->sale_price);
                    $customer_name = $data->sales->customer ? $data->sales->customer->name : 'Unknown';
                    $customer_slug = $data->sales->customer ? $data->sales->customer->slug : ''; // Assuming slug exists on the customer model

                    // Add link to customer name
                    $customer_link = $customer_slug ? '<a href="' . (route('admin.customer.details', $customer_slug)) . '">' . $customer_name . '</a>' : $customer_name;

                    return '<div class="text-dark">' .
                        '<span class="font-weight-bold">Client: </span>' . $customer_link . '<br>' .
                        '<span class="font-weight-bold">Sale: </span> ' . $sale_price . '/=' .
                        '</div>';
                }
                return '<div class="text-muted"><em>No sale data available</em></div>';
            })

            ->editColumn('hotel_description', function ($data) {
                $description = '<div class="text-dark">' .
                    '<span class="font-weight-bold">Hotel: </span>' . $data->hotel_name . ', ' . $data->hotel_location . '(' . $data->hotel_number_of_day . ') <br>' .
                    '<span class="font-weight-bold">Country: </span>' . $data->visit_country . '<br>' .
                    '<span class="font-weight-bold">Purchase Mail: </span>' . $data->hotel_purchase_email . '<br>';

                if ($data->hotel_refer) {
                    $description .= '<span class="font-weight-bold">Hotel Refer: </span>' . $data->hotel_refer . '<br>';
                }

                $description .= '</div>';

                return $description;
            })

            ->editColumn('loss_profit', function ($data) {
                $sale = $data->sales;

                // Only admin can see profit/loss
                if (!auth()->user()->hasRole('admin')) {
                    return '<div class="text-muted"><del>000</del></div>';
                }

                if (!$sale) {
                    return '<div class="text-muted"><del>No Sales Data</del></div>';
                }

                if ($sale->sale_profit) {
                    return '<div class="text-success"><span class="font-weight-bold">Profit:</span> ' . currencyBD($sale->sale_profit) . '/=</div>';
                }

                if ($sale->sale_loss) {
                    return '<div class="text-danger"><span class="font-weight-bold">Loss:</span> ' . currencyBD($sale->sale_loss) . '/=</div>';
                }

                return '<div class="text-muted">Neither loss nor profit</div>';
            })
            ->editColumn('sale_by', function ($data) {
                return 'Invoice No : <span class="text-info">' . $data->invoice_no . '</span> 
                <br> Sale by : ' . $data->user->name . '<br> Sale Date : ' . $data->sale_date;
            })


            ->addColumn('action', function ($data) {
                $buttons = '';

                $buttons .= '<a class="btn btn-sm btn-dark" href="' . route('admin.hotelbookingPdf', $data->id) . '" title="Download PDF">
                    <i class="bi bi-download"></i>
                 </a>';

                $buttons .= ' <a class="btn btn-sm btn-primary" href="' . route('admin.inventory.hotel.edit', $data->id) . '" title="Edit">
                    <i class="bi bi-pencil-square"></i>
                 </a>';
                $buttons .= ' <form action="' . route('admin.inventory.hotel.destroy', $data->id) . '" 
                        method="POST" style="display:inline;" 
                        onsubmit="return confirmDelete(event);">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                            <i class="bi bi-x-circle"></i>
                        </button>
                 </form>';

                return $buttons;
            })



            ->rawColumns(['action', 'hotel_description', 'sale_by', 'loss_profit', 'customer_data', 'sale_data', 'purchase_vendor'])
            ->make(true);
    }


    public function create()
    {
        return view('backend.pages.booking.create', [
            'customers' => User::type('customer')->get(),
            'account' => AgencyAccount::where('current_balance', '>', 0)->get(),
            'country' => Country::all(),
        ]);
    }
    public function edit($id)
    {
        $hotel =  Product::findOrFail($id);
        $pax_data =  json_decode($hotel->pax_data, true);

        return view('backend.pages.booking.edit', [
            'customers' => User::type('customer')->get(),
            'country' => Country::all(),
            'account' => AgencyAccount::where('current_balance', '>', 0)->get(),
            'hotel' => $hotel,
            'pax_data' => $pax_data,
        ]);
    }
    public function update(HotelBookingUpdate $request, $id)
    {
        $product = Product::findOrFail($id);
        DB::beginTransaction();
        try {
            $product->update([
                'user_id' => auth()->id(),
                'hotel_name' => $request->hotel_name,
                'visit_country' => $request->visit_country,
                'hotel_number_of_day' => $request->hotel_number_of_day,
                'hotel_purchase_email' => $request->hotel_purchase_email,
                'hotel_refer' => $request->hotel_refer,
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
                Transaction::where('product_id',$product->id)->delete();
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
                    'sale_note' => $request->sale_note ?? 0,
                ]);
            }
                transaction([
                    'product_id' => $product->id,
                    'from_account_id' => $account->id,
                    'amount' => $request->purchase_price,
                    'transaction_type' => $product->invoice_no,
                    'transaction_date' => $request->sale_date,
                    'note' => 'Hotel Booking payment for product',
                ]);

            DB::commit();
            session()->flash('success', 'Hotel booking updated successfully!');
            $route = route('admin.inventory.hotel.index');

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
        $product->sales()->delete();
        $product->purchase()->delete();
        $product->delete();
        session()->flash('success', 'Hotel booking deleted successfully!');
        return back();
    }
}
