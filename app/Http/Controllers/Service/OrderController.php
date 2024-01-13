<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\Servic\OrderStoreRequest;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        return response([
            'status' => true ,
            'orders' => Order::query()->where('user_id',($this->getUser($request)))->orderByDesc('created_at')->get()
        ],200);
    }

    public function store(OrderStoreRequest $request)
    {
        $user = $this->getUser($request);
        $order = Order::query()->create([
            'user_id' => $user->id ,
            'amount' => $request->get('amount')
        ]);
        $order_encrypted = encrypt($order->id);
        return response([
            'status' => true ,
            'url' => 'https://api.ferez.net/order/'.$order_encrypted
        ],200);
    }

    public function getUser(Request $request)
    {
        return User::query()->where('phone',decrypt($request->header('token'))['BMSN'])->firstOrFail();
    }
}
