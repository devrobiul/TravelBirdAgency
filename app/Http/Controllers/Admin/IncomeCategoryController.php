<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IncomeCategory;
use Illuminate\Http\Request;

class IncomeCategoryController extends Controller
{
    public function index()
    {
        return view('backend.pages.income.category', [
            'categories' => IncomeCategory::all(),
            'category' => null,
        ]);
    }
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|unique:income_categories,name',
        ]);
        $category = new IncomeCategory();
        $category->name = $request->name;
        $category->created_at = now();
        $category->save();
        session()->flash('success', 'Category created successfully.');
        return back();
    }

    public function edit($id)
    {
        $category = IncomeCategory::findOrFail($id);
        return view('backend.pages.income.category', [
            'categories' => IncomeCategory::all(),
            'category' => $category,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:income_categories,name,' . $id,
        ]);
        
        $category =  IncomeCategory::findOrFail($id);
        $category->name = $request->name;
        $category->updated_at = now();
        $category->save();
        
        session()->flash('success', 'Category updated successfully.');
        return redirect()->route('admin.income.category.index');
    }

    public function destroy($id)
    {
        if (!auth()->user()->hasRole('admin')) {
            session()->flash('warning', 'You do not have permission to delete a payment.');
            return back();
        }
        IncomeCategory::findOrFail($id)->delete();
        session()->flash('success', 'Category updated successfully.');
        return redirect()->route('admin.income.category.index');
    }
}