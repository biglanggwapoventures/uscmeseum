<?php

namespace App\Http\Controllers;

use App\Helpers\PayPal;
use Illuminate\Http\Request;
use Cart;
use DB;
use App\Order;
use Auth;
use LVR\CreditCard\{CardNumber, CardExpirationYear, CardExpirationMonth, CardCvc};
use Mail;
use Session;

class CheckoutController extends Controller
{
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

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $yearOptions  = $this->buildYearDataFormat();
        $monthOptions = $this->buildMonthDataFormat();
        $products     = Cart::allContents();

        return view('checkout', compact('yearOptions', 'monthOptions', 'products'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $input = $request->validate([
            'firstName'       => 'required',
            'lastName'        => 'required',
            'number'          => ['required', new CardNumber()],
            'expiryMonth'     => ['required', new CardExpirationMonth($request->input('expiryYear'))],
            'expiryYear'      => ['required', new CardExpirationYear($request->input('expiryMonth'))],
            'cvv'             => ['required', new CardCvc($request->input('number'))],
            'billingAddress1' => 'required',
            'billingCity'     => 'required',
            'billingPostcode' => 'required',
        ]);

        $creditCard = array_merge($input, [
            'billingCountry' => 'PH',
        ]);

        try {

            /** @var Order o$order */
            $order = $this->checkout($request);

            $gateway = new PayPal();

            $gateway->setCard($creditCard);

            $payment = $gateway->pay($order->total_amount);

            if ($payment['status']) {

                Cart::clear();
                $this->sendEmail($order);
                Session::flash('checkout', true);

                $order->transaction_details = $payment['details'];
                $order->save();

                return redirect('orders');

            } else {


                return redirect()->back()->with([
                    'creditCardError' => 'Credit card unavailable. Please contact your credit card provider',
                    'debug'           => $payment['details']
                ]);


            }

        } catch (\ErrorException $e) {

            return redirect()->back()->with([
                'creditCardError' => 'Credit card unavailable. Please contact your credit card provider',
                'debug'           => $e->getTrace()
            ]);

        }
    }

    protected function checkout(Request $request) : Order
    {
        /** @var Order $order */
        $order = null;

        /**
         * Use DB transaction because storing an order
         * in the database requires multiple queries
         */
        DB::transaction(function () use ($request, &$order) {


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
                'delivery_address' => $request->input('remarks', '-'),
                'remarks'          => $request->input('remarks', '-'),
                'order_status'     => Order::STATUS_APPROVED,
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
            $order->load('orderDetails.item');
            $order->orderDetails->each(function ($detail) use ($order){
                $detail->item->logs()->create([
                    'quantity' => ($detail->quantity * -1),
                    'item_id' => $detail->item->id,
                    'reason' => "Order # {$order->id}"
                ]);
            });


        });

        return $order;
    }

    /**
     * @return array
     */
    protected function buildYearDataFormat() : array
    {
        $data = [];

        $startYear = now()->subYears(10);
        $endYear   = now()->addYears(10);

        while ($startYear->lte($endYear)) {
            $yearString        = $startYear->format('y');
            $data[$yearString] = $yearString;

            $startYear = $startYear->addYear();
        }

        return $data;
    }

    /**
     * @return array
     */
    protected function buildMonthDataFormat() : array
    {
        $data = [];

        foreach (range(1, 12) AS $month) {
            $monthString        = str_pad("{$month}", 2, "0", STR_PAD_LEFT);
            $data[$monthString] = $monthString;
        }

        return $data;
    }
}
