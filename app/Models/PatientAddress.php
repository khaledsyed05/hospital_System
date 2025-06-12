<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\PatientAddress
 *
 * @property int $address_id
 * @property int $patient_id
 * @property int $address_type_id
 * @property int|null $country_id
 * @property string|null $city
 * @property string|null $district
 * @property string|null $street
 * @property string|null $building_number
 * @property string|null $apartment_number
 * @property string|null $postal_code
 * @property string|null $landmarks
 * @property string $is_primary
 * @property string $is_active
 * @property string $created_date
 * @property string $modified_date
 * @property-read \App\Models\Patient $patient
 * @property-read \App\Models\AddressType|null $addressType  // Assuming AddressType model will be created
 * @property-read \App\Models\Country|null $country        // Assuming Country model will be created
 * @method static \Illuminate\Database\Eloquent\Builder|PatientAddress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PatientAddress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PatientAddress query()
 * @mixin \Eloquent
 */
class PatientAddress extends Model
{
    use HasFactory;

    protected $table = 'PATIENT_ADDRESSES';
    protected $primaryKey = 'address_id';

    // Custom timestamp column names
    public const CREATED_AT = 'created_date';
    public const UPDATED_AT = 'modified_date';

    protected $fillable = [
        'patient_id',
        'address_type_id',
        'country_id',
        'city',
        'district',
        'street',
        'building_number',
        'apartment_number',
        'postal_code',
        'landmarks',
        'is_primary',
        'is_active',
    ];

    protected $casts = [
        'created_date' => 'datetime',
        'modified_date' => 'datetime',
        'patient_id' => 'integer',
        'address_type_id' => 'integer',
        'country_id' => 'integer',
    ];

    /**
     * Get the patient that owns the address.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'patient_id');
    }

    /**
     * Get the address type for this address.
     * (Assuming an AddressType model will be created)
     */
    // public function addressType(): BelongsTo
    // {
    //     return $this->belongsTo(AddressType::class, 'address_type_id', 'address_type_id');
    // }

    /**
     * Get the country for this address.
     * (Assuming a Country model will be created)
     */
    // public function country(): BelongsTo
    // {
    //     return $this->belongsTo(Country::class, 'country_id', 'country_id');
    // }
}
