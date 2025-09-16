<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AgencyAccount;
use App\Models\Airline;
use App\Models\Airport;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class SingleTicketController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return $this->getTicket();
        }

        return view('backend.pages.ticket.single_ticket', [
            'customers' => User::where('type', 'customer')->get()
        ]);
    }

    private function getTicket()
    {
        $ticket = Product::query()
            ->where('product_type', 'single_ticket')
            ->with(['sales.customer', 'purchase.vendor', 'airline']);

        $filters = request()->all();

        if ($ticket_pnr = request()->ticket_pnr) {
            $ticket->where('ticket_pnr', 'like', '%' . $ticket_pnr . '%');
        }

        if ($invoice_no = request()->invoice_no) {
            $ticket->where('invoice_no', 'like', '%' . $invoice_no . '%');
        }

        if ($ticket_type = request()->ticket_type) {
            $ticket->where('ticket_type', $ticket_type);
        }

        if (!empty($filters['sale_customer_id'])) {
            $ticket->whereHas('sales', function ($query) use ($filters) {
                $query->where('sale_customer_id', $filters['sale_customer_id']);
            });
        }

        if (!empty($filters['purchase_vendor_id'])) {
            $ticket->whereHas('purchase', function ($query) use ($filters) {
                $query->where('purchase_vendor_id', $filters['purchase_vendor_id']);
            });
        }

        if ($sale_date = request()->sale_date) {
            $ticket->where('sale_date', $sale_date);
        }

        if ($depart_date = request()->depart_date) {
            $ticket->where('depart_date', $depart_date);
        }

        if ($total_due = request()->total_due) {
            $ticket->where('total_due', '>', 0);
        }

        if ($purchase_due = request()->purchase_due) {
            $ticket->where('purchase_due', '>', 0);
        }

        $ticket = $ticket->latest()->get();

        return DataTables::of($ticket)
            ->addIndexColumn()

            // Purchase Vendor
            ->editColumn('purchase_vendor', function ($data) {
                $purchase = $data->purchase;
                if (!$purchase) return '<div>No purchase data available</div>';

                if ($purchase->purchase_vendor_id == 0) {
                    return '<span class="font-weight-bold">' . setting('app_name') . ' (MYSELF)</span><br>' .
                        '<span class="font-weight-bold">Method:</span> ' . ($purchase->fromAccount->account_name ?? '-') . '<br>' .
                        '<span class="font-weight-bold">TnxID:</span> ' . ($purchase->purchase_tnxid ?? '-') . '<br>' .
                        '<span class="font-weight-bold">Purchase:</span> ' . number_format($purchase->purchase_price) . '/=';
                }

                return '<span class="font-weight-bold"><a href="'.route('admin.customer.details',$purchase->vendor->slug).'">' . ($purchase->vendor->name ?? '-') . '</a></span> ' .
                    '(<span class="text-muted">' . ($purchase->vendor->phone ?? '-') . '</span>)<br>' .
                    '<span class="font-weight-bold">Purchase:</span> ' . number_format($purchase->purchase_price) . '/=';
            })

            // Customer & Pax Data
            ->editColumn('customer_data', function ($data) {
                $sale = $data->sales;
                if (!$sale) return '<div>No sale data available</div>';

                $customerInfo = '<div><a href="'.route('admin.customer.details',$sale->customer->slug).'">' . ($sale->customer->name ?? '-') . '</a> ' .
                    '(<span class="text-muted">' . ($sale->customer->phone ?? '-') . '</span>)<br>' .
                    '<span class="font-weight-bold">Sale:</span> ' . number_format($sale->sale_price) . '/=</div>';

                $paxData = json_decode($data->pax_data, true);
                $paxDetails = '';

                if (is_array($paxData)) {
                    foreach ($paxData as $item) {
                        $paxDetails .= '<div>' .
                            '<span class="font-weight-bold">Pax Name:</span> ' . htmlspecialchars($item['name'] ?? '-') . '<br>' .
                            '<span class="font-weight-bold">Type:</span> ' . htmlspecialchars($item['type'] ?? '-') . '<br>' .
                            '<span class="font-weight-bold">Mobile:</span> ' . htmlspecialchars($item['mobile_no'] ?? '-') .
                            '<span class="font-weight-bold">Price:</span> ' . htmlspecialchars($item['price'] ?? '-') .
                            '</div>';
                    }
                }

                return $customerInfo . $paxDetails;
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


            // Travel Status
            ->editColumn('travel_status', function ($data) {
                $status = strtoupper($data->travel_status);
                $icon = '<i class="fa fa-plane mx-1"></i>';

                $travel_info = '<strong>Status: </strong>' . $status . '<br>' .
                    '<strong>Airline: </strong>' . (($data->airline->IATA ?? '') . '-' . ($data->airline->Airline ?? '')) . '<br>';

                if ($status == 'ONEWAY') {
                    $travel_info .= '<strong>Departing: </strong>' . $data->depart_date . '<br>' .
                        '<strong>Depart Time: </strong>' . ($data->departer_time ?? '') . '<br>' .
                        '<strong>Arrival Time: </strong>' . ($data->arrival_time ?? '') . '<br>' .
                        $data->journey_from . $icon . $data->journey_to;
                } elseif ($status == 'ROUNDTRIP') {
                    $travel_info .= '<strong>Departing: </strong>' . $data->depart_date . '<br>' .
                        '<strong>Depart Time: </strong>' . ($data->departer_time ?? '') . '<br>' .
                        '<strong>Arrival Time: </strong>' . ($data->arrival_time ?? '') . '<br>' .
                        '<strong>Return: </strong>' . $data->return_date . '<br>' .
                        '<strong>Return Depart Time: </strong>' . ($data->return_departer_time ?? '') . '<br>' .
                        '<strong>Return Arrival Time: </strong>' . ($data->return_arrival_time ?? '') . '<br>' .
                        $data->journey_from . $icon . $data->journey_to;
                } elseif ($status == 'MULTICITY') {
                    $travel_info .= '<strong>Departing: </strong>' . $data->depart_date . '<br>' .
                        '<strong>Depart Time: </strong>' . ($data->departer_time ?? '') . '<br>' .
                        '<strong>Arrival Time: </strong>' . ($data->arrival_time ?? '') . '<br>' .
                        '<strong>Return: </strong>' . $data->return_date . '<br>' .
                        '<strong>Return Depart Time: </strong>' . ($data->return_departer_time ?? '') . '<br>' .
                        '<strong>Return Arrival Time: </strong>' . ($data->return_arrival_time ?? '') . '<br>' .
                        $data->journey_from . $icon . $data->journey_to . '<br>' .
                        $data->multicity_from . $icon . $data->multicity_to . '<br>' .
                        '<strong>Multicity Depart Time: </strong>' . ($data->multicity_departer_time ?? '') . '<br>' .
                        '<strong>Multicity Arrival Time: </strong>' . ($data->multicity_arrival_time ?? '');
                }

                return $travel_info;
            })


            // Ticket PNR
            ->editColumn('ticket_pnr', function ($data) {
                $reIssue = !empty($data->re_issue_date) ? '<br>Re-Issue: ' . $data->re_issue_date : '';
                return 'PNR: <span class="text-success">' . $data->ticket_pnr . '</span><br>' .
                    'Type: ' . strtoupper($data->ticket_type) . '<br>' .
                    'Sale date: ' . $data->sale_date .
                    $reIssue .
                    '<br>Invoice No: ' . $data->invoice_no;
            })

            // Actions
            ->addColumn('action', function ($data) {
                return '
        <a class="btn btn-dark btn-sm" href="' . route('admin.singleTicketPdf', $data->id) . '" title="Download PDF">
            <i class="bi bi-download"></i>
        </a>
        <a class="btn btn-info btn-sm" href="' . route('admin.inventory.singleticket.show', $data->id) . '" title="View">
            <i class="bi bi-eye"></i>
        </a>
        <a class="btn btn-primary btn-sm" href="' . route('admin.inventory.singleticket.edit', $data->id) . '" title="Edit">
            <i class="bi bi-pencil-square"></i>
        </a>
        <form action="' . route('admin.inventory.singleticket.destroy', $data->id) . '" 
              method="POST" style="display:inline;" 
              onsubmit="return confirmDelete(event);">
            ' . csrf_field() . method_field('DELETE') . '
            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                <i class="bi bi-x-circle"></i>
            </button>
        </form>
    ';
            })

            ->rawColumns(['action', 'ticket_pnr', 'purchase_vendor', 'loss_profit', 'customer_data', 'travel_status'])
            ->make(true);
    }


    public function create()
    {
        return view('backend.pages.ticket.single_ticket_create', [
            'customers' => User::type('customer')->get(),
            'airports' => Airport::all(),
            'airlines' => Airline::all(),
            'account' => AgencyAccount::where('current_balance', '>', 0)->get(),
            'ticket' => null,
        ]);
    }
    public function edit($id)
    {
        $ticket = Product::findOrFail($id);
        $pax_data = json_decode($ticket->pax_data, true);

        return view('backend.pages.ticket.single_ticket_edit', [
            'customers' => User::type('customer')->get(),
            'airports' => Airport::all(),
            'airlines' => Airline::all(),
            'account' => AgencyAccount::where('current_balance', '>', 0)->get(),
            'ticket' => $ticket,
            'pax_data' => $pax_data
        ]);
    }


    public function update(Request $request, $id)
    {
    
        $request->validate([
            'sale_customer_id' => 'required',
            'sale_date' => 'required',
            'purchase_price' => 'required|numeric',
            'ticket_pnr' => 'required|unique:products,ticket_pnr,' . $id,
            'ticket_type' => 'required',
            'airline_id' => 'required',
            'depart_date' => 'required',
            'sale_price' => 'required|numeric',
            'purchase_vendor_id' => 'required',
        ]);

        $product = Product::findOrFail($id);
        $paxData = [];

        foreach ($request->meta_data as $index => $name) {
            $paxData[] = [
                'name'       => $name,
                'type'       => $request->g_pax_type[$index] ?? null,
                'mobile_no'  => $request->g_pax_mobile_no[$index] ?? null,
                'price'  => $request->g_pax_price[$index] ?? null,
            ];
        }

        DB::beginTransaction();

        try {

            $product->update([
                'user_id' => auth()->id(),
                'ticket_type' => $request->ticket_type,
                'ticket_pnr' => $request->ticket_pnr,
                'airline_id' => $request->airline_id,
                'issue_date' => $request->sale_date ?? 'N/A',
                're_issue_date' => $request->re_issue_date,
                'refund_date' => $request->refund_date,
                'sale_date' => $request->sale_date,
                'travel_status' => $request->travel_status,
                'departer_time' => $request->departer_time,
                'arrival_time' => $request->arrival_time,
                'depart_date' => $request->depart_date,
                'return_date' => $request->return_date,
                'journey_from' => $request->journey_from,
                'journey_to' => $request->journey_to,
                'multicity_from' => $request->multicity_from,
                'multicity_to' => $request->multicity_to,
                'pax_data' => json_encode($paxData),
                'updated_at' => Carbon::now(),
            ]);

            $purchase = Purchase::where('product_id', $product->id)->first();
            if ($request->purchase_vendor_id == 0) {
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
                    Transaction::where('product_id',$product->id)->delete();
                    }
                }

                $purchase->update([
                    'purchase_vendor_id' => $request->purchase_vendor_id ?? 0,
                    'purchase_account_id' => $request->purchase_account_id,
                    'purchase_price' => $request->purchase_price,
                    'purchase_date' => $request->sale_date,
                    'purchase_tnxid' => $request->purchase_tnxid,
                    'purchase_note' => $request->purchase_note,
                ]);
            } else {
                $purchase->update([
                    'purchase_vendor_id' => $request->purchase_vendor_id ?? 0,
                    'purchase_account_id' => null,
                    'purchase_price' => $request->purchase_price,
                    'purchase_date' => $request->sale_date,
                    'purchase_tnxid' => $request->purchase_tnxid,
                    'purchase_note' => $request->purchase_note,
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

                
                transaction([
                    'product_id' => $product->id,
                    'from_account_id' => $account->id,
                    'amount' => $request->purchase_price,
                    'transaction_type' => 'Ticket -'.$product->invoice_no,
                    'transaction_date' => $request->sale_date,
                    'note' => 'Purchase payment for product',
                ]);
            }

            DB::commit();
            session()->flash('success', 'Product, purchase, sale, and transaction updated successfully!');
            $route = route('admin.inventory.singleticket.index');

            return response()->json([
                'success' => true,
                'message' => session('success'),
                'route' => $route,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in update method: ' . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function show($id)
    {
        $ticket =  Product::findOrFail($id);
        $pax_data = json_decode($ticket->pax_data, true);
        return view('backend.pages.ticket.single_ticket_show', compact('ticket', 'pax_data'));
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
           Transaction::where('product_id',$product->id)->delete();
        }
        $product->sales()->delete();
        $product->purchase()->delete();
        $product->delete();
        session()->flash('success', 'Product deleted successfully!');
        return back();
    }
}
