<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

/**
 * App\Models\Patient
 *
 * @property int $patient_id
 * @property string $patient_number
 * @property string $first_name
 * @property string|null $middle_name
 * @property string $last_name
 * @property string|null $full_name_ar
 * @property string|null $full_name_en
 * @property string|null $national_id
 * @property string|null $passport_number
 * @property string $date_of_birth
 * @property int|null $age_years
 * @property string $gender
 * @property int|null $nationality_id
 * @property string|null $marital_status
 * @property string $registration_date
 * @property string $status
 * @property string|null $deceased_date
 * @property string|null $photo_path
 * @property string|null $notes
 * @property string $created_by
 * @property string $created_date
 * @property string|null $modified_by
 * @property string $modified_date
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PatientAddress[] $addresses
 * @property-read int|null $addresses_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PatientContact[] $contacts
 * @property-read int|null $contacts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PatientInsurance[] $insurances
 * @property-read int|null $insurances_count
 * @property-read int|null $age  // Accessor for age
 * @method static \Illuminate\Database\Eloquent\Builder|Patient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Patient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Patient query()
 * @mixin \Eloquent
 */
class Patient extends Model
{
    use HasFactory;

    protected $table = 'PATIENTS';
    protected $primaryKey = 'patient_id';

    public const CREATED_AT = 'created_date';
    public const UPDATED_AT = 'modified_date';

    protected $fillable = [
        'patient_number',
        'first_name',
        'middle_name',
        'last_name',
        'full_name_ar',
        'full_name_en',
        'national_id',
        'passport_number',
        'date_of_birth',
        // 'age_years', // Typically calculated, not directly filled
        'gender',
        'nationality_id',
        'marital_status',
        // 'registration_date', // Handled by DB default
        'status',
        'deceased_date',
        'photo_path',
        'notes',
        'created_by',
        'modified_by',
    ];

    protected $casts = [
        'date_of_birth' => 'date:Y-m-d',
        'deceased_date' => 'date:Y-m-d',
        'registration_date' => 'datetime',
        'created_date' => 'datetime',
        'modified_date' => 'datetime',
        'age_years' => 'integer',
        'nationality_id' => 'integer',
    ];

    public function addresses(): HasMany
    {
        return $this->hasMany(PatientAddress::class, 'patient_id', 'patient_id');
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(PatientContact::class, 'patient_id', 'patient_id');
    }

    public function insurances(): HasMany
    {
        return $this->hasMany(PatientInsurance::class, 'patient_id', 'patient_id');
    }

    public function getAgeAttribute(): ?int
    {
        if ($this->attributes['date_of_birth']) {
            return Carbon::parse($this->attributes['date_of_birth'])->age;
        }
        return null;
    }
}
