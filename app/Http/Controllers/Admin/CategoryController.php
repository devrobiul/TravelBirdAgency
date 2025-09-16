<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return view('backend.pages.expense.category', [
            'categories' => Category::all(),
            'category' => null,
        ]);
    }
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|unique:categories,name',
        ]);
        $category = new Category();
        $category->name = $request->name;
        $category->category_type = $request->category_type;
        $category->created_at = now();
        $category->save();
        session()->flash('success', 'Category created successfully.');
        return back();
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('backend.pages.expense.category', [
            'categories' => Category::all(),
            'category' => $category,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:categories,name,' . $id,
        ]);
        
        $category =  Category::findOrFail($id);
        $category->name = $request->name;
        $category->category_type = $request->category_type;
        $category->updated_at = now();
        $category->save();
        
        session()->flash('success', 'Category updated successfully.');
        return redirect()->route('admin.expense.category.index');
    }

    public function destroy($id)
    {
        if (!auth()->user()->hasRole('admin')) {
            session()->flash('warning', 'You do not have permission to delete a payment.');
            return back();
        }
        Category::findOrFail($id)->delete();
        session()->flash('success', 'Category updated successfully.');
        return redirect()->route('admin.expense.category.index');
    }
}
