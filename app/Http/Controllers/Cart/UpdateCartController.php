<?php

namespace App\Http\Controllers\Cart;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Item;

class UpdateCartController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|int|min:1',
            'strategy' => 'required|in:append,replace'
        ]);

        $validator->after(function ($validator) use ($request) {
            if($validator->errors()->count()){
                return;
            }
            $item = Item::with('logs')->find($request->input('item_id'));

            if($item->balance < intval($request->input('quantity'))){
                $validator->errors()->add('quantity', 'Insufficient item stock');
            }
        });

        $validator->validate();

        $input = $validator->valid();

        \Cart::updateContents($input['item_id'], $input['quantity'], $input['strategy']);

        if($request->ajax()){
            return response()->json([
                'result' => true
            ]);
        }
        
        return redirect()->back()->with('message', 'You have successfully updated your cart!');
    }
}
