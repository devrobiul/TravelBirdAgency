<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AgencyAccount;
use App\Models\Deposit;
use App\Models\Depositamount;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SalePassport;
use App\Models\Ticket;
use App\Models\VisaSale;
use App\Models\Withdraw;
use App\Models\BalanceTransfer;
use App\Models\Income;
use App\Models\Salary;
use App\Models\User;
use App\Models\Purchase;
use App\Models\Transaction;
use Carbon\Carbon;
use DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    public function transaction()
    {
        return view('backend.pages.report.transaction',[
            'accounts' => AgencyAccount::all(),
        ]);
    }

public function transactionReport(Request $request)
{
    $request->validate([
        'from_account_id' => 'required|exists:agency_accounts,id',
        'start_date'      => 'required|date',
        'end_date'        => 'required|date',
    ]);

    $account = AgencyAccount::findOrFail($request->from_account_id);

    $transactionsOut = $account->transactionsOut()
                        ->whereDate('transaction_date', '>=', $request->start_date)
                        ->whereDate('transaction_date', '<=', $request->end_date)
                        ->get();

    $transactionsIn = $account->transactionsIn()
                        ->whereDate('transaction_date', '>=', $request->start_date)
                        ->whereDate('transaction_date', '<=', $request->end_date)
                        ->get();

    // Merge out/in and sort by date
    $transactions = $transactionsOut->merge($transactionsIn)->sortBy('transaction_date');

    $transaction_amount = $transactions->sum('amount');

    if ($transactions->isEmpty()) {
        session()->flash('warning', 'No data found for the selected date range.');
        return back();
    }

    // PDF
    $pdf = Pdf::loadView('backend.pages.report.transaction_pdf', [
        'transactions' => $transactions,
        'account'      => $account,
        'start_date'   => $request->start_date,
        'end_date'     => $request->end_date,
        'transaction_amount' => $transaction_amount,
    ]);

    return $pdf->download('transaction_report_'. now()->format('Y_m_d') .'.pdf');
}




    public function expenseReport()
    {
        return view('backend.pages.report.expense');
    }

   
    public function expenseReportPdf(Request $request)
    {
        $request->validate([
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $expenses = Expense::whereBetween('expense_date', [$start_date, $end_date])->orderBy('expense_date', 'ASC')->get();
        $expense_amount = $expenses->sum('expense_amount');
        if ($expenses->isEmpty()) {
            session()->flash('warning', 'No data found for the selected date range.');
            return back();
        }
        $pdf = Pdf::loadView('backend.pages.report.expense_pdf', compact('expenses', 'start_date', 'end_date', 'expense_amount'));
        return $pdf->download('expense_reports_' . now()->format('Y_m_d') . '.pdf');
    }


    public function saleReport()
    {
        return view('backend.pages.report.sale');
    }
    public function saleReportPdf(Request $request)
    {
        $request->validate([
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $product_type = $request->product_type; 
        $query = Sale::with(['product', 'product.airline'])
            ->whereBetween('sale_date', [$start_date, $end_date])->orderBy('sale_date', 'ASC');
        if ($product_type) {
            $query->whereHas('product', function ($q) use ($product_type) {
                $q->where('product_type', $product_type);
            });
        }
        $sales = $query->get();
        $product_profit = Product::whereBetween('sale_date', [$start_date, $end_date])->sum('product_sale_profit');
        $product_loss = Product::whereBetween('sale_date', [$start_date, $end_date])->sum('product_sale_loss');
      
        $sale_price = $sales->sum('sale_price');
        $sale_profit = $sales->sum('sale_profit') + $product_profit;
        $sale_loss = $sales->sum('sale_loss') + $product_loss;

        if ($sales->isEmpty()) {
            session()->flash('warning', 'No data found for the selected date range.');
            return back();
        }

        $pdf = Pdf::loadView('backend.pages.report.sale_pdf', compact('sales', 'start_date', 'end_date', 'sale_price', 'sale_profit', 'sale_loss', 'product_type'))->setPaper('a4', 'portrait');
        return $pdf->download('sale_purchase_reports_' . now()->format('Y_m_d') . '.pdf');
    }
    

public function profitloss(Request $request)
{
    $data = null;
    $start_date = null;
    $expenses = null;
    $total_expense = null;
    $end_date = null;
    $extraIncome = null;
    $totalExtraIncome = null;

    if ($request->filled('start_date') && $request->filled('end_date')) {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date',
        ]);

        $start_date = $request->start_date;
        $end_date   = $request->end_date;

        $types = [
            'group_ticket',
            'single_ticket',
            'ticket_refund',
            'hotel_booking',
            'custom_bill',
            'manpower',
            'passport',
            'visa',
        ];

        $data = [];

        foreach ($types as $type) {
            $sales = Sale::whereHas('product', function ($q) use ($type) {
                    $q->where('product_type', $type);
                })
                ->whereBetween('sale_date', [$start_date, $end_date])
                ->get();

            $purchase = Purchase::whereHas('product', function ($q) use ($type) {
                    $q->where('product_type', $type);
                })
                ->whereBetween('purchase_date', [$start_date, $end_date])
                ->get();

            $sale_price     = $sales->sum('sale_price');
            $purchase_price = $purchase->sum('purchase_price'); // নতুন যোগ
            $sale_profit    = $sales->sum('sale_profit');
            $sale_loss      = $sales->sum('sale_loss');

            $products = Product::where('product_type', $type)
                ->whereBetween('sale_date', [$start_date, $end_date])
                ->get();

            $product_profit = $products->sum('product_sale_profit');
            $product_loss   = $products->sum('product_sale_loss');

            $data[$type] = [
                'sale'           => $sale_price,
                'purchase'       => $purchase_price, // Blade এ দেখানোর জন্য
                'profit'         => $sale_profit + $product_profit,
                'loss'           => $sale_loss + $product_loss,
            ];
        }
        $expenses = Expense::whereBetween('expense_date', [$start_date, $end_date])
                ->get();
       $total_expense = Expense::whereBetween('expense_date', [$start_date, $end_date])->sum('expense_amount');
        $extraIncome = Income::whereBetween('income_date', [$start_date, $end_date])
                ->get();
       $totalExtraIncome= Income::whereBetween('income_date', [$start_date, $end_date])->sum('income_amount');
       
    }

    return view('backend.pages.report.profit-loss', compact('data','start_date','end_date','expenses','total_expense','totalExtraIncome','extraIncome'));
}


        public function profitlossSearch(Request $request)
    {
          
        
   

    }

 
}
