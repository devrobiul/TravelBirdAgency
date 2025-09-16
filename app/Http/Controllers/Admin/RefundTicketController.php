<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgencyAccount;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\TicketRefund;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RefundTicketController extends Controller
{
    public function index()
    {

        if (request()->ajax()) {
            return $this->getTicket();
        }
        return view('backend.pages.ticket_refund.index', [
            'customers' => User::type('customer')->get(),
        ]);
    }
    private function getTicket()
    {
        $ticket = TicketRefund::query();

        if ($ticket_pnr = request()->ticket_pnr) {
            $ticket->where('refund_pnr', 'like', '%' . $ticket_pnr . '%');
        }
        if ($refund_date = request()->refund_date) {
            $ticket->where('refund_date', $refund_date);
        }
        if (($status = request()->status) !== null) {
            $ticket->where('status', $status);
        }
        $ticket = $ticket->orderBy('refund_date', 'DESC')->get();
        return DataTables::of($ticket)
            ->addIndexColumn()
            ->editColumn('vendor', function ($data) {
                $vendor = '';

                $customerName  = optional(optional($data->product)->sales)->customer->name ?? 'N/A';
                $customerPhone = optional(optional($data->product)->sales)->customer->phone ?? 'N/A';

                if ($data->refund_vendor_id == 0) {
                    $vendor = '<span class="fw-bold">' . setting('app_name') . ' (MYSELF)</span><br>
           <span class="text-danger">
               <a href="}}">' . $customerName . '</a>
           </span>
           (<span class="text-muted">' . $customerPhone . '</span>)';
                } else {
                    $vendorName = $data->vendor->slug
                        ? '<a href="#" class="text-info">' . $data->vendor->name . '</a>'
                        : $data->vendor->name;

                    $vendor = '<span class="fw-bold">' . $vendorName . '</span>
           (<span class="text-muted">' . $data->vendor->phone . '</span>)<br>
           <span class="text-danger">
               <a href="#">' . $customerName . '</a>
           </span>
           (<span class="text-muted">' . $customerPhone . '</span>)';
                }

                return $vendor;
            })
            ->editColumn('status', function ($data) {

                if ($data->status == 1) {
                    return '<span class="text-success">REFUNDED</span><br>
                    <span class="text-danger">' . $data->refund_expected_date . '</span>';
                } else {
                    return '<span class="text-danger">PENDING</span><br>
                    <span class="text-danger">' . $data->refund_expected_date . '</span>';
                }
            })
            ->editColumn('ticket_pnr', function ($data) {
                return 'PNR :<span class="text-success">' . $data->refund_pnr . '</span><br>
                    <span class="text-danger">' . $data->refund_date . '</span>';
            })
            ->editColumn('refund', function ($data) {

                return '<span class="text-success">R/A: ' . number_format($data->refund_amount) . '/=</span> <br>
            <span class="text-danger">C/R: ' . number_format($data->customer_refund) . '/=</span> 
            ';
            })

            ->addColumn('action', function ($data) {

                $buttons = '';
                if ($data->status == 0) {
                    $buttons .= '<a class="btn btn-sm btn-info show-modal" data-url="' . route('admin.inventory.refundticket.status', $data->id) . '">
                  <i class="fas fa-hourglass-half"></i>
                 </a> ';
                    $buttons .= '<a class="btn btn-sm btn-primary" href="' . route('admin.inventory.refundticket.edit', $data->id) . '">
                    <i class="fas fa-edit"></i>
                 </a> ';
                }
                $buttons .= '<form action="' . route('admin.inventory.refundticket.destroy', $data->id) . '" method="POST" style="display:inline;" 
                    onsubmit="return confirmDelete(event);">
                    ' . csrf_field() . '
                    ' . method_field('DELETE') . '
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fas fa-times"></i>
                    </button>
                 </form>';

                return $buttons;
            })

            ->rawColumns(['action', 'ticket_pnr',  'vendor', 'refund', 'customer', 'status', 'travel_status'])
            ->make(true);
    }

public function ticketSearch(Request $request)
{
    $request->validate([
        'searchpnr' => 'required',
    ]);

    $searchPnr = $request->input('searchpnr');
    $ticket = Product::where('ticket_pnr', $searchPnr)->first();

    if (!$ticket) {
        session()->flash('error', 'PNR NOT FOUND!');
        return back();
    }
    $existpnr = TicketRefund::where('refund_pnr', $searchPnr)->first();

    if ($existpnr) {
        session()->flash('error', 'PNR ALREADY APPLIED!');
        return back();
    }
    $pax_data = json_decode($ticket->pax_data ?? '[]', true);
    $customers = User::type('customer')->get();

    return view('backend.pages.ticket_refund.create', compact('ticket', 'customers', 'pax_data'));
}


    public function store(Request $request)
    {
        $request->validate([
            'refund_pnr' => 'required|unique:ticket_refunds,refund_pnr',
            'refund_amount' => 'required|numeric',
            'customer_refund' => 'required|numeric',
            'refund_date' => 'required|date',
            'refund_expected_date' => 'required|date',
        ]);
        $data = $request->only([
            'product_id',
            'refund_pnr',
            'refund_vendor_id',
            'refund_amount',
            'customer_refund',
            'refund_date',
            'refund_expected_date',
            'status',
            'profit_account_id'
        ]);
        $data['refund_profit'] = $data['refund_amount'] - $data['customer_refund'];
        $data['status'] = $data['status'] ?? '0';

        $data['user_id'] = auth()->user()->id;
        TicketRefund::create($data);
        session()->flash('success', 'Refund Applied Successfully!');
        $route = route('admin.inventory.refundticket.index');
        return response()->json([
            'success' => true,
            'message' => session('success'),
            'route' => $route,
        ]);
    }
    public function edit($id)
{
    $ticketRefund = TicketRefund::findOrFail($id);

    $ticket = Product::find($ticketRefund->product_id);

    if (!$ticket) {
        return redirect()->back()->with('error', 'Related product not found for this refund ticket.');
    }

    $pax_data = json_decode($ticket->pax_data, true);
    $customers = User::type('customer')->get();

    return view('backend.pages.ticket_refund.edit', compact('ticketRefund', 'customers', 'ticket', 'pax_data'));
}


    public function status($id)
    {
        $ticket = TicketRefund::findOrFail($id);
        return view('backend.pages.ticket_refund.status', compact('ticket'));
    }

    public function destroy($id)
    {
        if (!auth()->user()->hasRole('admin')) {
            session()->flash('warning', 'You do not have permission to delete a payment.');
            return back();
        }

        $ticketRefund = TicketRefund::findOrFail($id);
        $ticket_refund = Product::where('product_type', 'ticket_refund')->where('ticket_pnr', 'Refund-' . $ticketRefund->refund_pnr)->first();
        Transaction::where('product_id',$ticket_refund->id)->delete();
        if ($ticket_refund) {
            $ticket_refund->purchase->delete();
            $ticket_refund->sales->delete();
            $ticket_refund->delete();
        }
        $ticketRefund->delete();
        session()->flash('success', 'Refund record deleted successfully!');
        return redirect()->route('admin.inventory.refundticket.index');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'refund_pnr' => 'required|unique:ticket_refunds,refund_pnr,' . $id,
            'refund_amount' => 'required|numeric',
            'customer_refund' => 'required|numeric',
            'refund_date' => 'required|date',
            'refund_expected_date' => 'required|date',
        ]);

        $ticketRefund = TicketRefund::findOrFail($id);
        $data = $request->only([
            'refund_pnr',
            'refund_vendor_id',
            'refund_amount',
            'customer_refund',
            'refund_date',
            'refund_expected_date',
            'profit_account_id',
        ]);

        $data['refund_profit'] = $data['refund_amount'] - $data['customer_refund'];
        $data['user_id'] = auth()->user()->id;
        $ticketRefund->update($data);
        session()->flash('success', 'Refund Updated Successfully!');
        $route = route('admin.inventory.refundticket.index');
        return response()->json([
            'success' => true,
            'message' => session('success'),
            'route' => $route,
        ]);
    }
    public function statusStore(Request $request, $id)
    {
        $ticket = TicketRefund::findOrFail($id);


        if ($request->status == 0) {
            session()->flash('warning', $ticket->refund_pnr . ' already pending,try again!');
            return back();
        } else {
            if ($ticket->refund_expected_date > today()) {
                return back()->with('warning', 'The status cannot be changed before the expected date!');
            }
            if ($ticket->refund_vendor_id === 0) {
                $accountId = $ticket->product->purchase->purchase_account_id;
                $account =  AgencyAccount::where('id', $accountId)->firstOrFail();
                $account->current_balance += $ticket->refund_amount;
                $account->save();
            } else {
                $account =  AgencyAccount::where('id', $ticket->profit_account_id)->firstOrFail();
                $account->current_balance += $ticket->refund_profit;
                $account->save();
            }
            $ticket_refund = Product::where('id', $ticket->product_id)->firstOrFail();
            $product = Product::create([
                'invoice_no' => mt_rand(100000, 999999),
                'user_id' => auth()->id(),
                'product_type' => 'ticket_refund',
                'ticket_type' => 'Refund Ticket',
                'ticket_pnr' => 'Refund-' . $ticket->refund_pnr,
                'refund_date' => date('Y-m-d'),
                'sale_date' => date('Y-m-d'),
            ]);
            Purchase::create([
                'product_id' => $product->id,
                'purchase_vendor_id' => $ticket_refund->sales->sale_customer_id,
                'purchase_account_id' => null,
                'purchase_price' => $ticket->customer_refund,
                'purchase_date' => date('Y-m-d'),
                'purchase_tnxid' => null,
                'purchase_note' => null,
                'created_at' => Carbon::now(),
            ]);
            Sale::create([
                'product_id' => $product->id,
                'sale_customer_id' => $ticket->refund_vendor_id,
                'sale_account_id' => null,
                'sale_date' => date('Y-m-d'),
                'sale_price' => $ticket->refund_amount,
                'sale_profit' => $ticket->refund_profit,
                'sale_loss' => 0,
                'sale_note' => null,
                'created_at' => Carbon::now(),
            ]);
            $ticket->update([
                'status' => $request->status,
                'refund_expected_date' => date('Y-m-d'),
                'updated_at' => Carbon::now(),
            ]);

            transaction([
            'product_id' => $product->id,
            'from_account_id' => $account->id,
            'amount' => $ticket->refund_profit,
            'transaction_type' =>'Refund ticket -'.$product->invoice_no,
            'transaction_date' => $ticket->refund_expected_date,
            'note' => 'Refund Ticket  payment for product',
           ]);
            session()->flash('success', $ticket->refund_pnr . ' Refunded Successfully!');
            return back();
        }
    }
}
