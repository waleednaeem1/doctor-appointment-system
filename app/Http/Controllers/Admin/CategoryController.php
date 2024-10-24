<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $pageTitle = 'All Categories';
        $categories   = Category::searchable(['category_name'])->orderBy('category_name')->paginate(getPaginate());
        return view('admin.category.index', compact('pageTitle', 'categories'));
    }

    public function store(Request $request, $id = 0)
    {
        if ($id) {
            $category           = Category::findOrFail($id);
            $notification       = 'Category updated successfully';
        } else {
            $category           = new Category();
            $notification       = 'Category added successfully';
        }
        $category->category_name    = $request->category_name;
        $category->category_slug = $this->slugify($request->category_name);
        $category->save();
        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }
}
