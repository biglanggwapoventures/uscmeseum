<?php

namespace App\Http\Controllers\Admin;

use App\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Item::with(['category', 'logs'])->orderBy('name')->get();

        return view('items.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::select('id', 'name')->orderBy('name')->get();

        return view('items.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|unique:items',
            'description'   => 'required',
            'category_id'   => 'required|exists:categories,id',
            'selling_price' => 'required|numeric',
            'image'         => 'required|image'
        ]);

        $data['image_filepath'] = $request->file('image')->store(
            $request->user()->id,
            'public'
        );

        unset($data['image']);

        Item::create($data);

        return redirect('admin/items');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        $categories = Category::select('id', 'name')->orderBy('name')->get();

        return view('items.edit', compact('item', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        $data = $request->validate([
            'name'          => "required|unique:items,name,{$item->id}",
            'description'   => 'required',
            'category_id'   => "required|exists:categories,id",
            'selling_price' => 'required|numeric',
            'image'         => 'image'
        ]);

        if($request->hasFile('image')){
            $data['image_filepath'] = $request->file('image')->store(
                $request->user()->id,
                'public'
            );
        }

        unset($data['image']);

        $item->fill($data)->save();

        return redirect('admin/items');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return redirect('admin/items');
    }
}
