<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PatientCollection extends ResourceCollection
{
    /**
     * The resource that this collection collects.
     *
     * @var string
     */
    public $collects = PatientResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Default behavior is often sufficient:
        // return parent::toArray($request);

        // Or, if you want to customize the top-level structure when a collection is returned:
        return [
            'data' => $this->collection, // This will be an array of PatientResource transformed data
            // 'links' and 'meta' keys are automatically added by Laravel's pagination
            // when the collection is based on a LengthAwarePaginator instance.
            // If you need to add other custom metadata:
            // 'meta' => [
            //     'some_custom_key' => 'some_value',
            // ],
        ];
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    // public function with($request)
    // {
    //     // This method is used if you want to add top-level keys alongside 'data'
    //     // when the collection is the outermost part of the response.
    //     // This is often handled by the ApiResponse trait for 'success' and 'message'.
    //     // If using the ApiResponse trait, direct customization here might conflict
    //     // unless the trait is aware of ResourceCollections.
    //
    //     // Example:
    //     // return [
    //     //     'success' => true, // Usually handled by a global response wrapper/trait
    //     //     'message' => 'Patients retrieved successfully.', // Also usually global
    //     // ];
    //     return []; // Keep it simple if global response wrapper handles meta like success/message
    // }
}
