<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Order;
use Illuminate\Http\Request;

class SalesReportController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'start' => 'date',
            'end'   => 'date|after:start'
        ]);

        $result = Order::query()
                       ->where('order_status', Order::STATUS_APPROVED)
                       ->when($startDate = $request->input('start'), function ($q) use ($startDate) {
                           $q->whereRaw("DATE(created_at) >= '{$startDate}'");
                       })
                       ->when($endDate = $request->input('end'), function ($q) use ($endDate) {
                           $q->whereRaw("DATE(created_at) <= '{$endDate}'");
                       })
                       ->with('orderDetails.item')
                       ->get();

        $data = $result->flatMap->orderDetails;

//        dd($data);


        return view('sales-report', compact('data'));
    }
}
