<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * List every non-deleted category.
     */
    public function index(): View
    {
        return view('categories.index', [
            'categories' => Category::orderBy('nombre')->get(),
        ]);
    }

    /**
     * Show the form to create a category.
     */
    public function create(): View
    {
        return view('categories.create');
    }

    /**
     * Persist a new category.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        Category::create($request->validated());

        return redirect()
            ->route('categories.index')
            ->with('success', 'La categoría fue registrada correctamente.');
    }

    /**
     * Show the form to edit a category.
     */
    public function edit(Category $category): View
    {
        return view('categories.edit', [
            'category' => $category,
        ]);
    }

    /**
     * Update an existing category.
     */
    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        return redirect()
            ->route('categories.index')
            ->with('success', 'La categoría fue actualizada correctamente.');
    }

    /**
     * Logically delete a category (soft delete).
     */
    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', 'La categoría fue eliminada correctamente.');
    }
}
