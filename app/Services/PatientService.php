<?php

namespace App\Services;

use App\Models\Patient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr; // For Arr::except

/**
 * Service class for managing patients.
 */
class PatientService
{
    /**
     * Create a new patient along with their related data.
     *
     * @param array $data The data for creating the patient and their relations.
     *                    Example: [
     *                        'patient_number' => 'P12345',
     *                        'first_name' => 'John',
     *                        // ... other patient fields
     *                        'created_by' => 'admin_user', // Required by PATIENTS table
     *                        'addresses' => [
     *                            ['address_type_id' => 1, 'street' => '123 Main St', ...],
     *                        ],
     *                        'contacts' => [
     *                            ['contact_type_id' => 1, 'contact_value' => '555-1234', ...],
     *                        ],
     *                        'insurances' => [
     *                            ['policy_number' => 'INS987', ...],
     *                        ]
     *                    ]
     * @return Patient The created patient model.
     * @throws \Exception
     */
    public function createPatient(array $data): Patient
    {
        // return DB::transaction(function () use ($data) {
        //     $patientData = Arr::except($data, ['addresses', 'contacts', 'insurances']);
        //     // Ensure required fields like 'created_by' are present if not auto-filled
        //     if (!isset($patientData['created_by'])) {
        //         // Or fetch from auth user, for now, let's assume it's passed
        //         throw new \InvalidArgumentException('created_by is required to create a patient.');
        //     }
        //     if (!isset($patientData['patient_number'])) {
        //          // Consider generating this if not provided
        //         throw new \InvalidArgumentException('patient_number is required.');
        //     }


        //     $patient = Patient::create($patientData);

        //     if (isset($data['addresses']) && is_array($data['addresses'])) {
        //         foreach ($data['addresses'] as $addressData) {
        //             $patient->addresses()->create($addressData);
        //         }
        //     }

        //     if (isset($data['contacts']) && is_array($data['contacts'])) {
        //         foreach ($data['contacts'] as $contactData) {
        //             $patient->contacts()->create($contactData);
        //         }
        //     }

        //     if (isset($data['insurances']) && is_array($data['insurances'])) {
        //         foreach ($data['insurances'] as $insuranceData) {
        //             $patient->insurances()->create($insuranceData);
        //         }
        //     }

        //     return $patient;
        // });
        // Placeholder implementation - full logic will be complex
        // For now, just focusing on the structure and basic patient creation
        $patient = Patient::create(Arr::except($data, ['addresses', 'contacts', 'insurances']));
        // TODO: Implement creation of related models (addresses, contacts, insurances) within a transaction
        return $patient;
    }

    /**
     * Find a patient by their ID.
     *
     * @param int $patientId
     * @return Patient|null
     */
    public function findPatientById(int $patientId): ?Patient
    {
        return Patient::find($patientId);
        // Consider eager loading relationships: Patient::with(['addresses', 'contacts', 'insurances'])->find($patientId);
    }

    /**
     * Update an existing patient and their related data.
     *
     * @param Patient $patient The patient model to update.
     * @param array $data The data for updating the patient.
     *                    Similar structure to createPatient data.
     * @return Patient The updated patient model.
     * @throws \Exception
     */
    public function updatePatient(Patient $patient, array $data): Patient
    {
        // return DB::transaction(function () use ($patient, $data) {
        //     $patientModelData = Arr::except($data, ['addresses', 'contacts', 'insurances']);
             // Ensure 'modified_by' is set if required by your logic/schema
        //     if (!isset($patientModelData['modified_by'])) {
        //          // Or fetch from auth user
        //         // throw new \InvalidArgumentException('modified_by is required to update a patient.');
        //     }
        //     $patient->update($patientModelData);

        //     // For related models, common strategies are:
        //     // 1. Delete all existing and recreate (simple but can be inefficient/destructive)
        //     // 2. Diff and update/create/delete (more complex, more efficient)

        //     // Example: Simple delete and recreate for addresses
        //     if (isset($data['addresses'])) { // If 'addresses' key is present, even if empty array
        //         $patient->addresses()->delete();
        //         if(is_array($data['addresses'])) {
        //             foreach ($data['addresses'] as $addressData) {
        //                 $patient->addresses()->create($addressData);
        //             }
        //         }
        //     }

        //     // Similar logic for contacts and insurances

        //     return $patient->refresh(); // Refresh to get latest data including relations
        // });
        // Placeholder implementation
        $patient->update(Arr::except($data, ['addresses', 'contacts', 'insurances']));
        // TODO: Implement update/creation/deletion of related models (addresses, contacts, insurances) within a transaction
        return $patient;
    }

    /**
     * Delete a patient.
     *
     * @param Patient $patient The patient model to delete.
     * @return bool|null True on success, false or null on failure.
     * @throws \Exception
     */
    public function deletePatient(Patient $patient): ?bool
    {
        // Related data with cascade on delete (addresses, contacts, insurances)
        // should be deleted automatically by the database if foreign keys are set up correctly.
        return $patient->delete();
    }
}
