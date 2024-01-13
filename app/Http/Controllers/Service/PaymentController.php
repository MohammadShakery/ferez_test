<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;

class PaymentController extends Controller
{
    public function store($encrypted_order)
    {
        $order= Order::query()->where('id',decrypt($encrypted_order))->first();
        if($order->pay == 1)
        {
            return response([
                'message' => 'این فاکتور یکبار پرداخت شده است و امکان پرداخت مجدد آن وحود ندراد'
            ],200);
        }
        $invoice = (new Invoice)->amount($order->amount);
        return Payment::callbackUrl(route('order.verify'))->purchase($invoice, function($driver, $transactionId) use ($order){
            $order->update([
                'transaction_id' => $transactionId ,
                'pay' => 1
            ]);
        })->pay()->render();
    }

    public function verify(Request $request)
    {
        $order = Order::query()->where('transaction_id', $request->get('Authority'))->first();
        $receipt = Payment::amount($order->amount)->transactionId($order->transaction_id)->verify();
        dd($receipt);
    }
}
