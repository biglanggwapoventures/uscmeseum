<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cart;
use DB;
use App\Order;
use Auth;
use Illuminate\Http\JsonResponse;
use Mail;
use Session;

class CheckoutController extends Controller
{
    public function __invoke(Request $request) : JsonResponse
    {
        /**
         * Get the user's delivery address and remarks
         * from the checkout page
         */
        $input = $request->validate([
//            'delivery_address' => 'required|string',
            'remarks'          => 'present|nullable',
        ]);

        /** @var Order $order */
        $order = null;

        /**
         * Use DB transaction because storing an order
         * in the database requires multiple queries
         */
        DB::transaction(function () use ($input, &$order) {


            /**
             * Get all items in cart
             */
            $cartItems = Cart::allContents();

            $order = null;

            /**
             * Create the order
             */
            $order = Order::create([
                'user_id'          => Auth::id(),
//                'delivery_address' => $input['delivery_address'],
                'delivery_address' => '-',
                'remarks'          => $input['remarks'],
                'order_status',
                'order_status_remarks',
            ]);

            /**
             * Create the order detail structure based
             * from the items in the cart
             */
            $orderDetails = $cartItems->map(function ($item) {
                return [
                    'item_id'       => $item['product']->id,
                    'quantity'      => $item['quantity'],
                    'selling_price' => $item['product']->selling_price,
                    'cost'          => $item['product']->purchase_cost
                ];
            });

            /**
             * Then we associate the order details
             */
            $order->orderDetails()->createMany($orderDetails->toArray());


        });

        Cart::clear();

        $this->sendEmail($order);

        Session::flash('checkout', true);

        /**
         * Everything seems to be ok!
         */
        return response()->json([
            'result'   => true,
            'redirect' => url('orders')
        ]);
    }

    public function sendEmail(Order $order)
    {
        $order->load(['user', 'orderDetails.item']);

        Mail::send(
            'email.checkout',
            compact('order'),
            function ($mail) use ($order) {
                $mail->to($order->user->email, $order->user->fullname)
                     ->subject('Order Confirmation')
                     ->from('uscmuseum2019@gmail.com', config('app.name'));
            }
        );
    }
}
