<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GroupTicketStore;
use App\Models\AgencyAccount;
use App\Models\Airline;
use App\Models\Airport;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class GroupTicketController extends Controller
{


    public function index()
    {
        if (request()->ajax()) {
            return $this->getTicket();
        }
        return view('backend.pages.ticket.group_ticket_index', [
            'customers' => User::type('customer')->get(),
        ]);
    }


    private function getTicket()
    {
        $ticket = Product::query()->where('product_type', 'group_ticket')->with(['sales.customer', 'vendor']);

        if ($ticket_pnr = request()->ticket_pnr) {
            $ticket->where('ticket_pnr', 'like', '%' . $ticket_pnr . '%');
        }

        if ($invoice_no = request()->invoice_no) {
            $ticket->where('invoice_no', 'like', '%' . $invoice_no . '%');
        }


        if ($sale_date = request()->sale_date) {
            $ticket->where('sale_date', $sale_date);
        }
        if ($departing_date = request()->departing_date) {
            $ticket->where('depart_date', $departing_date);
        }


        $ticket = $ticket->latest()->get();
        return DataTables::of($ticket)
            ->addIndexColumn()
            ->editColumn('purchase_vendor', function ($data) {
                $purchasedata = $data->purchase;
                $vendor = '';

                if ($purchasedata) {
                    if ($purchasedata->purchase_vendor_id == 0) {
                        $accountName = $purchasedata->fromAccount->account_name ?? 'N/A';
                        $tnxid = $purchasedata->purchase_tnxid ?? 'N/A';
                        $price = currencyBD($purchasedata->purchase_price);

                        $vendor = '<span class="font-weight-bold">' . setting('app_name') . ' (MYSELF)</span> <br>' .
                            '<span class="font-weight-bold">Method:</span> ' . $accountName . '<br>' .
                            '<span class="font-weight-bold">TnxID:</span> ' . $tnxid . '<br>' .
                            '<span class="font-weight-bold">Purchase:</span> ' . $price . '/=';
                    } else {
                        $vendorName = $purchasedata->vendor->name ?? 'N/A';
                        $vendorPhone = $purchasedata->vendor->phone ?? 'N/A';
                        $price = currencyBD($purchasedata->purchase_price);

                        $vendor = '<a class="" href="'.route('admin.customer.details',$purchasedata->vendor->slug).'">' . $vendorName . '</a> ' .
                            '(<span class="text-muted">' . $vendorPhone . '</span>)<br>' .
                            '<span class="font-weight-bold">Purchase:</span> ' . $price . '/=';
                    }
                } else {
                    $vendor = '<div>No purchase data available</div>';
                }

                return $vendor;
            })
            ->editColumn('customer_data', function ($data) {
                $saleData = '';


                if (!$data->group_ticket_sales || $data->group_ticket_sales->isEmpty()) {
                    return '<div class="text-muted"><em>No sale data available</em></div>';
                }

                foreach ($data->group_ticket_sales as $sale) {

                    if (!$sale) {
                        continue;
                    }

                    $customer_name = $sale->customer->name ?? 'Unknown';
                    $customer_phone = $sale->customer->phone ?? 'Unknown';


                    $saleData .= "
            <div>
                C/N: {$customer_name} <br>
                C/P: {$customer_phone} <br>
            </div> <br>
        ";
                }

                return $saleData;
            })



            ->editColumn('sale_data', function ($data) {
                $saleData = '';


                if (!$data->group_ticket_sales || $data->group_ticket_sales->isEmpty()) {
                    return '<div class="text-muted"><em>No sale data available</em></div>';
                }

                foreach ($data->group_ticket_sales as $sale) {

                    if (!$sale) {
                        continue;
                    }

                    $sale_price = is_numeric($sale->sale_price) ? number_format($sale->sale_price) : '0';
                    $saleData .= "
            <div>
                P/N : <span>{$sale->pax_name} ||</span>
                Sale Price: <span>{$sale_price}/=</span>
            </div><br>
        ";
                }

                return $saleData;
            })


          ->editColumn('loss_profit', function ($data) {

    if (!auth()->user()->hasRole('admin')) {
        return '<div class="text-muted"><del>000</del></div>';
    }

    if (!$data) {
        return '<div class="text-muted"><del>No Sales Data</del></div>';
    }

    $remainingSeats = $data->group_qty - $data->group_ticket_qty;
    $output = '<div class="text-success"><span class="fs-1" style="font-size:16px">Total Seats:</span><span style="font-size:20px"> ' . $data->group_qty . '</span></div><div class="text-danger"><span class="fs-1" style="font-size:16px">Remaining Seats:</span> <span style="font-size:20px"> ' . $remainingSeats . '</span></div>';


    if ($data->product_sale_profit) {
        $output .= '<div class="text-success"><span class="font-weight-bold">Profit:</span> ' . currencyBD($data->product_sale_profit) . '/=</div>';
    } elseif ($data->product_sale_loss) {
        $output .= '<div class="text-danger"><span class="font-weight-bold">Loss:</span> ' . currencyBD($data->product_sale_loss) . '/=</div>';
    } else {
        $output .= '<div class="text-muted">Neither loss nor profit</div>';
    }

    return $output;
})



            ->editColumn('travel_status', function ($data) {
                $status = strtoupper($data->travel_status);
                $departing_date = $data->depart_date;
                $journey_from = $data->journey_from;
                $journey_to = $data->journey_to;
                $return_date = $data->return_date;
                $multicity_from = $data->multicity_from;
                $multicity_to = $data->multicity_to;
                $icon = '<i class="fa fa-plane mx-1"></i>';
                $travel_info = '<strong>Status: </strong>' . $status . '<br> <strong>Airline: </strong>' . $data->airline->IATA . '-' . $data->airline->Airline . '<br>';

                if ($status == 'ONEWAY') {
                    $travel_info .= '<strong>Departing: </strong>' . $departing_date . '<br>';
                    $travel_info .= $journey_from . $icon . $journey_to;
                } elseif ($status == 'ROUNDTRIP') {
                    $travel_info .= '<strong>Departing: </strong>' . $departing_date . '<br>';
                    $travel_info .= '<strong>Return: </strong>' . $return_date . '<br>';
                    $travel_info .= $journey_from . $icon . $journey_to;
                } elseif ($status == 'MULTICITY') {
                    $travel_info .= '<strong>Departing: </strong>' . $departing_date . ',';
                    $travel_info .= '<strong>Return: </strong>' . $return_date . '<br>';
                    $travel_info .= $journey_from . $icon . $journey_to . '<br>';
                    $travel_info .= $multicity_from . $icon . $multicity_to . '<br>';
                }
                return $travel_info;
            })


            ->editColumn('ticket_pnr', function ($data) {
                if (!empty($data->re_issue_date)) {
                    return 'PNR : <span class="text-success">' . $data->ticket_pnr . '</span><br>Type : ' . strtoupper($data->ticket_type) . '<br>Issue : ' . $data->issue_date . '<br>Re-Issue : ' . $data->re_issue_date . '<br>Inoice No : ' . $data->invoice_no;
                } else {
                    return 'PNR : <span class="text-success">' . $data->ticket_pnr . '</span><br>Type : ' . strtoupper($data->ticket_type) . '<br>Issue : ' . $data->issue_date . '<br>Inoice No : ' . $data->invoice_no;
                }
            })


            ->addColumn('action', function ($data) {
                $buttons = '';
                $buttons .= '<a class="btn btn-sm btn-dark" href="' . route('admin.grouptikceSalePdf', $data->id) . '">
                    <i class="bi bi-download"></i>
                 </a> ';
                $buttons .= '<a class="btn btn-sm btn-info" href="' . route('admin.inventory.groupticket.show', $data->id) . '">
                    <i class="bi bi-eye"></i>
                 </a> ';
                $buttons .= '<a class="btn btn-sm btn-primary" href="' . route('admin.inventory.groupticket.edit', $data->id) . '">
                    <i class="bi bi-pencil-square"></i>
                 </a> ';
                $buttons .= '<form action="' . route('admin.inventory.groupticket.destroy', $data->id) . '" method="POST" style="display:inline;" 
                    onsubmit="return confirmDelete(event);">
                    ' . csrf_field() . '
                    ' . method_field('DELETE') . '
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="bi bi-x-circle"></i>
                    </button>
                 </form>';

                return $buttons;
            })

            ->rawColumns(['action', 'ticket_pnr',  'purchase_vendor', 'loss_profit', 'customer_data', 'sale_data', 'travel_status'])
            ->make(true);
    }
    public function create()
    {
        return view('backend.pages.ticket.group_ticket_create', [
            'customers' => User::type('customer')->get(),
            'airports' => Airport::all(),
            'airlines' => Airline::all(),
            'account' => AgencyAccount::where('current_balance', '>', 0)->get(),
            'g_ticket' => null,
        ]);
    }
    public function store(GroupTicketStore $request)
    {

        if ($request->product_type == 'group_ticket') {
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
            $product = Product::create([
                'invoice_no' => mt_rand(10000, 99999),
                'user_id' => auth()->id(),
                'product_type' => $request->product_type,
                'ticket_type' => 'Group-Ticket',
                'ticket_pnr' => $request->ticket_pnr,
                'group_qty' => $request->group_qty,
                'group_ticket_qty' => $request->group_ticket_qty,
                'airline_id' => $request->airline_id,
                'issue_date' => $request->issue_date,
                'sale_date' => $request->sale_date,
                'travel_status' => $request->travel_status,
                'depart_date' => $request->depart_date,
                'return_date' => $request->return_date,
                'journey_from' => $request->journey_from,
                'journey_to' => $request->journey_to,
                'multicity_from' => $request->multicity_from,
                'multicity_to' => $request->multicity_to,
                'product_sale_profit' => $request->product_sale_profit,
                'product_sale_loss' => $request->product_sale_loss,
                'created_at' => now(),
            ]);
            $customerIds = $request->input('sale_customer_id', []);
            $groupNames = $request->input('group_pax_name', []);
            $mobileNumbers = $request->input('group_pax_mobile_no', []);
            $paxTypes = $request->input('group_pax_type', []);
            $salePrices = $request->input('sale_price', []);

            foreach ($customerIds as $index => $customerId) {

                Sale::create([
                    'product_id' => $product->id,
                    'sale_customer_id' => $customerId,
                    'pax_name' => $groupNames[$index],
                    'pax_mobile_no' => $mobileNumbers[$index],
                    'pax_type' => $paxTypes[$index],
                    'sale_date' => $request->sale_date ?? now(),
                    'sale_price' => $salePrices[$index] ?? 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            Purchase::create([
                'product_id' => $product->id,
                'purchase_vendor_id' => $request->purchase_vendor_id ?? 0,
                'purchase_account_id' => $request->purchase_account_id,
                'purchase_price' => $request->purchase_price,
                'purchase_date' => $request->sale_date,
                'purchase_tnxid' => $request->purchase_tnxid,
                'purchase_note' => $request->purchase_note,
                'created_at' => now(),
            ]);
            if($request->purchase_account_id){
                transaction([
                    'product_id' => $product->id,
                    'from_account_id' => $account->id,
                    'amount' => $request->purchase_price,
                    'transaction_type' => 'Group-ticket-'.$product->invoice_no,
                    'transaction_date' => $request->sale_date,
                    'note' => 'Group Ticket Purchase',
                ]);
            }

            session()->flash('success', 'Group ticket Sale successfully!');
            return response()->json([
                'success' => true,
                'message' => session('success'),
                'route' => route('admin.inventory.groupticket.index'),
            ]);
        }
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('backend.pages.ticket.group_ticket_show', compact('product'));
    }
    public function edit($id)
    {
        $g_ticket = Product::findOrFail($id);
        return view('backend.pages.ticket.group_ticket_edit', [
            'customers' => User::type('customer')->get(),
            'airports' => Airport::all(),
            'airlines' => Airline::all(),
            'account' => AgencyAccount::where('current_balance', '>', 0)->get(),
            'g_ticket' => $g_ticket,
        ]);
    }

    public function destroy($id)
    {

        if (!auth()->user()->hasRole('admin')) {
            session()->flash('warning', 'You do not have permission to delete this product.');
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
            Transaction::where('product_id',$product->id)->delete();
            }
        }
        
        $product->sales()->delete();
        $product->purchase()->delete();
        $product->delete();
        session()->flash('success', 'Group ticket deleted successfully!');
        return back();
    }

public function update(Request $request, $id)
{
    $request->validate([
        'airline_id' => 'required',
        'depart_date' => 'required',
        'ticket_pnr' => 'required|unique:products,ticket_pnr,' . $id,
    ]);

    $product = Product::findOrFail($id);

    if ($request->purchase_price) {
        $purchase = $product->purchase;

        if ($purchase) {
            $oldPurchasePrice = $purchase->purchase_price;
            $account = $purchase->fromAccount; 
            if ($account) {
                $account->current_balance += $oldPurchasePrice;
                $account->save();
            }
        
            if ($account) {
                if ($account->current_balance >= $request->purchase_price) {
                    $account->current_balance -= $request->purchase_price;
                    $account->save();
                } else {
                    return response()->json([
                        'error' => 'Insufficient account balance for purchase'
                    ], 400);
                }
            Transaction::where('product_id',$product->id)->delete();
            }
            $purchase->update([
                'purchase_price' => $request->purchase_price,
                'purchase_note' => $request->purchase_note,
                'updated_at' => now(),
            ]);
        }
    }
      
    Sale::where('product_id', $product->id)->delete();
    $product->update([
        'user_id' => auth()->id(),
        'ticket_pnr' => $request->ticket_pnr,
        'group_qty' => $request->group_qty,
        'group_ticket_qty' => $request->group_ticket_qty,
        'airline_id' => $request->airline_id,
        'issue_date' => $request->issue_date,
        'sale_date' => $request->sale_date,
        'travel_status' => $request->travel_status,
        'depart_date' => $request->depart_date,
        'return_date' => $request->return_date,
        'journey_from' => $request->journey_from,
        'journey_to' => $request->journey_to,
        'multicity_from' => $request->multicity_from,
        'multicity_to' => $request->multicity_to,
        'product_sale_profit' => $request->product_sale_profit,
        'product_sale_loss' => $request->product_sale_loss,
        'created_at' => now(),
    ]);
    Sale::where('product_id', $product->id)->delete();

   $customerIds = $request->input('sale_customer_id', []);
    $groupNames = $request->input('group_pax_name', []);
    $mobileNumbers = $request->input('group_pax_mobile_no', []);
    $paxTypes = $request->input('group_pax_type', []);
    $salePrices = $request->input('sale_price', []);

    if(is_array($customerIds) && count($customerIds) > 0){
        foreach ($customerIds as $index => $customerId) {
            Sale::create([
                'product_id' => $product->id,
                'sale_customer_id' => $customerId,
                'pax_name' => $groupNames[$index] ?? null,
                'pax_mobile_no' => $mobileNumbers[$index] ?? null,
                'pax_type' => $paxTypes[$index] ?? null,
                'sale_date' => $request->sale_date ?? now(),
                'sale_price' => $salePrices[$index] ?? 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

          $purchase = Purchase::where('product_id', $product->id)->first();
            $purchase->update([
                'purchase_vendor_id' => $request->purchase_vendor_id ?? 0,
                'purchase_account_id' => $request->purchase_account_id,
                'purchase_price' => $request->purchase_price,
                'purchase_date' => $request->sale_date,
                'purchase_tnxid' => $request->purchase_tnxid,
            ]);
           if($request->purchase_account_id){
                transaction([
                    'product_id' => $product->id,
                    'from_account_id' => $account->id,
                    'amount' => $request->purchase_price,
                    'transaction_type' => 'Group-ticket-'.$product->invoice_no,
                    'transaction_date' => $request->sale_date,
                    'note' => 'Group Ticket Purchase',
                ]);
            }
    session()->flash('success', 'Group ticket Sale successfully updated!');

    return response()->json([
        'success' => true,
        'message' => session('success'),
        'route' => route('admin.inventory.groupticket.index'),
    ]);
}

}
