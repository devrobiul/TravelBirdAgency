<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AgencyAccount;
use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductController extends Controller
{


    public function store(Request $request)
    {

        $commonRules = [
            'sale_customer_id' => 'required',
            'sale_date' => 'required',
            'purchase_price' => 'required|numeric',
        ];

        if ($request->product_type == 'visa_sale') {
            $request->validate(array_merge($commonRules, [
                'visa_id' => 'required',
                'visa_type' => 'required',
                'visit_country' => 'required',
                'visa_issue_date' => 'required',
                'visa_exp_date' => 'required',
                'sale_price' => 'required|numeric',
                'purchase_vendor_id' => 'required',
            ]));

            if ($request->purchase_vendor_id == 0) {
                $request->validate([
                    'purchase_account_id' => 'required',
                    'purchase_tnxid' => 'required',
                ]);
            }
        }
        if ($request->product_type == 'hotel_booking') {
            $request->validate(array_merge($commonRules, [
                'hotel_name' => 'required',
                'hotel_location' => 'required',
                'hotel_purchase_email' => 'required',
                'hotel_number_of_day' => 'required',
                'visit_country' => 'required',
                'g_pax_name.*' => 'required|string',
                'g_pax_mobile_no.*' => 'required|numeric',
                'sale_price' => 'required|numeric',
                'purchase_account_id' => 'required',
                'purchase_tnxid' => 'required',
            ]));
        }
        if ($request->product_type == 'manpower') {
            $request->validate(array_merge($commonRules, [
                'visit_country' => 'required',
                'tracking_id' => 'required|unique:products,tracking_id',
                'g_pax_name.*' => 'required|string',
                'g_pax_type*' => 'required|string',
                'sale_price' => 'required|numeric',
                'purchase_vendor_id' => 'required',
            ]));
        }
        if ($request->product_type == 'custom_bill') {
            $request->validate([
                'service_type' => 'required',
                'service_name.*' => 'required|string',
                'service_cost*' => 'required|numaric',
           
            ]);
            if ($request->purchase_vendor_id == 0) {
                $request->validate([
                    'purchase_account_id' => 'required',
                ]);
            }
        }
        if ($request->product_type == 'single_ticket') {
            $request->validate(array_merge($commonRules, [
                'ticket_pnr' => 'required|unique:products,ticket_pnr',
                'ticket_type' => 'required',
                'airline_id' => 'required',
                'depart_date' => 'required',
                'g_pax_name.*' => 'required|string',
                'g_pax_type*' => 'required|string',
                'g_pax_mobile_no.*' => 'required|numeric',
                'sale_price' => 'required|numeric',
                'purchase_vendor_id' => 'required',
            ]));

            if ($request->purchase_vendor_id == 0) {
                $request->validate([
                    'purchase_account_id' => 'required',
                    'purchase_tnxid' => 'required',
                ]);
            }
        }
       

        if ($request->purchase_account_id) {
            $account = AgencyAccount::find($request->purchase_account_id);

            if ($account->current_balance >= $request->purchase_price) {
                $account->current_balance -= $request->purchase_price;
                $account->save();
            } else {
                return response()->json(['error' => 'Insufficient account balance'], 400);
            }
        }

            $paxData = [];
            if (is_array($request->g_pax_name)) {
                foreach ($request->g_pax_name as $index => $name) {
                    $paxData[] = [
                        'name'      => $name,
                        'type'      => $request->g_pax_type[$index] ?? 'Adult',
                        'mobile_no' => $request->g_pax_mobile_no[$index] ?? null,
                        'price'     => $request->g_pax_price[$index] ?? null,
                    ];
                }
            }

            $metaData = [];
            if (is_array($request->service_name)) {
                foreach ($request->service_name as $index => $name) {
                    $metaData[] = [
                        'service_name' => $name,
                        'service_cost' => $request->service_cost[$index] ?? null,
                    ];
                }
            }
        $invoice_no  = mt_rand(100000, 999999);
        $product = Product::create([
            'invoice_no' => $invoice_no,
            'user_id' => auth()->id(),
            'product_type' => $request->product_type,
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
            'service_type' => $request->service_type,
            'pax_data' => json_encode($paxData),
            'meta_data' => json_encode($metaData),
            'tracking_id' => $request->tracking_id,
            'application_date' => $request->application_date,
            'delivery_date' => $request->delivery_date,
            'visa_id' => $request->visa_id,
            'visa_type' => $request->visa_type,
            'visit_country' => $request->visit_country,
            'visa_issue_date' => $request->visa_issue_date,
            'visa_exp_date' => $request->visa_exp_date,
            'hotel_name' => $request->hotel_name,
            'hotel_location' => $request->hotel_location,
            'hotel_purchase_email' => $request->hotel_purchase_email,
            'hotel_number_of_day' => $request->hotel_number_of_day,
            'hotel_refer' => $request->hotel_refer,
            'created_at' =>now(),
        ]);

        Purchase::create([
            'product_id' => $product->id,
            'purchase_vendor_id' => $request->purchase_vendor_id ?? 0,
            'purchase_account_id' => $request->purchase_account_id,
            'purchase_price' => $request->purchase_price,
            'purchase_date' => $request->sale_date,
            'purchase_tnxid' => $request->purchase_tnxid,
            'purchase_note' => $request->purchase_note,
            'created_at' => Carbon::now(),
        ]);

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
            'transaction_type' => $request->product_type.'-'.$invoice_no,
            'transaction_date' => $request->sale_date,
            'note' => 'Purchase payment for product',
        ]);

        session()->flash('success', 'Product, purchase, and sales recorded successfully!');
        if ($request->product_type == 'visa_sale') {
            $route = route('admin.inventory.visasale.index');
        } elseif ($request->product_type == 'single_ticket') {
            $route = route('admin.inventory.singleticket.index');
        } elseif ($request->product_type == 'manpower') {
            $route = route('admin.inventory.manpower.index');
        } elseif ($request->product_type == 'hotel_booking') {
            $route = route('admin.inventory.hotel.index');
        }elseif($request->product_type == 'custom_bill') {
            $route = route('admin.inventory.other.index');
        }
        return response()->json([
            'success' => true,
            'message' => session('success'),
            'route' => $route
        ]);
    }
}
