<?php

namespace App\Http\Controllers\Cart;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UpdateCartController extends Controller
{
    public function __invoke(Request $request)
    {
        $input = $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|int|min:0',
            'strategy' => 'required|in:append,replace' 
        ]);

        \Cart::updateContents($input['item_id'], $input['quantity'], $input['strategy']);

        if($request->ajax()){
            return response()->json([
                'result' => true
            ]);
        }
        
        return redirect()->back()->with('message', 'You have successfully updated your cart!');
    }
}
