<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::orderBy('name')->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                     => 'required|unique:categories',
            'image'                    => 'required|image',
            'attributes.*.name'        => 'nullable|required_if:attributes.*.is_required,1|required_if:attributes.*.is_unique,1|string|distinct',
            'attributes.*.is_required' => 'sometimes|boolean',
            'attributes.*.is_unique'   => 'sometimes|boolean'
        ]);

        $data['image_filepath'] = $request->file('image')->store(
            $request->user()->id,
            'public'
        );

        DB::transaction(function () use ($data) {
            $category = Category::create(Arr::except($data, ['attributes', 'image']));

            $attributes = collect($data['attributes'])->filter(function ($attribute) {
                return isset($attribute['name']) && strlen(trim($attribute['name']));
            });

            if ($attributes->isNotEmpty()) {
                $category->attributes()->createMany($attributes->all());
            }
        }, 2);


        return response()->json([
            'result'   => true,
            'next_url' => url('admin/categories')
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $category->load('attributes');

        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Category            $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name'                     => "required|unique:categories,name,{$category->id}",
            'image'                    => 'image',
            'attributes.*.name'        => 'nullable|required_if:attributes.*.is_required,1|required_if:attributes.*.is_unique,1|string|distinct',
            'attributes.*.is_required' => 'sometimes|boolean',
            'attributes.*.is_unique'   => 'sometimes|boolean',
            'attributes.*.id'          => 'sometimes',
        ]);

        if ($request->hasFile('image')) {
            $data['image_filepath'] = $request->file('image')->store(
                $request->user()->id,
                'public'
            );
        }

        DB::transaction(function () use ($data, $category) {
            $category->fill($data)->save();

            $new     = collect();
            $updated = collect();

            foreach ($data['attributes'] as $attribute) {
                if ( ! isset($attribute['name']) || ! strlen(trim($attribute['name']))) {
                    continue;
                }

                if (isset($attribute['id'])) {
                    $updated->push($attribute);
                    continue;
                }

                $new->push($attribute);
            }

            if ($updated->isEmpty()) {
                $category->attributes()->delete();
            } else {
                $category->attributes()->whereNotIn('id', $updated->pluck('id')->all())->delete();
                $category->load('attributes');
                $updated = $updated->keyBy('id');
                $category->attributes->each(function ($existingAttribute) use ($updated) {
                    $updates = $updated->get($existingAttribute->id) + ['is_required' => false, 'is_unique' => false];
                    $existingAttribute->fill($updates);
                    $existingAttribute->save();
                });
            }

            if ($new->isNotEmpty()) {
                $category->attributes()->createMany($new->all());
            }
        }, 2);


        return response()->json([
            'result'   => true,
            'next_url' => url('admin/categories')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();

            return redirect('admin/categories')->with('deletion',
                ['variant' => 'success', 'message' => "You have successfully deleted category: {$category->name}"]);
        } catch (\Exception $exception) {
            return redirect('admin/categories')->with('deletion',
                ['variant' => 'danger', 'message' => "Cannot delete \"{$category->name}\" because it is being used."]);
        }
    }
}
