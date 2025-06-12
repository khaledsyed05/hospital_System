<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\PatientInsurance
 *
 * @property int $insurance_id
 * @property int $patient_id
 * @property int|null $insurance_company_id
 * @property string|null $insurance_type
 * @property string|null $policy_number
 * @property string|null $group_number
 * @property int|null $coverage_percentage
 * @property string|null $deductible_amount
 * @property string|null $copay_amount
 * @property string|null $effective_date
 * @property string|null $expiry_date
 * @property string|null $payment_method
 * @property string $is_primary
 * @property string $is_active
 * @property string|null $notes
 * @property string $created_date
 * @property string $modified_date
 * @property-read \App\Models\Patient $patient
 * // Assuming InsuranceCompany model might be created later
 * @property-read \App\Models\InsuranceCompany|null $insuranceCompany
 * @method static \Illuminate\Database\Eloquent\Builder|PatientInsurance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PatientInsurance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PatientInsurance query()
 * @mixin \Eloquent
 */
class PatientInsurance extends Model
{
    use HasFactory;

    protected $table = 'PATIENT_INSURANCE';
    protected $primaryKey = 'insurance_id';

    // Custom timestamp column names
    public const CREATED_AT = 'created_date';
    public const UPDATED_AT = 'modified_date';

    protected $fillable = [
        'patient_id',
        'insurance_company_id',
        'insurance_type',
        'policy_number',
        'group_number',
        'coverage_percentage',
        'deductible_amount',
        'copay_amount',
        'effective_date',
        'expiry_date',
        'payment_method',
        'is_primary',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'created_date' => 'datetime',
        'modified_date' => 'datetime',
        'effective_date' => 'date:Y-m-d',
        'expiry_date' => 'date:Y-m-d',
        'patient_id' => 'integer',
        'insurance_company_id' => 'integer',
        'coverage_percentage' => 'integer',
        'deductible_amount' => 'decimal:2',
        'copay_amount' => 'decimal:2',
    ];

    /**
     * Get the patient that owns the insurance information.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'patient_id');
    }

    /**
     * Get the insurance company for this insurance information.
     * (Assuming an InsuranceCompany model will be created later)
     */
    // public function insuranceCompany(): BelongsTo
    // {
    //    return $this->belongsTo(InsuranceCompany::class, 'insurance_company_id', 'company_id'); // Adjust foreign key if needed
    // }
}
