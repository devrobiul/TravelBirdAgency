<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Transaction;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfDownloadController extends Controller
{

    // Single Ticket PDF
    public function singleTicketPdf($id)
    {
        $product = Product::findOrFail($id);
        $pax_datas = json_decode($product->pax_data, true);
        $filename = $product->sales->customer->name . '_' . $product->ticket_pnr . '.pdf';
        $pdf = Pdf::loadView('backend.pages.ticket.single_ticket_invoice', compact('product', 'pax_datas'))
            ->setPaper('a4', 'portrait');

        return $pdf->download($filename);
    }

    // Hotel Booking PDF
    public function hotelbookingPdf($id)
    {
        $hotel = Product::findOrFail($id);
        $pax_data = json_decode($hotel->pax_data, true);
        $filename = 'invoice_' . $hotel->sales->customer->name . '_' . $hotel->hotel_name . '.pdf';
        $pdf = Pdf::loadView('backend.pages.booking.invoice', compact('hotel', 'pax_data'))
            ->setPaper('a4', 'portrait');
        return $pdf->download($filename);
    }
    // Passport Sale PDF
    public function passportSalePdf($id)
    {
        $product = Product::findOrFail($id);
        $filename = $product->sales->customer->name . '_' . $product->passport_type . '.pdf';
        $pax_data = json_decode($product->pax_data, true);
        $pdf = Pdf::loadView('backend.pages.passport.invoice', compact('product', 'pax_data'))
            ->setPaper('a4', 'portrait');
        return $pdf->download($filename);
    }

    // Manpower Sale PDF
    public function manpowerSalePdf($id)
    {
        $product = Product::findOrFail($id);
        $pax_data = json_decode($product->pax_data, true);
        $filename = $product->sales->customer->name . '_' . $product->visit_country . '.pdf';
        $pdf = Pdf::loadView('backend.pages.manpower.invoice', compact('product', 'pax_data'))
            ->setPaper('a4', 'portrait');
        return $pdf->download($filename);
    }
    // Visa Sale PDF
    public function visaSalePdf($id)
    {
        $visasale = Product::findOrFail($id);
        $pax_data = json_decode($visasale->pax_data, true);
        $filename = 'invoice_' . $visasale->sales->customer->name . '_' . $visasale->visa->visa_name . '.pdf';
        $pdf = Pdf::loadView('backend.pages.visa_sale.invoice', compact('visasale', 'pax_data'))
            ->setPaper('a4', 'portrait');
        return $pdf->download($filename);
    }

    // Group Ticket PDF
    public function grouptikceSalePdf($id)
    {
        $product = Product::findOrFail($id);
        $filename = 'invoice_' . $product->ticket_pnr . '.pdf';
        $pdf = Pdf::loadView('backend.pages.ticket.group_ticket_invoice', compact('product'))
            ->setPaper('a4', 'portrait');
        return $pdf->download($filename);
    }



    // Customer Transaction PDF  b2bTransactionReport
    public function b2bTransactionReport(Request $request, $id)
    {
        $request->validate([
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $customer = User::find($id);
        if (!$customer) {
            return back()->with('error', 'Customer not found.');
        }
        $transaction = Transaction::where('customer_id', $customer->id)->where('payment_type', 'client_payment')->latest()->whereBetween('transaction_date', [$start_date, $end_date])->get();

        if ($transaction->isEmpty()) {
            session()->flash('warning', 'No data found for the selected date range.');
            return back();
        }
        $total_transaction = $transaction->sum('amount');
        $pdf = Pdf::loadView('backend.pages.customer.transaction_pdf', [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'customer' => $customer,
            'transaction' => $transaction,
            'total_transaction' => $total_transaction,
        ]);

        return $pdf->download('customer_transaction_report_' . now()->format('Y_m_d') . '.pdf');
    }



    // Customer Balance Sheet Download PDF
    public function customerBalanceSheetPdf(Request $request, $id)
    {

        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $customer = User::find($id);
        if (!$customer) {
            return back()->with('error', 'Customer not found.');
        }

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
        $combined = collect([])
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

        $combined = $combined->sortBy('date')->values();
        $previous_balance = $this->calculatePreviousBalance($id, $start_date);
        $pdf = PDF::loadView('backend.pages.customer.sale_pdf', [
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
        $pdf->setOption('page-break-inside', 'avoid');
        return $pdf->download($customer->name . '_sale_report_' . now()->format('Y_m_d') . '.pdf');
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


    // with out header

        public function billGeneratePDf($id)
    {
        $product = Product::findOrFail($id);
        $filename = 'invoice_' . $product->invoice_no. '.pdf';
        $pdf = Pdf::loadView('backend.pages.otherSale.invoice', compact('product'))
            ->setPaper('a4', 'portrait');
        return $pdf->download($filename);
    }
}
