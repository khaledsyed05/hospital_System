<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Patient; // Required for Rule::unique

class StorePatientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // For now, allow anyone. In a real app, implement proper authorization logic.
        // Example: return auth()->user()->can('create', Patient::class);
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'patient_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique(Patient::class, 'patient_number') // Assuming PATIENTS table model is Patient
            ],
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'full_name_ar' => 'nullable|string|max:300',
            'full_name_en' => 'nullable|string|max:300',
            'national_id' => [
                'nullable',
                'string',
                'max:30',
                Rule::unique(Patient::class, 'national_id'),
                // Rule::requiredIf(fn () => !$this->input('passport_number')), // If one is required
            ],
            'passport_number' => [
                'nullable',
                'string',
                'max:30',
                Rule::unique(Patient::class, 'passport_number'),
                // Rule::requiredIf(fn () => !$this->input('national_id')), // If one is required
            ],
            'date_of_birth' => 'required|date_format:Y-m-d|before_or_equal:today',
            // age_years is usually calculated, not directly submitted. If submitted, add validation.
            // 'age_years' => 'nullable|integer|min:0|max:150',
            'gender' => ['required', Rule::in(['M', 'F'])],
            'nationality_id' => 'nullable|integer', // Should check existence in COUNTRIES table if Country model & table exists
            'marital_status' => ['nullable', Rule::in(['Single', 'Married', 'Divorced', 'Widowed'])],
            // registration_date is usually server-set
            'status' => ['nullable', Rule::in(['Active', 'Inactive', 'Deceased'])],
            'deceased_date' => 'nullable|date_format:Y-m-d|after_or_equal:date_of_birth',
            // 'photo_path' => 'nullable|string|max:500', // Or image validation if uploading file
            'notes' => 'nullable|string',
            'created_by' => 'required|string|max:50', // Or set automatically based on auth user

            // Rules for related data (arrays of objects)
            // Addresses
            'addresses' => 'nullable|array',
            'addresses.*.address_type_id' => 'required_with:addresses|integer', // check existence in ADDRESS_TYPES
            'addresses.*.country_id' => 'nullable|integer', // check existence in COUNTRIES
            'addresses.*.city' => 'nullable|string|max:100',
            'addresses.*.street' => 'nullable|string|max:500',
            'addresses.*.is_primary' => ['nullable', Rule::in(['Y', 'N'])],
            'addresses.*.is_active' => ['nullable', Rule::in(['Y', 'N'])],

            // Contacts
            'contacts' => 'nullable|array',
            'contacts.*.contact_type_id' => 'required_with:contacts|integer', // check existence in CONTACT_TYPES
            'contacts.*.contact_value' => 'required_with:contacts|string|max:100',
            // Add regex validation for contact_value based on contact_type_id if possible/needed
            'contacts.*.is_primary' => ['nullable', Rule::in(['Y', 'N'])],
            'contacts.*.is_active' => ['nullable', Rule::in(['Y', 'N'])],

            // Insurances
            'insurances' => 'nullable|array',
            'insurances.*.insurance_company_id' => 'nullable|integer', // check existence
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
            // Add other custom messages as needed
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    // protected function prepareForValidation(): void
    // {
    //     // Example: Ensure national_id and passport_number are null if empty strings
    //     if ($this->input('national_id') === '') {
    //         $this->merge(['national_id' => null]);
    //     }
    //     if ($this->input('passport_number') === '') {
    //         $this->merge(['passport_number' => null]);
    //     }
    // }
}
