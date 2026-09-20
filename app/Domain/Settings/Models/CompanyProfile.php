<?php

namespace App\Domain\Settings\Models;

use Database\Factories\CompanyProfileFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string|null $tax_id
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $logo_path
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'tax_id', 'email', 'phone', 'address', 'logo_path'])]
class CompanyProfile extends Model
{
    /** @use HasFactory<CompanyProfileFactory> */
    use HasFactory;

    protected static function newFactory(): CompanyProfileFactory
    {
        return CompanyProfileFactory::new();
    }

    /**
     * Get the single company profile record, creating a default one if missing.
     */
    public static function current(): self
    {
        return static::query()->first() ?? static::create([
            'name' => config('app.name'),
        ]);
    }
}
