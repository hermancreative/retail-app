<?php

namespace App\Http\Controllers\MasterData;

use App\Domain\MasterData\Models\ItemCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\ItemCategoryRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ItemCategoryController extends Controller
{
    public function index(Request $request): Response
    {
        $categories = ItemCategory::query()
            ->when($request->string('search')->trim()->toString(), fn ($query, $search) => $query->where('name', 'like', "%{$search}%"))
            ->withCount('items')
            ->orderBy('name')
            ->get();

        return Inertia::render('master-data/ItemCategories', [
            'categories' => $categories,
            'filters' => $request->only('search'),
        ]);
    }

    public function store(ItemCategoryRequest $request): RedirectResponse
    {
        ItemCategory::create($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Category created.')]);

        return to_route('item-categories.index');
    }

    public function update(ItemCategoryRequest $request, ItemCategory $item_category): RedirectResponse
    {
        $item_category->update($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Category updated.')]);

        return to_route('item-categories.index');
    }

    public function destroy(ItemCategory $item_category): RedirectResponse
    {
        $item_category->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Category deleted.')]);

        return to_route('item-categories.index');
    }
}
