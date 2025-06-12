<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\PatientContact
 *
 * @property int $contact_id
 * @property int $patient_id
 * @property int $contact_type_id
 * @property string $contact_value
 * @property string|null $contact_person_name
 * @property string|null $relationship
 * @property string $is_primary
 * @property string $is_verified
 * @property string|null $verification_date
 * @property string $is_active
 * @property string $created_date
 * @property string $modified_date
 * @property-read \App\Models\Patient $patient
 * @property-read \App\Models\ContactType|null $contactType // Assuming ContactType model will be created
 * @method static \Illuminate\Database\Eloquent\Builder|PatientContact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PatientContact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PatientContact query()
 * @mixin \Eloquent
 */
class PatientContact extends Model
{
    use HasFactory;

    protected $table = 'PATIENT_CONTACTS';
    protected $primaryKey = 'contact_id';

    // Custom timestamp column names
    public const CREATED_AT = 'created_date';
    public const UPDATED_AT = 'modified_date';

    protected $fillable = [
        'patient_id',
        'contact_type_id',
        'contact_value',
        'contact_person_name',
        'relationship',
        'is_primary',
        'is_verified',
        'verification_date',
        'is_active',
    ];

    protected $casts = [
        'created_date' => 'datetime',
        'modified_date' => 'datetime',
        'verification_date' => 'datetime',
        'patient_id' => 'integer',
        'contact_type_id' => 'integer',
    ];

    /**
     * Get the patient that owns the contact information.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'patient_id');
    }

    /**
     * Get the contact type for this contact information.
     * (Assuming a ContactType model will be created)
     */
    // public function contactType(): BelongsTo
    // {
    //     return $this->belongsTo(ContactType::class, 'contact_type_id', 'contact_type_id');
    // }
}
