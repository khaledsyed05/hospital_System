<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Patient; // Required for Rule::unique

class UpdatePatientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // For now, allow anyone. In a real app, implement proper authorization logic.
        // Example: return auth()->user()->can('update', $this->route('patient'));
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $patientId = $this->route('patient') instanceof Patient ? $this->route('patient')->patient_id : $this->route('patient');

        return [
            'patient_number' => [
                'sometimes', // Use 'sometimes' for update if field is not always sent or allowed to be unchanged
                'required',
                'string',
                'max:20',
                Rule::unique(Patient::class, 'patient_number')->ignore($patientId, 'patient_id')
            ],
            'first_name' => 'sometimes|required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'sometimes|required|string|max:100',
            'full_name_ar' => 'nullable|string|max:300',
            'full_name_en' => 'nullable|string|max:300',
            'national_id' => [
                'nullable',
                'string',
                'max:30',
                Rule::unique(Patient::class, 'national_id')->ignore($patientId, 'patient_id'),
                // Rule::requiredIf(fn () => !$this->input('passport_number')),
            ],
            'passport_number' => [
                'nullable',
                'string',
                'max:30',
                Rule::unique(Patient::class, 'passport_number')->ignore($patientId, 'patient_id'),
                // Rule::requiredIf(fn () => !$this->input('national_id')),
            ],
            'date_of_birth' => 'sometimes|required|date_format:Y-m-d|before_or_equal:today',
            // 'age_years' => 'nullable|integer|min:0|max:150',
            'gender' => ['sometimes', 'required', Rule::in(['M', 'F'])],
            'nationality_id' => 'nullable|integer', // Should check existence
            'marital_status' => ['nullable', Rule::in(['Single', 'Married', 'Divorced', 'Widowed'])],
            'status' => ['nullable', Rule::in(['Active', 'Inactive', 'Deceased'])],
            'deceased_date' => 'nullable|date_format:Y-m-d|after_or_equal:date_of_birth',
            // 'photo_path' => 'nullable|string|max:500',
            'notes' => 'nullable|string',
            'modified_by' => 'required|string|max:50', // Or set automatically

            // Rules for related data (arrays of objects) - typically involves full replacement or more complex logic
            // For updates, you might decide if these fields are fully replaceable or if you support partial updates.
            // Using 'sometimes' allows these sections to be omitted from the request if no changes are needed.
            'addresses' => 'sometimes|nullable|array',
            'addresses.*.address_id' => 'nullable|integer|exists:PATIENT_ADDRESSES,address_id', // For existing addresses
            'addresses.*.address_type_id' => 'sometimes|required_with:addresses|integer',
            'addresses.*.country_id' => 'nullable|integer',
            'addresses.*.city' => 'nullable|string|max:100',
            'addresses.*.street' => 'nullable|string|max:500',
            'addresses.*.is_primary' => ['nullable', Rule::in(['Y', 'N'])],
            'addresses.*.is_active' => ['nullable', Rule::in(['Y', 'N'])],

            'contacts' => 'sometimes|nullable|array',
            'contacts.*.contact_id' => 'nullable|integer|exists:PATIENT_CONTACTS,contact_id', // For existing contacts
            'contacts.*.contact_type_id' => 'sometimes|required_with:contacts|integer',
            'contacts.*.contact_value' => 'sometimes|required_with:contacts|string|max:100',
            'contacts.*.is_primary' => ['nullable', Rule::in(['Y', 'N'])],
            'contacts.*.is_active' => ['nullable', Rule::in(['Y', 'N'])],

            'insurances' => 'sometimes|nullable|array',
            'insurances.*.insurance_id' => 'nullable|integer|exists:PATIENT_INSURANCE,insurance_id', // For existing insurances
            'insurances.*.insurance_company_id' => 'nullable|integer',
            'insurances.*.insurance_type' => ['nullable', Rule::in(['Government', 'Private', 'Self-Pay', 'Military'])],
            'insurances.*.policy_number' => 'nullable|string|max:50',
            // 'insurances.*.coverage_percentage' => 'nullable|integer|min:0|max:100',
            // 'insurances.*.effective_date' => 'nullable|date_format:Y-m-d',
            // 'insurances.*.expiry_date' => 'nullable|date_format:Y-m-d|after_or_equal:insurances.*.effective_date',
            'insurances.*.is_primary' => ['nullable', Rule::in(['Y', 'N'])],
            'insurances.*.is_active' => ['nullable', Rule::in(['Y', 'N'])],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'date_of_birth.before_or_equal' => 'The date of birth cannot be in the future.',
        ];
    }
}
