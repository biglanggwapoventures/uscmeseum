<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Item;

class MostFavoritedItemsController extends Controller
{
    public function __invoke()
    {
        $items = Item::withCount('likers')
                     ->with('category')
                     ->get()
                     ->sortByDesc('likers_count')
                     ->values();

        return view('most-favorited-items', compact('items'));
    }
}
