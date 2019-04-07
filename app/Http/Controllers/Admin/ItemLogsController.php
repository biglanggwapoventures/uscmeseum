<?php

namespace App\Http\Controllers\Admin;

use App\Item;
use App\ItemLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ItemLogsController extends Controller
{
    public function index(Item $item, Request $request)
    {
        $item->load('logs');

        return view('item-logs', compact('item'));
    }

    public function store(Item $item, Request $request)
    {
        $input = $request->validate([
            'quantity' => 'required|numeric',
        ]);

        $item->logs()->create(array_merge($input, [
            'item_id' => $item->id,
            'reason' => 'Quantity Adjustment'
        ]));

        return redirect('admin/items')->with('message', "Successfully adjusted quantity for {$item->name}");
    }
}
