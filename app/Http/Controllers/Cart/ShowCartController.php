<?php

namespace App\Http\Controllers\Cart;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cart;

class ShowCartController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('cart', [
            'contents' => Cart::allContents()
        ]);
    }
}
