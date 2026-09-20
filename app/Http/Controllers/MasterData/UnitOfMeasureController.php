<?php

namespace App\Http\Controllers\MasterData;

use App\Domain\MasterData\Models\UnitOfMeasure;
use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\UnitOfMeasureRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UnitOfMeasureController extends Controller
{
    public function index(Request $request): Response
    {
        $units = UnitOfMeasure::query()
            ->when($request->string('search')->trim()->toString(), fn ($query, $search) => $query->where('name', 'like', "%{$search}%"))
            ->withCount('items')
            ->orderBy('name')
            ->get();

        return Inertia::render('master-data/UnitsOfMeasure', [
            'units' => $units,
            'filters' => $request->only('search'),
        ]);
    }

    public function store(UnitOfMeasureRequest $request): RedirectResponse
    {
        UnitOfMeasure::create($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Unit created.')]);

        return to_route('units-of-measure.index');
    }

    public function update(UnitOfMeasureRequest $request, UnitOfMeasure $units_of_measure): RedirectResponse
    {
        $units_of_measure->update($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Unit updated.')]);

        return to_route('units-of-measure.index');
    }

    public function destroy(UnitOfMeasure $units_of_measure): RedirectResponse
    {
        $units_of_measure->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Unit deleted.')]);

        return to_route('units-of-measure.index');
    }
}
