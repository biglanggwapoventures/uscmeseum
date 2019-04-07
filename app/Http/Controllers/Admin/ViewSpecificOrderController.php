<?php

namespace App\Http\Controllers\Admin;

use App\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ViewSpecificOrderController extends Controller
{
    public function __invoke(Order $order)
    {

    }
}
