<?php

namespace App\Http\Controllers\MasterData;

use App\Domain\MasterData\Models\Warehouse;
use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\WarehouseRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WarehouseController extends Controller
{
    public function index(Request $request): Response
    {
        $warehouses = Warehouse::query()
            ->when($request->string('search')->trim()->toString(), function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->get();

        return Inertia::render('master-data/Warehouses', [
            'warehouses' => $warehouses,
            'filters' => $request->only('search'),
        ]);
    }

    public function store(WarehouseRequest $request): RedirectResponse
    {
        Warehouse::create($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Warehouse created.')]);

        return to_route('warehouses.index');
    }

    public function update(WarehouseRequest $request, Warehouse $warehouse): RedirectResponse
    {
        $warehouse->update($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Warehouse updated.')]);

        return to_route('warehouses.index');
    }

    public function destroy(Warehouse $warehouse): RedirectResponse
    {
        $warehouse->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Warehouse deleted.')]);

        return to_route('warehouses.index');
    }
}
