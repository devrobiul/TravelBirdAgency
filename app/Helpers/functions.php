<?php

use App\Models\Setting;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

if (!function_exists('uploadFile')) {
    function uploadFile($file, $path = 'frontend/upload/files')
    {
        if ($file) {
            $fileName = hexdec(uniqid()) . '.' . $file->extension();
            $file->move(public_path($path), $fileName);
            $filePath = $path . '/' . $fileName;
            return str_replace('\\', '/', $filePath);
        }
        return null;
    }
}


if (! function_exists('setting')) :
    function setting($name, $default = null)
    {
        static $settings;
        if (! $settings) {
            $settings = Setting::get()->pluck('value', 'name')->toArray();
        }

        return $settings[$name] ?? $default;
    }
endif;



function currencyBD($num) {
    $num = (int) $num; // Convert to integer to remove decimals
    $num = (string) $num;
    $len = strlen($num);
    if ($len > 3) {
        $lastThree = substr($num, -3);
        $rest = substr($num, 0, $len - 3);
        $rest = preg_replace("/\B(?=(\d{2})+(?!\d))/", ",", $rest);
        $num = $rest . ',' . $lastThree;
    }
    return $num;
}


if (!function_exists('transaction')) {
    function transaction(array $data)
    {
        return Transaction::create([
            'user_id'          => Auth::id(),
            'product_id'       => $data['product_id'] ?? null,
            'from_account_id'  => $data['from_account_id'] ?? null,
            'to_account_id'    => $data['to_account_id'] ?? null,
            'amount'           => $data['amount'] ?? 0,
            'transaction_type' => $data['transaction_type'] ?? 'general',
            'transaction_number' => $data['transaction_number'] ?? Str::upper(Str::random(10)),
            'transaction_date' => $data['transaction_date'] ?? now(),
            'note'             => $data['note'] ?? null,
            'status'           => $data['status'] ?? 'pending',
            'created_at'       => now(),
        ]);
    }
}


function numberToWords($num){
    $ones = ["","one","two","three","four","five","six","seven","eight","nine","ten",
            "eleven","twelve","thirteen","fourteen","fifteen","sixteen","seventeen",
            "eighteen","nineteen"];
    $tens = ["","","twenty","thirty","forty","fifty","sixty","seventy","eighty","ninety"];

    if($num < 20) return ucfirst($ones[$num]);
    if($num < 100) return ucfirst($tens[intval($num/10)].($num%10? " ".$ones[$num%10] : ""));
    if($num < 1000) return ucfirst($ones[intval($num/100)]." hundred".($num%100? " ".numberToWords($num%100) : ""));
    if($num < 1000000) return ucfirst(numberToWords(intval($num/1000))." thousand".($num%1000? " ".numberToWords($num%1000) : ""));
    return ucfirst($num); // simplicity
}