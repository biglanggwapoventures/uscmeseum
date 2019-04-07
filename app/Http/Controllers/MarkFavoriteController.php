<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class MarkFavoriteController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $itemId)
    {
        Auth::user()->favoriteItems()->toggle([$itemId]);

        return response()->json([
            'result' => true
        ]);
    }

    public function index()
    {
        $items = Auth::user()->favoriteItems;

        return view('my-favorites', compact('items'));
    }
}
