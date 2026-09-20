<?php

namespace App\Http\Controllers\MasterData;

use App\Domain\MasterData\Models\Item;
use App\Domain\MasterData\Models\ItemCategory;
use App\Domain\MasterData\Models\UnitOfMeasure;
use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\ItemRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ItemController extends Controller
{
    public function index(Request $request): Response
    {
        $items = Item::query()
            ->with(['category', 'unitOfMeasure'])
            ->when($request->string('search')->trim()->toString(), function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->get();

        return Inertia::render('master-data/Items', [
            'items' => $items,
            'categories' => ItemCategory::query()->orderBy('name')->get(['id', 'name']),
            'units' => UnitOfMeasure::query()->orderBy('name')->get(['id', 'name', 'symbol']),
            'filters' => $request->only('search'),
        ]);
    }

    public function store(ItemRequest $request): RedirectResponse
    {
        Item::create($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Item created.')]);

        return to_route('items.index');
    }

    public function update(ItemRequest $request, Item $item): RedirectResponse
    {
        $item->update($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Item updated.')]);

        return to_route('items.index');
    }

    public function destroy(Item $item): RedirectResponse
    {
        $item->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Item deleted.')]);

        return to_route('items.index');
    }
}
