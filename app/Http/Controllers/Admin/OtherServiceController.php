<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgencyAccount;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OtherServiceController extends Controller
{
    public function index(){
        return view('backend.pages.otherSale.index',[
            'customers'=>User::type('customer')->get(),
            'customBills'=>Product::where('product_type','custom_bill')->latest()->get(),
        ]);
    }
    public function create(){
        return view('backend.pages.otherSale.create',[
            'customers'=>User::type('customer')->get(),
            'account'=>AgencyAccount::all(),
        ]);
    }
    public function edit($id){
        return view('backend.pages.otherSale.edit',[
            'customers'=>User::type('customer')->get(),
            'account'=>AgencyAccount::all(),
            'bill'=>Product::findOrFail($id),
        ]);
    }
    public function update(Request $request,$id){

           {

        $request->validate([
            'sale_customer_id' => 'required',
            'sale_date' => 'required',
            'purchase_price' => 'required|numeric',
            'service_type' => 'required',
        ]);

        $product = Product::findOrFail($id);

            $metaData = [];
            if (is_array($request->service_name)) {
                foreach ($request->service_name as $index => $name) {
                    $metaData[] = [
                        'service_name' => $name,
                        'service_cost' => $request->service_cost[$index] ?? null,
                    ];
                }
            }

        DB::beginTransaction();

        try {
            $product->update([
                'user_id' => auth()->id(),
                'sale_date' => $request->sale_date,
                'service_type' => $request->service_type,
                'meta_data' => json_encode($metaData),
                'updated_at' =>now(),
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
                    }
                }
                Transaction::where('product_id',$product->id)->delete();
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
            }
                transaction([
                    'product_id' => $product->id,
                    'from_account_id' => $account->id,
                    'amount' => $request->purchase_price,
                    'transaction_type' =>'Bill -'. $product->invoice_no,
                    'transaction_date' => $request->sale_date,
                    'note' => 'Custom Bill payment for product',
                ]);
            DB::commit();
            session()->flash('success', 'Custom bill updated successfully!');
            $route = route('admin.inventory.other.index');

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
                Transaction::where('product_id',$product->id)->delete();
            }
        }
        $product->sales()->delete();
        $product->purchase()->delete();
        $product->delete();
        session()->flash('success', 'Product deleted successfully!');
        return back();
    }
}
