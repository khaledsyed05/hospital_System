<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request); // Default implementation

        return [
            'patient_id' => $this->patient_id,
            'patient_number' => $this->patient_number,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'full_name_ar' => $this->full_name_ar,
            'full_name_en' => $this->full_name_en,
            'national_id' => $this->national_id,
            'passport_number' => $this->passport_number,
            'date_of_birth' => $this->date_of_birth ? ($this->date_of_birth instanceof \Carbon\Carbon ? $this->date_of_birth->format('Y-m-d') : $this->date_of_birth) : null,
            // 'age_years' => $this->age_years, // This is a direct column from DB
            'age' => $this->age, // This uses the accessor we defined in Patient model
            'gender' => $this->gender,
            'nationality_id' => $this->nationality_id,
            // 'nationality' => new CountryResource($this->whenLoaded('nationality')), // Example if CountryResource exists
            'marital_status' => $this->marital_status,
            'registration_date' => $this->registration_date ? ($this->registration_date instanceof \Carbon\Carbon ? $this->registration_date->toDateTimeString() : $this->registration_date) : null,
            'status' => $this->status,
            'deceased_date' => $this->deceased_date ? ($this->deceased_date instanceof \Carbon\Carbon ? $this->deceased_date->format('Y-m-d') : $this->deceased_date) : null,
            'photo_path' => $this->photo_path,
            'notes' => $this->notes,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'created_date' => $this->created_date ? ($this->created_date instanceof \Carbon\Carbon ? $this->created_date->toDateTimeString() : $this->created_date) : null,
            'modified_date' => $this->modified_date ? ($this->modified_date instanceof \Carbon\Carbon ? $this->modified_date->toDateTimeString() : $this->modified_date) : null,

            // Conditionally load relationships
            // These will only be included if they were eager-loaded on the model
            'addresses' => PatientAddressResource::collection($this->whenLoaded('addresses')),
            'contacts' => PatientContactResource::collection($this->whenLoaded('contacts')),
            'insurances' => PatientInsuranceResource::collection($this->whenLoaded('insurances')),

            // You might also want to include other related data if models exist:
            // 'medical_info' => new PatientMedicalInfoResource($this->whenLoaded('medicalInfo')),
            // 'social_info' => new PatientSocialInfoResource($this->whenLoaded('socialInfo')),
            // 'allergies' => PatientAllergyResource::collection($this->whenLoaded('allergies')),
        ];
    }
}
