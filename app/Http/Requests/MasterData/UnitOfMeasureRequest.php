<?php

namespace App\Http\Requests\MasterData;

use App\Domain\MasterData\Models\UnitOfMeasure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UnitOfMeasureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $unit = $this->route('units_of_measure');
        $unitId = $unit instanceof UnitOfMeasure ? $unit->id : null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'symbol' => [
                'required',
                'string',
                'max:20',
                Rule::unique('units_of_measure', 'symbol')->ignore($unitId),
            ],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
