<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerStoreRequest;
use App\Models\Account;
use App\Models\AgencyAccount;
use App\Models\Customer;
use App\Models\Depositamount;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Transaction;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;


use Illuminate\Support\Str;

class CustomerController extends Controller
{


    public function index($type)
    {
        // base query
        $customers = User::type('customer')->get();

        // Due Customers (যারা টাকা বাকী রেখেছে)
        $due_customers = $customers->map(function ($customer) {
            $credit = Sale::where('sale_customer_id', $customer->id)->sum('sale_price');
            $purchase_debit = Purchase::where('purchase_vendor_id', $customer->id)->sum('purchase_price');
            $deposit_debit = Transaction::where('customer_id', $customer->id)
                ->where('payment_type', 'client_payment')
                ->sum('amount');
            $gotrip_debit = Transaction::where('customer_id', $customer->id)
                ->where('payment_type', 'gotrip_payment')
                ->sum('amount');

            $balance = $credit - ($purchase_debit + $deposit_debit - $gotrip_debit);

            if ($balance > 0) {
                $customer->balance = $balance;
                return $customer;
            }
        })->filter();

        // Due Our Agency (যারা আমাদের কাছে টাকা পাবে)
        $due_agency = $customers->map(function ($customer) {
            $credit = Sale::where('sale_customer_id', $customer->id)->sum('sale_price');
            $purchase_debit = Purchase::where('purchase_vendor_id', $customer->id)->sum('purchase_price');
            $deposit_debit = Transaction::where('customer_id', $customer->id)
                ->where('payment_type', 'client_payment')
                ->sum('amount');
            $gotrip_debit = Transaction::where('customer_id', $customer->id)
                ->where('payment_type', 'gotrip_payment')
                ->sum('amount'); // এখানে amount না deposit_amount ব্যবহার করবেন নিশ্চিত করুন

            $balance = $credit - ($purchase_debit + $deposit_debit - $gotrip_debit);

            if ($balance < 0) {   // আমরা টাকা বাকী আছি
                $customer->balance = $balance;
                return $customer;
            }
        })->filter();

        // কোন টাইপের ডাটা ফেরত দিতে হবে সেটি চেক করুন
        if ($type == 'due_customer') {
            $data = $due_customers;
        } elseif ($type == 'due_our_agency') {
            $data = $due_agency;
        } else {
            // all_customer
            $data = $customers;
        }

        return view('backend.pages.customer.index', compact('type', 'data'));
    }



    public function create()
    {
        return view('backend.pages.customer.modal_create', [
            'customer' => null
        ]);
    }
    public function store(CustomerStoreRequest $request)
    {
        $customer =  new User();
        $customer->type = 'customer';
        $customer->name = $request->name;
        $customer->slug = Str::slug($request->phone);
        $customer->uuid = rand(1111,9999);
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->password = '12345678';
        $customer->save();
        return response()->json([
            'success' => true,
            'message' => 'Customer created successfully!'
        ]);
    }
        public function edit($id)
    {
        return view('backend.pages.customer.modal_create', [
            'customer' => User::findOrFail($id),
        ]);
    }
        public function update(Request $request, $id)
    {
        $request->validate([
            'name'=>'required',
            'phone'=>'required|unique:users,phone,'.$id,
            'address'=>'required',
        ]);

        $customer = User::findOrFail($id);
        $customer->name = $request->name;
        $customer->slug = Str::slug($request->phone);
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->updated_at = now();
        $customer->save();

        return response()->json([
            'success' => true,
            'route' => route('admin.customer.details',$customer->slug),
            'message' => 'Customer updated successfully!'
        ]);
    }

    public function details($slug)
    {
        $customer = User::where('slug', $slug)->first();
        $total_sale = Sale::where('sale_customer_id', $customer->id)->sum('sale_price');
        $total_purchase = Purchase::where('purchase_vendor_id', $customer->id)->sum('purchase_price');
        $last_payment = Transaction::where('customer_id', $customer->id)->where('payment_type', 'client_payment')->latest()->first();
        $office_last_payment = Transaction::where('customer_id', $customer->id)->where('payment_type', 'office_payment')->latest()->first();
        $sales = collect(Sale::with(['product', 'product.airline'])
            ->where('sale_customer_id', $customer->id)
            ->latest()->limit(10)->get());

        $purchase = collect(Purchase::with(['product', 'product.airline'])
            ->where('purchase_vendor_id', $customer->id)
            ->latest()->limit(10)->get());
        $deposit = collect(Transaction::where('customer_id', $customer->id)->where('payment_type', 'client_payment')->latest()->get());
        $office_payment = collect(Transaction::where('customer_id', $customer->id)->where('payment_type', 'office_payment')->latest()->get());
        $sale_price = $sales->sum('sale_price');
        $purchase_price = $sales->sum('purchase_price');

        $credit = Sale::where('sale_customer_id', $customer->id)
            ->sum('sale_price');
        $purchase_debit = Purchase::where('purchase_vendor_id', $customer->id)
            ->sum('purchase_price');
        $deposit_debit = Transaction::where('customer_id', $customer->id)->where('payment_type', 'client_payment')
            ->sum('amount');
        $office_debit = Transaction::where('customer_id', $customer->id)
            ->where('payment_type', 'office_payment')
            ->sum('amount');

        $balance = $credit - ($purchase_debit + $deposit_debit - $office_debit);



        return view('backend.pages.customer.show', [
            'customer' => $customer,
            'total_sale' => $total_sale,
            'total_purchase' => $total_purchase,
            'last_payment' => $last_payment,
            'office_last_payment' => $office_last_payment,
            'sales' => $sales,
            'purchase' => $purchase,
            'deposit' => $deposit,
            'office_payment' => $office_payment,
            'sale_price' => $sale_price,
            'purchase_price' => $purchase_price,
            'balance' => $balance,
            'account' => AgencyAccount::all(),
        ]);
    }



    public function destroy($id)
    {
        if (!auth()->user()->hasRole('admin')) {
            session()->flash('warning', 'You do not have permission to delete a customer.');
            return back();
        }

        session()->flash('success', 'Customer deleted successfully!');
        return redirect()->route('admin.customer.index');
    }



    public function allTransaction($slug)
    {
        $customer = User::where('slug', $slug)->first();
        $account = AgencyAccount::all();
        $transaction = Transaction::where('customer_id', $customer->id)->latest()->get();
        return view('backend.pages.customer.transaction', compact('customer', 'account', 'transaction'));
    }
    public function sale($slug)
    {
        $customer = User::where('slug', $slug)->first();
        return view('backend.pages.customer.sale', compact('customer'));
    }


    public function transaction(Request $request, $id)
    {

        $customer = User::findOrFail($id);
        if (!$customer) {
            session()->flash('error', 'Customer not found!');
            return back();
        }
        if ($request->payment_type === 'office_payment') {
            $account = AgencyAccount::find($request->from_account_id);
            if (!$account) {
                session()->flash('error', 'Account not found. Please select a valid account.');
                return back();
            }
            if ($request->amount > $account->current_balance) {
                session()->flash('error', 'Insufficient account balance. Please check the account balance and try again.');
                return back();
            }
            Transaction::create([
                'user_id' => auth()->user()->id,
                'customer_id' => $customer->id,
                'payment_type' => $request->payment_type,
                'account_name' => $request->account_name,
                'transaction_number' => $request->transaction_number,
                'transaction_type' => $request->payment_type,
                'from_account_id' => $request->from_account_id,
                'amount' => $request->amount,
                'transaction_id' => $request->transaction_id,
                'transaction_date' => $request->transaction_date,
                'note' => $request->note,
                'created_at' => Carbon::now(),
            ]);

            $account->current_balance -= $request->amount;
            $account->save();
        } else {
            Transaction::create([
                'user_id' => auth()->user()->id,
                'customer_id' => $customer->id,
                'payment_type' => $request->payment_type,
                'account_name' => $request->account_name,
                'transaction_number' => $request->transaction_number,
                'transaction_type' => $request->payment_type,
                'from_account_id' => $request->from_account_id,
                'amount' => $request->amount,
                'transaction_id' => $request->transaction_id,
                'transaction_date' => $request->transaction_date,
                'note' => $request->note,
                'created_at' => Carbon::now(),
            ]);

            $account = AgencyAccount::find($request->account_id);
            if ($account) {
                $account->current_balance += $request->amount;
                $account->save();
            }

        
        }
        session()->flash('success', 'Transaction created successfully!');
        return back();
    }
public function customerSalereport(Request $request, $id)
{
    
    
    
    $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ]);


    $start_date = $request->start_date;
    $end_date = $request->end_date;

    // Find the customer
    $customer = User::find($id);
    if (!$customer) {
        return back()->with('error', 'Customer not found.');
    }
  
    // Fetch the data with eager loading to prevent N+1 query issues
    $sales = Sale::with(['product', 'product.airline'])
        ->where('sale_customer_id', $customer->id)
        ->whereBetween('sale_date', [$start_date, $end_date])
        ->get();

    $purchase = Purchase::with(['product', 'product.airline'])
        ->where('purchase_vendor_id', $customer->id)
        ->whereBetween('purchase_date', [$start_date, $end_date])
        ->get();

    $customer_payment = Transaction::with(['customer', 'fromAccount'])
        ->where('customer_id', $customer->id)
        ->where('payment_type', 'client_payment')
        ->whereBetween('transaction_date', [$start_date, $end_date])
        ->get();

    $office_payment = Transaction::with(['customer', 'fromAccount'])
        ->where('customer_id', $customer->id)
        ->where('payment_type', 'office_payment')
        ->whereBetween('transaction_date', [$start_date, $end_date])
        ->get();

    // Ensure we use collections and prevent null errors
    $combined = collect([]) // Start with an empty collection
        ->merge($sales->map(function ($sale) {
            return [
                'type' => 'sale',
                'date' => $sale->sale_date,
                'invoice' => $sale->product?->invoice_no ?? 'N/A',
                'price' => $sale->sale_price,
                'product' => $sale->product,
                'pax_name' => $sale->pax_name,
                'pax_mobile_no' => $sale->pax_mobile_no,
                'pax_type' => $sale->pax_type,
                'purchase_date' => null,
                'purchase_price' => null,
            ];
        }))
        ->merge($purchase->map(function ($purchase) {
            return [
                'type' => 'purchase',
                'date' => $purchase->purchase_date,
                'invoice' => $purchase->product?->invoice_no ?? 'N/A',
                'price' => null,
                'product' => $purchase->product,
                'purchase_date' => $purchase->purchase_date,
                'purchase_price' => $purchase->purchase_price,
                'user_balance' => $purchase->customer?->balance ?? null,
                'pax_name' => null,
                'pax_mobile_no' => null,
                'pax_type' => null,
            ];
        }))
        ->merge($customer_payment->map(function ($customer_payment) {
            return [
                'type' => 'client_payment',
                'date' => $customer_payment->transaction_date,
                'invoice' => null,
                'price' => $customer_payment->amount,
                'purchase_price' => null,
                'product' => null,
                'transaction_id' => $customer_payment->transaction_id,
                'account_name' => $customer_payment->fromAccount?->account_name ?? 'N/A',
                'account_number' => $customer_payment->fromAccount?->transaction_number ?? 'N/A',
                'pax_name' => null,
                'pax_mobile_no' => null,
                'pax_type' => null,
            ];
        }))
        ->merge($office_payment->map(function ($office_payment) {
            return [
                'type' => 'office_payment',
                'date' => $office_payment->transaction_date,
                'invoice' => null,
                'price' => $office_payment->amount,
                'purchase_price' => null,
                'product' => null,
                'pax_name' => null,
                'pax_mobile_no' => null,
                'pax_type' => null,
                'transaction_id' => $office_payment->transaction_id,
                'account_name' => $office_payment->fromAccount?->account_name ?? 'N/A',
                'account_number' => $office_payment->fromAccount?->transaction_number ?? 'N/A',
            ];
        }));

    // Sort the combined data by date
    $combined = $combined->sortBy('date')->values(); // Reindex to prevent gaps

    // Calculate previous balance
    $previous_balance = $this->calculatePreviousBalance($id, $start_date);
    // Pass the data to the view
    return view('backend.pages.customer.sale', [
        'sales' => $sales,
        'start_date' => $start_date,
        'end_date' => $end_date,
        'customer' => $customer,
        'customer_payment' => $customer_payment,
        'office_payment' => $office_payment,
        'purchase' => $purchase,
        'combined' => $combined,
        'previous_balance' => $previous_balance,
    ]);
}



protected function calculatePreviousBalance($id, $start_date)
{
    $credit = Sale::where('sale_customer_id', $id)
        ->where('sale_date', '<', $start_date)
        ->sum('sale_price');

    $purchase_debit = Purchase::where('purchase_vendor_id', $id)
        ->where('purchase_date', '<', $start_date)
        ->sum('purchase_price');

    $customer_debit = Transaction::where('customer_id', $id)
        ->where('transaction_date', '<', $start_date)
        ->where('payment_type', 'client_payment')
        ->sum('amount');

    $office_debit = Transaction::where('customer_id', $id)
        ->where('payment_type', 'office_payment')
        ->where('transaction_date', '<', $start_date)
        ->sum('amount');

    return $credit - ($purchase_debit + $customer_debit - $office_debit);
}

    public function transactionDelete(Request $request, $id)
    {

        if (!auth()->user()->hasRole('admin')) {
            session()->flash('warning', 'You do not have permission to delete a payment.');
            return back();
        }
        $transaction = Transaction::findOrFail($id);
        if ($transaction->payment_type == "office_payment") {
            $account = AgencyAccount::find($transaction->account_id);
            if ($account) {
                $account->current_balance += $transaction->amount;
                $account->save();
            }
        }

        if ($transaction->payment_type == "client_payment") {
            $account = AgencyAccount::find($transaction->account_id);
            if ($account) {
                $account->current_balance -= $transaction->amount;
                $account->save();
            }
        }


        $transaction->delete();

        session()->flash('success', 'Transaction deleted successfully!');
        return back();
    }
    public function dueCustomerList()
    {
 // base query
        $customers = User::type('customer')->get();

        $due_customers = $customers->map(function ($customer) {
            $credit = Sale::where('sale_customer_id', $customer->id)->sum('sale_price');
            $purchase_debit = Purchase::where('purchase_vendor_id', $customer->id)->sum('purchase_price');
            $deposit_debit = Transaction::where('customer_id', $customer->id)
                ->where('payment_type', 'client_payment')
                ->sum('amount');
            $gotrip_debit = Transaction::where('customer_id', $customer->id)
                ->where('payment_type', 'gotrip_payment')
                ->sum('amount');

            $balance = $credit - ($purchase_debit + $deposit_debit - $gotrip_debit);

            if ($balance > 0) {
                $customer->balance = $balance;
                return $customer;
            }
        })->filter();

       

         $pdf = Pdf::loadView('backend.pages.customer.due-pdf', compact( 'due_customers'))
            ->setPaper('a4', 'portrait');
        return $pdf->download('Due-customer-list.pdf');
        
    }



}
