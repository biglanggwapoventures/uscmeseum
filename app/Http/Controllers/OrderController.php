<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\{User, Order};

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::with(['customer', 'orderDetails.item'])
                       ->when(Auth::user()->isRole(User::ROLE_STANDARD), function ($query) {
                           $query->where('user_id', Auth::id());
                       })
                       ->latest()
                       ->get();

        return view('orders.index', [
            'orders' => $orders
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Order $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Order $order)
    {
        $order->load(['orderDetails.item', 'customer']);

        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Order   $order
     */
    public function update(Request $request, Order $order)
    {
        $input = $request->validate([
            'order_status' => 'required|in:pending,rejected,approved'
        ]);

        $order->fill($input);
        $order->save();

        if($order->status('approved')){
            $order->load('orderDetails.item');
            $order->orderDetails->each(function ($detail) use ($order){
                $detail->item->logs()->create([
                    'quantity' => ($detail->quantity * -1),
                    'item_id' => $detail->item->id,
                    'reason' => "Order # {$order->id}"
                ]);
            });
        }

        return redirect('orders')->with('message', "Order # {$order->id} has been successfully {$input['order_status']}");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
