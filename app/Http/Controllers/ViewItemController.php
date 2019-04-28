<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Item;

class ViewItemController extends Controller
{
    public function __invoke(Item $item, $slug)
    {
        $item->load(['category', 'attributes', 'logs']);
        
        if(Str::slug($item->name) !== $slug){
            return abort(404);
        }

        return view('view-item', compact('item'));
    }
}
