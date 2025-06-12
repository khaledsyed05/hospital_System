<?php

namespace App\Services;

use App\Models\Patient;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

/**
 * Service class for searching patients.
 */
class PatientSearchService
{
    /**
     * Default items per page for pagination.
     */
    const DEFAULT_PER_PAGE = 15;

    /**
     * Search for patients based on various criteria.
     *
     * @param array $criteria Criteria for searching patients.
     *                        Example: [
     *                            'name' => 'John Doe', // Searches first_name, last_name, full_name_ar, full_name_en
     *                            'phone' => '555-1234', // Searches PATIENT_CONTACTS
     *                            'national_id' => '123456789',
     *                            'age_min' => 30,
     *                            'age_max' => 40,
     *                            'gender' => 'M',
     *                            'sort_by' => 'registration_date', // e.g., patient_number, full_name_ar, age_years
     *                            'sort_direction' => 'desc', // 'asc' or 'desc'
     *                            'per_page' => 20,
     *                            'page' => 1
     *                        ]
     * @return LengthAwarePaginator
     */
    public function search(array $criteria): LengthAwarePaginator
    {
        $query = Patient::query();

        // Search by name (first, last, full_name_ar, full_name_en)
        if (!empty($criteria['name'])) {
            $name = $criteria['name'];
            $query->where(function (Builder $q) use ($name) {
                $q->where('first_name', 'like', "%{$name}%")
                  ->orWhere('last_name', 'like', "%{$name}%")
                  ->orWhere('middle_name', 'like', "%{$name}%")
                  ->orWhere('full_name_ar', 'like', "%{$name}%")
                  ->orWhere('full_name_en', 'like', "%{$name}%");
            });
        }

        // Search by National ID
        if (!empty($criteria['national_id'])) {
            $query->where('national_id', $criteria['national_id']);
        }

        // Search by Phone Number (from PATIENT_CONTACTS)
        if (!empty($criteria['phone'])) {
            $phone = $criteria['phone'];
            $query->whereHas('contacts', function (Builder $q) use ($phone) {
                $q->where('contact_value', 'like', "%{$phone}%");
                // Optionally filter by contact_type_id if 'Mobile Phone', 'Home Phone' etc. are known
            });
        }

        // Filter by age (age_years column)
        if (!empty($criteria['age_min'])) {
            $query->where('age_years', '>=', $criteria['age_min']);
        }
        if (!empty($criteria['age_max'])) {
            $query->where('age_years', '<=', $criteria['age_max']);
        }

        // Filter by gender
        if (!empty($criteria['gender'])) {
            $query->where('gender', $criteria['gender']);
        }

        // Sorting
        $sortBy = $criteria['sort_by'] ?? 'patient_id'; // Default sort
        $sortDirection = $criteria['sort_direction'] ?? 'asc';
        if (in_array($sortBy, (new Patient())->getFillable()) || $sortBy === (new Patient())->getKeyName() || $sortBy === 'registration_date' || $sortBy === 'age_years') {
             // Basic check to prevent sorting on arbitrary columns; enhance as needed
            $query->orderBy($sortBy, $sortDirection);
        }


        // Pagination
        $perPage = $criteria['per_page'] ?? self::DEFAULT_PER_PAGE;

        // Eager load relationships that might be needed for the response
        // $query->with(['addresses', 'contacts', 'insurances']); // Adjust as needed for PatientResource

        return $query->paginate($perPage);
    }
}
