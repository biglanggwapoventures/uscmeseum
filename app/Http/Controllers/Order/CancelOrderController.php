<?php

namespace App\Http\Controllers\Order;

use App\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CancelOrderController extends Controller
{
    public function __invoke(Order $order)
    {
        $order->cancel();

        return redirect()->back();
    }
}
