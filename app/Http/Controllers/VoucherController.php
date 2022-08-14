<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VoucherController extends Controller
{
    public function create_voucher(Request $request){
        $voucher = Voucher::create([
            'users_id'=>$request->user()->id,
            'voucher_code' =>Str::random(40)]);
        return $voucher;
    }
    public function get_voucher(Request $request){
        $vouchers = $request->user()->vouchers()->paginate(5);
        return $vouchers;
    }
}
