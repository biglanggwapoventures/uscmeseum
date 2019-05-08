<?php

namespace App\Http\Controllers\Admin;

use App\Attribute;
use App\Item;
use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;

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
        $categories = Category::with('attributes')
                              ->select('id', 'name')
                              ->orderBy('name')
                              ->get();

        return view('items.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /** @var Validator $validator */
        $validator = \Validator::make($request->all(), [
            'name'                      => 'required|unique:items',
            'description'               => 'required',
            'category_id'               => 'required|exists:categories,id',
            'selling_price'             => 'required|numeric',
            'purchase_cost'             => 'required|numeric',
            'reorder_level'             => 'required|numeric|min:0',
            'image'                     => 'required|image',
            'attributes.*.attribute_id' => 'sometimes|required|exists:attributes,id|distinct',
            'attributes.*.value'        => 'sometimes'
        ]);

        $validator->after(function ($validator) use ($request) {
            /** @var Validator $validator */

            $attributeInputs = array_column($request->input('attributes', []), 'value', 'attribute_id');
//            dd($attributeInputs);
            if (empty($attributeInputs)) {
                return;
            }

            $category = Category::query()
                                ->with('attributes.values')
                                ->find($request->input('category_id'));

            $category->attributes
                ->each(function ($attribute, $index) use ($attributeInputs, $validator) {

                    if ($attribute->is_required && ! filled($attributeInputs[$attribute->id])) {
                        $validator->errors()->add("attributes.{$index}.value", 'This field is required');

                        return;
                    }

                    if ($attribute->is_unique && $attribute->values->where('value',
                            $attributeInputs[$attribute->id])->first()) {
                        $validator->errors()->add("attributes.{$index}.value", 'This must be unique');
                    }
                });

        });


        $validator->validate();

        $data = $validator->valid();

        $data['image_filepath'] = $request->file('image')->store(
            $request->user()->id,
            'public'
        );


        DB::transaction(function () use ($data, $request) {

            $item       = Item::create(Arr::except($data, ['image', 'attributes']));
            $attributes = collect($request->input('attributes', []))->mapWithKeys(function ($attribute) {
                ['attribute_id' => $id, 'value' => $value] = $attribute;

                return [
                    $id => compact('value')
                ];
            });

            $item->attributes()->attach($attributes);

        }, 2);

        return response()->json([
            'result'   => true,
            'next_url' => url('admin/items')
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Item $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Item $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        $item->load('attributes');

        $categories = Category::with('attributes')
                              ->select('id', 'name')
                              ->orderBy('name')
                              ->get();

        return view('items.edit', compact('item', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Item                $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        $validator = \Validator::make($request->all(), [
            'name'                      => "required|unique:items,name,{$item->id}",
            'description'               => 'required',
            'category_id'               => "required|exists:categories,id",
            'selling_price'             => 'required|numeric',
            'reorder_level'             => 'required|numeric|min:0',
            'purchase_cost'             => 'required|numeric',
            'image'                     => 'image',
            'attributes.*.attribute_id' => 'sometimes|required|exists:attributes,id|distinct',
            'attributes.*.value'        => 'sometimes'
        ]);

        $validator->after(function ($validator) use ($request, $item) {
            /** @var Validator $validator */

            $attributeInputs = array_column($request->input('attributes', []), 'value', 'attribute_id');
//            dd($attributeInputs);
            if (empty($attributeInputs)) {
                return;
            }

            $category = Category::query()
                                ->with('attributes.values')
                                ->find($request->input('category_id'));

            $category->attributes
                ->each(function ($attribute, $index) use ($attributeInputs, $validator, $item) {

                    if ($attribute->is_required && ! filled($attributeInputs[$attribute->id])) {
                        $validator->errors()->add("attributes.{$index}.value", 'This field is required');

                        return;
                    }

                    if ($attribute->is_unique) {
                        $existingAttribute = $attribute->values->where('value',
                            $attributeInputs[$attribute->id])->first();


                        if ($existingAttribute && $existingAttribute->item_id !== $item->id) {
                            $validator->errors()->add("attributes.{$index}.value", 'This must be unique');
                        }

                    }
                });

        });


        $validator->validate();

        $data = $validator->valid();

        if ($request->hasFile('image')) {
            $data['image_filepath'] = $request->file('image')->store(
                $request->user()->id,
                'public'
            );
        }

        DB::transaction(function () use ($data, $request, $item) {


            $item->fill(Arr::except($data, ['image', 'attributes']))->save();
            $attributes = collect($request->input('attributes', []))->mapWithKeys(function ($attribute) {
                ['attribute_id' => $id, 'value' => $value] = $attribute;

                return [
                    $id => compact('value')
                ];
            });

            $item->attributes()->sync($attributes);

        }, 2);


        return response()->json([
            'result'   => true,
            'next_url' => url('admin/items')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Item $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        try {
            $item->delete();

            return redirect('admin/items')->with('deletion',
                ['variant' => 'success', 'message' => "You have successfully deleted item: {$item->name}"]);
        } catch (\Exception $exception) {
            return redirect('admin/items')->with('deletion',
                ['variant' => 'danger', 'message' => "Cannot delete \"{$item->name}\" because it is being used."]);
        }

    }
}
