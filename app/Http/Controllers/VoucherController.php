<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VoucherController extends Controller
{
    public function create_voucher(Request $request)
    {
        $user = User::where('id',$request->user()->id)->first();
        if (count($user->vouchers) >= 10) {
            return 'Error';
        } else {
            $voucher = Voucher::create([
                'users_id' => $user->id,
                'voucher_code' => Str::random(40)
            ]);
            return 'Success';
        }
    }
    public function get_voucher(Request $request)
    {
        $vouchers = $request->user()->vouchers()->paginate(5);
        return $vouchers;
    }
    public function delete_voucher(Request $request)
    {
        $voucher = Voucher::where('id',$request->voucher_id)->first();
        $voucher->delete();
        return 'success';
    }
}
