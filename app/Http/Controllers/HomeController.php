<?php

namespace App\Http\Controllers;

use App\Category;
use App\Item;
use Illuminate\Http\Request;


class HomeController extends Controller
{
    public function welcome(Request $request)
    {

        $categories = Category::orderBy('name')->get();
        $items = Item::latest()
            ->when($request->has('category_id'), function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            })
            ->when($request->has('q'), function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->q}%");
            })
            ->get();

        return view('welcome', compact('categories', 'items'));
    }
}
 