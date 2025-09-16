<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AgencyAccount;
use App\Models\Customer;
use App\Models\Depositamount;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdraw;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
public function dashboard(Request $request)
{
    // -----------------------------
    // Customers and Due Calculation
    // -----------------------------
    $customers = User::type('customer')->get();
    $totalCustomerDue = 0;
    $totalCustomerDueCount = 0;

    foreach ($customers as $customer) {
        $credit = Sale::where('sale_customer_id', $customer->id)->sum('sale_price');
        $purchase_debit = Purchase::where('purchase_vendor_id', $customer->id)->sum('purchase_price');
        $customer_payment = Transaction::where('customer_id', $customer->id)
            ->where('payment_type', 'client_payment')
            ->sum('amount');
        $office_payment = Transaction::where('customer_id', $customer->id)
            ->where('payment_type', 'office_payment')
            ->sum('amount');

        $balance = $credit - ($purchase_debit + $customer_payment - $office_payment);

        if ($balance > 0) {
            $totalCustomerDue += $balance;
            $totalCustomerDueCount++;
        }
    }

    // -----------------------------
    // Office Due Calculation
    // -----------------------------
    $officeData = User::type('customer')->get()->map(function ($customer) {
        $credit = Sale::where('sale_customer_id', $customer->id)->sum('sale_price');
        $purchase_debit = Purchase::where('purchase_vendor_id', $customer->id)->sum('purchase_price');
        $customer_payment = Transaction::where('customer_id', $customer->id)
            ->where('payment_type', 'client_payment')
            ->sum('amount');
        $office_payment = Transaction::where('customer_id', $customer->id)
            ->where('payment_type', 'office_payment')
            ->sum('amount');

        $balance = $credit - ($purchase_debit + $customer_payment - $office_payment);

        return $balance < 0 ? abs($balance) : null; // Ignore positive balances
    })->filter();

    $totalOfficeDue = $officeData->sum();
    $totalOfficeDueCount = $officeData->count();

    // -----------------------------
    // Sales and Profit/Loss
    // -----------------------------
    $sales = Sale::with(['product', 'product.airline'])
        ->where("sale_date", Carbon::today()->format('Y-m-d'))
        ->latest()
        ->get();

    $sale_price = $sales->sum('sale_price');
    $sale_profit = $sales->sum('sale_profit');
    $sale_loss = $sales->sum('sale_loss');

    $credit = Sale::sum('sale_price');
    $purchase_debit = Purchase::where('purchase_vendor_id', '!=', 0)->sum('purchase_price');
    $clientDebit = Transaction::where('payment_type', 'client_payment')->sum('amount');
    $officeDebit = Transaction::where('payment_type', 'office_payment')->sum('amount');

    $due_balance = $credit - ($purchase_debit + $clientDebit + $officeDebit);

    $group_profit = Product::sum('product_sale_profit');
    $group_loss = Product::sum('product_sale_loss');
    $today_group_profit = Product::whereDate('sale_date', Carbon::today())->sum('product_sale_profit');
    $today_group_loss = Product::whereDate('sale_date', Carbon::today())->sum('product_sale_loss');
    $total_profit = Sale::sum('sale_profit');
    $today_sale_profit = Sale::whereDate('sale_date', Carbon::today())->sum('sale_profit');


$months_Chart = [];
$sales_Chart = [];
$sale_loss_Chart = [];
$sale_profit_Chart = [];

$selectedYear = $request->input('year', Carbon::now()->year);

for ($m = 1; $m <= 12; $m++) {
    $months_Chart[] = Carbon::create($selectedYear, $m, 1)->format('F');

    // Sale + Product Monthly Totals
    $monthly_sale_price = Sale::whereYear('sale_date', $selectedYear)
                              ->whereMonth('sale_date', $m)
                              ->sum('sale_price');

    $monthly_sale_loss = Sale::whereYear('sale_date', $selectedYear)
                             ->whereMonth('sale_date', $m)
                             ->sum('sale_loss');

    $monthly_sale_profit = Sale::whereYear('sale_date', $selectedYear)
                               ->whereMonth('sale_date', $m)
                               ->sum('sale_profit');

    $monthly_product_loss = Product::whereYear('sale_date', $selectedYear)
                                   ->whereMonth('sale_date', $m)
                                   ->sum('product_sale_loss');

    $monthly_product_profit = Product::whereYear('sale_date', $selectedYear)
                                     ->whereMonth('sale_date', $m)
                                     ->sum('product_sale_profit');

    // Combine Sale + Product
    $sales_Chart[] = $monthly_sale_price;
    $sale_loss_Chart[] = $monthly_sale_loss + $monthly_product_loss;
    $sale_profit_Chart[] = $monthly_sale_profit + $monthly_product_profit;
}







    // -----------------------------
    // Return View with Data
    // -----------------------------
    return view('backend.pages.dashboard.index', [
        'staff' => User::role('staff')->count(),
        'client' => User::type('customer')->count(),
        'account_total' => AgencyAccount::sum('current_balance'),
        'account' => AgencyAccount::count(),
        'total_sale' => Sale::sum('sale_price'),
        'today_sale' => Sale::where("sale_date", Carbon::today()->format('Y-m-d'))->sum('sale_price'),
        'total_loss' => Sale::sum('sale_loss')+$group_loss,
        'today_sale_loss' => Sale::where("sale_date", Carbon::today()->format('Y-m-d'))->sum('sale_loss')+$today_group_loss,
        'sales' => $sales,
        'sale_price' => $sale_price,
        'sale_profit' => $sale_profit,
        'sale_loss' => $sale_loss,
        'due_balance' => $due_balance,
        'today_expense' => Expense::where("expense_date", Carbon::today()->format('Y-m-d'))->sum('expense_amount'),
        'total_expense' => Expense::sum('expense_amount'),
        'due_customers_count' => $totalCustomerDueCount,
        'total_profit' => $total_profit + $group_profit,
        'today_sale_profit' => $today_sale_profit + $today_group_profit,
        'total_cus_due' => $totalCustomerDue,
        'total_office_due' => $totalOfficeDue,
        'office_due_count' => $totalOfficeDueCount,
        'todayClientPayment' => Transaction::where([['payment_type', 'client_payment'],['transaction_date', Carbon::today()->toDateString()]])->get(),
        'todayOfficePayment' => Transaction::where([['payment_type', 'office_payment'],['transaction_date', Carbon::today()->toDateString()]])->get(),
        'total_deposit' => Transaction::where('transaction_type', 'deposit')->sum('amount'),
        'today_withdraw' => Transaction::where("transaction_date", Carbon::today()->format('Y-m-d'))
            ->where('transaction_type', 'withdraw')
            ->sum('amount'),
        'total_withdraw' => Transaction::where('transaction_type', 'withdraw')->sum('amount'),
  
        'today_office_payment_list' => Transaction::where('payment_type', 'office_payment')
            ->where("transaction_date", Carbon::today()->format('Y-m-d'))
            ->get(),
    'months_Chart' => $months_Chart,
    'sales_Chart' => $sales_Chart,
    'sale_loss_Chart' => $sale_loss_Chart,
    'sale_profit_Chart' => $sale_profit_Chart,
    'currentYear' => $selectedYear,
    ]);
}

public function cacheClear()
{
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    
    session()->flash('success', 'Cache cleared successfully!');
    return redirect()->back();
}



    public function changePassword()
    {
        return view('backend.pages.dashboard.change-password');
    }
    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();
        Auth::logoutOtherDevices($request->password);
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Auth::logout();
        session()->flash('success', 'Password changed successfully. All other devices are logged out.');
        return redirect()->route('login');
    }


    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        session()->flash('success', 'Logged out successfully!');
        return redirect()->route('login');
    }
}
