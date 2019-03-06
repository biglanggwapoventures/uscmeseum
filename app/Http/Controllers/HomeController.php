<?php

namespace App\Http\Controllers;

use App\Category;


class HomeController extends Controller
{
    public function welcome()
    {
        $categories = Category::orderBy('name')->get();

        return view('welcome', compact('categories'));
    }
}
